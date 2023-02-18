<?php

namespace Deloitte\Alipay\Model\Config\Source\Order\Action;

class PaymentAction
{
    /**
     * @var string[] 
     */
    public function toOptionArray()
    {
       return [
           ['value' => 'authorize', 'label' => __('Authorize Only')], 
            ['value' => 'authorize_capture', 'label' => __('Authorize and Capture')]
        ];
    }
}
