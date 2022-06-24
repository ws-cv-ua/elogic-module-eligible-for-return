<?php

declare(strict_types=1);

namespace Elogic\EligibleForReturn\Model\Resolver;

use Elogic\EligibleForReturn\Helper\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Stdlib\ArrayManager;

class EligibleForReturnResolver implements ResolverInterface
{
    private Config $_config;

    private ArrayManager $_arrayManager;

    private ValueFactory $_valueFactory;

    public function __construct(
        Config $config,
        ArrayManager $arrayManager,
        ValueFactory $valueFactory
    ) {
        $this->_config = $config;
        $this->_arrayManager = $arrayManager;
        $this->_valueFactory = $valueFactory;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return \Magento\Framework\GraphQl\Query\Resolver\Value|string
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        /** @var Product $product */
        $product = $this->_arrayManager->get('model', $value);
        if (is_null($product)) {
            return null;
        }

        /** Skip if value is not set */
        $attribute = $product->getCustomAttribute('eligible_for_return');
        if (is_null($attribute)) {
            return null;
        }

        /** Skip if value is wrong */
        $eligibleForReturn = (int)$attribute->getValue();
        if (!$eligibleForReturn) {
            return null;
        }

        return $this->_config->getTemplateAndRender($eligibleForReturn);
    }
}
