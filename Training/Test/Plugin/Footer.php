<?php

namespace Training\Test\Plugin;

class Footer
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(\Magento\Framework\View\Element\Context $context, array $data = [])
    {
        $this->_scopeConfig = $context->getScopeConfig();
    }

    public function afterGetCopyright(\Magento\Theme\Block\Html\Footer $subject, $result)
    {
        return __('Customized copyright!');
    }
}