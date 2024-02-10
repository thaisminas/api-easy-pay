<?php

namespace App\Application\UseCases;

use App\Application\Factory\CustomerFactory;
use App\Domain\Interfaces\CustomerRepository;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;

class CreateCustomer
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function execute(array $customer): CustomerMapper
    {
        $document = validateDocument($customer['document']);
        $customer['document'] = $document;

        if(!$document){
            throw new Error('Document is invalid');
        }

        $customerEntity = CustomerFactory::createFromArray($customer);

        return $this->customerRepository->save($customerEntity);
    }
}