# Api Easy Pay


## 📕 Índice

- [Sobre](#sobre)
- [Tecnologia](#tecnologia)
- [Instalação](#instalação)
- [PHP MyAdmin](#php-myadmin)
- [Arquitetura](#arquitetura)
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

## Tecnologias
* Lingugem PHP
* Framework Symfony
* MySql 
* PHPUnit

<hr>

## Instalação

Clone o repositório

```bash
  git clone https://github.com/thaisminas/api-easy-pay.git
```

Comando para rodar a aplicação através do docker compose 
```bash
  docker-compose up -d --build  
```


Execute as migrações

```bash
  docker-compose exec app bin/console doctrine:migrations:migrate  
```




Comando para rodar os Seeds

```bash
  docker-compose exec app bin/console app:seed-customer
  
  docker-compose exec app bin/console app:seed-wallet
```



<hr>

## PHP MyAdmin

Para visualizar os dados do banco acesse a url:
*http://localhost:8080*

```bash
  User: root
  
  Password: root
```



<hr>

## Arquitetura


<hr>

## Swagger

Para visualizar a documentação do projeto no swagger acessar a rota:
*http://localhost:8000/api/doc* 


