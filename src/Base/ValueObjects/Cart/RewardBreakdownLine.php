<?php

namespace Hyrograsper\LunarRewards\Base\ValueObjects\Cart;

use Lunar\Models\CartLine;

class RewardBreakdownLine
{
    public function __construct(
        public CartLine $line,
        public int      $quantity,
    )
    {
        //
    }
}
