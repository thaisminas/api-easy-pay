# Api Easy Pay


## 📕 Índice

- [Sobre](#sobre)
- [Tecnologia](#tecnologias)
- [Instalação](#instalação)
- [Endpoints](#endpoints)
- [Arquitetura](#endpoints)
- [Swagger](#swagger)

<hr>
<!-- About -->

## Sobre

A Api Easy Pay é uma aplicação desenvolvida para facilitar e agilizar transferências de fundos, com o objetivo de simplificar o processo de transferência de dinheiro entre usuários.

<hr>

## Principais Recursos

* Enviar transferencias entre usuários
* Obter saldo da carteira
* Obter relatório de extrato das transações efetuadas

<hr>

## Tecnologia
* Lingugem PHP
* Framework Symfont

<hr>

## Instalação local

clone o repositório

```bash
  git clone git@github.com:thaisminas/api-easy-pay.git
```

Rodando localmente

```bash
  composer install
```


Rodando as migrações

```bash
  php bin/console doctrine:migrations:migrate
```

Rodar os Seeds

```bash
  php bin/console app:seed-customer
  php bin/console app:seed-wallet
```



<hr>

## Arquitetura


<hr>

## Swagger

Para visualizar a documentação do projeto no swagger acessar a rota:
*http://localhost:8080/api/doc* 


