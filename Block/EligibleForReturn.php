<?php

declare(strict_types=1);

namespace Elogic\EligibleForReturn\Block;

use Elogic\EligibleForReturn\Helper\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class EligibleForReturn extends Template
{
    private Config $_config;
    private Registry $_registry;

    public function __construct(
        Registry $registry,
        Config $config,
        Context $context
    ) {
        $this->_registry = $registry;
        $this->_config = $config;
        parent::__construct($context);
    }

    public function getText(): ?string
    {
        /** Checking if product exist */
        $product = $this->getProduct();
        if (empty($product)) {
            return null;
        }

        /** Checking if setting is set */
        $eligibleForReturn = (int)$product->getEligibleForReturn();
        if (!$eligibleForReturn) {
            return null;
        }

        return $this->_config->getTemplateAndRender($eligibleForReturn);
    }

    /**
     * Returns saleable item instance
     *
     * @return Product
     */
    private function getProduct(): Product
    {
        $parentBlock = $this->getParentBlock();

        return $parentBlock && $parentBlock->getProductItem()
            ? $parentBlock->getProductItem()
            : $this->_registry->registry('product');
    }
}
