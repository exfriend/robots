<?php

namespace Exfriend\Robots\Generators\Scraper;

use Exfriend\Robots\Generators\BaseCommand;

class Command extends BaseCommand
{
    protected $name = 'robots:scraper';
    protected $namespace = 'App\\Robots';

    protected $description = 'Generate the whole scraper';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $this->error( 'Does not work yet' );
    }

    protected function getArguments()
    {
        return [
//                        [ 'name', InputArgument::REQUIRED, 'The name of the robot, lowercase e.g. ebay' ],
        ];
    }

    protected function getOptions()
    {
        return [
            //            [ 'url', 'u', InputOption::VALUE_OPTIONAL, 'Base URL e.g. http://site.com' ],
        ];
    }
}
