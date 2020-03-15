<?php
declare(strict_types=1);
namespace Watchmaker;

use Watchmaker\task\Install;
use Watchmaker\task\Show;

class Watchmaker
{
    private $taskList = [];

    public function task($command = '')
    {
        return new WatchmakerCore($command);
    }

    public function add(WatchmakerCore $kairos)
    {
        $this->taskList[] = $kairos;
    }

    public function show()
    {
        $show = new Show();

        return $show->execute($this->taskList);
    }

    public function install()
    {
        $install = new Install();

        return $install->execute($this->taskList);
    }
}
