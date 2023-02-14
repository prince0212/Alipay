<?php
/** We suggest merchant to use both return_url and notify_url to retrieve payment result.
 * If user close the browser window during payment, return_url will not get any response about 
 * the transaction. Therefore, merchant should use notify_url to retrieve payment result.
 */
namespace Deloitte\Alipay\Controller\Webhook;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * Webhook is responsible to get the real time response on browser and redirect to order success page
 */
class Index implements HttpGetActionInterface
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
    private $resultRedirectFactory;
    
    /**
     * Initialization
     * 
     * @param CheckoutSession $checkoutSession
     * @param Http $httpRequest
     * @param ManagerInterface $_eventManager
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Http $httpRequest,
        ManagerInterface $_eventManager,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->httpRequest = $httpRequest;
        $this->_eventManager = $_eventManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function execute()
    {
        $quoteId = $this->checkoutSession->getQuote()->getId();
        if (empty($quoteId)) {
            /*Redirect to cart page*/
            return $this->resultRedirectFactory->create()->setPath('checkout/');
        }
        
        $responseData = $this->httpRequest->getParams();
        
        // capture payment response
        try {
            $this->_eventManager->dispatch('alipay_payment_response', ['response' => $responseData]);
        } catch (\Exception $ex) {
            return $this->resultRedirectFactory->create()->setPath('checkout/');
        }
        $this->_eventManager->dispatch('alipay_create_order', ['response' => $responseData]);
        echo 'SUCCESS';
        return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success');
    }
}
