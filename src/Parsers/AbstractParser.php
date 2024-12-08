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
use Itatsi\MrzParser\Document;
use Itatsi\MrzParser\Enums\MrzType;

abstract class AbstractParser
{
    /** @var array<string,array{offset:int,length:int}> */
    protected const FIELD_POS = [];

    public static function parse(string $mrz): Document
    {
        $mrz = static::normalizeMrz($mrz);

        return new Document(
            static::getMrzType(),
            // @phpstan-ignore staticClassAccess.privateMethod
            ...static::getFields($mrz),
        );
    }

    abstract public static function getMrzType(): MrzType;

    protected static function normalizeMrz(string $mrz): string
    {
        return str_replace(["\n", ' '], '', $mrz);
    }

    /**
     * @return array<string,string>
     */
    private static function getFields(string $mrz): array
    {
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

        return $result;
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
