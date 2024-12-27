<?php declare(strict_types=1);
/*
 * This file is part of MrzParser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Contracts;

use MustelaItatsi\MrzParser\Enums\CheckDigitType;
use MustelaItatsi\MrzParser\Enums\MrzType;

/**
 * @phpstan-type CheckDigits array<CheckDigitType::*, array{extracted:int,calculated:int,isValid:bool}>
 */
interface DocumentInterface
{
    public function getMrzType(): MrzType;

    /**
     * Get the document code.
     * Typically starts with I (ID card), P (passport), A (residence permit),
     * or V (visa). Some countries may use other codes.
     */
    public function getDocumentCode(): string;

    /**
     * three-letter codes (per ICAO Doc 9303 Part 3 Section 4.7).
     *
     * @see ICAO Doc 9303 Part 3 Section 5
     */
    public function getIssuingStateOrOrganization(): string;

    /**
     * (e.g., surname. Names may be cropped.
     */
    public function getPrimaryIdentifier(): string;

    /**
     * e.g., given names. Names may be cropped.
     */
    public function getSecondaryIdentifier(): string;

    public function getDocumentNumber(): string;

    /**
     * three-letter codes (per ICAO Doc 9303 Part 3 Section 4.7).
     *
     * @see ICAO Doc 9303 Part 3 Section 5
     */
    public function getNationality(): string;

    /**
     * in 'ymd' format (per ICAO Doc 9303 Part 3 Section 4.8).
     */
    public function getDateOfBirth(): string;

    /**
     * in 'Y-m-d' format.
     */
    public function getDateOfBirthWithEstimatedEpoch(): ?string;

    public function getSex(): ?string;

    /**
     * in 'ymd' format (per ICAO Doc 9303 Part 3 Section 4.8).
     */
    public function getDateOfExpiry(): string;

    /**
     * in 'Y-m-d' format.
     */
    public function getDateOfExpiryWithEstimatedEpoch(): ?string;

    /**
     * Get the check digits for the document fields.
     *
     * @return CheckDigits
     */
    public function getCheckDigits(): array;
}
