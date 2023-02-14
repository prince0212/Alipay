<?php
/** We suggest merchant to use both return_url and notify_url to retrieve payment result.
 * If user close the browser window during payment, return_url will not get any response about 
 * the transaction. Therefore, merchant should use notify_url to retrieve payment result.
 */
namespace Deloitte\Alipay\Controller\Notify;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Notify is responsible to get the payment status if any response has not been received real time
 */
class Index extends Action implements CsrfAwareActionInterface
{   
    /**
     * @var ManagerInterface
     */
    protected $_eventManager;
    
    /**
     * Initialization
     * 
     * @param Context $context
     * @param ManagerInterface $_eventManager
     */
    public function __construct(
        Context $context,
        ManagerInterface $_eventManager
    ) {
        $this->_eventManager = $_eventManager;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $responseData = $this->getRequest()->getPostValue();
        
        try {
            $this->_eventManager->dispatch('alipay_payment_response', ['response' => $responseData]);
        } catch (\Exception $ex) {
            if ($ex->getMessage() == 'Status already captured in our database!') {
                echo 'SUCCESS';
                exit;
            }
            throw new \Exception(__($ex->getMessage()));
        }
        
        $this->_eventManager->dispatch('alipay_create_order', ['response' => $responseData]);
        echo 'SUCCESS';
        exit;
    }
    
    /**
     * 
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ? InvalidRequestException
    {
        return null;
    }
    
    /**
     * 
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ? bool
    {
        return true;
    }
}
