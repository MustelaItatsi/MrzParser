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

use function explode;
use function str_replace;
use function str_starts_with;
use function strlen;
use function substr;
use function trim;
use MustelaItatsi\MrzParser\CheckDigit;
use MustelaItatsi\MrzParser\Document;
use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;

/**
 * @phpstan-type MrzRange array{offset:int,length:int}
 */
abstract class AbstractParser
{
    /** @var array<string,array{offset:int,length:int}> */
    protected const FIELD_POS  = [];
    protected const LINELENGTH = 0;
    protected const LINECOUNT  = 2;
    protected const MRZTYPE    = MrzType::TD1;

    /**
     * @var array<CheckDigitType::*,array{ranges:MrzRange[],checkDigitOffset:int}>
     */
    protected static array $checkDigits = [];

    public static function isValidMrz(string $mrz): bool
    {
        $mrz = self::normalizeMrz($mrz);

        return strlen($mrz) === static::LINELENGTH * static::LINECOUNT;
    }

    public static function parse(string $mrz): Document
    {
        $mrz    = self::normalizeMrz($mrz);
        $result = [];

        foreach (static::FIELD_POS as $key => $value) {
            $result[$key] = substr($mrz, ...$value);
        }
        [$result['primaryIdentifier'], $result['secondaryIdentifier']] = explode('<<', $result['fullName']);
        unset($result['fullName']);

        foreach ($result as $key => &$value) {
            $isDate = str_starts_with($key, 'date');
            $value  = self::normalizeField($value, $isDate);
        }

        $document = (new Document)
            ->setMrzType(self::getMrzType())
            ->setDocumentCode($result['documentCode'])
            ->setIssuingStateOrOrganization($result['issuingStateOrOrganization'])
            ->setPrimaryIdentifier($result['primaryIdentifier'])
            ->setSecondaryIdentifier($result['secondaryIdentifier'])
            ->setDocumentNumber($result['documentNumber'])
            ->setNationality($result['nationality'])
            ->setDateOfBirth($result['dateOfBirth'])
            ->setSex($result['sex'])
            ->setDateOfExpiry($result['dateOfExpiry']);
        $checkDigitArray = [];

        foreach (static::$checkDigits as $key => $checkDigitConfig) {
            $checkDigit            = new CheckDigit(...$checkDigitConfig);
            $checkDigitArray[$key] = [
                'extracted'  => $checkDigit->getCheckDigitFromMrz($mrz),
                'calculated' => $checkDigit->calculateCheckDigit($mrz),
                'isValid'    => $checkDigit->isCheckDigitValidInMrz($mrz),
            ];
        }
        $document->setCheckDigits($checkDigitArray);

        return $document;
    }

    private static function getMrzType(): MrzType
    {
        return static::MRZTYPE;
    }

    private static function normalizeMrz(string $mrz): string
    {
        return str_replace(["\n", ' '], '', $mrz);
    }

    private static function normalizeField(string $value, bool $isDate): ?string
    {
        if ($isDate) {
            $value = str_replace('<', 'X', $value);
        }
        $value = trim($value, '<');
        $value = str_replace('<', ' ', $value);

        if ($value === '') {
            $value = null;
        }

        return $value;
    }
}
