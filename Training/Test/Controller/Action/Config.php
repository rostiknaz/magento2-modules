<?php
/**
 * Product controller.
 *
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Training\Test\Controller\Action;

class Config extends \Magento\Framework\App\Action\Action
{

    protected $_configInterface;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Training\Test\Model\Config\ConfigInterface $configInterface
    ) {
        parent::__construct($context);
        $this->_configInterface = $configInterface;
    }

    public function execute() {
        $testConfig = $this->_objectManager->get('Training\Test\Model\Config\ConfigInterface');
        $myNodeInfo = $testConfig->getMyNodeInfo();
        if (is_array($myNodeInfo)) {
            foreach($myNodeInfo as $str) {
                $this->getResponse()->appendBody($str . "<br>");
            }
        }
    }
}