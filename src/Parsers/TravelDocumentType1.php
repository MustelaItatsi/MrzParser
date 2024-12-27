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

use MustelaItatsi\MrzParser\Contracts\ParserInterface;
use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;

class TravelDocumentType1 extends AbstractParser implements ParserInterface
{
    protected const MRZTYPE    = MrzType::TD1;
    protected const LINELENGTH = 30;
    protected const LINECOUNT  = 3;
    protected const FIELD_POS  = [
        'documentCode'               => ['offset' => 0, 'length' => 2],
        'issuingStateOrOrganization' => ['offset' => 2, 'length' => 3],
        'fullName'                   => ['offset' => 60, 'length' => 30],
        'documentNumber'             => ['offset' => 5, 'length' => 9],
        'nationality'                => ['offset' => 45, 'length' => 3],
        'dateOfBirth'                => ['offset' => 30, 'length' => 6],
        'sex'                        => ['offset' => 37, 'length' => 1],
        'dateOfExpiry'               => ['offset' => 38, 'length' => 6],
    ];
    protected static array $checkDigits = [
        CheckDigitType::DOCUMENT_NUMBER => ['ranges' => [['offset' => 5, 'length' => 9]], 'checkDigitOffset' => 14],
        CheckDigitType::DATE_OF_BIRTH   => ['ranges' => [['offset' => 30, 'length' => 6]], 'checkDigitOffset' => 30 + 6],
        CheckDigitType::DATE_OF_EXPIRY  => ['ranges' => [['offset' => 30 + 8, 'length' => 6]], 'checkDigitOffset' => 30 + 14],
        CheckDigitType::OVERALL         => ['ranges' => [
            ['offset' => 5, 'length' => 25],
            ['offset' => 30, 'length' => 7],
            ['offset' => 30 + 8, 'length' => 7],
            ['offset' => 30 + 18, 'length' => 11],
        ], 'checkDigitOffset' => 30 + 29],
    ];
}
