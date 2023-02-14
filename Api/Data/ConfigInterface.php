<?php

namespace Deloitte\Alipay\Api\Data;

interface ConfigInterface
{
    /**#@+ Constants */
    CONST XML_ALIPAY_ENABLE = 'payment/alipay/active';
    CONST XML_ALIPAY_TITLE  = 'payment/alipay/title';
    CONST XML_ALIPAY_API  = 'payment/alipay/api';
    CONST XML_ALIPAY_MERCHANT_ID  = 'payment/alipay/mid';
    CONST XML_ALIPAY_MERCHANT_SECRET_KEY = 'payment/alipay/merchent_secret_key';
    CONST XML_ALIPAY_NOTIFY_URL = 'alipay/notifyurl/';
    CONST XML_ALIPAY_RETURN_URL = 'alipay/returnurl/';
    CONST XML_ALIPAY_PAYMENT_ACTION = 'payment/alipay/payment_action';
    CONST XML_ALIPAY_ORDER_STATUS = 'payment/alipay/order_status';
    /**#@  */
    
}
