<?php

use Bissolli\ValidadorCpfCnpj\CNPJ;
use Bissolli\ValidadorCpfCnpj\CPF;

function validateDocument($document): string
{
    $length = 14;

    if(strlen($document) <= $length){
        $cpf =  new CPF($document);
        return $cpf->getValue();
    }

    $cnpj = new CNPJ();
    return $cnpj->getValue();
}