<?php

declare(strict_types=1);

namespace Elogic\EligibleForReturn\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_SHOW = 'elogic/general/show';
    const XML_PATH_TEMPLATE = 'elogic/general/tpl';

    private ScopeConfigInterface $_scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param null $scopeCode
     * @return bool
     */
    public function getShow($scopeCode = null): bool
    {
        return (boolean)(int)$this->_scopeConfig->getValue(
            self::XML_PATH_SHOW,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * @param $scopeCode
     * @return string
     */
    public function getTemplate($scopeCode = null): string
    {
        return (string)$this->_scopeConfig->getValue(
            self::XML_PATH_TEMPLATE,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }


    /**
     * Getting template and replace days part
     * @param int $n
     * @param null $scopeCode
     * @return string
     */
    public function getTemplateAndRender(int $n, $scopeCode = null): string
    {
        $tpl = $this->getTemplate($scopeCode);
        return strtr($tpl, [
            '{n}' => $n
        ]);
    }
}
