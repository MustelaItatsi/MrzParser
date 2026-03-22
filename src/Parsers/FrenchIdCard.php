<?php declare(strict_types=1);
/*
 * This file is part of MrzParser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Parsers;

use function sprintf;
use function str_starts_with;
use function substr;
use MustelaItatsi\MrzParser\Contracts\ParserInterface;
use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;

class FrenchIdCard extends AbstractParser implements ParserInterface
{
    protected const LINELENGTH = 36;
    protected const LINECOUNT  = 2;
    protected const MRZTYPE    = MrzType::FRA_ID;

    /** @var array<string,array{offset:int,length:int}> */
    protected const FIELD_POS = [
        'documentCode'               => ['offset' => 0,  'length' => 2],
        'issuingStateOrOrganization' => ['offset' => 2,  'length' => 3],
        'fullName'                   => ['offset' => 5,  'length' => 25],
        'secondaryIdentifier'        => ['offset' => 49, 'length' => 14],
        'documentNumber'             => ['offset' => 36, 'length' => 12],
        'dateOfBirth'                => ['offset' => 63, 'length' => 6],
        'sex'                        => ['offset' => 70, 'length' => 1],
    ];
    protected static array $checkDigits = [
        CheckDigitType::DOCUMENT_NUMBER => [
            'ranges'           => [['offset' => 36, 'length' => 12]],
            'checkDigitOffset' => 48,
        ],
        CheckDigitType::DATE_OF_BIRTH => [
            'ranges'           => [['offset' => 63, 'length' => 6]],
            'checkDigitOffset' => 69,
        ],
        CheckDigitType::OVERALL => [
            'ranges' => [
                ['offset' => 0,  'length' => 36],
                ['offset' => 36, 'length' => 35],
            ],
            'checkDigitOffset' => 71,
        ],
    ];

    public static function isValidMrz(string $mrz): bool
    {
        return str_starts_with($mrz, 'IDFRA') && parent::isValidMrz($mrz);
    }

    /** @param array<string,null|string> $result */
    protected static function resolveNationality(array $result): string
    {
        return 'FRA';
    }

    /** @param array<string,null|string> $result */
    protected static function resolveDateOfExpiry(array $result): string
    {
        $issueYear  = (int) substr($result['documentNumber'], 0, 2);
        $issueMonth = substr($result['documentNumber'], 2, 2);
        $yearsToAdd = ($issueYear < 14 || $issueYear > 50) ? 10 : 15;

        return sprintf('%02d%s01', ($issueYear + $yearsToAdd) % 100, $issueMonth);
    }
}
