<?php

namespace App\Models;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class GameLevelingOrderIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $name = 'order';
    /**
     * @var array
     */
    protected $settings = [
        //
    ];
}