<?php
/** We suggest merchant to use both return_url and notify_url to retrieve payment result.
 * If user close the browser window during payment, return_url will not get any response about 
 * the transaction. Therefore, merchant should use notify_url to retrieve payment result.
 */
namespace Deloitte\Alipay\Controller\ReturnUrl;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Deloitte\Alipay\Logger\Logger;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Webhook is responsible to get the real time response on browser and redirect to order success page
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    
    /**
     * @var Http
     */
    private $httpRequest;
    
    /**
     * @var ManagerInterface
     */
    protected $_eventManager;
    
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;
    
    /**
     * Initialization
     * 
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param Http $httpRequest
     * @param ManagerInterface $_eventManager
     * @param RedirectFactory $resultRedirectFactory
     * @param Logger $logger
     */
    public function __construct(
        Context         $context,    
        CheckoutSession $checkoutSession,
        Http $httpRequest,
        ManagerInterface $_eventManager,
        RedirectFactory $resultRedirectFactory,
        Logger $logger,
        OrderRepositoryInterface $orderRepo
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->httpRequest = $httpRequest;
        $this->_eventManager = $_eventManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->logger = $logger;
        $this->orderRepo = $orderRepo;
        parent::__construct($context);
    }

    public function execute()
    {
        $quoteId = $this->checkoutSession->getQuote()->getId();
        $responseData = $this->httpRequest->getParams();
        if (empty($quoteId) || empty($responseData)) {
            /*Redirect to cart page*/
            return $this->resultRedirectFactory->create()->setPath('checkout/cart/');
        }
        
        $this->logger->info('Retrun url Response Data:'.print_r($responseData, true));
        // capture payment response
        try {
            $this->_eventManager->dispatch('alipay_payment_response', ['response' => $responseData]);
        } catch (\Exception $ex) {
            $this->logger->error('Alipay payment failed! '.$ex->getMessage());
            return $this->resultRedirectFactory->create()->setPath('checkout/');
        }
        $this->logger->error('Going to place order');
        $this->_eventManager->dispatch('alipay_create_order', ['response' => $responseData]);
        
        if ($this->setSessionData()) {
            $this->logger->error('Order placed successfull!');
            return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success');
        }
        $this->logger->error('successful error');
        return $this->resultRedirectFactory->create()->setPath('checkout/cart/');
        
    }
    
    /**
     * Set Checkout session with require data
     * 
     * @return true
     */
    private function setSessionData()
    {   
        $orderId = $this->checkoutSession->getLastOrderId();
        $this->logger->error('successful order id'.$orderId);
        $order =  $this->orderRepo->get($orderId);
        $this->checkoutSession->setLastQuoteId($order->getQuoteId())
            ->setLastSuccessQuoteId($order->getQuoteId())
            ->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());
        
        $this->logger->error('Session Data'.print_r($this->checkoutSession->getData(), true));
        return true;
    }
}
