<?php

namespace Exfriend\Robots;

/**
 * Class Kernel
 * @author yourname
 */
abstract class Kernel
{
    protected $robots;

    public function getRobots()
    {
        return $this->robots;
    }
}
