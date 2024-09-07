<?php

namespace Hyrograsper\LunarRewards\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hyrograsper\LunarRewards\LunarRewards
 */
class LunarRewards extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Hyrograsper\LunarRewards\LunarRewards::class;
    }
}
