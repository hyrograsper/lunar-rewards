<?php

namespace Hyrograsper\LunarRewards\Base\ValueObjects\Cart;

class RewardBreakdownLine
{
    public function __construct(
        public int $lineId,
        public int $quantity,
    ) {
        //
    }
}
