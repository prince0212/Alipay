<?php

namespace Deloitte\Alipay\Controller\ScanEvent;

use Magento\Checkout\Model\Session AS CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;

class Index extends Action
{   
    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;
    
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * Initialization
     * 
     * @param Context $context
     * @param CheckoutSession $_checkoutSession
     */
    public function __construct(
        Context         $context,
        CheckoutSession $_checkoutSession,
        OrderRepositoryInterface $orderRepo
    ) {
        $this->_checkoutSession = $_checkoutSession;
        $this->orderRepo = $orderRepo;
        parent::__construct($context);
    }

    public function execute()
    {
        $hasQuote = $this->_checkoutSession->hasQuote(); 
        if ($hasQuote) {
            return $this->_redirect('checkout/');
        }
        
        $orderId = $this->_checkoutSession->getLastOrderId();
        $order =  $this->orderRepo->get($orderId);
        $this->_checkoutSession->setLastQuoteId($order->getQuoteId())
            ->setLastSuccessQuoteId($order->getQuoteId())
            ->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());
        return $this->_redirect('checkout/onepage/success');
    }
}
