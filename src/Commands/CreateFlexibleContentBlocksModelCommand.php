<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Commands;

use Illuminate\Console\Command;

class CreateFlexibleContentBlocksModelCommand extends Command
{
    public $signature = 'filament-flexible-content-blocks:model';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
