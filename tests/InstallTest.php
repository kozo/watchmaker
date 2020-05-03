<?php
namespace Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Watchmaker\Config;
use Watchmaker\task\Install;
use Watchmaker\Watchmaker;
use Watchmaker\WatchmakerCore;

class InstallTest extends TestCase
{
    private static $loadMethod = null;

    public function testHoge()
    {
        $this->assertTrue(true);
    }

    protected function setUp(): void
    {
        $SingletonMock = Mockery::mock('alias:\Watchmaker\lib\CrontabLoader');
        self::$loadMethod = $SingletonMock
            ->shouldReceive('load');

        $SingletonMock = Mockery::mock('alias:\Watchmaker\lib\CronWriter');
        self::$loadMethod = $SingletonMock
            ->shouldReceive('write')
            ->andReturn(true);
    }

    public function testCronOnlyForDisabledDelete()
    {
        $config = new Config(['delete' => false]);
        new Watchmaker($config);

        $mergeList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::CRON_ONLY)
        ];

        $install = new Install();
        $reflection = new ReflectionClass($install);
        $method = $reflection->getMethod('createInstallList');
        $method->setAccessible(true);

        $installList = $method->invoke($install, $mergeList);
        $this->assertEquals($mergeList, $installList);
    }

    public function testCronOnlyForEnabledDelete()
    {
        $config = new Config(['delete' => true]);
        new Watchmaker($config);

        $mergeList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::CRON_ONLY)
        ];

        $install = new Install();
        $reflection = new ReflectionClass($install);
        $method = $reflection->getMethod('createInstallList');
        $method->setAccessible(true);

        $installList = $method->invoke($install, $mergeList);
        $this->assertEquals([], $installList);
    }

    public function testNonInstalled()
    {
        $config = new Config(['delete' => false]);
        new Watchmaker($config);

        $mergeList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::NOT_INSTALLED),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::NOT_INSTALLED),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::NOT_INSTALLED)
        ];

        $install = new Install();
        $reflection = new ReflectionClass($install);
        $method = $reflection->getMethod('createInstallList');
        $method->setAccessible(true);

        $installList = $method->invoke($install, $mergeList);
        $this->assertEquals($mergeList, $installList);
    }

    public function testInstalled()
    {
        $config = new Config(['delete' => false]);
        new Watchmaker($config);

        $mergeList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::INSTALLED)
        ];

        $install = new Install();
        $reflection = new ReflectionClass($install);
        $method = $reflection->getMethod('createInstallList');
        $method->setAccessible(true);

        $installList = $method->invoke($install, $mergeList);
        $this->assertEquals($mergeList, $installList);
    }

    public function testMixedForDisabledDelete()
    {
        $config = new Config(['delete' => false]);
        new Watchmaker($config);

        $mergeList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::NOT_INSTALLED)
        ];

        $install = new Install();
        $reflection = new ReflectionClass($install);
        $method = $reflection->getMethod('createInstallList');
        $method->setAccessible(true);

        $installList = $method->invoke($install, $mergeList);
        $this->assertEquals($mergeList, $installList);
    }

    public function testMixedForEnabledDelete()
    {
        $config = new Config(['delete' => true]);
        new Watchmaker($config);

        $mergeList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::NOT_INSTALLED)
        ];
        $expectedList = [
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore('example.com'))
                ->day(1)
                ->mark(WatchmakerCore::NOT_INSTALLED)
        ];

        $install = new Install();
        $reflection = new ReflectionClass($install);
        $method = $reflection->getMethod('createInstallList');
        $method->setAccessible(true);

        $installList = $method->invoke($install, $mergeList);
        $this->assertEquals($expectedList, $installList);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
