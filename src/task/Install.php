<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\CronWriter;
use Watchmaker\lib\Merge;
use Watchmaker\Watchmaker;

class Install
{
    public function execute(array $taskList)
    {
        $cronList = CrontabLoader::load();

        $mergeList = Merge::execute($taskList, $cronList);
        $installList = $this->createInstallList($mergeList);

        $cron = new CronWriter();
        $ret = $cron->write($installList);
        if ($ret === false) {
            echo "hogehoge";
        }//*/
        //dump($newList);
    }

    private function createInstallList(array $mergeList)
    {
        $installList = [];
        $config = Watchmaker::getConfig();

        foreach($mergeList as $watchmakerCore)
        {
            if ($watchmakerCore->isCronOnly() === true)
            {
                if ($config->delete === true){
                    continue;
                }
            }

            $installList[] = $watchmakerCore;
        }

        return $installList;
    }
}
