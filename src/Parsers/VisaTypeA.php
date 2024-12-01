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

use function str_starts_with;
use function strlen;
use Itatsi\MrzParser\Contracts\ParserInterface;
use Itatsi\MrzParser\Enums\MrzType;

class VisaTypeA extends TravelDocumentType3 implements ParserInterface
{
    public static function isValidMrz(string $mrz): bool
    {
        $mrz = self::normalizeMrz($mrz);

        return str_starts_with($mrz, 'V') && strlen($mrz) === 88;
    }

    public static function getMrzType(): MrzType
    {
        return MrzType::VA;
    }
}
