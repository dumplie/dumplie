# Dumplie

Dumplie is first ecommerce framework not bound to any php framework. It is build to be used with any existing solution. 

Feel free to join us on slack!
[![Slack Status](https://dumplie.herokuapp.com/badge.svg)](https://dumplie.herokuapp.com/)

## Development environment

### Docker

Project does not provide any docker image yet, but
you can use docker in this project in following way:

- Download [this Dockerfile](https://gist.github.com/l3l0/ce670ffc4de3de713548ffc2b091b904) in some new directory ex. "/foo/bar":

```dockerfile
FROM php:7.0.7-cli

RUN apt-get update && apt-get install -y git zlib1g-dev
RUN docker-php-ext-install zip
```

- Build image from use this Dockerfile:

```bash
docker build -t your-namespace/php7 /foo/bar
```

- Add following alias (for example to .bashrc):
```bash
alias php='docker run --rm --name php -it -v "$PWD":/usr/src/app -w /usr/src/app your-namespace/php7 php'
```

- Now you can download composer: see [composer page](https://getcomposer.org/download/) and install project
- Running test:
```php
php bin/phpspec run
php bin/phpunit
```


### Tests

Dumplie idea is to be as much technology agnostic as possible, however we support some popular software like for 
example ``mysql`` or ``postgresql``. 

In order to execute integration tests against specific database use ``DUMPLIE_TEST_DB_CONNECTION`` env variable. 

Example:
```
$ export DUMPLIE_TEST_DB_CONNECTION='{"driver":"pdo_pgsql","host":"127.0.0.1","dbname":"dumplie","user":"docker","password":"docker","port":32771}' && bin/phpunit
$ export DUMPLIE_TEST_DB_CONNECTION='{"driver":"pdo_mysql","host":"127.0.0.1","dbname":"dumplie","user":"root","password":"root","port":32777}' && bin/phpunit
```