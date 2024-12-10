<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser;

use function count;
use function strlen;
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

    private static function calcCheckDigit(string $str): int
    {
        // from https://github.com/tetrahydra/SolidusMRZ/blob/master/mrz.php#L480
        $nmbrs     = [];
        $weighting = [7, 3, 1];

        for ($i = 0; $i < strlen($str); $i++) {
            $nmbrs[] = self::$characterValues[$str[$i]];
        }

        $curWeight = 0;
        $total     = 0;

        for ($j = 0; $j < count($nmbrs); $j++) {
            $total += $nmbrs[$j] * $weighting[$curWeight];
            $curWeight++;

            if ($curWeight === 3) {
                $curWeight = 0;
            }
        }

        return $total % 10;
    }
}
