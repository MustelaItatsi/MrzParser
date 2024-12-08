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
    private MrzType $mrzType;
    private string $documentCode;
    private string $countryOfIssue;
    private string $surname;
    private string $givenNames;
    private string $documentNumber;
    private string $nationality;
    private string $dateOfBirth;
    private ?string $sex = null;
    private string $dateOfExpiry;

    public static function fromMrz(string $mrz): ?self
    {
        foreach (self::$parser as $parser) {
            if ($parser::isValidMrz($mrz)) {
                return $parser::parse($mrz);
            }
        }

        return null;
    }

    public function getMrzType(): MrzType
    {
        return $this->mrzType;
    }

    public function setMrzType(MrzType $mrzType): self
    {
        $this->mrzType = $mrzType;

        return $this;
    }

    public function getDocumentCode(): string
    {
        return $this->documentCode;
    }

    public function setDocumentCode(string $documentCode): self
    {
        $this->documentCode = $documentCode;

        return $this;
    }

    public function getCountryOfIssue(): string
    {
        return $this->countryOfIssue;
    }

    public function setCountryOfIssue(string $countryOfIssue): self
    {
        $this->countryOfIssue = $countryOfIssue;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getGivenNames(): string
    {
        return $this->givenNames;
    }

    public function setGivenNames(string $givenNames): self
    {
        $this->givenNames = $givenNames;

        return $this;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(string $documentNumber): self
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getDateOfExpiry(): string
    {
        return $this->dateOfExpiry;
    }

    public function setDateOfExpiry(string $dateOfExpiry): self
    {
        $this->dateOfExpiry = $dateOfExpiry;

        return $this;
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
