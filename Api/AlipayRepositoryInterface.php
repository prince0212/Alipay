<?php
declare(strict_types=1);

namespace Deloitte\Alipay\Api;

interface AlipayRepositoryInterface
{
    /**
     * Save
     *
     * @param \Deloitte\Alipay\Api\Data\AlipayInterface $alipay
     * @return \Deloitte\Alipay\Api\Data\AlipayInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\AlipayInterface $alipay);

    /**
     * Retrieve
     *
     * @param int $id
     * @return \Deloitte\Alipay\Api\Data\AlipayInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deloitte\Alipay\Api\Data\AlipaySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
    
    /**
     * Get by signature.
     *
     * @param string $signature
     * @return \Deloitte\Alipay\Api\Data\AlipayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBySignature($signature);
    
    /**
     * Get by quote id.
     *
     * @param string $quoteId
     * @return \Deloitte\Alipay\Api\Data\AlipayInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByQuoteId($quoteId);
    
}
