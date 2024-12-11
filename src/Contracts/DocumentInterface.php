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
 * @phpstan-type CheckDigits array<'overall'|'dateOfBirth'|'dateOfExpiry'|'documentNumber', array{extracted:int,calculated:int,isValid:bool}>
 * @phpstan-type DocumentArray array{
 *     mrzType:MrzType,
 *     documentCode:string,
 *     issuingStateOrOrganization:string,
 *     primaryIdentifier:string,
 *     secondaryIdentifier:string,
 *     documentNumber:string,
 *     nationality:string,
 *     dateOfBirth:string,
 *     sex:?string,
 *     dateOfExpiry:string,
 *     checkDigits:CheckDigits
 * }
 */
interface DocumentInterface
{
    public function getMrzType(): MrzType;

    /**
     * Get the document code.
     * Typically starts with I (ID card), P (passport), A (residence permit),
     * or V (visa). Some countries may use other codes.
     *
     * @see ICAO Doc 9303 Part 3 Section 4
     */
    public function getDocumentCode(): string;

    /**
     * @see https://www.icao.int/publications/Documents/9303_p3_cons_en.pdf#page=29
     */
    public function getIssuingStateOrOrganization(): string;

    /**
     * (e.g., surname. Names may be croped.
     */
    public function getPrimaryIdentifier(): string;

    /**
     * e.g., given names. Names may be croped.
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
     * in YYMMDD format.
     */
    public function getDateOfBirth(): string;

    public function getSex(): ?string;

    /**
     * in YYMMDD format.
     */
    public function getDateOfExpiry(): string;

    /**
     * Get the check digits for the document fields.
     *
     * @return CheckDigits
     */
    public function getCheckDigits(): array;

    /**
     * Convert the document to an associative array.
     *
     * @return DocumentArray
     */
    public function toArray(): array;
}
