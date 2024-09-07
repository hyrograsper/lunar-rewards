<?php

namespace Hyrograsper\LunarRewards\Commands;

use Illuminate\Console\Command;

class LunarRewardsCommand extends Command
{
    public $signature = 'lunar-rewards';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
