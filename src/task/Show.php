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
    private $isAllGreen = true;

    public function execute(array $taskList)
    {
        $cronList = CrontabLoader::load();

        $output = '';
        $output .= Decorator::newLine();
        $output .= Decorator::hr();
        // echo "# Kairos\n\n";

        // for installed / not installed
        $output .= Decorator::alert('Installed / Not Install');
        $newList = Diff::execute($taskList, $cronList);
        foreach ($newList as $task)
        {
            $output .= $this->showLine($task);
        }

        $output .= Decorator::newLine();

        // for delete
        /*$output .= Decorator::alert('Delete for crontab');
        $prevList = LockLoader::load();
        foreach ($prevList as $prevTask)
        {
            $text = $this->showDeleteLine($prevTask, $taskList);
            if (!empty($text)) {
                $output .= $text;
            }
        }*/

        $output .= Decorator::newLine();

        if ($this->isAllGreen === true) {
            $output .= Decorator::flashSuccess();
        } else {
            $output .= Decorator::flashError();
        }

        $output .= Decorator::newLine();
        $output .= Decorator::hr();

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
        $consoleColor = new \JakubOnderka\PhpConsoleColor\ConsoleColor();

        if ($watchmaker->isInstalled()) {
            $text = "[ ✔ ]\t" . Decorator::greenText($watchmaker->generate());
        } elseif ($watchmaker->isNotInstalled()) {
            $text = "[ ➖ ]\t" . $consoleColor->apply('color_226', $watchmaker->generate());
            $this->isAllGreen = false;
        } elseif ($watchmaker->isCronOnly()) {
            $text = "[ ❌ ]\t" . $consoleColor->apply("color_196", $watchmaker->generate());
            $this->isAllGreen = false;
        }
        /*if ($kairos->isInstalled()) {
            $text = "[ ✔ ]\t" . Decorator::greenText($kairos->generate());
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
