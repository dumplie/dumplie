# Dumplie

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
