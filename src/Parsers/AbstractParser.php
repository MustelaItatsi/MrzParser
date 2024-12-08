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

use function explode;
use function str_replace;
use function strlen;
use function substr;
use function trim;
use Itatsi\MrzParser\CheckDigit;
use Itatsi\MrzParser\Document;
use Itatsi\MrzParser\Enums\MrzType;

/**
 * @phpstan-type MrzRange array{offset:int,length:int}
 */
abstract class AbstractParser
{
    /** @var array<string,array{offset:int,length:int}> */
    protected const FIELD_POS  = [];
    protected const LINELENGTH = 0;
    protected const LINECOUNT  = 2;

    /**
     * @var array<'combinedCheckDigit'|'dateOfBirth'|'dateOfExpiry'|'documentNumber',array{ranges:MrzRange[],checkDigitOffset:int}>
     */
    protected static array $checkDigits = [];

    public static function parse(string $mrz): Document
    {
        $mrz    = self::normalizeMrz($mrz);
        $result = [];

        foreach (static::FIELD_POS as $key => $value) {
            $result[$key] = substr($mrz, ...$value);
        }
        [$result['surname'], $result['givenNames']] = explode('<<', $result['fullName']);
        unset($result['fullName']);

        foreach ($result as $key => &$value) {
            $value = self::normalizeField($value);
        }

        $document = (new Document)
            ->setMrzType(static::getMrzType())
            ->setDocumentCode($result['documentCode'])
            ->setCountryOfIssue($result['countryOfIssue'])
            ->setSurname($result['surname'])
            ->setGivenNames($result['givenNames'])
            ->setDocumentNumber($result['documentNumber'])
            ->setNationality($result['nationality'])
            ->setDateOfBirth($result['dateOfBirth'])
            ->setSex($result['sex'])
            ->setDateOfExpiry($result['dateOfExpiry']);
        $checkDigitArray = [];

        foreach (static::$checkDigits as $key => $checkDigitConfig) {
            $checkDigit            = new CheckDigit(...$checkDigitConfig);
            $checkDigitArray[$key] = [
                'value'      => $checkDigit->getCheckDigitFromMrz($mrz),
                'calculated' => $checkDigit->calculateCheckDigit($mrz),
                'isValid'    => $checkDigit->isCheckDigitValidInMrz($mrz),
            ];
        }
        $document->setCheckDigits($checkDigitArray);

        return $document;
    }

    public static function isValidMrz(string $mrz): bool
    {
        $mrz = self::normalizeMrz($mrz);

        return strlen($mrz) === static::LINELENGTH * static::LINECOUNT;
    }

    abstract public static function getMrzType(): MrzType;

    private static function normalizeMrz(string $mrz): string
    {
        return str_replace(["\n", ' '], '', $mrz);
    }

    private static function normalizeField(string $value): ?string
    {
        $value = trim($value, '<');
        $value = str_replace('<', ' ', $value);

        if ($value === '') {
            $value = null;
        }

        return $value;
    }
}
