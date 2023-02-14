<?php
declare(strict_types=1);

namespace Deloitte\Alipay\Controller\Index;

use Magento\Checkout\Model\Session AS CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Deloitte\Alipay\Gateway\Request\CreateRequestFactory;
use Magento\Framework\Controller\Result\Redirect;
use Deloitte\Alipay\Logger\Logger;

class Index extends Action
{
    private Logger $logger;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    
    /**
     * @var PageFactory
     */
    private $pageFactory;
    
    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;
    
    /**
     * @var CreateRequestFactory
     */
    private $createRequestFactory;
    
    /**
     * @var Redirect
     */
    private $redirect;

    /**
     * Initialization
     * 
     * @param Context $context
     * @param CheckoutSession $_checkoutSession
     * @param RedirectFactory $redirectFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context         $context,
        CheckoutSession $_checkoutSession,
        RedirectFactory $redirectFactory,
        PageFactory     $pageFactory,
        CreateRequestFactory $createRequestFactory,
        Redirect $redirect,
        Logger $logger
    )
    {
        $this->pageFactory = $pageFactory;
        $this->_checkoutSession = $_checkoutSession;
        $this->resultRedirectFactory = $redirectFactory;
        $this->createRequestFactory = $createRequestFactory;
        $this->redirect = $redirect;
        $this->logger = $logger;
        parent::__construct($context);
        
    }


    public function execute()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->_checkoutSession->getQuote();
        if (empty($quote)) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }
        
        $cartId = $quote->getId();
        $grandTotal = $quote->getGrandTotal();
        $cartTotal = number_format((float)$grandTotal, 2);
        $requestData = $this->createRequestFactory->create()->createPaymentRequest($cartId, $cartTotal);
        $this->logger->info('Request to alipay : '.$requestData);
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($requestData);
        return $resultRedirect;
    }
}
