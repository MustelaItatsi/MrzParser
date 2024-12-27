<?php declare(strict_types=1);
/*
 * This file is part of MrzParser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Enums;

class CheckDigitType
{
    public const OVERALL         = 'overall';
    public const DATE_OF_BIRTH   = 'dateOfBirth';
    public const DATE_OF_EXPIRY  = 'dateOfExpiry';
    public const DOCUMENT_NUMBER = 'documentNumber';
}
