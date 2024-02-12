<?php

use Bissolli\ValidadorCpfCnpj\CNPJ;
use Bissolli\ValidadorCpfCnpj\CPF;

function validateDocument(array $customer): string
{
    $length = 14;
    $document = $customer['document'];

    if(strlen($document) <= $length){
        $cpf =  new CPF($document);
        return $cpf->getValue() && $cpf->isValid();
    }

    $cnpj = new CNPJ();
    return $cnpj->getValue() && $cnpj->isValid();
}
