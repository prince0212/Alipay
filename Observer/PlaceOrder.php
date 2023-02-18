<?php

namespace Deloitte\Alipay\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\UrlInterface;
use Magento\Sales\Model\Order;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Framework\DB\TransactionFactory;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Deloitte\Alipay\Api\AlipayRepositoryInterface;
use Deloitte\Alipay\Api\Data\AlipayInterface;
use Deloitte\Alipay\Logger\Logger;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Invoice;

class PlaceOrder implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    
    /**
     * @var UrlInterface
     */
    private $url;
    
    /**
     * @var CartManagementInterface
     */
    private $cartManagmentInterface;
    
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepoInterface;
    
    /**
     * @var TransactionFactory
     */
    private $transactionFactory;
    
    /**
     * @var BuilderInterface
     */
    private $transBuilder;
    
    /**
     * @var AlipayRepositoryInterface
     */
    private $repositoryInterface;
    
    /**
     * @var AlipayInterface
     */
    private $dataInterface;
    
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var InvoiceService
     */
    private $_invoiceService;

    /**
     * @var InvoiceSender
     */
    private $invoiceSender;
    
    /**
     * Initialization
     * 
     * @param ManagerInterface $messageManager
     * @param ResponseFactory $responseFactory
     * @param UrlInterface $url
     * @param CartManagementInterface $cartManagmentInterface
     * @param OrderRepositoryInterface $orderRepoInterface
     * @param TransactionFactory $transactionFactory
     * @param BuilderInterface $transBuilder
     * @param AlipayRepositoryInterface $repositoryInterface
     * @param AlipayInterface $dataInterface
     * @param Logger $logger
     * @param InvoiceService $invoiceService
     * @param InvoiceSender $invoiceSender
     */
    public function __construct(
        ManagerInterface            $messageManager,
        ResponseFactory             $responseFactory,
        UrlInterface                $url,
        CartManagementInterface     $cartManagmentInterface,
        OrderRepositoryInterface    $orderRepoInterface,
        TransactionFactory          $transactionFactory,
        BuilderInterface            $transBuilder,
        AlipayRepositoryInterface   $repositoryInterface,
        AlipayInterface             $dataInterface,
        Logger                      $logger,
        InvoiceService              $invoiceService,
        InvoiceSender               $invoiceSender
    ) {
        $this->messageManager = $messageManager;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->cartManagmentInterface = $cartManagmentInterface;
        $this->orderRepoInterface = $orderRepoInterface;
        $this->transactionFactory = $transactionFactory;
        $this->transBuilder = $transBuilder;
        $this->repositoryInterface = $repositoryInterface;
        $this->dataInterface = $dataInterface;
        $this->logger = $logger;
        $this->_invoiceService = $invoiceService;
        $this->invoiceSender = $invoiceSender;
    }
    
    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $responseData = $observer->getData('response');
        if ($responseData['trans_status'] != 'SUCCESS') {
            $this->logger->info('Payment Failed! '.$responseData['trans_status']);
            throw new \Exception(__('Alipay Payment Failed!'));
        }
        
        /**
         * @var \Deloitte\Alipay\Api\Data\AlipayInterface $alipayData
         */
        $alipayData = $this->repositoryInterface->getByQuoteId($responseData['tid']);
        $order = $this->createOrder($alipayData->getQuoteId());
        $this->createTransaction($order, $responseData['trade_no'], $responseData['trans_amount'], json_encode($responseData, true));
        return $this;
    }
    
    /**
     * 
     * @param int $quoteId
     * @return type
     * @throws \Exception
     */
    private function createOrder($quoteId)
    {
        try {
            $orderId = $this->cartManagmentInterface->placeOrder($quoteId);
            $order = $this->orderRepoInterface->get($orderId);
            $order->setStatus(Order::STATE_PROCESSING);
            $order->setState(Order::STATE_PROCESSING);
            $order->save();
        } catch (\Exception $ex) {
            $this->logger->info('Alipay Payment Failed! '.$ex->getMessage());
            throw new \Exception(__($ex->getMessage()));
        }
        
        $this->updateAlipayWithOrderId($quoteId, $orderId);
        
        return $order;
    }
    
    /**
     * @param $order
     * @param $transactionId
     * @param $paymentAmount
     * @param $paymentData
     * @return int|void
     */
    private function createTransaction($order, $transactionId, $paymentAmount, $paymentData)
    {
        try {
            $payment = $order->getPayment();
            $payment->setLastTransId($transactionId);
            $payment->setTransactionId($transactionId);
            $payment->setAdditionalInformation(
                [Transaction::RAW_DETAILS => $paymentData]
            );
            $formatedPrice = $order->getBaseCurrency()->formatTxt(
                $paymentAmount
            );

            $message = __('The authorized & captured amount is %1.', $formatedPrice);

            $transaction = $this->transBuilder->setPayment($payment)
                ->setOrder($order)
                ->setTransactionId($transactionId)
                ->setAdditionalInformation(
                    [Transaction::RAW_DETAILS => $paymentData]
                )
                ->setFailSafe(true)
                ->build(Transaction::TYPE_PAYMENT);

            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            $payment->setParentTransactionId(null);
            $payment->save();
            $order->save();
        } catch (\Exception $e) {
            $this->logger->info('Alipay Payment Failed! '.$e->getMessage());
            throw new \Exception(__($e->getMessage()));
        }
    }
    
    /**
     * 
     * @param int $quoteId
     * @param int $orderId
     * @return void
     * @throws \Exception
     */
    private function updateAlipayWithOrderId($quoteId, $orderId)
    {
        $data = $this->repositoryInterface->getByQuoteId($quoteId);
        $data->setOrderId($orderId);
        try {
            $this->repositoryInterface->save($data);
        } catch (\Exception $ex) {
            $this->logger->info('Alipay Payment Failed! '.$ex->getMessage());
            throw new \Exception(__($ex->getMessage()));
        }
    }

    /**
     * @param Order $order
     * @throws LocalizedException
     * @throws Exception
     */
    public function createOrderInvoice($order)
    {
        if ($order->canInvoice()) {
            $invoice = $this->_invoiceService->prepareInvoice($order);
            $invoice->setRequestedCaptureCase(Invoice::CAPTURE_OFFLINE);
            $invoice->register();
            $invoice->save();
            $transactionSave = $this->transactionFactory->create()->addObject($invoice)->addObject($invoice->getOrder());
            $transactionSave->save();
            $this->invoiceSender->send($invoice);
            //Send Invoice mail to customer
            $order->addStatusHistoryComment(
                __('Notified customer about invoice creation #%1.', $invoice->getId())
                )
                ->setIsCustomerNotified(true)
                ->save();
        }
    }
}
