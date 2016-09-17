<?php


namespace Exfriend\Robots;


use Exfriend\CloudFactory\Request;

class DefaultRobot extends Robot
{

    public function configure()
    {

    }

    public function handle()
    {

    }

    public function get( $url )
    {
        $rq = new Request( $url );
        ( new Engine() )->run( $rq );
        return $rq->response;
    }
}