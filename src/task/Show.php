<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\Decorator;
use Watchmaker\lib\Merge;
use Watchmaker\lib\StringCollector;
use Watchmaker\WatchmakerCore;

class Show
{
    private $decorator;
    private $isAllGreen = true;

    public function execute(array $taskList)
    {
        $collector = new StringCollector();
        $this->decorator = new Decorator($collector);

        $cronList = CrontabLoader::load();

        $this->decorator->newLine();
        $this->decorator->hr();

        // for installed / not installed
        $this->decorator->alert('Installed / Not Install / cron only');
        $newList = Merge::execute($taskList, $cronList);
        foreach ($newList as $task)
        {
            $this->showLine($task);
        }

        $this->decorator->newLine();

        if ($this->isAllGreen === true) {
            $this->decorator->flashSuccess();
        } else {
            $this->decorator->flashError();
        }

        $this->decorator->hr();

        return $collector->generate();
    }

    private function showLine(WatchmakerCore $watchmaker)
    {
        if ($watchmaker->isInstalled()) {
            $this->decorator->greenText("[ ✔ ]\t" . $watchmaker->generate());
        } elseif ($watchmaker->isNotInstalled()) {
            $this->decorator->yellowText("[ ➖ ]\t" . $watchmaker->generate());
            $this->isAllGreen = false;
        } elseif ($watchmaker->isCronOnly()) {
            $this->decorator->redText("[ ❌ ]\t" . $watchmaker->generate());
            $this->isAllGreen = false;
        }
    }
}
