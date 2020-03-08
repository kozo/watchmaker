<?php
declare(strict_types=1);
namespace Watchmaker;

use Watchmaker\error\CronLineParseException;
use Watchmaker\lib\CrontabLoader;

class WatchmakerCore
{
    private $command = '';

    private $month = '*';
    private $day = '*';
    private $hour = '*';
    private $minute = '*';
    private $week = '*';

    /**
     * Kairos constructor.
     * @param string $command
     */
    public function __construct($command = '')
    {
        $this->command = $command;
    }

    /**
     * @param $cronLine
     * @return WatchmakerCore
     */
    public static function parse($cronLine) : WatchmakerCore
    {
        $instance = new static();

        $cronLine = trim($cronLine);
        $arr = explode(' ', $cronLine, 6);
        if (count($arr) !== 6) {
            throw new CronLineParseException();
        }

        $instance = $instance
            ->minute($arr[0])
            ->hour($arr[1])
            ->day($arr[2])
            ->month($arr[3])
            ->week($arr[4])
            ->command($arr[5]);

        return $instance;
    }

    /**
     * @param $v
     * @return $this
     */
    public function month($v) : self
    {
        $new = clone $this;
        $new->month = $v;

        return $new;
    }

    /**
     * @param $v
     * @return $this
     */
    public function day($v) : self
    {
        $new = clone $this;
        $new->day = $v;

        return $new;
    }

    /**
     * @param $v
     * @return $this
     */
    public function hour($v) : self
    {
        $new = clone $this;
        $new->hour = $v;

        return $new;
    }

    /**
     * @param $v
     * @return $this
     */
    public function minute($v) : self
    {
        $new = clone $this;
        $new->minute = $v;

        return $new;
    }

    /**
     * @param $v
     * @return $this
     */
    public function week($v) : self
    {
        $new = clone $this;
        $new->week = $v;

        return $new;
    }

    public function monday() : self
    {
        return $this->week(1);
    }

    public function tuesday() : self
    {
        return $this->week(2);
    }

    public function wednesday() : self
    {
        return $this->week(3);
    }

    public function thursday() : self
    {
        return $this->week(4);
    }

    public function friday() : self
    {
        return $this->week(5);
    }

    public function saturday() : self
    {
        return $this->week(6);
    }

    public function sunday() : self
    {
        return $this->week(7);
    }

    public function command($v)
    {
        $new = clone $this;
        $new->command = $v;

        return $new;
    }

    public function isInstalled() : bool
    {
        $originList = CrontabLoader::load();
        foreach($originList as $originKairos)
        {
            $origin = $originKairos->generate();
            if ($origin === $this->generate()) {
                return true;
            }
        }

        return false;
    }

    public function generate() : string
    {
        return sprintf("%s %s %s %s %s %s\n", $this->minute, $this->hour, $this->day, $this->month, $this->week, $this->command);
    }
}
