<?php

namespace Hyrograsper\LunarRewards\Facades;

use Hyrograsper\LunarRewards\Base\RewardManagerInterface;
use Illuminate\Support\Facades\Facade;

class Rewards extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RewardManagerInterface::class;
    }
}
