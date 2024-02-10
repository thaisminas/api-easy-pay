<?php

namespace App\Application\UseCases;

use App\Application\Factory\CustomerFactory;
use App\Domain\Exception\CustomerAlreadyExistException;
use App\Domain\Port\Inbound\CustomerRepositoryPort;
use App\Infra\Repository\Mappers\CustomerMapper;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use function Symfony\Component\String\u;

class CreateCustomer
{
    private $customerRepository;

    public function __construct(CustomerRepositoryPort $customerRepository)
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

        $customerAlreadExist = $this->customerRepository->findByDocument($customer['document']);


        $customerEntity = CustomerFactory::createFromArray($customer);

        return $this->customerRepository->save($customerEntity);
    }
}