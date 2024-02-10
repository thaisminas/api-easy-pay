<?php

namespace App\Application\UseCases;

use App\Application\Factory\CustomerFactory;
use App\Domain\Exception\DocumentInvalidException;
use App\Domain\Exception\InvalidRoleException;
use App\Domain\Interfaces\CustomerRepository;
use App\Infra\Repository\Mappers\CustomerMapper;


class CreateCustomer
{
    const COMMON = 'COMMON';
    const STORE_USER = 'STORE_USER';
    private $customerRepository;
    private $customerFactory;

    public function __construct(CustomerRepository $customerRepository, CustomerFactory $customerFactory)
    {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
    }

    public function execute(array $customer): CustomerMapper
    {
        $this->validateCustomer($customer);

        $customer['document'] = validateDocument($customer);
        $customerEntity = $this->customerFactory->createFromArray($customer);

        return $this->customerRepository->save($customerEntity);
    }

    private function validateCustomer(array $customer)
    {
        $role = strtoupper($customer['role']);
        $document = $customer['document'];

        if($role !== self::COMMON && $role !== self::STORE_USER){
            throw new InvalidRoleException();
        }

        if(strlen($customer['name']) < 3){
            throw new \Exception('Name is Invalid', 422);
        }

        if($document === null || strlen($document) < 11){
            throw new DocumentInvalidException('Document is invalid');
        }
    }
}