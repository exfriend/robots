<?php

namespace Exfriend\Robots;

trait RotateProxies
{
    private $rotator;


    public function rotateProxies( $list )
    {
        $this->rotator = new \Exfriend\Rotator\ProxyRotator( $list );
    }

    public function proxy()
    {
        return $this->rotator;
    }
}
