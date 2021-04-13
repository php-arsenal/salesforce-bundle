# salesforce-bundle

[![Release](https://img.shields.io/github/v/release/php-arsenal/salesforce-bundle)](https://github.com/php-arsenal/salesforce-bundle/releases)
[![CI](https://img.shields.io/github/workflow/status/php-arsenal/salesforce-bundle/CI)](https://github.com/php-arsenal/salesforce-bundle/actions/workflows/ci.yml)
[![Packagist](https://img.shields.io/packagist/dt/php-arsenal/salesforce-bundle)](https://packagist.org/packages/php-arsenal/salesforce-bundle)

## Introduction

Symfony bundle for the Salesforce SOAP client.

## Installation

`composer require php-arsenal/salesforce-bundle`

add to your config yml:

```yaml
arsenal:
   soap_client_salesforce:
       wsdl: '%kernel.root_dir%/Resources/wsdl/%env(SALESFORCE_WSDL)%'
       username: '%env(SALESFORCE_USERNAME)%'
       password: '%env(SALESFORCE_PASSWORD)%'
       token: ~
```

add env vars yo your config and fill in the values
```
SALESFORCE_USERNAME=
SALESFORCE_PASSWORD=
# Your .wsdl file path
SALESFORCE_WSDL=
```
