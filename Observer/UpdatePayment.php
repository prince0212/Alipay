<?php

namespace Deloitte\Alipay\Observer;

use Magento\Framework\Event\ObserverInterface;
use Deloitte\Alipay\Api\AlipayRepositoryInterface;
use Deloitte\Alipay\Api\Data\AlipayInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Response\Http;
use Deloitte\Alipay\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Deloitte\Alipay\Api\Data\ConfigInterface;

class UpdatePayment implements ObserverInterface, ConfigInterface
{
    /**
     * @var AlipayRepositoryInterface
     */
    private $repositoryInterface;
    
    /**
     * @var AlipayInterface
     */
    private $dataInterface;
    
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    
    /**
     * @var Http
     */
    private $_redirect;
    
    /**
     * @var Logger
     */
    private $logger;
    
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    
    /**
     * Initialization
     * 
     * @param AlipayRepositoryInterface $repositoryInterface
     * @param AlipayInterface $dataInterface
     * @param ManagerInterface $messageManager
     * @param ResponseFactory $responseFactory
     * @param UrlInterface $url
     * @param Http $_redirect
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        AlipayRepositoryInterface $repositoryInterface,
        AlipayInterface $dataInterface,
        ManagerInterface $messageManager,
        ResponseFactory $responseFactory,
        UrlInterface $url,
        Http $_redirect,
        Logger $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->repositoryInterface = $repositoryInterface;
        $this->dataInterface = $dataInterface;
        $this->messageManager = $messageManager;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->_redirect = $_redirect;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * @param Observer $observer
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $responseData = $observer->getData('response');
        
        try {
            $data = $this->validate($responseData);
        } catch (\Exception $ex) {
            $this->logger->info('Payment validation Failed! '.$ex->getMessage());
            throw new \Exception(__($ex->getMessage()));
        }
        
        if ($data instanceof \Deloitte\Alipay\Api\Data\AlipayInterface) {
            $updateResponse = $data->setTradeNumber($responseData['trade_no'])
            ->setTransReturnTime($responseData['trans_return_time'])
            ->setTransactionStatus($responseData['trans_status'])
            ->setTransactionAmount($responseData['trans_amount']);
        
            try {
                $this->repositoryInterface->save($updateResponse);
            } catch (\Exception $ex) {
                $this->logger->info('Payment Failed! '.$ex->getMessage());
                throw new \Exception(__('Payment failed! '.$ex->getMessage()));
            }

            $status = $responseData['trans_status'];
            if ($status != 'SUCCESS') {
                $this->logger->info('Payment Failed! '.$status);
                throw new \Exception(__('Payment failed! '));
            }
            return $this;
        }
        throw new \Exception(__('Payment failed! '));
    }
    
    /**
     * Validate
     * 
     * @param [] $responseData
     * @return \Deloitte\Alipay\Api\Data\AlipayInterface | $this
     * @throws \Exception
     */
    private function validate($responseData)
    {
        if (empty($responseData['signature']) || empty($responseData['trans_status']) || empty($responseData['tid'])) {
            $this->logger->info('Payment empty response');
            throw new \Exception(__('Payment failed!'));
        }
        
        try {
            $data = $this->repositoryInterface->getByQuoteId($responseData['tid']);
            $this->validateSignature($responseData);
            $this->validateMerchRefNo($responseData['merch_ref_no'], $data->getMerchRefNo());
            $this->validateTid($responseData['tid'], $data->getQuoteId());
            $this->validateAmount($responseData['trans_amount'], $data->getQuoteGrandTotal());
            $this->validateStatus($data->getTransactionStatus(), $responseData['trans_status']);
        } catch (\Exception $ex) {
            $this->logger->info('Payment failed '. $ex->getMessage());
            throw new \Exception(__($ex->getMessage()));
        }
        
        return $data;
    }
    
    /**
     * Validate Amount
     * 
     * @param float $response
     * @param float $grandTotal
     * @return boolean
     * @throws \Exception
     */
    private function validateAmount($response, $grandTotal)
    {
        if ($response != $grandTotal) {
            $this->logger->info('Invalide capture amount');
            throw new \Exception(__('Invalide capture amount'));
        }
        return true;
    }
    
    /**
     * Validate Signature
     * 
     * @param [] $response
     * @return boolean
     * @throws \Exception
     */
    private function validateSignature($response)
    {
        $EOPGKey = $this->scopeConfig->getValue(self::XML_ALIPAY_MERCHANT_SECRET_KEY,ScopeInterface::SCOPE_STORE);
        $merchRefNo = $response['merch_ref_no'];
        $mid = $this->scopeConfig->getValue(self::XML_ALIPAY_MERCHANT_ID,ScopeInterface::SCOPE_STORE);
        $orderId = $response['order_id'];
        $transAmount = $response['trans_amount'];
        $transStatus = $response['trans_status'];
        $tradeNo = $response['trade_no'];
        $hashString = $EOPGKey."merch_ref_no=$merchRefNo"."&mid=$mid"."&order_id=$orderId"."&payment_type=ALIPAY&service=SALE&trade_no=$tradeNo"."&trans_amount=$transAmount"."&trans_status=$transStatus";
        $veifySign = hash('sha256', $hashString);
        $this->logger->info('new encrypted signature : '.strtoupper($veifySign));
        if (strtoupper($veifySign) == strtoupper($response['signature'])) {
            return true;
        }
        $this->logger->info('Invalide Signature');
        throw new \Exception(__('Invalid Signature!'));
    }
    
    /**
     * Validate Merchant Ref No
     * 
     * @param string $response
     * @param string $merchRefNo
     * @return boolean
     * @throws \Exception
     */
    private function validateMerchRefNo($response, $merchRefNo)
    {
        if ($response != $merchRefNo) {
            $this->logger->info('Invalide merchant ref num');
            throw new \Exception(__('Invalid merchant ref num!'));
        }
        return true;
    }
    
    /**
     * Validate Status
     * 
     * @param string $response
     * @param string $status
     * @return boolean
     * @throws \Exception
     */
    private function validateStatus($response, $status)
    {
        if ($response != $status) {
            return true;
        }
        $this->logger->info('Status already captured in our database!');
        throw new \Exception(__('Status already captured in our database!'));
    }
    
    /**
     * 
     * @param int $response
     * @param int $quoteId
     * @return boolean
     * @throws \Exception
     */
    private function validateTid($response, $quoteId)
    {
        if ($response != $quoteId) {
            $this->logger->info('Invalide Tid');
            throw new \Exception(__('Invalide Tid!'));
        }
        return true;
    }
}
