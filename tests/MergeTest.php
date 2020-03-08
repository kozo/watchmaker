<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Watchmaker\lib\Merge;
use Watchmaker\WatchmakerCore;

class MergeTest extends TestCase
{
    public function testZero()
    {
        $cronList = [];
        $watchmakerList = [];
        $targetList = [];

        $newList = Merge::execute($watchmakerList, $cronList);
        $this->assertEquals($targetList, $newList);
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
        $watchmakerList = [];
        $targetList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY)
                ->day(1)
        ];

        $newList = Merge::execute($watchmakerList, $cronList);
        $this->assertEquals($targetList, $newList);
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
        $targetList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::NOT_INSTALLED),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::NOT_INSTALLED),
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::NOT_INSTALLED)
                ->day(1)
        ];

        $newList = Merge::execute($watchmakerList, $cronList);
        $this->assertEquals($targetList, $newList);
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
        $targetList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::INSTALLED)
                ->day(1)
        ];

        $newList = Merge::execute($watchmakerList, $cronList);
        $this->assertEquals($targetList, $newList);
    }

    public function testMixed()
    {
        $cronList = [
            new WatchmakerCore('example.com'),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
        ];
        $watchmakerList = [
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA'),
            (new WatchmakerCore('example.com'))
                ->day(1)
        ];
        $targetList = [
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::CRON_ONLY),
            (new WatchmakerCore())
                ->rawLine('HOGE=FUGA')
                ->mark(WatchmakerCore::INSTALLED),
            (new WatchmakerCore('example.com'))
                ->mark(WatchmakerCore::NOT_INSTALLED)
                ->day(1)
        ];

        $newList = Merge::execute($watchmakerList, $cronList);
        $this->assertEquals($targetList, $newList);
    }
}
