<?php

function x($name = 'default')
{
    return app('robots.' . $name);
}
