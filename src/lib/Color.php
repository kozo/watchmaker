<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use JakubOnderka\PhpConsoleColor\ConsoleColor;
use Watchmaker\Watchmaker;

class Color extends ConsoleColor
{
    public function isSupported()
    {
        $config = Watchmaker::getConfig();
        if ($config->ansi === true) {
            return true;
        }

        return parent::isSupported();
    }
}
