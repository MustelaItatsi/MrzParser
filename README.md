# MrzParser
[![Latest Version on Packagist](https://img.shields.io/packagist/v/itatsi/mrzparser.svg?style=flat-square)](https://packagist.org/packages/itatsi/mrzparser)
[![Tests](https://img.shields.io/github/actions/workflow/status/itatsi/mrzparser/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/itatsi/mrzparser/actions/workflows/run-tests.yml)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2FMrzParser)](https://dashboard.stryker-mutator.io/reports/MrzParser)
[![Total Downloads](https://img.shields.io/packagist/dt/itatsi/mrzparser.svg?style=flat-square)](https://packagist.org/packages/itatsi/mrzparser)

A PHP package for MRZ parsing

## Installation

You can install the package via composer:

```bash
composer require itatsi/mrzparser
```

## Usage

```php
require 'vendor/autoload.php';
$mrz = 'I<UTOD231458907<<<<<<<<<<<<<<<7408122F1204159UTO<<<<<<<<<<<6ERIKSSON<<ANNA<MARIA<<<<<<<<<<';
$document = Itatsi\MrzParser\Facades\ParserFacade::parseMrz($mrz);
print_r($document->toArray());
Array
(
    [mrzType] => Itatsi\MrzParser\Enums\MrzType Enum
        (
            [name] => TD1
        )

    [documentCode] => I
    [countryOfIssue] => UTO
    [surname] => ERIKSSON
    [givenNames] => ANNA MARIA
    [documentNumber] => D23145890
    [nationality] => UTO
    [dateOfBirth] => 740812
    [sex] => F
    [dateOfExpiry] => 120415
)
```

## Testing

```bash
composer test
```
