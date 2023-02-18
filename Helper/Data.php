<?php

namespace Deloitte\Alipay\Helper;

use Deloitte\Alipay\Model\AbstractModel;

class Data extends AbstractModel
{
    /**
     * 
     * @param int $quoteId
     * @param float $grandTotal
     * @return []
     */
    public function getSignature($quoteId, $grandTotal)
    {
        $secretKey = $this->_getMerchantSecretKey();
        $mid = $this->_getMerchantId();
        $currentTime = strtotime("now");
        $merchRefNo = $mid+$currentTime;
        $string = $secretKey."merch_ref_no=$merchRefNo"."&mid=$mid"."&payment_type=ALIPAY&service=SALE&trans_amount=$grandTotal";
        $signature = hash('sha256', $string);
        $this->_eventManager->dispatch('quote_to_alipay', ['quote_id' => $quoteId, 'merch_ref_no' => $merchRefNo,'signature' => $signature, 'grand_total' => $grandTotal]);
        return ['signature' => $signature, 'merch_ref_no' => $merchRefNo];
    }
}
