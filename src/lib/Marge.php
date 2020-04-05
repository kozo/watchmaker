<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use Watchmaker\Watchmaker;

class Marge
{
    public static function execute($watchmakerList, $cronList, $skipUnManage = false)
    {
        $newList = [];

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

                if ($skipUnManage === true && $cron->isManage() === true) {
                    $newList[] = $cron->cronOnly();
                }
            }
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
