<?php
namespace Training\Repository\Controller\Repository;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Training\Repository\Api\ExampleRepositoryInterface;

class Example extends Action
{
    /**
     * @var ExampleRepositoryInterface
     */
    private $exampleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $_filterBuilder;

    /**
     * @var FilterGroupBuilder
     */
    private $_filterGroupBuilder;


    public function __construct(
        Context $context,
        ExampleRepositoryInterface $exampleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->exampleRepository = $exampleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_filterBuilder = $filterBuilder;
        $this->_filterGroupBuilder = $filterGroupBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->getResponse()->setHeader('content-type', 'text/plain');
        
        $examples = $this->_getExamplesByFilter();
        
        foreach ($examples as $example) {
            $this->getResponse()->appendBody(
                sprintf(
                    "%s (%d)\n",
                    $example->getName(),
                    $example->getId()
                )
            );
        }
    }
    
    private function _getExamplesByFilter()
    {
        $filters = [
            'name eq Foo',
            'name eq Qux'
        ];
        $this->_addFilters($filters);
        
        $this->searchCriteriaBuilder->setFilterGroups(
            [$this->_filterGroupBuilder->create()]
        );
        $examples = $this->exampleRepository->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
        
        return $examples;
    }

    private function _addFilters($filters)
    {
        foreach ($filters as $filter) {
            if (is_string($filter) && preg_match('/[a-z]/', $filter)) {
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