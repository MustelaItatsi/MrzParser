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

use function ucfirst;
use MustelaItatsi\MrzParser\Document;
use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;
use MustelaItatsi\MrzParser\Facades\ParserFacade;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DocumentTest extends TestCase
{
    /**
     * @SuppressWarnings(PHPMD)
     */
    public static function parseDataProvider(): array
    {
        return [
            'FRA-BO-02002 with FRA_ID' => [
                "IDFRABERTHIER<<<<<<<<<<<<<<<<<<<<<<<\n" .
                '9409923102854CORINNE<<<<<<<6512068F4',
                [
                    'mrzType'                    => MrzType::FRA_ID,
                    'documentCode'               => 'ID',
                    'issuingStateOrOrganization' => 'FRA',
                    'primaryIdentifier'          => 'BERTHIER',
                    'secondaryIdentifier'        => 'CORINNE',
                    'nationality'                => 'FRA',
                    'documentNumber'             => '940992310285',
                    'dateOfBirth'                => '651206',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '040901',
                    'checkDigits'                => [
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'FRA-FRA_ID issueYear=14 (+15yr boundary)' => [
                "IDFRAMOREAU<<<<<<<<<<<<<<<<<<<<<<<<< \n" .
                '1406002345678ALICE<<<<<<<<<9201154F2',
                [
                    'mrzType'                    => MrzType::FRA_ID,
                    'documentCode'               => 'ID',
                    'issuingStateOrOrganization' => 'FRA',
                    'primaryIdentifier'          => 'MOREAU',
                    'secondaryIdentifier'        => 'ALICE',
                    'documentNumber'             => '140600234567',
                    'nationality'                => 'FRA',
                    'dateOfBirth'                => '920115',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '290601',
                    'checkDigits'                => [
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 2,
                            'calculated' => 2,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'FRA-FRA_ID issueYear=50 (+15yr boundary)' => [
                "IDFRASIMON<<<<<<<<<<<<<<<<<<<<<<<<<<\n" .
                '5008003456780PAUL<<<<<<<<<<7503201M6',
                [
                    'mrzType'        => MrzType::FRA_ID,
                    'documentNumber' => '500800345678',
                    'nationality'    => 'FRA',
                    'dateOfExpiry'   => '650801',
                    'checkDigits'    => [
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 0,
                            'calculated' => 0,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'FRA-BO-03001 with TD1' => [
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
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 9,
                            'calculated' => 9,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'DEU-BO-02004 with TD1' => [
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
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 2,
                            'calculated' => 2,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 5,
                            'calculated' => 5,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'DEU-BP-03001 with TD2' => [
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
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 5,
                            'calculated' => 5,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'ESP-AO-05001 with TD3' => [
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
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 9,
                            'calculated' => 9,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'USA-CO-01001 with VA' => [
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
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 0,
                            'calculated' => 0,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 1,
                            'calculated' => 1,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'FRA-CO-03001 with VB' => [
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
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 5,
                            'calculated' => 5,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'Unknown Date of Birth' => [
                "IDD<<L01XPVM8J2<<<<<<<<<<<<<<<\n" .
                "<<<<<<6<3108011D<<2108<<<<<<<5\n" .
                'MUSTERMANN<<ERIKA<<<<<<<<<<<<<',
                [
                    'dateOfBirth' => 'XXXXXX',
                ],
            ],
            'Partially Unknown Date of Birth' => [
                "IDD<<L01XPVM8J2<<<<<<<<<<<<<<<\n" .
                "83<<<<6<3108011D<<2108<<<<<<<5\n" .
                'MUSTERMANN<<ERIKA<<<<<<<<<<<<<',
                [
                    'dateOfBirth' => '83XXXX',
                ],
            ],
            'Long Lastname' => [
                "IDD<<L01XPVM8J2<<<<<<<<<<<<<<<\n" .
                "<<<<<<6<3108011D<<2108<<<<<<<5\n" .
                'MUSTERMANNNNNNNNNNNNNNNNNNNNNN',
                [
                    'primaryIdentifier'   => 'MUSTERMANNNNNNNNNNNNNNNNNNNNNN',
                    'secondaryIdentifier' => null,
                ],
            ],
            'BEL-BO-03003' => [
                "IDBEL000590240<6013<<<<<<<<<<<\n" .
                "8512017F1311048BEL851201002007\n" .
                'REINARTZ<<ULRIKE<KATIA<E<<<<<<',
                [
                    'mrzType'                    => MrzType::BEL_ID,
                    'documentCode'               => 'ID',
                    'issuingStateOrOrganization' => 'BEL',
                    'primaryIdentifier'          => 'REINARTZ',
                    'secondaryIdentifier'        => 'ULRIKE KATIA E',
                    'nationality'                => 'BEL',
                    'documentNumber'             => '000590240601',
                    'dateOfBirth'                => '851201',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '131104',
                    'checkDigits'                => [
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 3,
                            'calculated' => 3,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 8,
                            'calculated' => 8,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'BEL synthetic: leading < in base doc number + 14-char continuation' => [
                "IDBEL<12345678<012345678901242\n" .
                "8001014F3012316BEL<<<<<<<<<<<0\n" .
                'SPECIMEN<<SPECIMEN<<<<<<<<<<<<',
                [
                    'mrzType'        => MrzType::BEL_ID,
                    'documentNumber' => '1234567801234567890124',
                    'checkDigits'    => [
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 2,
                            'calculated' => 2,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 6,
                            'calculated' => 6,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 0,
                            'calculated' => 0,
                            'isValid'    => true,
                        ],
                    ],
                ],
            ],
            'BEL-BO-11005' => [
                "IDBEL600001795<0152<<<<<<<<<<<\n" .
                "1301014F2311207BEL130101987398\n" .
                'SPECIMEN<<SPECIMEN<<<<<<<<<<<<',
                [
                    'mrzType'                    => MrzType::BEL_ID,
                    'documentCode'               => 'ID',
                    'issuingStateOrOrganization' => 'BEL',
                    'primaryIdentifier'          => 'SPECIMEN',
                    'secondaryIdentifier'        => 'SPECIMEN',
                    'nationality'                => 'BEL',
                    'documentNumber'             => '600001795015',
                    'dateOfBirth'                => '130101',
                    'sex'                        => 'F',
                    'dateOfExpiry'               => '231120',
                    'checkDigits'                => [
                        CheckDigitType::DOCUMENT_NUMBER => [
                            'extracted'  => 2,
                            'calculated' => 2,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_BIRTH => [
                            'extracted'  => 4,
                            'calculated' => 4,
                            'isValid'    => true,
                        ],
                        CheckDigitType::DATE_OF_EXPIRY => [
                            'extracted'  => 7,
                            'calculated' => 7,
                            'isValid'    => true,
                        ],
                        CheckDigitType::OVERALL => [
                            'extracted'  => 8,
                            'calculated' => 8,
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

        foreach ($expected as $key => $value) {
            // @phpstan-ignore method.dynamicName
            Assert::assertEquals($value, $document->{'get' . ucfirst($key)}());
        }
    }

    public function testUnsupportedMrzReturnsNull(): void
    {
        $document = ParserFacade::parseMrz('Not a MRZ');

        Assert::assertNull($document);
    }

    public function testGetterAndSetter(): void
    {
        $document = new Document;

        foreach (self::parseDataProvider()['FRA-BO-03001 with TD1'][1] as $key => $value) {
            // retuns same instance
            // @phpstan-ignore method.dynamicName
            Assert::assertEquals($document, $document->{'set' . ucfirst($key)}($value));

            // get given value
            // @phpstan-ignore method.dynamicName
            Assert::assertEquals($value, $document->{'get' . ucfirst($key)}());
        }
    }
}
