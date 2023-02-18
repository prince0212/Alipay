<?php


namespace Deloitte\Alipay\Model\ResourceModel\Alipay;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Deloitte\Alipay\Model\Alipay', 'Deloitte\Alipay\Model\ResourceModel\Alipay');
    }
}
