<?php
declare(strict_types=1);
namespace Watchmaker\lib;

// @todo : クラス名Diffじゃない.　そもそもここでいいのか？
use Watchmaker\Watchmaker;
use Watchmaker\WatchmakerCore;

class Diff
{
    public static function execute($watchmakerList, $cronList)
    {
        $newList = [];

        // check install or not install
        foreach ($watchmakerList as $watchmaker)
        {
            $watchmakerLine = $watchmaker->generate();
            foreach ($cronList as $cron)
            {
                if ($watchmakerLine === $cron->generate()) {
                    $newList[] = $watchmaker->installed();
                    continue 2;
                }
            }

            $newList[] = $watchmaker->notInstalled();
        }

        // check cron only
        $config = Watchmaker::getConfig();
        if ($config->delete === false) {
            foreach ($cronList as $cron)
            {
                $cronLine = $cron->generate();
                foreach ($watchmakerList as $watchmaker)
                {
                    if ($cronLine === $watchmaker->generate()) {
                        continue 2;
                    }
                }

                $newList[] = $cron->cronOnly();
            }
        }

        return $newList;
    }
}
