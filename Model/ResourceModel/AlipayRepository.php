<?php

namespace Deloitte\Alipay\Model\ResourceModel;

use Deloitte\Alipay\Api\Data;
use Deloitte\Alipay\Api\AlipayRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\DataObjectHelper;
use Deloitte\Alipay\Model\ResourceModel\Alipay\CollectionFactory as AlipayCollectionFactory;
use Deloitte\Alipay\Model\ResourceModel\Alipay as AlipayResource;
use Deloitte\Alipay\Model\ResourceModel\AlipaySignature;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Deloitte\Alipay\Model\AlipayFactory;
use Deloitte\Alipay\Model\ResourceModel\AlipayQuoteId;

class AlipayRepository implements AlipayRepositoryInterface
{
    
    /**
     * @var Data\AlipaySearchResultInterfaceFactory
     */
    protected $searchResultFactory;
    
    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;
    
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;
    
    /**
     * @var Data\AlipayInterfaceFactory
     */
    protected $dataAlipayFactory;
    
    /**
     * @var AlipayCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var AlipayResource
     */
    protected $resourceModel;
    
    /**
     * @var AlipayFactory
     */
    private $alipayFactory;
    
    /**
     * @var AlipaySignature
     */
    private $alipaySignature;
    
    /**
     * @var AlipayQuoteId
     */
    private $alipayQuoteId;

    /**
     * Initialization
     * 
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param Data\AlipayInterfaceFactory $dataAlipayFactory
     * @param AlipayCollectionFactory $collectionFactory
     * @param AlipayResource $resourceModel
     * @param AlipayFactory $alipayFactory
     * @param AlipaySignature $alipaySignature
     * @param AlipayQuoteId $alipayQuoteId
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        Data\AlipayInterfaceFactory $dataAlipayFactory,
        AlipayCollectionFactory $collectionFactory,
        AlipayResource $resourceModel,
        AlipayFactory $alipayFactory,
        AlipaySignature $alipaySignature,
        AlipayQuoteId $alipayQuoteId
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAlipayFactory = $dataAlipayFactory;
        $this->collectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
        $this->alipayFactory = $alipayFactory;
        $this->alipaySignature = $alipaySignature;
        $this->alipayQuoteId = $alipayQuoteId;
    }
    
    /**
     * {@inheritDoc}
     */
    public function save(Data\AlipayInterface $alipay)
    {
        try {
            $this->resourceModel->save($alipay);
        } catch (\Exception $ex) {
            throw new CouldNotSaveException(__($ex->getMessage()));
        }
        return $alipay;
    }

    /**
     * {@inheritDoc}
     */
    public function getById($id)
    {
        $alipay = $this->dataAlipayFactory->create();
        $this->resourceModel->load($alipay, $id);
        if (!$alipay->getId()) {
            throw new NoSuchEntityException(__('Alipay with id %1 does not exist', $id));
        }
        return $alipay;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        
    }

    /**
     * {@inheritDoc}
     */
    public function deleteById($id)
    {
        try {
            $row = $this->alipayFactory->create()->load($id);
            $row->delete();
        } catch (Exception $ex) {
            throw new Exception(__($ex->getMessage()));
        }
        return true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getBySignature($signature)
    {
        $alipay = $this->dataAlipayFactory->create();
        $this->alipaySignature->load($alipay, $signature);
        if (!$alipay->getSignature()) {
            throw new NoSuchEntityException(__('Alipya with signature %1 does not exist', $signature));
        }
        return $alipay;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getByQuoteId($quoteId)
    {
        $alipay = $this->dataAlipayFactory->create();
        $this->alipayQuoteId->load($alipay, $quoteId);
        if (!$alipay->getQuoteId()) {
            throw new NoSuchEntityException(__('Alipya with quote_id %1 does not exist', $quoteId));
        }
        return $alipay;
    }
}
