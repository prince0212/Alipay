<?php
/** We suggest merchant to use both return_url and notify_url to retrieve payment result.
 * If user close the browser window during payment, return_url will not get any response about 
 * the transaction. Therefore, merchant should use notify_url to retrieve payment result.
 */
namespace Deloitte\Alipay\Controller\NotifyUrl;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Deloitte\Alipay\Logger\Logger;

/**
 * Notify is responsible to get the payment status if any response has not been received real time
 * Notify url build on post method to update the payment status on alipay
 */
class Index extends Action implements CsrfAwareActionInterface
{   
    /**
     * @var ManagerInterface
     */
    protected $_eventManager;
    
    /**
     * @var Logger
     */
    private $logger;
    
    /**
     * Initialization
     * 
     * @param Context $context
     * @param ManagerInterface $_eventManager
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        ManagerInterface $_eventManager,
        Logger $logger
    ) {
        $this->_eventManager = $_eventManager;
        $this->logger = $logger;
        parent::__construct($context);
    }
    
    public function execute()
    {
        
        $responseData = $this->getRequest()->getParams();
        if (empty($responseData)) {
            $responseData = $this->getRequest()->getPostValue();
        }
        
        $this->logger->info('Notify url Response Data:'.print_r($responseData, ture));
        
        if (empty($responseData)) {
            $this->logger->info('Empty Response from Alipay');
            exit;
            //throw new \Exception(__('Invalid Response'));
        }
        
        $this->logger->info('Notify url Response Data:'.$responseData);
        
        try {
            $this->_eventManager->dispatch('alipay_payment_response', ['response' => $responseData]);
        } catch (\Exception $ex) {
            $this->logger->info('Status already captured in our database!');
            if ($ex->getMessage() == 'Status already captured in our database!') {
                echo 'SUCCESS';
                exit;
            }
            $this->logger->info('payment failed! '.$ex->getMessage());
            exit;
            //throw new \Exception(__($ex->getMessage()));
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
