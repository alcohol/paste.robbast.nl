# Alcohol\sf-minimal-demo

A demo that attempts to setup a minimalistic Symfony application.

Inspired by: http://www.whitewashing.de/2014/10/26/symfony_all_the_things_web.html

[![Build Status](https://img.shields.io/travis/alcohol/sf-minimal-demo/master.svg?style=flat-square)](https://travis-ci.org/alcohol/sf-minimal-demo)

## Installing

``` bash
git clone https://github.com/alcohol/sf-minimal-demo.git
cd sf-minimal-demo
composer install
cp .env.example .env
```

## Configuring

Environment needs to be setup by adding a `.env` (see
[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)) file containing
something like:

``` shell
SYMFONY_ENV=dev
SYMFONY_DEBUG=1
SYMFONY__SECRET=super-secret-string
SYMFONY__MONOLOG_ACTION_LEVEL=debug
SYMFONY__REDIS__SCHEME=tcp
SYMFONY__REDIS__HOST=localhost
SYMFONY__REDIS__PORT=6379
```

> See [external
> parameters](http://symfony.com/doc/current/cookbook/configuration/external_parameters.html)
> regarding **SYMFONY__** prefixed environment variables.

Worth mentioning:
* It uses a directory structure similar to the one described
  [here](http://stackoverflow.com/questions/23993295/what-is-the-new-symfony-3-directory-structure/23994473#23994473),
  so make sure the **httpd** or **fcgi** user can write to `var/`.

## cURL Examples

``` bash
$ curl -X POST --data-binary 'Lorem ipsum' http://pastebin.tld
# 201 Created
# Location: /54ae
# X-Paste-Token: 99d6a7cb2f
http://pastebin.tld/54ae

$ curl http://pastebin.tld/54ae
# 200 OK
Lorem ipsum

$ curl -H 'X-Paste-Token: 99d6a7cb2f' -X PUT --data-binary 'Lipsum lorem' \
    http://pastebin.tld/54ae
# 204 No Content

$ curl http://pastebin.tld/54ae
# 200 OK
Lipsum lorem

$ curl -H 'X-Paste-Token: 99d6a7cb2f' -X DELETE \
    http://pastebin.tld/54ae
# 204 No Content
```

## Contributing

Feel free to submit a pull request or create an issue.

## License

Alcohol\sf-minimal-demo is licensed under the MIT license.
