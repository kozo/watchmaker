<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\Marge;
use Watchmaker\lib\LockLoader;
use Watchmaker\lib\Decorator;
use Watchmaker\lib\StringCollector;
use Watchmaker\WatchmakerCore;

class Show
{
    private $decorator = null;
    private $isAllGreen = true;

    public function execute(array $taskList)
    {
        $collector = new StringCollector();
        $this->decorator = new Decorator($collector);

        $cronList = CrontabLoader::load();

        $this->decorator->newLine();
        $this->decorator->hr();
        // echo "# Kairos\n\n";

        // for installed / not installed
        $this->decorator->alert('Installed / Not Install');
        $newList = Marge::execute($taskList, $cronList, true);
        foreach ($newList as $task)
        {
            $this->showLine($task);
        }

        $this->decorator->newLine();

        $this->decorator->newLine();

        if ($this->isAllGreen === true) {
            $this->decorator->flashSuccess();
        } else {
            $this->decorator->flashError();
        }

        $this->decorator->newLine();
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
