# MrzParser

[![Pipeline Status](https://gitlab.com/MustelaItatsi/MrzParser/badges/main/pipeline.svg)](https://gitlab.com/MustelaItatsi/MrzParser/-/commits/main)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mustelaitatsi/mrzparser.svg?style=flat-square)](https://packagist.org/packages/mustelaitatsi/mrzparser)
[![Total Downloads](https://img.shields.io/packagist/dt/mustelaitatsi/mrzparser.svg?style=flat-square)](https://packagist.org/packages/mustelaitatsi/mrzparser)
[![Coverage](https://gitlab.com/MustelaItatsi/MrzParser/badges/main/coverage.svg)](https://gitlab.com/MustelaItatsi/MrzParser/badges/main/coverage.svg)
[![Mutation testing badge](https://img.shields.io/endpoint?logo=null&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FMustelaItatsi%2FMrzParser%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/MustelaItatsi/MrzParser/main)

A PHP package for MRZ parsing.

I needed a method to parse mrz for a project. Unfortunately, many other mrz parsers for php either had the country decoded (I prefer to work with alpha3 codes rather than country names), few/no tests or even look abandoned.

That's why I wrote a small lib that takes care of this.

## Installation

You can install the package via composer:

```bash
composer require mustelaitatsi/mrzparser
```

## Usage

```php
require 'vendor/autoload.php';
$mrz = 'I<UTOD231458907<<<<<<<<<<<<<<<7408122F1204159UTO<<<<<<<<<<<6ERIKSSON<<ANNA<MARIA<<<<<<<<<<';
$document = MustelaItatsi\MrzParser\Facades\ParserFacade::parseMrz($mrz);
[
'mrzType'                    => MustelaItatsi\MrzParser\Enums\MrzType::TD1,
'documentCode'               => 'I',
'issuingStateOrOrganization' => 'UTO',
'primaryIdentifier'          => 'ERIKSSON',
'secondaryIdentifier'        => 'ANNA MARIA',
'documentNumber'             => 'D23145890',
'nationality'                => 'UTO',
'dateOfBirth'                => '740812',
'sex'                        => 'F',
'dateOfExpiry'               => '120415',
'checkDigits'                => [
    'documentNumber' => [
        'value'      => 7,
        'calculated' => 7,
        'isValid'    => true,
    ],
    'dateOfBirth' => [
        'value'      => 2,
        'calculated' => 2,
        'isValid'    => true,
    ],
    'dateOfExpiry' => [
        'value'      => 9,
        'calculated' => 9,
        'isValid'    => true,
    ],
    'overall' => [
        'value'      => 6,
        'calculated' => 6,
        'isValid'    => true,
    ],
],
];
```

## Testing

```bash
composer test
```
