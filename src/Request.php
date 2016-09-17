<?php

namespace Exfriend\Robots;

use Exfriend\Rotator\ProxyRotator;
use Exfriend\Rotator\RotatingProxy;

/**
 * Class Request
 * @author exfriend
 */
class Request extends \Exfriend\CloudFactory\Request
{
    /**
     * @var ProxyRotator
     */
    protected $rotator;

    /**
     * Validate the request's response
     */
    public function validate()
    {

    }

    public function __construct( $url = null )
    {
        $url = $url ? $url : $this->url;
        parent::__construct( $this->method, $url, $this->options );

        if ( method_exists( $this, 'validate' ) )
        {
            $this->validateUsing( [ $this, 'validate' ] );
        }

        if ( method_exists( $this, 'success' ) )
        {
            $this->onSuccess( [ $this, 'success' ] );
        }

        if ( method_exists( $this, 'fail' ) )
        {
            $this->onFail( [ $this, 'fail' ] );
        }

        if ( method_exists( $this, 'lastFail' ) )
        {
            $this->onLastFail( [ $this, 'lastFail' ] );
        }
    }

    public function rotateProxies( ProxyRotator $rotator )
    {
        $this->rotator = $rotator;
        $proxy = $this->rotator->getWorkingProxy();
        $this->store( '__proxy', $proxy );
        $this->withProxy( $proxy->getProxyString() );

        $this->onSuccess( function ( $r )
        {
            /**
             * @var RotatingProxy $proxy
             */
            $proxy = $this->storage->get( '__proxy' );
            $proxy->requested();
            $proxy->succeeded();
        } );

        $this->onFail( function ( $r )
        {
            /**
             * @var RotatingProxy $proxy
             */
            $proxy = $this->storage->get( '__proxy' );
            $proxy->requested();
            $proxy->failed();
            $proxy = $this->rotator->getWorkingProxy();
            $this->store( '__proxy', $proxy );
            $this->withProxy( $proxy->getProxyString() );
        } );
    }
}
