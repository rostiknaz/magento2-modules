<?php
/**
 * Created by PhpStorm.
 * User: naro
 * Date: 12/26/16
 * Time: 4:10 PM
 */

namespace Training\Repository\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData
{
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $dbVersion = $context->getVersion();
        if (version_compare($dbVersion, '1.4.0', '<')) {
            $tableName = $setup->getTable('training_repository_example');
            $setup->getConnection()->insertMultiple(
                $tableName,
                [
                    ['name' => 'Foo'],
                    ['name' => 'Bar'],
                    ['name' => 'Baz'],
                    ['name' => 'Qux']
                ]
            );
        }
    }
}