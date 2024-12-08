<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Itatsi\MrzParser\Parsers;

use function strlen;
use Itatsi\MrzParser\Contracts\ParserInterface;
use Itatsi\MrzParser\Enums\MrzType;

class TravelDocumentType2 extends AbstractParser implements ParserInterface
{
    protected const FIELD_POS = [
        'documentCode'   => ['offset' => 0, 'length' => 2],
        'countryOfIssue' => ['offset' => 2, 'length' => 3],
        'fullName'       => ['offset' => 5, 'length' => 31],
        'documentNumber' => ['offset' => 36, 'length' => 9],
        'nationality'    => ['offset' => 46, 'length' => 3],
        'dateOfBirth'    => ['offset' => 49, 'length' => 6],
        'sex'            => ['offset' => 56, 'length' => 1],
        'dateOfExpiry'   => ['offset' => 57, 'length' => 6],
    ];
    protected static array $checkDigits = [
        'documentNumber'     => ['ranges' => [['offset' => 36 + 0, 'length' => 9]], 'checkDigitOffset' => 36 + 9],
        'dateOfBirth'        => ['ranges' => [['offset' => 36 + 13, 'length' => 6]], 'checkDigitOffset' => 36 + 19],
        'dateOfExpiry'       => ['ranges' => [['offset' => 36 + 21, 'length' => 6]], 'checkDigitOffset' => 36 + 27],
        'combinedCheckDigit' => ['ranges' => [
            ['offset' => 36 + 0, 'length' => 10],
            ['offset' => 36 + 13, 'length' => 7],
            ['offset' => 36 + 21, 'length' => 7],
        ], 'checkDigitOffset' => 36 + 35],
    ];

    public static function isValidMrz(string $mrz): bool
    {
        $mrz = self::normalizeMrz($mrz);

        return strlen($mrz) === 72;
    }

    public static function getMrzType(): MrzType
    {
        return MrzType::TD2;
    }
}
