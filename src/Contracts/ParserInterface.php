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

use MustelaItatsi\MrzParser\Document;

interface ParserInterface
{
    public static function isValidMrz(string $mrz): bool;

    public static function parse(string $mrz): Document;
}
