<?php

namespace Tigress;

/**
 * Class Controller (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.06.19.0
 * @package Tigress\Controller
 */
class Controller
{
    /**
     * Get the version of the Repository
     *
     * @return string
     */
    public static function version(): string
    {
        return '2025.06.19';
    }

    /**
     * Convert a table name to a class name.
     *
     * @param string $tableName
     * @param string $suffix
     * @return string
     */
    public function tableNameToClass(string $tableName, string $suffix = 'Repo'): string
    {
        $parts = explode('_', $tableName);
        $camelParts = array_map('ucfirst', $parts);
        return implode('', $camelParts) . $suffix;
    }
}