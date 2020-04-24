<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\CronWriter;
use Watchmaker\lib\Decorator;
use Watchmaker\lib\Merge;
use Watchmaker\lib\StringCollector;
use Watchmaker\Watchmaker;

class Install
{
    public function execute(array $taskList)
    {
        $collector = new StringCollector();
        $this->decorator = new Decorator($collector);

        $this->decorator->newLine();

        $cronList = CrontabLoader::load();

        $mergeList = Merge::execute($taskList, $cronList);
        $installList = $this->createInstallList($mergeList);

        $cron = new CronWriter();
        $cron->write($installList);

        $this->decorator->flashSuccess();

        return $collector->generate();
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
