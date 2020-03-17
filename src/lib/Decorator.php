<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use JakubOnderka\PhpConsoleColor\ConsoleColor;
use Watchmaker\Watchmaker;

class Decorator
{
    private $color = null;

    public function __construct(array $options = array())
    {
        $this->color = new Color();
    }

    public function greenText($text)
    {
        return $this->color->apply('color_82', $text);
    }

    public function yellowText($text)
    {
        return $this->color->apply('color_226', $text);
    }

    public function redText($text)
    {
        return $this->color->apply('color_196', $text);
    }

    public function alert($text)
    {
        $consoleColor = new ConsoleColor();
        $length = mb_strlen($text) + 12;

        $outText = '';
        $outText .= $this->color->apply('color_130', str_repeat('*', $length)) . "\n";
        $outText .= $this->color->apply('color_130', '*     ' . $text . '     *') . "\n";
        $outText .= $this->color->apply('color_130', str_repeat('*', $length)) . "\n";

        return $outText;
    }

    public function flashSuccess($message = ' All Installed')
    {
        $consoleColor = new ConsoleColor();
        $width  = intval(trim(`tput cols`));

        $text = '';
        $b = $this->color->apply("color_22", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_22", $b) . "\n";
        $b = $this->color->apply("color_20", str_repeat(' ', $width-14));
        $text .= $this->color->apply("bg_color_22", $message . $b) . "\n";
        $b = $this->color->apply("color_22", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_22", $b) . "\n";

        return $text;
    }

    public function flashError($message = ' crontab needs to be updated.')
    {
        $consoleColor = new ConsoleColor();
        $width  = intval(trim(`tput cols`));

        $text = '';
        $b = $this->color->apply("color_1", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_1", $b) . "\n";
        $b = $this->color->apply("color_20", str_repeat(' ', $width - 29));
        $text .= $this->color->apply("bg_color_1", $message . $b) . "\n";
        $b = $this->color->apply("color_1", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_1", $b) . "\n";

        return $text;
    }

    public function hr()
    {
        $width  = intval(trim(`tput cols`));
        return str_repeat('-', $width) . "\n";
    }

    public function newLine()
    {
        return "\n";
    }
}
