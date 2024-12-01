<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Itatsi\MrzParser;

use Itatsi\MrzParser\Contracts\DocumentInterface;
use Itatsi\MrzParser\Contracts\ParserInterface;
use Itatsi\MrzParser\Enums\MrzType;
use Itatsi\MrzParser\Parsers\TravelDocumentType1;
use Itatsi\MrzParser\Parsers\TravelDocumentType2;
use Itatsi\MrzParser\Parsers\TravelDocumentType3;
use Itatsi\MrzParser\Parsers\VisaTypeA;
use Itatsi\MrzParser\Parsers\VisaTypeB;

class Document implements DocumentInterface
{
    /**
     * @var class-string<ParserInterface>[]
     */
    private static array $parser = [
        VisaTypeA::class,
        VisaTypeB::class,
        TravelDocumentType1::class,
        TravelDocumentType2::class,
        TravelDocumentType3::class,
    ];

    public static function fromMrz(string $mrz): ?self
    {
        foreach (self::$parser as $parser) {
            if ($parser::isValidMrz($mrz)) {
                return $parser::parse($mrz);
            }
        }

        return null;
    }

    public function __construct(
        private MrzType $mrzType,
        private string $documentCode,
        private string $countryOfIssue,
        private string $surname,
        private string $givenNames,
        private string $documentNumber,
        private string $nationality,
        private string $dateOfBirth,
        private ?string $sex,
        private string $dateOfExpiry
    ) {
    }

    public function getMrzType(): MrzType
    {
        return $this->mrzType;
    }

    public function getDocumentCode(): string
    {
        return $this->documentCode;
    }

    public function getCountryOfIssue(): string
    {
        return $this->countryOfIssue;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getGivenNames(): string
    {
        return $this->givenNames;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function getDateOfExpiry(): string
    {
        return $this->dateOfExpiry;
    }

    /**
     * @return array{mrzType:MrzType,documentCode:string,countryOfIssue:string,
     * surname:string,givenNames:string,documentNumber:string,nationality:string,
     * dateOfBirth:string,sex:?string,dateOfExpiry:string}
     */
    public function toArray(): array
    {
        return [
            'mrzType'        => $this->getMrzType(),
            'documentCode'   => $this->getDocumentCode(),
            'countryOfIssue' => $this->getCountryOfIssue(),
            'surname'        => $this->getSurname(),
            'givenNames'     => $this->getGivenNames(),
            'documentNumber' => $this->getDocumentNumber(),
            'nationality'    => $this->getNationality(),
            'dateOfBirth'    => $this->getDateOfBirth(),
            'sex'            => $this->getSex(),
            'dateOfExpiry'   => $this->getDateOfExpiry(),
        ];
    }
}
