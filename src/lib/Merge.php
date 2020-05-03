<?php
declare(strict_types=1);
namespace Watchmaker\lib;

class Merge
{
    /**
     * @param $watchmakerList
     * @param $cronList
     * @return array
     */
    public static function execute($watchmakerList, $cronList/*, $skipUnManage = false*/): array
    {
        $newList = [];

        // check cron only
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

        return $newList;
    }
}
