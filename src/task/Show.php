<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\Diff;
use Watchmaker\lib\LockLoader;
use Watchmaker\lib\Decorator;
use Watchmaker\WatchmakerCore;

class Show
{
    private $decorator = null;
    private $isAllGreen = true;

    public function execute(array $taskList)
    {
        $this->decorator = new Decorator();

        $cronList = CrontabLoader::load();

        $output = '';
        $output .= $this->decorator->newLine();
        $output .= $this->decorator->hr();
        // echo "# Kairos\n\n";

        // for installed / not installed
        $output .= $this->decorator->alert('Installed / Not Install');
        $newList = Diff::execute($taskList, $cronList);
        foreach ($newList as $task)
        {
            $output .= $this->showLine($task);
        }

        $output .= $this->decorator->newLine();

        // for delete
        /*$output .= $this->>decorator->alert('Delete for crontab');
        $prevList = LockLoader::load();
        foreach ($prevList as $prevTask)
        {
            $text = $this->showDeleteLine($prevTask, $taskList);
            if (!empty($text)) {
                $output .= $text;
            }
        }*/

        $output .= $this->decorator->newLine();

        if ($this->isAllGreen === true) {
            $output .= $this->decorator->flashSuccess();
        } else {
            $output .= $this->decorator->flashError();
        }

        $output .= $this->decorator->newLine();
        $output .= $this->decorator->hr();

        return $output;
        /*if ($rand === 1) {
            echo "[ ✔ ]\t" . $consoleColor->apply("color_82", $cron);
        } else if($rand === 2) {
            echo "[ ❌ ]\t" . $consoleColor->apply("color_196", $cron);
        } else {
            echo "[ ➖ ]\t" . $consoleColor->apply("color_226", $cron);
        }*/
    }

    private function showLine(WatchmakerCore $watchmaker)
    {
        if ($watchmaker->isInstalled()) {
            $text = "[ ✔ ]\t" . $this->decorator->greenText($watchmaker->generate());
        } elseif ($watchmaker->isNotInstalled()) {
            $text = "[ ➖ ]\t" . $this->decorator->yellowText($watchmaker->generate());
            $this->isAllGreen = false;
        } elseif ($watchmaker->isCronOnly()) {
            $text = "[ ❌ ]\t" . $this->decorator->redText($watchmaker->generate());
            $this->isAllGreen = false;
        }
        /*if ($kairos->isInstalled()) {
            $text = "[ ✔ ]\t" . $this->greenText($kairos->generate());
        } else {
            $text = "[ ➖ ]\t" . $consoleColor->apply('color_226', $kairos->generate());
            $this->isAllGreen = false;
        }*/

        return $text;
    }

    private function showDeleteLine(WatchmakerCore $prevTask, array $taskList)
    {
        foreach($taskList as $task)
        {
            if ($prevTask->generate() === $task->generate()) {
                return '';
            }
        }

        $isInstalled = false;
        $originList = CrontabLoader::load();
        foreach($originList as $originKairos)
        {
            if ($prevTask->generate() === $originKairos->generate()) {
                $isInstalled = true;
                break;
            }
        }

        $text = '';
        if ($isInstalled === true) {
            $consoleColor = new \JakubOnderka\PhpConsoleColor\ConsoleColor();
            $text = "[ ❌ ]\t" . $consoleColor->apply("color_196", $prevTask->generate());

            $this->isAllGreen = false;
        }

        return $text;
    }


    private function alert($text)
    {
        $consoleColor = new \JakubOnderka\PhpConsoleColor\ConsoleColor();
        $length = mb_strlen($text) + 12;

        echo $consoleColor->apply('color_130', str_repeat('*', $length)) . "\n";
        echo $consoleColor->apply('color_130', '*     ' . $text . '     *') . "\n";
        echo $consoleColor->apply('color_130', str_repeat('*', $length)) . "\n";
    }

    /**
     * @param Kairos $kairos
     * @return bool
     * @throws \Kairos\error\MissingCrontabException
     */
    /*private function isInstalled(Kairos $kairos) : bool
    {
        $originList = CrontabLoader::load();
        foreach($originList as $originKairos)
        {
            $origin = $originKairos->generate();
            if ($origin === $kairos->generate()) {
                return true;
            }
        }

        return false;
    }*/
}
