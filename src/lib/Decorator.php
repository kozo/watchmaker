<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use JakubOnderka\PhpConsoleColor\ConsoleColor;

class Decorator
{
    public static function greenText($text)
    {
        $consoleColor = new ConsoleColor();
        return $consoleColor->apply('color_82', $text);
    }

    public static function alert($text)
    {
        $consoleColor = new ConsoleColor();
        $length = mb_strlen($text) + 12;

        $outText = '';
        $outText .= $consoleColor->apply('color_130', str_repeat('*', $length)) . "\n";
        $outText .= $consoleColor->apply('color_130', '*     ' . $text . '     *') . "\n";
        $outText .= $consoleColor->apply('color_130', str_repeat('*', $length)) . "\n";

        return $outText;
    }

    public static function flashSuccess($message = ' All Installed')
    {
        $consoleColor = new ConsoleColor();
        $width  = intval(trim(`tput cols`));

        $text = '';
        $b = $consoleColor->apply("color_22", str_repeat('-', $width));
        $text .= $consoleColor->apply("bg_color_22", $b) . "\n";
        $b = $consoleColor->apply("color_20", str_repeat(' ', $width-14));
        $text .= $consoleColor->apply("bg_color_22", $message . $b) . "\n";
        $b = $consoleColor->apply("color_22", str_repeat('-', $width));
        $text .= $consoleColor->apply("bg_color_22", $b) . "\n";

        return $text;
    }

    public static function flashError($message = ' crontab needs to be updated.')
    {
        $consoleColor = new ConsoleColor();
        $width  = intval(trim(`tput cols`));

        $text = '';
        $b = $consoleColor->apply("color_1", str_repeat('-', $width));
        $text .= $consoleColor->apply("bg_color_1", $b) . "\n";
        $b = $consoleColor->apply("color_20", str_repeat(' ', $width - 29));
        $text .= $consoleColor->apply("bg_color_1", $message . $b) . "\n";
        $b = $consoleColor->apply("color_1", str_repeat('-', $width));
        $text .= $consoleColor->apply("bg_color_1", $b) . "\n";

        return $text;
    }

    public static function hr()
    {
        $width  = intval(trim(`tput cols`));
        return str_repeat('-', $width) . "\n";
    }

    public static function newLine()
    {
        return "\n";
    }
}
