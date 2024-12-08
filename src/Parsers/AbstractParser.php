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
    protected const FIELD_POS = [];

    /**
     * @var array<'combinedCheckDigit'|'dateOfBirth'|'dateOfExpiry'|'documentNumber',array{ranges:MrzRange[],checkDigitOffset:int}>
     */
    protected static array $checkDigits = [];

    public static function parse(string $mrz): Document
    {
        $mrz    = static::normalizeMrz($mrz);
        $result = [];

        foreach (static::FIELD_POS as $key => $value) {
            $result[$key] = substr($mrz, ...$value);
        }
        [$result['surname'], $result['givenNames']] = explode('<<', $result['fullName']);
        unset($result['fullName']);

        foreach ($result as $key => &$value) {
            // @phpstan-ignore staticClassAccess.privateMethod
            $value = static::normalizeField($value);
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

    abstract public static function getMrzType(): MrzType;

    protected static function normalizeMrz(string $mrz): string
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
