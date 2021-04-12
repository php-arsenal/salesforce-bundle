# salesforce-bundle

[![Release](https://img.shields.io/github/v/release/php-arsenal/salesforce-bundle)](https://github.com/php-arsenal/salesforce-bundle/releases)
[![Travis](https://img.shields.io/travis/php-arsenal/salesforce-bundle)](https://travis-ci.org/php-arsenal/salesforce-bundle)
[![Test Coverage](https://img.shields.io/codeclimate/coverage/php-arsenal/salesforce-bundle)](https://codeclimate.com/github/php-arsenal/salesforce-bundle)

## Introduction

Symfony bundle for the Salesforce SOAP client.

add to your config yml:

```yaml
arsenal_salesforce:
   soap_client:
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
