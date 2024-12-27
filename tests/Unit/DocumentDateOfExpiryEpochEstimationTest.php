<?php declare(strict_types=1);
/*
 * This file is part of MrzParser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Tests\Unit;

use DateTimeImmutable;
use MustelaItatsi\MrzParser\Document;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Document::class)]
final class DocumentDateOfExpiryEpochEstimationTest extends TestCase
{
    public static function dateOfExpiryProvider(): array
    {
        $pastBeforeBreakpoint = new DateTimeImmutable('-20 years');
        $pastAfterBreakpoint  = $pastBeforeBreakpoint->modify('+1 day');

        return [
            'date in the past century' => [
                '990421',
                '2099-04-21',
            ],
            'date in the current century' => [
                '200113',
                '2020-01-13',
            ],
            'past older then 20 years is parsed as future' => [
                $pastBeforeBreakpoint->format('ymd'),
                $pastBeforeBreakpoint->modify('+100 years')->format('Y-m-d'),
            ],
            'past newer then 20 years is parsed as past' => [
                $pastAfterBreakpoint->format('ymd'),
                $pastAfterBreakpoint->format('Y-m-d'),
            ],
            'invalid date format' => [
                'invalid',
                null,
            ],
        ];
    }

    #[DataProvider('dateOfExpiryProvider')]
    public function testGetDateOfExpiryWithEstimatedEpoch(string $inputDate, ?string $expectedDate): void
    {
        $document = new Document;
        $document->setDateOfExpiry($inputDate);

        $result = $document->getDateOfExpiryWithEstimatedEpoch();

        Assert::assertSame($expectedDate, $result);
    }
}
