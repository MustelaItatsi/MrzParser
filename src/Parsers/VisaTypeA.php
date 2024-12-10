<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Parsers;

use function str_starts_with;
use MustelaItatsi\MrzParser\Contracts\ParserInterface;
use MustelaItatsi\MrzParser\Enums\MrzType;

class VisaTypeA extends TravelDocumentType3 implements ParserInterface
{
    protected const MRZTYPE             = MrzType::VA;
    protected static array $checkDigits = [
        'documentNumber' => ['ranges' => [['offset' => 44 + 0, 'length' => 9]], 'checkDigitOffset' => 44 + 9],
        'dateOfBirth'    => ['ranges' => [['offset' => 44 + 13, 'length' => 6]], 'checkDigitOffset' => 44 + 19],
        'dateOfExpiry'   => ['ranges' => [['offset' => 44 + 21, 'length' => 6]], 'checkDigitOffset' => 44 + 27],
    ];

    public static function isValidMrz(string $mrz): bool
    {
        return str_starts_with($mrz, 'V') && parent::isValidMrz($mrz);
    }
}
