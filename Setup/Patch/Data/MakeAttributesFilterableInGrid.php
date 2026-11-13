<?php

namespace PHPCuong\CustomerLogin\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Enables the admin customer grid filters for the "Blocked" and "Reason" attributes.
 *
 * InstallData creates both attributes with is_filterable_in_grid = 0, so the columns
 * show up in the grid but cannot be filtered. Being a data patch, this also runs on
 * stores that already had the module installed.
 *
 * The "reason" source model is intentionally NOT changed here: it stays a manual step
 * so that an image without this module, connected to the same database, does not
 * crash trying to load a class it does not have.
 */
class MakeAttributesFilterableInGrid implements DataPatchInterface
{
    private const ATTRIBUTES = ['login_status', 'reason'];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        foreach (self::ATTRIBUTES as $attributeCode) {
            if ($customerSetup->getAttributeId(Customer::ENTITY, $attributeCode)) {
                $customerSetup->updateAttribute(Customer::ENTITY, $attributeCode, 'is_filterable_in_grid', 1);
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
