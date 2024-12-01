<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Itatsi\MrzParser\Tests\Unit;

use function ucfirst;
use Itatsi\MrzParser\Enums\MrzType;
use Itatsi\MrzParser\Facades\ParserFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public static function parseDataProvider(): array
    {
        return [
            'FRA-BO-03001' => [
                "IDFRAX4RTBPFW46<<<<<<<<<<<<<<<\n" .
                "9007138F3002119FRA<<<<<<<<<<<6\n" .
                'MARTIN<<MAELYS<GAELLE<MARIE<<<',
                [
                    'mrzType'        => MrzType::TD1,
                    'documentCode'   => 'ID',
                    'countryOfIssue' => 'FRA',
                    'surname'        => 'MARTIN',
                    'givenNames'     => 'MAELYS GAELLE MARIE',
                    'documentNumber' => 'X4RTBPFW4',
                    'nationality'    => 'FRA',
                    'dateOfBirth'    => '900713',
                    'sex'            => 'F',
                    'dateOfExpiry'   => '300211',
                ]],
            'DEU-BO-02004' => [
                "IDD<<LOIXPVMEJ2<<<<<<<<<<<<<<<\n" .
                "8308126<3108011D<<2108<<<<<<<5\n" .
                'MUSTERMANN<<ERIKA<<<<<<<<<<<<<',
                [
                    'mrzType'        => MrzType::TD1,
                    'documentCode'   => 'ID',
                    'countryOfIssue' => 'D',
                    'surname'        => 'MUSTERMANN',
                    'givenNames'     => 'ERIKA',
                    'documentNumber' => 'LOIXPVMEJ',
                    'nationality'    => 'D',
                    'dateOfBirth'    => '830812',
                    'sex'            => null,
                    'dateOfExpiry'   => '310801',
                ]],
            'DEU-BP-03001' => [
                "ITD<<MUSTERMANN<<ERIKA<<<<<<<<<<<<<<\n" .
                'C<00000004D<<6408125F0404011<<<<<<<4',
                [
                    'mrzType'        => MrzType::TD2,
                    'documentCode'   => 'IT',
                    'countryOfIssue' => 'D',
                    'surname'        => 'MUSTERMANN',
                    'givenNames'     => 'ERIKA',
                    'documentNumber' => 'C 0000000',
                    'nationality'    => 'D',
                    'dateOfBirth'    => '640812',
                    'sex'            => 'F',
                    'dateOfExpiry'   => '040401',
                ]],
            'ESP-AO-05001' => [
                "P<ESPESPANOLA<ESPANOLA<<CARMEN<<<<<<<<<<<<<<\n" .
                'ZAB0002549ESP8001014F2501017A9999999900<<<44',
                [
                    'mrzType'        => MrzType::TD3,
                    'documentCode'   => 'P',
                    'countryOfIssue' => 'ESP',
                    'surname'        => 'ESPANOLA ESPANOLA',
                    'givenNames'     => 'CARMEN',
                    'documentNumber' => 'ZAB000254',
                    'nationality'    => 'ESP',
                    'dateOfBirth'    => '800101',
                    'sex'            => 'F',
                    'dateOfExpiry'   => '250101',
                ]],
            'USA-CO-01001' => [
                "VNUSAHAPPY<<TRAVELER<<<<<<<<<<<<<<<<<<<<<<<<\n" .
                '0000000<<0CYP0001018M1601231B2NCS00E0F060308',
                [
                    'mrzType'        => MrzType::VA,
                    'documentCode'   => 'VN',
                    'countryOfIssue' => 'USA',
                    'surname'        => 'HAPPY',
                    'givenNames'     => 'TRAVELER',
                    'documentNumber' => '0000000',
                    'nationality'    => 'CYP',
                    'dateOfBirth'    => '000101',
                    'sex'            => 'M',
                    'dateOfExpiry'   => '160123',
                ]],
            'FRA-CO-03001' => [
                "VEFRAWANG<<YI<CHENG<<<<<<<<<<<<<<<<<\n" .
                '6000000024CHN8001014M1909155<M900618',
                [
                    'mrzType'        => MrzType::VB,
                    'documentCode'   => 'VE',
                    'countryOfIssue' => 'FRA',
                    'surname'        => 'WANG',
                    'givenNames'     => 'YI CHENG',
                    'documentNumber' => '600000002',
                    'nationality'    => 'CHN',
                    'dateOfBirth'    => '800101',
                    'sex'            => 'M',
                    'dateOfExpiry'   => '190915',
                ]],
        ];
    }

    #[DataProvider('parseDataProvider')]
    public function testParse(string $mrz, array $expected): void
    {
        $document = ParserFacade::parseMrz($mrz);

        foreach ($expected as $key => $value) {
            $this->assertEquals($document->{'get' . ucfirst($key)}(), $value);
        }
        $this->assertEqualsCanonicalizing($document->toArray(), $expected);
    }
}
