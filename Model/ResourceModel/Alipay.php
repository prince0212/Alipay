<?php

namespace Deloitte\Alipay\Model\ResourceModel;

class Alipay extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('deloitte_alipay_history', 'id');
    }
}
