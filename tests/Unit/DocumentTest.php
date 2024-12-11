<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Tests\Unit;

use function ucfirst;
use MustelaItatsi\MrzParser\Document;
use MustelaItatsi\MrzParser\Enums\MrzType;
use MustelaItatsi\MrzParser\Facades\ParserFacade;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD)
     */
    public static function parseDataProvider(): array
    {
        return [
            'FRA-BO-03001' => [
                "IDFRAX4RTBPFW46<<<<<<<<<<<<<<<\n" .
                "9007138F3002119FRA<<<<<<<<<<<6\n" .
                'MARTIN<<MAELYS<GAELLE<MARIE<<<',
                [
                    'mrzType'                    => MrzType::TD1,
                    'documentCode'               => 'ID',
                    'issuingStateOrOrganization' => 'FRA',
                    'primaryIdentifier'          => 'MARTIN',
                    'secondaryIdentifier'        => 'MAELYS GAELLE MARIE',
                    'documentNumber'             => 'X4RTBPFW4',
                    'nationality'                => 'FRA',
                    'dateOfBirth'                => '900713',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '300211',
                    'checkDigits'                => [
                        'documentNumber' => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                        'dateOfBirth' => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        'dateOfExpiry' => [
                            'extracted'  => 9,
                            'calculated' => 9,
                            'isValid'    => true,
                        ],
                        'overall' => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'DEU-BO-02004' => [
                "IDD<<L01XPVM8J2<<<<<<<<<<<<<<<\n" .
                "8308126<3108011D<<2108<<<<<<<5\n" .
                'MUSTERMANN<<ERIKA<<<<<<<<<<<<<',
                [
                    'mrzType'                    => MrzType::TD1,
                    'documentCode'               => 'ID',
                    'issuingStateOrOrganization' => 'D',
                    'primaryIdentifier'          => 'MUSTERMANN',
                    'secondaryIdentifier'        => 'ERIKA',
                    'documentNumber'             => 'L01XPVM8J',
                    'nationality'                => 'D',
                    'dateOfBirth'                => '830812',
                    'sex'                        => null,
                    'dateOfExpiry'               => '310801',
                    'checkDigits'                => [
                        'documentNumber' => [
                            'extracted'  => 2,
                            'calculated' => 2,
                            'isValid'    => true,
                        ],
                        'dateOfBirth' => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                        'dateOfExpiry' => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                        'overall' => [
                            'extracted'  => 5,
                            'calculated' => 5,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'DEU-BP-03001' => [
                "ITD<<MUSTERMANN<<ERIKA<<<<<<<<<<<<<<\n" .
                'C<00000004D<<6408125F0404011<<<<<<<4',
                [
                    'mrzType'                    => MrzType::TD2,
                    'documentCode'               => 'IT',
                    'issuingStateOrOrganization' => 'D',
                    'primaryIdentifier'          => 'MUSTERMANN',
                    'secondaryIdentifier'        => 'ERIKA',
                    'documentNumber'             => 'C 0000000',
                    'nationality'                => 'D',
                    'dateOfBirth'                => '640812',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '040401',
                    'checkDigits'                => [
                        'documentNumber' => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        'dateOfBirth' => [
                            'extracted'  => 5,
                            'calculated' => 5,
                            'isValid'    => true,
                        ],
                        'dateOfExpiry' => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                        'overall' => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'ESP-AO-05001' => [
                "P<ESPESPANOLA<ESPANOLA<<CARMEN<<<<<<<<<<<<<<\n" .
                'ZAB0002549ESP8001014F2501017A9999999900<<<44',
                [
                    'mrzType'                    => MrzType::TD3,
                    'documentCode'               => 'P',
                    'issuingStateOrOrganization' => 'ESP',
                    'primaryIdentifier'          => 'ESPANOLA ESPANOLA',
                    'secondaryIdentifier'        => 'CARMEN',
                    'documentNumber'             => 'ZAB000254',
                    'nationality'                => 'ESP',
                    'dateOfBirth'                => '800101',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '250101',
                    'checkDigits'                => [
                        'documentNumber' => [
                            'extracted'  => 9,
                            'calculated' => 9,
                            'isValid'    => true,
                        ],
                        'dateOfBirth' => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        'dateOfExpiry' => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                        'overall' => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'USA-CO-01001' => [
                "VNUSAHAPPY<<TRAVELER<<<<<<<<<<<<<<<<<<<<<<<<\n" .
                '0000000<<0CYP0001018M1601231B2NCS00E0F060308',
                [
                    'mrzType'                    => MrzType::VA,
                    'documentCode'               => 'VN',
                    'issuingStateOrOrganization' => 'USA',
                    'primaryIdentifier'          => 'HAPPY',
                    'secondaryIdentifier'        => 'TRAVELER',
                    'documentNumber'             => '0000000',
                    'nationality'                => 'CYP',
                    'dateOfBirth'                => '000101',
                    'sex'                        => 'M',
                    'dateOfExpiry'               => '160123',
                    'checkDigits'                => [
                        'documentNumber' => [
                            'extracted'  => 0,
                            'calculated' => 0,
                            'isValid'    => true,
                        ],
                        'dateOfBirth' => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        'dateOfExpiry' => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'FRA-CO-03001' => [
                "VEFRAWANG<<YI<CHENG<<<<<<<<<<<<<<<<<\n" .
                '1234567897CHN8001014M1909155<M900618',
                [
                    'mrzType'                    => MrzType::VB,
                    'documentCode'               => 'VE',
                    'issuingStateOrOrganization' => 'FRA',
                    'primaryIdentifier'          => 'WANG',
                    'secondaryIdentifier'        => 'YI CHENG',
                    'documentNumber'             => '123456789',
                    'nationality'                => 'CHN',
                    'dateOfBirth'                => '800101',
                    'sex'                        => 'M',
                    'dateOfExpiry'               => '190915',
                    'checkDigits'                => [
                        'documentNumber' => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                        'dateOfBirth' => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        'dateOfExpiry' => [
                            'extracted'  => 5,
                            'calculated' => 5,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('parseDataProvider')]
    public function testParse(string $mrz, array $expected): void
    {
        $document = ParserFacade::parseMrz($mrz);

        Assert::assertEquals($expected, $document?->toArray());
    }

    public function testGetterAndSetter(): void
    {
        $document = new Document;

        foreach (self::parseDataProvider()['FRA-BO-03001'][1] as $key => $value) {
            // retuns same instance
            // @phpstan-ignore method.dynamicName
            Assert::assertSame($document, $document->{'set' . ucfirst($key)}($value));

            // get given value
            // @phpstan-ignore method.dynamicName
            Assert::assertEquals($value, $document->{'get' . ucfirst($key)}());
        }
    }
}
