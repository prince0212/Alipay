<?php

namespace Deloitte\Alipay\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return [
            'payment' => [
                'alipay' => [
                    'test' => 'Deloitte_Alipay/images/alipay.png'
                ]
            ]
        ];
    }
}
