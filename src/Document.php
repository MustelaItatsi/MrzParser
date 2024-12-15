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

use MustelaItatsi\MrzParser\Contracts\DocumentInterface;
use MustelaItatsi\MrzParser\Contracts\ParserInterface;
use MustelaItatsi\MrzParser\Enums\MrzType;
use MustelaItatsi\MrzParser\Parsers\TravelDocumentType1;
use MustelaItatsi\MrzParser\Parsers\TravelDocumentType2;
use MustelaItatsi\MrzParser\Parsers\TravelDocumentType3;
use MustelaItatsi\MrzParser\Parsers\VisaTypeA;
use MustelaItatsi\MrzParser\Parsers\VisaTypeB;

/**
 * @phpstan-import-type CheckDigits from DocumentInterface
 */
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
    private string $issuingStateOrOrganization;
    private string $primaryIdentifier;
    private string $secondaryIdentifier;
    private string $documentNumber;
    private string $nationality;
    private string $dateOfBirth;
    private ?string $sex = null;
    private string $dateOfExpiry;

    /** @var CheckDigits */
    private array $checkDigits = [];

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

    public function getIssuingStateOrOrganization(): string
    {
        return $this->issuingStateOrOrganization;
    }

    public function setIssuingStateOrOrganization(string $issuingStateOrOrganization): self
    {
        $this->issuingStateOrOrganization = $issuingStateOrOrganization;

        return $this;
    }

    public function getPrimaryIdentifier(): string
    {
        return $this->primaryIdentifier;
    }

    public function setPrimaryIdentifier(string $primaryIdentifier): self
    {
        $this->primaryIdentifier = $primaryIdentifier;

        return $this;
    }

    public function getSecondaryIdentifier(): string
    {
        return $this->secondaryIdentifier;
    }

    public function setSecondaryIdentifier(string $secondaryIdentifier): self
    {
        $this->secondaryIdentifier = $secondaryIdentifier;

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
     * @return CheckDigits
     */
    public function getCheckDigits(): array
    {
        return $this->checkDigits;
    }

    /**
     * @param CheckDigits $checkDigits
     */
    public function setCheckDigits(array $checkDigits): self
    {
        $this->checkDigits = $checkDigits;

        return $this;
    }
}
