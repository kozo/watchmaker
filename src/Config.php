<?php
declare(strict_types=1);
namespace Watchmaker;

use Watchmaker\error\MissingParameterException;


/**
 * @property-read string $ansi
 * @property-read string $delete
 */
class Config
{
    private $ansi = false;
    private $delete = false;

    public function __construct(array $options = array())
    {
        $this->init($options);
    }

    private function init($options)
    {
        if (empty($options)) {
            return;
        }

        foreach ($options as $key => $val)
        {
            if (!isset($this->{$key})) {
                continue;
            }
            $this->{$key} = $val;
        }
    }

    /**
     * @param $get
     * @return mixed
     * @throws MissingParameterException
     */
    public function __get($get)
    {
        if (isset($this->{$get})) {
            return $this->{$get};
        }

        throw new MissingParameterException();
    }
}
