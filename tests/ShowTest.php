<?php
namespace Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Watchmaker\Config;
use Watchmaker\task\Show;
use Watchmaker\Watchmaker;
use Watchmaker\WatchmakerCore;

class ShowTest extends TestCase
{
    private static $loadMethod = null;

    public static function setUpBeforeClass(): void
    {
        $config = new Config();
        new Watchmaker($config);
    }

    protected function setUp(): void
    {
        $SingletonMock = Mockery::mock('alias:\Watchmaker\lib\CrontabLoader');
        self::$loadMethod = $SingletonMock
            ->byDefault()
            ->shouldReceive('load');
    }

    public function testCronOnly()
    {
        $cronList = [
            new WatchmakerCore('example.com'),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA'),
            (new WatchmakerCore('example.com'))
                ->day(1)
        ];
        $watchmakerList = [
        ];

        $show = new Show();
        self::$loadMethod
            ->andReturn($cronList);
        $text = $show->execute($watchmakerList);

        $expectedList = [
            "[ ❌ ]\t* * * * * example.com",
            "[ ❌ ]\tHOGE=FUGA",
            "[ ❌ ]\t* * 1 * * example.com"
        ];

        foreach($expectedList as $expected)
        {
            $expected = preg_quote($expected, '/');
            $this->assertRegExp('/'. $expected . '/', $text);
        }
    }

    public function testNotInstalled()
    {
        $cronList = [
        ];
        $watchmakerList = [
            new WatchmakerCore('example.com'),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA'),
            (new WatchmakerCore('example.com'))
                ->day(1)
        ];

        $show = new Show();
        self::$loadMethod
            ->andReturn($cronList);
        $text = $show->execute($watchmakerList);

        $expectedList = [
            "[ ➖ ]\t* * * * * example.com",
            "[ ➖ ]\tHOGE=FUGA",
            "[ ➖ ]\t* * 1 * * example.com"
        ];

        foreach($expectedList as $expected)
        {
            $expected = preg_quote($expected, '/');
            $this->assertRegExp('/'. $expected . '/', $text);
        }
    }

    public function testInstalled()
    {
        $cronList = [
            new WatchmakerCore('example.com'),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA'),
            (new WatchmakerCore('example.com'))
                ->day(1)
        ];
        $watchmakerList = [
            new WatchmakerCore('example.com'),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA'),
            (new WatchmakerCore('example.com'))
                ->day(1)
        ];

        $show = new Show();
        self::$loadMethod
            ->andReturn($cronList);
        $text = $show->execute($watchmakerList);

        $expectedList = [
            "[ ✔ ]\t* * * * * example.com",
            "[ ✔ ]\tHOGE=FUGA",
            "[ ✔ ]\t* * 1 * * example.com"
        ];

        foreach($expectedList as $expected)
        {
            $expected = preg_quote($expected, '/');
            $this->assertRegExp('/'. $expected . '/', $text);
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
