<?php

function x( $name = 'default' )
{
    return app( 'robots.' . $name );
}

function robot( $name = 'default' )
{
    return app( 'robots.' . $name );
}
