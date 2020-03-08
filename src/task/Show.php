<?php
declare(strict_types=1);
namespace Watchmaker\task;

use Watchmaker\lib\CrontabLoader;
use Watchmaker\lib\LockLoader;
use Watchmaker\lib\Decorator;
use Watchmaker\WatchmakerCore;

class Show
{
    private $isAllGreen = true;

    public function execute(array $taskList)
    {
        echo Decorator::hr();
        // echo "# Kairos\n\n";

        // for installed / not installed
        echo Decorator::alert('Installed / Not Install');
        foreach ($taskList as $task)
        {
            $this->showLine($task);
        }

        // for delete
        echo "\n\n";
        echo Decorator::alert('Delete for crontab');
        $prevList = LockLoader::load();
        foreach ($prevList as $prevTask)
        {
            $this->showDeleteLine($prevTask, $taskList);
        }

        Decorator::newLine();

        if ($this->isAllGreen === true) {
            Decorator::flashSuccess();
        } else {
            Decorator::flashError();
        }

        Decorator::newLine();
        echo Decorator::hr();
        return ;
        /*if ($rand === 1) {
            echo "[ ✔ ]\t" . $consoleColor->apply("color_82", $cron);
        } else if($rand === 2) {
            echo "[ ❌ ]\t" . $consoleColor->apply("color_196", $cron);
        } else {
            echo "[ ➖ ]\t" . $consoleColor->apply("color_226", $cron);
        }*/
    }

    private function showLine(WatchmakerCore $kairos)
    {
        $consoleColor = new \JakubOnderka\PhpConsoleColor\ConsoleColor();

        if ($kairos->isInstalled()) {
            echo "[ ✔ ]\t" . Decorator::greenText($kairos->generate());
        } else {
            echo "[ ➖ ]\t" . $consoleColor->apply('color_226', $kairos->generate());
            $this->isAllGreen = false;
        }
    }

    private function showDeleteLine(WatchmakerCore $prevTask, array $taskList)
    {
        foreach($taskList as $task)
        {
            if ($prevTask->generate() === $task->generate()) {
                return ;
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

        if ($isInstalled === true) {
            $consoleColor = new \JakubOnderka\PhpConsoleColor\ConsoleColor();
            echo "[ ❌ ]\t" . $consoleColor->apply("color_196", $prevTask->generate());

            $this->isAllGreen = false;
        }
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
