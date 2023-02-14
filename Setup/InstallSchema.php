<?php

namespace Deloitte\Alipay\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('deloitte_alipay_history');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity id'
                )
                ->addColumn(
                    'quote_id',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'cart Id'
                )
                ->addColumn(
                    'quote_grand_total',
                    Table::TYPE_FLOAT,
                    255,
                    [],
                    'cart grand total'
                )
                ->addColumn(
                    'order_id',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Order Id'
                )
                ->addColumn(
                    'trans_status',
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Transaction Status'
                )
                ->addColumn(
                    'signature',
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Signature'
                )
                ->addColumn(
                    'trans_return_time',
                    Table::TYPE_DATETIME,
                    null,
                    [],
                    'Transaction Return time'
                )
                ->addColumn(
                    'trade_no',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Status'
                )
                ->addColumn(
                    'trans_amount',
                    Table::TYPE_FLOAT,
                    255,
                    ['nullable' => true, 'default' => NULL],
                    'Transaction Amount'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}