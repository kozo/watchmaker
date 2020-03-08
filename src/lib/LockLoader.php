<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use Watchmaker\WatchmakerCore;

class LockLoader
{
    private static $originList = [];

    /**
     * @param null $path
     * @return array
     */
    public static function load($path = null) : array
    {
        $k = WatchmakerCore::parse('* * 7 1 * php hoge/fuga.php');
        self::$originList[] = $k;
        return self::$originList;
    }
}
