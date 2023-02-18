<?php

namespace Deloitte\Alipay\Model\ResourceModel;

class AlipayQuoteId extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('deloitte_alipay_history', 'quote_id');
    }
}
