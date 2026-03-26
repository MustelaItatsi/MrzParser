# MrzParser

[![Pipeline Status](https://gitlab.com/MustelaItatsi/MrzParser/badges/main/pipeline.svg)](https://gitlab.com/MustelaItatsi/MrzParser/-/commits/main)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mustelaitatsi/mrzparser.svg)](https://packagist.org/packages/mustelaitatsi/mrzparser)
[![Total Downloads](https://img.shields.io/packagist/dt/mustelaitatsi/mrzparser.svg)](https://packagist.org/packages/mustelaitatsi/mrzparser)
[![Coverage](https://gitlab.com/MustelaItatsi/MrzParser/badges/main/coverage.svg)](https://gitlab.com/MustelaItatsi/MrzParser/badges/main/coverage.svg)
[![Mutation testing badge](https://img.shields.io/badge/mutation%20score-100%25-brightgreen)](https://gitlab.com/MustelaItatsi/MrzParser)
[![License](https://img.shields.io/packagist/l/mustelaitatsi/mrzparser.svg)](https://gitlab.com/MustelaItatsi/MrzParser/-/blob/main/LICENSE)

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

$document->getMrzType()                        => MustelaItatsi\MrzParser\Enums\MrzType::TD1,
$document->getDocumentCode()                   => 'I',
$document->getIssuingStateOrOrganization()     => 'UTO',
$document->getPrimaryIdentifier()              => 'ERIKSSON',
$document->getSecondaryIdentifier()            => 'ANNA MARIA',
$document->getDocumentNumber()                 => 'D23145890',
$document->getNationality()                    => 'UTO',
$document->getDateOfBirth()                    => '740812',
$document->getDateOfBirthWithEstimatedEpoch()  => '1974-08-12',
$document->getSex()                            => 'F',
$document->getDateOfExpiry()                   => '120415',
$document->getDateOfExpiryWithEstimatedEpoch() => '2012-04-15',

$document->getCheckDigits() => [
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
]
```

## API Stability

Only `ParserFacade::parseMrz()` and `DocumentInterface` are stable public contracts. Downstream
consumers should depend only on these two. All other classes, enum cases, and interfaces are
internal implementation details and may change in any release without notice.

## Testing

```bash
composer test
```
