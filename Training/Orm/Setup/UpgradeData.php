<?php
namespace Training\Orm\Setup;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as CatalogAttribute;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;


class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CategorySetupFactory
     */
    private $catalogSetupFactory;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Constructor.
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->catalogSetupFactory = $categorySetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * Upgrade data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $dbVersion = $context->getVersion();
        if (version_compare($dbVersion, '0.0.1', '<')) {
            /** @var CategorySetup $catalogSetup */
            $catalogSetup = $this->catalogSetupFactory->create(['setup' => $setup]);
            $catalogSetup->addAttribute(
                Product::ENTITY,
                'example_multiselect',
                [
                    'label' => 'Example multiselect',
                    'backend_model' => 'Magento\Eav\Entity\Attribute\Backend\ArrayBackend',
                    'input' => 'multiselect',
                    'visible_on_front' => true,
                    'visible' => true,
                    'option' => [
                        'values' => [
                            'Left',
                            'Right',
                            'Up',
                            'Down',
                            'All'
                        ]
                    ]
                ]
            );
        }
        if (version_compare($dbVersion, '0.0.3', '<')) {
            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerSetup->addAttribute(
                Customer::ENTITY,
                'priority',
                [
                    'label' => 'Priority',
                    'type' => 'int',
                    'input' => 'select',
                    'source' => \Training\Orm\Entity\Attribute\Source\CustomerPriority::class,
                    'required' => 0,
                    'system' => 0,
                    'position' => 100
                ]
            );
            $customerSetup->getEavConfig()->getAttribute('customer', 'priority')
                ->setData('used_in_forms', ['adminhtml_customer'])
                ->save();
        }
    }
}