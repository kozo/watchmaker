<?php
declare(strict_types=1);
namespace Watchmaker\lib;

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
        if ($this->isInteractive() === false) {
            return ;
        }

        $this->collector->text($text);
    }

    public function greenText($text)
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $d = $this->color->apply('color_82', $text);
        $this->collector->text($d);
    }

    public function yellowText($text)
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $d = $this->color->apply('color_226', $text);
        $this->collector->text($d);
    }

    public function redText($text)
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $d = $this->color->apply('color_196', $text);
        $this->collector->text($d);
    }

    public function alert($text)
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $length = mb_strlen($text) + 12;

        $outText = '';
        $outText .= $this->color->apply('color_130', str_repeat('*', $length)) . "\n";
        $outText .= $this->color->apply('color_130', '*     ' . $text . '     *') . "\n";
        $outText .= $this->color->apply('color_130', str_repeat('*', $length)) . "\n";

        $this->collector->text($outText);
    }

    public function flashSuccess($message = ' All Installed')
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $width = $this->getWidth();
        $length = mb_strlen($message);

        $text = '';
        $b = $this->color->apply('color_22', str_repeat('-', $width));
        $text .= $this->color->apply('bg_color_22', $b) . "\n";
        $b = $this->color->apply('color_22', str_repeat('-', $width - $length));
        $text .= $this->color->apply('bg_color_22', $message . $b) . "\n";
        $b = $this->color->apply('color_22', str_repeat('-', $width));
        $text .= $this->color->apply('bg_color_22', $b) . "\n";

        $this->collector->text($text);
    }

    public function flashError($message = ' Please update crontab.')
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $width = $this->getWidth();
        $length = mb_strlen($message);

        $text = '';
        $b = $this->color->apply('color_1', str_repeat('-', $width));
        $text .= $this->color->apply('bg_color_1', $b) . "\n";
        $b = $this->color->apply('color_1', str_repeat('-', $width - $length));
        $text .= $this->color->apply('bg_color_1', $message . $b) . "\n";
        $b = $this->color->apply('color_1', str_repeat('-', $width));
        $text .= $this->color->apply('bg_color_1', $b) . "\n";

        $this->collector->text($text);
    }

    public function hr()
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        $width = $this->getWidth();
        $d = str_repeat('-', $width) . "\n";
        $this->collector->text($d);
    }

    public function newLine($line = 1)
    {
        if ($this->isInteractive() === false) {
            return ;
        }

        for ($i = 0; $i < $line; $i++) {
            $this->collector->text("\n", false);
        }
    }

    private function getWidth()
    {
        return intval(trim(`tput cols` ?? '80'));
    }

    private function isInteractive()
    {
        if (function_exists('posix_isatty') && posix_isatty(STDOUT) === false) {
            return false;
        }

        return true;
    }
}
