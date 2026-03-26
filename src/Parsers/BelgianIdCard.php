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

use function rtrim;
use function str_replace;
use function str_starts_with;
use function substr;
use function trim;
use MustelaItatsi\MrzParser\CheckDigit;
use MustelaItatsi\MrzParser\Contracts\ParserInterface;
use MustelaItatsi\MrzParser\Document;
use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;

class BelgianIdCard extends AbstractParser implements ParserInterface
{
    protected const LINELENGTH = 30;
    protected const LINECOUNT  = 3;
    protected const MRZTYPE    = MrzType::BEL_ID;

    /** @var array<string,array{offset:int,length:int}> */
    protected const FIELD_POS = [
        'documentCode'               => ['offset' => 0,  'length' => 2],
        'issuingStateOrOrganization' => ['offset' => 2,  'length' => 3],
        'documentNumber'             => ['offset' => 5,  'length' => 9],
        'fullName'                   => ['offset' => 60, 'length' => 30],
        'nationality'                => ['offset' => 45, 'length' => 3],
        'dateOfBirth'                => ['offset' => 30, 'length' => 6],
        'sex'                        => ['offset' => 37, 'length' => 1],
        'dateOfExpiry'               => ['offset' => 38, 'length' => 6],
    ];

    // DOCUMENT_NUMBER is excluded here — handled dynamically in parse() because
    // the check digit position depends on the length of the continuation.
    protected static array $checkDigits = [
        CheckDigitType::DATE_OF_BIRTH  => ['ranges' => [['offset' => 30, 'length' => 6]], 'checkDigitOffset' => 36],
        CheckDigitType::DATE_OF_EXPIRY => ['ranges' => [['offset' => 38, 'length' => 6]], 'checkDigitOffset' => 44],
        CheckDigitType::OVERALL        => ['ranges' => [
            ['offset' => 5,  'length' => 25],
            ['offset' => 30, 'length' => 7],
            ['offset' => 38, 'length' => 7],
            ['offset' => 48, 'length' => 11],
        ], 'checkDigitOffset' => 59],
    ];

    public static function isValidMrz(string $mrz): bool
    {
        return str_starts_with($mrz, 'IDBEL') && parent::isValidMrz($mrz);
    }

    public static function parse(string $mrz): Document
    {
        $document      = parent::parse($mrz);
        $normalizedMrz = self::normalizeMrz($mrz);

        // Arg0s1080 algorithm: strip trailing '<' from optional1 (pos 15–29);
        // the last remaining char is the variable-position check digit,
        // everything before it is the continuation of the document number.

        $stripped      = rtrim(substr($normalizedMrz, 15, 15), '<');
        $continuation  = substr($stripped, 0, -1);
        $baseDocNumber = substr($normalizedMrz, 5, 9);
        $extDocNumber  = $baseDocNumber . $continuation;

        $document->setDocumentNumber(trim(str_replace('<', ' ', $extDocNumber)));

        $extractedCd = (int) substr($stripped, -1);

        // Belgian ID generations differ on whether position 14 ('<') is included in
        // the CD input string. Position 14 is always '<' (=0) but it shifts the ICAO
        // [7,3,1] weight cycle for subsequent characters, so the two variants are not
        // equivalent. Try filler-inclusive first; fall back to filler-exclusive.
        $calculatedWithFiller = CheckDigit::calcCheckDigit($baseDocNumber . '<' . $continuation);
        $calculatedCd         = $calculatedWithFiller === $extractedCd
            ? $calculatedWithFiller
            : CheckDigit::calcCheckDigit($extDocNumber);

        $checkDigits = $document->getCheckDigits();

        $checkDigits[CheckDigitType::DOCUMENT_NUMBER] = [
            'extracted'  => $extractedCd,
            'calculated' => $calculatedCd,
            'isValid'    => $calculatedCd === $extractedCd,
        ];
        $document->setCheckDigits($checkDigits);

        return $document;
    }
}
