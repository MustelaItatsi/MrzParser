<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Enums;

enum MrzType
{
    /**
     * TD1 size MRTDs.
     */
    case TD1;

    /**
     * TD2 size MRTDs.
     */
    case TD2;

    /**
     * Machine Readable Passports (MRPs) and other TD3 size MRTDs.
     */
    case TD3;

    /**
     *  MRVs Format-A.
     */
    case VA;

    /**
     *  MRVs Format-B.
     */
    case VB;
}
