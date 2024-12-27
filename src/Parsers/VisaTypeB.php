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

use function str_starts_with;
use MustelaItatsi\MrzParser\Contracts\ParserInterface;
use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;

class VisaTypeB extends TravelDocumentType2 implements ParserInterface
{
    protected const MRZTYPE             = MrzType::VB;
    protected static array $checkDigits = [
        CheckDigitType::DOCUMENT_NUMBER => ['ranges' => [['offset' => 36 + 0, 'length' => 9]], 'checkDigitOffset' => 36 + 9],
        CheckDigitType::DATE_OF_BIRTH   => ['ranges' => [['offset' => 36 + 13, 'length' => 6]], 'checkDigitOffset' => 36 + 19],
        CheckDigitType::DATE_OF_EXPIRY  => ['ranges' => [['offset' => 36 + 21, 'length' => 6]], 'checkDigitOffset' => 36 + 27],
    ];

    public static function isValidMrz(string $mrz): bool
    {
        return str_starts_with($mrz, 'V') && parent::isValidMrz($mrz);
    }
}
