<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\CronWriter;
use Watchmaker\lib\Marge;

class Install
{
    public function execute(array $taskList)
    {
        $cronList = CrontabLoader::load();

        $newList = Marge::execute($taskList, $cronList);

        $cron = new CronWriter();
        $ret = $cron->write($newList);
        if ($ret === false) {
            echo "hogehoge";
        }//*/
        dump($newList);
    }
}
