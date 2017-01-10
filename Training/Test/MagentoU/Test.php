<?php
/**
 * Created by PhpStorm.
 * User: naro
 * Date: 12/12/16
 * Time: 6:18 PM
 */

namespace Training\Test\MagentoU;

class Test
{
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Checkout\Model\Session $session,
        \Training\Test\Api\ProductRepositoryInterface $testProductRepository,
        $justAParameter = false,
        array $data
    ) {
        $this;
    }
}