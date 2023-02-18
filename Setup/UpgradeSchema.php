<?php

namespace Deloitte\Alipay\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('deloitte_alipay_history');
        // Check if the table already exists
        
        if(version_compare($context->getVersion(), '0.0.2', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable( 'deloitte_alipay_history' ),
                'merch_ref_no',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 255,
                    'comment' => 'Merchent Refrence Number',
                    'after' => 'quote_id'
                ]
            );
        }
        
        if(version_compare($context->getVersion(), '0.0.3', '<')) {
            if ($installer->getConnection()->tableColumnExists('deloitte_alipay_history', 'quote_grand_total')){
                $definition = [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,2'
                ];
                $installer->getConnection()->modifyColumn(
                    $setup->getTable('deloitte_alipay_history'),
                    'quote_grand_total',
                    $definition
                );
            }
            
            if ($installer->getConnection()->tableColumnExists('deloitte_alipay_history', 'trans_amount')){
                $definition = [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,2'
                ];
                $installer->getConnection()->modifyColumn(
                    $setup->getTable('deloitte_alipay_history'),
                    'trans_amount',
                    $definition
                );
            }
        }
        $installer->endSetup();
    }
}