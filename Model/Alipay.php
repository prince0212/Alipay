<?php
declare(strict_types=1);

namespace Deloitte\Alipay\Model;

use Magento\Framework\Model\AbstractModel;
use Deloitte\Alipay\Api\Data\AlipayInterface;

class Alipay extends AbstractModel implements AlipayInterface
{
    /**
     * @var string
     */
    protected $_cacheTag = 'deloitte_alipay_history';

    /**
     * @var string
     */
    protected $_eventPrefix = 'deloitte_alipay_history';

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(\Deloitte\Alipay\Model\ResourceModel\Alipay::class);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getMerchRefNo()
    {
        return $this->getData(self::MERCH_REF_NO);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getQuoteGrandTotal()
    {
        return $this->getData(self::QUOTE_GRAND_TOTAL);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTransactionStatus()
    {
        return $this->getData(self::TRANSACTION_STATUS);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTransReturnTime()
    {
        return $this->getData(self::TRANS_RETURN_TIME);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getSignature()
    {
        return $this->getData(self::SIGNATURE);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTradeNumber()
    {
        return $this->getData(self::TRADE_NO);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTransactionAmount()
    {
        return $this->getData(self::TRANSACTION_AMOUNT);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * {@inheritDoc}
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setMerchRefNo($merchRefNo)
    {
        return $this->setData(self::MERCH_REF_NO, $merchRefNo);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setQuoteGrandTotal($quoteGrandTotal)
    {
        return $this->setData(self::QUOTE_GRAND_TOTAL, $quoteGrandTotal);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setTransactionStatus($transStatus)
    {
        return $this->setData(self::TRANSACTION_STATUS, $transStatus);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setTransReturnTime($transReturnTime)
    {
        return $this->setData(self::TRANS_RETURN_TIME, $transReturnTime);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setSignature($signature)
    {
        return $this->setData(self::SIGNATURE, $signature);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setTradeNumber($tradeNumber)
    {
        return $this->setData(self::TRADE_NO, $tradeNumber);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setTransactionAmount($transAmount)
    {
        return $this->setData(self::TRANSACTION_AMOUNT, $transAmount);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
