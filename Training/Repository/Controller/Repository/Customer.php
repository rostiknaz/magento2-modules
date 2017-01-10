<?php
/**
 * Created by PhpStorm.
 * User: naro
 * Date: 12/26/16
 * Time: 10:32 AM
 */
namespace Training\Repository\Controller\Repository;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;

class Customer extends Action
{

    private $_searchCriteriaBuilder;

    private $_customerRepository;
    
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct($context);
        $this->_customerRepository = $customerRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute()
    {
        $this->getResponse()->setHeader('content-type', 'text/plain');
        $customers = $this->_getCustomersFromRepository();
        $this->getResponse()->appendBody(
            sprintf("List contains %s\n\n", get_class($customers[0])));
        foreach ($customers as $customer) {
            $this->_outputCustomer($customer);
        }
    }

    private function _getCustomersFromRepository()
    {
        $criteria = $this->_searchCriteriaBuilder->create();
        $customers = $this->_customerRepository->getList($criteria);
        return $customers->getItems();
    }

    private function _outputCustomer(CustomerInterface $customer)
    {
        $this->getResponse()->appendBody(sprintf(
            "\"%s %s\" <%s> (%s)\n",
            $customer->getFirstname(),
            $customer->getLastname(),
            $customer->getEmail(),
            $customer->getId()
        ));
    }
}