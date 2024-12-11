<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Contracts;

use MustelaItatsi\MrzParser\Enums\MrzType;

/**
 * @phpstan-type CheckDigits array<'combinedCheckDigit'|'dateOfBirth'|'dateOfExpiry'|'documentNumber',array{value:int,calculated:int,isValid:bool}>
 * @phpstan-type DocumentArray array{mrzType:MrzType,documentCode:string,issuingStateOrOrganization:string,
 * primaryIdentifier:string,secondaryIdentifier:string,documentNumber:string,nationality:string,
 * dateOfBirth:string,sex:?string,dateOfExpiry:string,checkDigits:CheckDigits}
 */
interface DocumentInterface
{
    public function getMrzType(): MrzType;

    /**
     * Starts normaly with I(id card), P(passport), A(residence permit),
     * or V(visa), but some counties make a lot af magic with it...
     * See IR on FRA-HO-12001. Send me a mail if someone finds a definition.
     */
    public function getDocumentCode(): string;

    /**
     * Country or organizations of issue.
     *
     * @see https://www.icao.int/publications/Documents/9303_p3_cons_en.pdf#page=29
     */
    public function getIssuingStateOrOrganization(): string;

    /**
     * Keep in mind, that names can be croped.
     */
    public function getPrimaryIdentifier(): string;

    /**
     * Keep in mind, that names can be croped.
     */
    public function getSecondaryIdentifier(): string;

    public function getDocumentNumber(): string;

    /**
     * Nationality or special code.
     *
     * @see https://www.icao.int/publications/Documents/9303_p3_cons_en.pdf#page=29
     */
    public function getNationality(): string;

    /**
     * in datetime format ymd.
     */
    public function getDateOfBirth(): string;

    /**
     * sex, if available.
     */
    public function getSex(): ?string;

    /**
     * in datetime format ymd.
     */
    public function getDateOfExpiry(): string;

    /**
     * @return CheckDigits
     */
    public function getCheckDigits(): array;

    /**
     * Convert the document to an associative array. Keep in mind, that this list can be extended anytime!
     *
     * @return DocumentArray
     */
    public function toArray(): array;
}
