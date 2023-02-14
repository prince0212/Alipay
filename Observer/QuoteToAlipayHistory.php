<?php

namespace Deloitte\Alipay\Observer;

use Magento\Framework\Event\ObserverInterface;
use Deloitte\Alipay\Api\AlipayRepositoryInterface;
use Deloitte\Alipay\Api\Data\AlipayInterface;
use Magento\Framework\Event\Observer;
use Deloitte\Alipay\Logger\Logger;

class QuoteToAlipayHistory implements ObserverInterface
{

    private Logger $logger;

    /**
     * @var AlipayRepositoryInterface
     */
    private $repositoryInterface;
    
    /**
     * @var AlipayInterface
     */
    private $dataInterface;
    
    /**
     * Initialization
     * 
     * @param AlipayRepositoryInterface $repositoryInterface
     * @param AlipayInterface $dataInterface
     * @param Logger $logger
     */
    public function __construct(
        AlipayRepositoryInterface $repositoryInterface,
        AlipayInterface $dataInterface,
        Logger $logger
    ) {
        $this->repositoryInterface = $repositoryInterface;
        $this->dataInterface = $dataInterface;
        $this->logger = $logger;
    }
    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $quoteId = $observer->getData('quote_id');
        $signature = $observer->getData('signature');
        $grandTotal = $observer->getData('grand_total');
        $merchRefNo = $observer->getData('merch_ref_no');
        
        try {
            $data = $this->repositoryInterface->getByQuoteId($quoteId);
            $data->setSignature($signature);
            $data->setQuoteGrandTotal($grandTotal);
            $data->setMerchRefNo($merchRefNo);
        } catch (\Exception $ex) {
            $data = $this->dataInterface->setQuoteId($quoteId)
                ->setSignature($signature)
                ->setQuoteGrandTotal($grandTotal)
                ->setMerchRefNo($merchRefNo);
        }
        
        try {
            $this->repositoryInterface->save($data);
        } catch (Exception $ex) {
            $this->logger->info('Alipay failed while place order : '.$ex->getMessage());
            throw new \Exception(__($ex->getMessage()));
        }
    }
}
