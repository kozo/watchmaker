<?php
declare(strict_types=1);
namespace Watchmaker\lib;

class StringCollector
{
    private $text = '';

    public function text($text, $newLine = true)
    {
        if ($newLine === true) {
            $text .= "\n";
        }
        $this->text .= $text;
    }

    public function generate() : string
    {
        return $this->text;
    }
}
