<?php
declare(strict_types=1);

namespace Deloitte\Alipay\Api\Data;

interface AlipayInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID                    = 'id';
    const QUOTE_ID              = 'quote_id';
    const MERCH_REF_NO          = 'merch_ref_no';
    const QUOTE_GRAND_TOTAL     = 'quote_grand_total';
    const ORDER_ID              = 'order_id';
    const TRANSACTION_STATUS    = 'trans_status';
    const TRANS_RETURN_TIME     = 'trans_return_time';
    const SIGNATURE             = 'signature';
    const TRADE_NO              = 'trade_no';
    const TRANSACTION_AMOUNT    = 'trans_amount';
    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Order Id
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Get Quote Id
     *
     * @return int
     */
    public function getQuoteId();
    
    /**
     * Get Merchant reference number
     *
     * @return string
     */
    public function getMerchRefNo();
    
    /**
     * Get Quote grand total
     *
     * @return float
     */
    public function getQuoteGrandTotal();
    
    /**
     * Get Transaction Id
     *
     * @return string
     */
    public function getTransactionStatus();
    
    /**
     * Get Status
     *
     * @return string
     */
    public function getTransReturnTime();
    
    /**
     * Get Signature
     *
     * @return string
     */
    public function getSignature();
    
    /**
     * Get Trade number
     *
     * @return string
     */
    public function getTradeNumber();
    
    /**
     * Get Transaction amount
     *
     * @return string
     */
    public function getTransactionAmount();
    
    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();
    
    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();
    
    /**
     * Set id
     *
     * @param $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set Order Id
     *
     * @param $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * Set Quote
     *
     * @param $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);
    
    /**
     * Set Merchant reference number
     *
     * @param $merchRefNo
     * @return $this
     */
    public function setMerchRefNo($merchRefNo);
    
    /**
     * Set content
     *
     * @param float $quoteGrandTotal
     * @return $this
     */
    public function setQuoteGrandTotal($quoteGrandTotal);
    
    /**
     * Set transaction status
     *
     * @param $transStatus
     * @return $this
     */
    public function setTransactionStatus($transStatus);
    
    /**
     * Set transactions return time
     *
     * @param $transReturnTime
     * @return $this
     */
    public function setTransReturnTime($transReturnTime);
    
    /**
     * Set signature
     *
     * @param $signature
     * @return $this
     */
    public function setSignature($signature);
    
    /**
     * Set Trade number
     *
     * @param $tradeNumber
     * @return $this
     */
    public function setTradeNumber($tradeNumber);
    
    /**
     * Set Transaction amount
     *
     * @param $transAmount
     * @return $this
     */
    public function setTransactionAmount($transAmount);
    
    /**
     * Set created at
     * 
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
    
    /**
     * Set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

}
