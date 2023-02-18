<?php

namespace Deloitte\Alipay\Model;

use Deloitte\Alipay\Api\Data\ConfigInterface;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

abstract class AbstractModel extends AbstractHelper implements ConfigInterface
{   
    /**
     * @return bool
     */
    protected function _isActive()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_ENABLE,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
    
    /**
     * @return string
     */
    protected function _getTitle()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_TITLE,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
    
    /**
     * @return string
     */
    protected function _getApiUrl()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_API,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
    
    /**
     * @return string
     */
    protected function _getMerchantId()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_MERCHANT_ID,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
    
    /**
     * @return string
     */
    protected function _getMerchantSecretKey()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_MERCHANT_SECRET_KEY,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
    
    /**
     * @return string
     */
    protected function _getNotifyUrl()
    {
        $notifyUrl = $this->_urlBuilder->getUrl(self::XML_ALIPAY_NOTIFY_URL);
        return $notifyUrl;
    }
    
    /**
     * @return string
     */
    protected function _getReturnUrl()
    {
        $returnUrl = $this->_urlBuilder->getUrl(self::XML_ALIPAY_RETURN_URL);
        return $returnUrl;
    }
    
    /**
     * @return string
     */
    protected function _getDefaultPaymentAction()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_PAYMENT_ACTION,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
    
    protected function _getDefaultOrderStatus()
    {
        $configValue = $this->scopeConfig->getValue(self::XML_ALIPAY_ORDER_STATUS,ScopeInterface::SCOPE_STORE);
        return $configValue;
    }
}
