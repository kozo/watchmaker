<?php
declare(strict_types=1);
namespace Watchmaker\lib;

class CronWriter
{
    public function write(array $watchmakerList)
    {
        $ret = $this->isInstalledCrontab();
        if ($ret === false) {
            return;
        }

        if(($cron = popen("/usr/bin/crontab -", "w"))){
            foreach ($watchmakerList as $watchmaker)
            {
                fputs($cron, $watchmaker->generate());
            }
            //fputs($cron, $s);
            //fputs($cron, "*  *  *  *  *  df -h > dev/null 2>&1\n");

            pclose($cron);
            return true;
        }

        return false;
    }

    private function isInstalledCrontab() : bool
    {
        $out = null;
        $return = null;
        exec("which crontab", $out, $return);
        if ($return !== 0) {
            return false;
        }

        return true;
    }
}
