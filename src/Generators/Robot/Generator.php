<?php


namespace Exfriend\Robots\Generators\Robot;


use Exfriend\Robots\Generators\Generator as BaseGenerator;

class Generator extends BaseGenerator
{
    protected $name;

    public function getNamespace()
    {
        return 'App\\Robots\\' . ucfirst( $this->name ) . '\\Robot';
    }

    public function getOutputFilePath()
    {
        return app_path() . '/Robots/' . ucfirst( $this->name ) . '/Robot.php';
    }


    public function generate( $name, $bindings )
    {
        $this->name = $name;
        $this->handle( 'robot', $bindings );
    }

    public function registerInRobotsKernel()
    {
        return true;
    }

}