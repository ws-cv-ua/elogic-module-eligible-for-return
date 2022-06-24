<?php

declare(strict_types=1);

namespace Elogic\EligibleForReturn\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class EligibleForReturnAttribute implements DataPatchInterface
{
    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Init
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create();
        try {
            $eavSetup->addAttribute(
                Product::ENTITY,
                'eligible_for_return',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'label' => 'Eligible for return',
                    'required' => false,
                    'sort_order' => 50,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'visible' => true,
                    'is_html_allowed_on_front' => true,
                    'visible_on_front' => true
                ]
            );
        } catch (LocalizedException|\Zend_Validate_Exception $e) {
            $this->logger->critical("Can't create eligible_for_return attribute");
        }
    }
}
