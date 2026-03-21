<?php declare(strict_types=1);
/*
 * This file is part of MrzParser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MustelaItatsi\MrzParser\Facades;

use MustelaItatsi\MrzParser\Contracts\DocumentInterface;
use MustelaItatsi\MrzParser\Document;

class ParserFacade
{
    public static function parseMrz(string $mrz): ?DocumentInterface
    {
        return Document::fromMrz($mrz);
    }
}
