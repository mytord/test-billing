Billing app
==========

Billing micro-service. 
You can see usage examples at the end.

## Overview

Docker environment:

- PHP 7.1
- Nginx
- PostgreSQL

Framework and helper libs:

- Symfony 4
- Doctrine
- [4xxi/skeleton](https://github.com/4xxi/skeleton)

## Installation

### 0. Create configuration file and check it

```
cp .env.dist .env
```

### 1. Start Containers
On Linux:
```bash
docker-compose up -d
```
On MacOS:
```bash
docker-sync-stack start
```

### 2. Run migrations, install fixtures
```bash
docker-compose exec php bin/console doctrine:migrations:migrate -n
```

### 4. Open project
Just go to [http://127.0.0.1](http://127.0.0.1)

## Tests

```
docker-compose exec php bin/phpunit -c phpunit.xml.dist
```

## Examples

### Make a deposit

```
curl --request POST \
  --url http://127.0.0.1/deposit \
  --header 'Cache-Control: no-cache' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data 'walletId=1&amount=10000'
```

### Make a withdrawal

```
curl --request POST \
  --url http://127.0.0.1/withdrawal \
  --header 'Cache-Control: no-cache' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data 'walletId=1&amount=5000'
```

### Make a money transfer

```
curl --request POST \
  --url http://127.0.0.1/transfer \
  --header 'Cache-Control: no-cache' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data 'sourceWalletId=1&recipientWalletId=2&amount=1500'
```

- walletId, sourceWalletId, recipientWalletId - ID of user wallet (there are three test wallets with ids 1, 2, 3)
- amount - Money amount in _cents_