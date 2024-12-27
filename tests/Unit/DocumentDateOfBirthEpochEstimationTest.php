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

use DateInterval;
use DateTimeImmutable;
use MustelaItatsi\MrzParser\Document;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Document::class)]
final class DocumentDateOfBirthEpochEstimationTest extends TestCase
{
    public static function dateOfBirthProvider(): array
    {
        $now      = new DateTimeImmutable;
        $tomorrow = $now->modify('+1 day');

        return [
            'date in the past century' => [
                '990421',
                '1999-04-21',
            ],
            'date in the current century' => [
                '200113',
                '2020-01-13',
            ],
            'today in the current century' => [
                $now->format('ymd'),
                $now->format('Y-m-d'),
            ],
            'future date treated as past century' => [
                $tomorrow->format('ymd'),
                $tomorrow->sub(new DateInterval('P100Y'))->format('Y-m-d'),
            ],
            'invalid date format' => [
                'invalid',
                null,
            ],
        ];
    }

    #[DataProvider('dateOfBirthProvider')]
    public function testGetDateOfBirthWithEstimatedEpoch(string $inputDate, ?string $expectedDate): void
    {
        $document = new Document;
        $document->setDateOfBirth($inputDate);

        $result = $document->getDateOfBirthWithEstimatedEpoch();

        Assert::assertSame($expectedDate, $result);
    }
}
