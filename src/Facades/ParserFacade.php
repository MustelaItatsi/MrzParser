<?php declare(strict_types=1);
/*
 * This file is part of mrz-parser.
 *
 * (c) Alexander Herrmann <alexander-herrmann@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Itatsi\MrzParser\Facades;

use Itatsi\MrzParser\Contracts\DocumentInterface;
use Itatsi\MrzParser\Document;

class ParserFacade
{
    /**
     * Parse an MRZ string and return Document instance if valid.
     */
    public static function parseMrz(string $mrz): ?DocumentInterface
    {
        $document = Document::fromMrz($mrz);

        if ($document === null) {
            return null;
        }

        return $document;
    }
}
