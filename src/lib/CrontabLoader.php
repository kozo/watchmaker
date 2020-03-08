<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use Watchmaker\error\MissingCrontabException;
use Watchmaker\WatchmakerCore;

class CrontabLoader
{
    private static $originList = [];

    /**
     * @return array
     * @throws MissingCrontabException
     */
    public static function load() : array
    {
        if (!empty(self::$originList)) {
            return self::$originList;
        }

        $out = null;
        $return = null;

        exec('which crontab', $out, $return);
        if ($return !== 0) {
            throw new MissingCrontabException();
        }

        exec('crontab -l', $out, $return);

        $list = [];
        array_shift($out);
        foreach ($out as $index=>$line) {
            try {
                $k = WatchmakerCore::parse($line);

                $list[] = $k;
            } catch (\Exception $e) {
                // skip line
            }
        }
        self::$originList = $list;

        return self::$originList;
    }
}
