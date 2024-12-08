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

use Itatsi\MrzParser\Contracts\ParserInterface;
use Itatsi\MrzParser\Enums\MrzType;

class TravelDocumentType3 extends AbstractParser implements ParserInterface
{
    protected const LINELENGTH = 44;
    protected const FIELD_POS  = [
        'documentCode'   => ['offset' => 0, 'length' => 2],
        'countryOfIssue' => ['offset' => 2, 'length' => 3],
        'fullName'       => ['offset' => 5, 'length' => 39],
        'documentNumber' => ['offset' => 44, 'length' => 9],
        'nationality'    => ['offset' => 54, 'length' => 3],
        'dateOfBirth'    => ['offset' => 57, 'length' => 6],
        'sex'            => ['offset' => 64, 'length' => 1],
        'dateOfExpiry'   => ['offset' => 65, 'length' => 6],
    ];
    protected static array $checkDigits = [
        'documentNumber'     => ['ranges' => [['offset' => 44 + 0, 'length' => 9]], 'checkDigitOffset' => 44 + 9],
        'dateOfBirth'        => ['ranges' => [['offset' => 44 + 13, 'length' => 6]], 'checkDigitOffset' => 44 + 19],
        'dateOfExpiry'       => ['ranges' => [['offset' => 44 + 21, 'length' => 6]], 'checkDigitOffset' => 44 + 27],
        'combinedCheckDigit' => ['ranges' => [
            ['offset' => 44 + 0, 'length' => 10],
            ['offset' => 44 + 13, 'length' => 7],
            ['offset' => 44 + 21, 'length' => 43 - 21],
        ], 'checkDigitOffset' => 44 + 43],
    ];

    public static function getMrzType(): MrzType
    {
        return MrzType::TD3;
    }
}
