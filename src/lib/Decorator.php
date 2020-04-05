<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use JakubOnderka\PhpConsoleColor\ConsoleColor;

class Decorator
{
    private $color = null;
    private $collector = null;

    public function __construct(StringCollector $collector)
    {
        $this->color = new Color();
        $this->collector = $collector;
    }

    public function simple($text)
    {
        $this->collector->text($text);
    }

    public function greenText($text)
    {
        $d = $this->color->apply('color_82', $text);
        $this->collector->text($d);
    }

    public function yellowText($text)
    {
        $d = $this->color->apply('color_226', $text);
        $this->collector->text($d);
    }

    public function redText($text)
    {
        $d = $this->color->apply('color_196', $text);
        $this->collector->text($d);
    }

    public function alert($text)
    {
        $length = mb_strlen($text) + 12;

        $outText = '';
        $outText .= $this->color->apply('color_130', str_repeat('*', $length)) . "\n";
        $outText .= $this->color->apply('color_130', '*     ' . $text . '     *') . "\n";
        $outText .= $this->color->apply('color_130', str_repeat('*', $length)) . "\n";

        $this->collector->text($outText);
    }

    public function flashSuccess($message = ' All Installed')
    {
        $width  = intval(trim(`tput cols`));

        $text = '';
        $b = $this->color->apply("color_22", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_22", $b) . "\n";
        $b = $this->color->apply("color_20", str_repeat(' ', $width-14));
        $text .= $this->color->apply("bg_color_22", $message . $b) . "\n";
        $b = $this->color->apply("color_22", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_22", $b) . "\n";

        $this->collector->text($text);
    }

    public function flashError($message = ' crontab needs to be updated.')
    {
        $width  = intval(trim(`tput cols`));

        $text = '';
        $b = $this->color->apply("color_1", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_1", $b) . "\n";
        $b = $this->color->apply("color_20", str_repeat(' ', $width - 29));
        $text .= $this->color->apply("bg_color_1", $message . $b) . "\n";
        $b = $this->color->apply("color_1", str_repeat('-', $width));
        $text .= $this->color->apply("bg_color_1", $b) . "\n";

        $this->collector->text($text);
    }

    public function hr()
    {
        $width  = intval(trim(`tput cols`));
        $d = str_repeat('-', $width) . "\n";
        $this->collector->text($d);
    }

    public function newLine($line = 1)
    {
        for ($i=0; $i < $line; $i++) {
            $this->collector->text("\n", false);
        }
    }
}
