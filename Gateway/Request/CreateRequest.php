<?php
declare(strict_types=1);

namespace Deloitte\Alipay\Gateway\Request;

use Deloitte\Alipay\Model\AbstractModel;
use Deloitte\Alipay\Helper\Data;
use Magento\Framework\App\Helper\Context;

class CreateRequest extends AbstractModel
{
    /**
     * @var Data
     */
    private $helperData;
    
    /**
     * Initialization
     * 
     * @param Data $helperData
     * @param Context $context
     */
    public function __construct(
       Data $helperData,
       Context $context
    ) {
        parent::__construct($context);
        $this->helperData = $helperData;
    }
    
    /**
     * @param int $quoteId
     * @param float $grandTotal
     * @return string
     */
    public function createPaymentRequest($quoteId, $grandTotal)
    {
        $data = $this->helperData->getSignature($quoteId, $grandTotal);
        return $this->buildData($data['signature'], $data['merch_ref_no'], $grandTotal, $quoteId);
    }
    
    /**
     * 
     * @param string $signature
     * @param string $merchRefNo
     * @param float $amount
     * @param int $quoteId
     * @return string
     */
    public function buildData($signature, $merchRefNo, $amount, $quoteId)
    {
        $mid = $this->_getMerchantId();
        $returnUrl = $this->_getReturnUrl();
        $notifyUrl = $this->_getNotifyUrl();
        $apiUrl = $this->_getApiUrl();
        $data = "service=SALE&payment_type=ALIPAY&mid=$mid&return_url=$returnUrl&signature=$signature&merch_ref_no=$merchRefNo&goods_subject=genki&goods_body=genkisushi&trans_amount=$amount&api_version=2.9&wallet=HK&app_pay=WEB&active_time=60000&notify_url=$notifyUrl&tid=$quoteId";
        return $apiUrl.$data;
    }
}
