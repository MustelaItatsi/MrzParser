# MrzParser

[![Pipeline Status](https://gitlab.com/Itatsi/MrzParser/badges/main/pipeline.svg)](https://gitlab.com/Itatsi/MrzParser/-/commits/main)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/itatsi/mrzparser.svg?style=flat-square)](https://packagist.org/packages/itatsi/mrzparser)
[![Total Downloads](https://img.shields.io/packagist/dt/itatsi/mrzparser.svg?style=flat-square)](https://packagist.org/packages/itatsi/mrzparser)
[![Coverage](https://gitlab.com/Itatsi/MrzParser/badges/main/coverage.svg)](https://gitlab.com/Itatsi/MrzParser/badges/main/coverage.svg)
[![Mutation testing badge](https://img.shields.io/endpoint?logo=null&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FItatsi%2FMrzParser%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/Itatsi/MrzParser/main)

A PHP package for MRZ parsing.

I needed a method to parse mrz for a project. Unfortunately, many other mrz parsers for php either had the country decoded (I prefer to work with alpha3 codes rather than country names), few/no tests or even look abandoned.

That's why I wrote a small lib that takes care of this.

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
    [checkDigits] => Array
        (
            [documentNumber] => Array
                (
                    [value] => 7
                    [calculated] => 7
                    [isValid] => 1
                )
            [dateOfBirth] => Array
                (
                    [value] => 2
                    [calculated] => 2
                    [isValid] => 1
                )
            [dateOfExpiry] => Array
                (
                    [value] => 9
                    [calculated] => 9
                    [isValid] => 1
                )
            [combinedCheckDigit] => Array
                (
                    [value] => 6
                    [calculated] => 6
                    [isValid] => 1
                )
        )
)
```

## Testing

```bash
composer test
```
