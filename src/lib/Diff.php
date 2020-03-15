<?php
declare(strict_types=1);
namespace Watchmaker\lib;

// @todo : クラス名Diffじゃない.　そもそもここでいいのか？
use Watchmaker\WatchmakerCore;

class Diff
{
    public static function execute($watchmakerList, $cronList)
    {
        $newList = [];

        foreach ($watchmakerList as $index=>$watchmaker)
        {
            $watchmakerLine = $watchmaker->generate();
            foreach ($cronList as $cron)
            {
                if ($watchmakerLine == $cron->generate()) {
                    $newList[$index] = $watchmaker->installed();
                    continue 2;
                }
            }

            $newList[$index] = $watchmaker->notInstalled();
        }

        return $newList;
    }
}
