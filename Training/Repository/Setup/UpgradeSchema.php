<?php
/**
 * Created by PhpStorm.
 * User: naro
 * Date: 12/26/16
 * Time: 4:06 PM
 */

namespace Training\Repository\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table as DdlTable;
use Magento\Framework\DB\Adapter\AdapterInterface;


class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('training_repository_example');
        $ddlTable = $setup->getConnection()->newTable(
            $tableName
        );
        $ddlTable->addColumn(
            'example_id',
            DdlTable::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ]
        )->addColumn('name',
            DdlTable::TYPE_TEXT,
            255,
            [
                'nullable' => false
            ]
        )->addColumn(
            'created_at',
            DdlTable::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => DdlTable::TIMESTAMP_INIT
            ]
        )->addColumn(
            'updated_at',
            DdlTable::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => DdlTable::TIMESTAMP_INIT
            ]
        )->addIndex(
            $setup->getIdxName(
                $tableName,
                ['name'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['name'],
            ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
        );
        $setup->getConnection()->createTable($ddlTable);
        $setup->endSetup();
    }
}