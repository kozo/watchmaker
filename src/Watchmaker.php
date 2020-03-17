<?php
declare(strict_types=1);
namespace Watchmaker;

use Watchmaker\task\Install;
use Watchmaker\task\Show;

class Watchmaker
{
    private static $config = [];
    private $taskList = [];

    public function __construct(Config $config)
    {
        self::$config = $config;
    }

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

    public static function getConfig() : Config
    {
        return self::$config;
    }
}
