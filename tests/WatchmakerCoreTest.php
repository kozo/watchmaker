<?php
namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Watchmaker\error\EmptyCommandException;
use Watchmaker\WatchmakerCore;

class WatchmakerCoreTest extends TestCase
{
    public function testGenerate()
    {
        $core = new WatchmakerCore('example.php');
        $this->assertEquals('* * * * * example.php', $core->generate());

        try {
            (new WatchmakerCore())
                ->generate();
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertInstanceOf(EmptyCommandException::class, $e);
        }

        $command = (new WatchmakerCore())
            ->command('example.php')
            ->generate();
        $this->assertEquals('* * * * * example.php', $command);

        $command = (new WatchmakerCore())
            ->rawLine('HOGE=FUGA')
            ->generate();
        $this->assertEquals('HOGE=FUGA', $command);
    }

    public function testParse()
    {
        $compCore = new WatchmakerCore('example.com');
        $target = (new WatchmakerCore())
            ->parse('* * * * * example.com');
        // @todo : manage必要なんだっけ？
        $this->assertTrue($target->isManage());
        $this->assertEquals($target, $compCore);

        $compCore = (new WatchmakerCore('example.com'))
            ->minute(1)
            ->hour(2)
            ->day(3)
            ->month(4)
            ->week(5);
        $target = (new WatchmakerCore())
            ->parse('1 2 3 4 5 example.com');
        // @todo : manage必要なんだっけ？
        $this->assertTrue($target->isManage());
        $this->assertEquals($target, $compCore);

        $compCore = (new WatchmakerCore())
            ->rawLine('HOGE=FUGA');
        $target = (new WatchmakerCore())
            ->parse('HOGE=FUGA');
        $this->assertFalse($target->isManage());
        $this->assertEquals($target, $compCore);
    }
}
