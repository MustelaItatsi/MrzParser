<?php declare(strict_types=1);
/*
 * This file is part of MrzParser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser;

use function count;
use function str_split;
use function substr;

/**
 * @phpstan-import-type MrzRange from Parsers\AbstractParser
 */
class CheckDigit
{
    /** @var array<int|string,int> */
    private static array $characterValues = [
        '<' => 0,
        '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4,
        '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
        'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14,
        'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19,
        'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24,
        'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29,
        'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34,
        'Z' => 35,
    ];

    /**
     * @param MrzRange[] $ranges
     */
    public function __construct(
        private array $ranges,
        private int $checkDigitOffset,
    ) {
    }

    public function calculateCheckDigit(string $mrz): int
    {
        return self::calcCheckDigit($this->getCheckedString($mrz));
    }

    public function isCheckDigitValidInMrz(string $mrz): bool
    {
        return $this->calculateCheckDigit($mrz) === $this->getCheckDigitFromMrz($mrz);
    }

    public function getCheckDigitFromMrz(string $mrz): int
    {
        return (int) $mrz[$this->checkDigitOffset];
    }

    private function getCheckedString(string $mrz): string
    {
        $result = '';

        foreach ($this->ranges as $range) {
            $result .= substr($mrz, ...$range);
        }

        return $result;
    }

    private static function calcCheckDigit(string $input): int
    {
        $weights = [7, 3, 1];
        $sum     = 0;

        foreach (str_split($input) as $index => $char) {
            $value  = self::$characterValues[$char];
            $weight = $weights[$index % count($weights)];
            $sum += $value * $weight;
        }

        return $sum % 10;
    }
}
