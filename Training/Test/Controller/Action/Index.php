<?php
/**
 * Product controller.
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Training\Test\Controller\Action;

use Magento\Store\Model\ResourceModel\Store\Collection;

class Index extends \Magento\Framework\App\Action\Action
{

    public function execute() {
        $block = $this->_view->getLayout()->createBlock('Training\Test\Block\Template');
        $block->setTemplate('test.phtml');
        $collection = 
        $this->getResponse()->appendBody($block->toHtml());
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @return bool
     */
    protected function _isAllowed() {
        $secret = $this->getRequest()->getParam('secret');
        return isset($secret) && (int)$secret==1;
    }
}