<?php

namespace Exfriend\Robots;

class Engine extends \Exfriend\CloudFactory\Engine
{

    protected function fulfillCachedRequests()
    {
        $this->requests->unprocessed()->where( 'remember', true )->map( function ( Request $item )
        {
            if ( \Cache::has( 'robots.request.' . $item->url ) )
            {
                $item->response = \Cache::get( 'robots.request.' . $item->url );
                $item->processed = true;
                $item->processing = false;

                $this->handleProcessedRequest( $this->client, $item );
            }
            else
            {
                $item->onSuccess( function ( Request $r )
                {
                    \Cache::forever( 'robots.request.' . $r->url, $r->response );
                } );
            }
        } );
    }
}

?>
