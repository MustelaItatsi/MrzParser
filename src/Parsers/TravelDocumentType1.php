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

class TravelDocumentType1 extends AbstractParser implements ParserInterface
{
    protected const FIELD_POS = [
        'documentCode'   => ['offset' => 0, 'length' => 2],
        'countryOfIssue' => ['offset' => 2, 'length' => 3],
        'fullName'       => ['offset' => 60, 'length' => 30],
        'documentNumber' => ['offset' => 5, 'length' => 9],
        'nationality'    => ['offset' => 45, 'length' => 3],
        'dateOfBirth'    => ['offset' => 30, 'length' => 6],
        'sex'            => ['offset' => 37, 'length' => 1],
        'dateOfExpiry'   => ['offset' => 38, 'length' => 6],
    ];

    public static function isValidMrz(string $mrz): bool
    {
        $mrz = self::normalizeMrz($mrz);

        return strlen($mrz) === 90;
    }

    public static function getMrzType(): MrzType
    {
        return MrzType::TD1;
    }
}
