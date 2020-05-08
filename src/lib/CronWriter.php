<?php
declare(strict_types=1);
namespace Watchmaker\lib;

use ErrorException;
use Watchmaker\error\FailedInstallCrontabException;
use Watchmaker\error\MissingCrontabException;
use Watchmaker\error\NotInstalledCrontabException;

class CronWriter
{
    /**
     * @param array $watchmakerList
     * @return bool
     * @throws FailedInstallCrontabException
     * @throws MissingCrontabException
     * @throws NotInstalledCrontabException
     * @throws ErrorException
     */
    public function write(array $watchmakerList) : bool
    {
        $ret = $this->isInstalledCrontab();
        if ($ret === false) {
            throw new NotInstalledCrontabException();
        }

        $error = fopen('php://temp', 'wb+');
        if ($error === false) {
            throw new ErrorException();

        }
        $descriptorspec = [
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => $error
        ];
        $process = proc_open('/usr/bin/crontab -', $descriptorspec, $pipes);
        if ($process === false) {
            throw new MissingCrontabException();
        }
        stream_set_blocking($pipes[1], false);

        foreach ($watchmakerList as $watchmaker)
        {
            fputs($pipes[0], $watchmaker->generate() . "\n");
        }
        fclose($pipes[0]);
        fclose($pipes[1]);

        $ret = proc_close($process);
        if ($ret !== 0) {
            // error (Error is output after proc_close)
            fseek($error, 0);
            $errorMessage = stream_get_contents($error);
            throw new FailedInstallCrontabException($errorMessage);
        }

        fclose($error);

        return true;
    }

    private function isInstalledCrontab() : bool
    {
        $out = null;
        $return = null;
        exec("which crontab", $out, $return);
        if ($return !== 0) {
            return false;
        }

        return true;
    }
}
