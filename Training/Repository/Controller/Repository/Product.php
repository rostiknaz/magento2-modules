<?php
namespace Training\Repository\Controller\Repository;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Api\FilterBuilder;;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Product extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $_searchCriteriaBuilder;

    /**
 * @var FilterGroupBuilder
 */
    private $_filterGroupBuilder;

    /**
     * @var FilterBuilder
     */
    private $_filterBuilder;


    /**
     * Constructor.
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder
    ) {
        parent::__construct($context);
        $this->_productRepository = $productRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_filterBuilder = $filterBuilder;
        $this->_filterGroupBuilder = $filterGroupBuilder;
    }

    public function execute()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/plain');

        $filters = [
            'type_id eq ' . Configurable::TYPE_CODE,
            'name like %Jacket%'
        ];
        $this->_addFilters($filters);
        $products = $this->_getProductsFromRepository();

        foreach ($products as $product) {
            $this->outputProduct($product);
        }
    }

    /**
     * @return ProductInterface[]
     */
    private function _getProductsFromRepository()
    {
        $this->_searchCriteriaBuilder->setFilterGroups(
            [$this->_filterGroupBuilder->create()]
        );
        $criteria = $this->_searchCriteriaBuilder->create();
        $products = $this->_productRepository->getList($criteria);
        return $products->getItems();
    }

    private function outputProduct(ProductInterface $product)
    {
        $this->getResponse()->appendBody(
            sprintf(
                "%s - %s (%d)\n",
                $product->getName(),
                $product->getSku(),
                $product->getId()
            )
        );
    }


    private function _addFilters($filters)
    {
        foreach ($filters as $filter) {
            if (is_string($filter) && preg_match('/([0-9])* ?([a-zA-Z])* ?(.+)/', $filter)) {
                $filterParts = explode(' ', $filter);
                $filter = $this->_filterBuilder
                    ->setField($filterParts[0])
                    ->setValue($filterParts[2])
                    ->setConditionType($filterParts[1])
                    ->create();
                $this->_filterGroupBuilder->addFilter($filter);
            }
        }

        $this->_filterGroupBuilder;
    }
}