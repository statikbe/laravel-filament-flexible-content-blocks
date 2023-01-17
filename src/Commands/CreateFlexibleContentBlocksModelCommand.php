<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Commands;

use Illuminate\Console\Command;

class FilamentFlexibleContentBlocksCommand extends Command
{
    public $signature = 'laravel-filament-flexible-content-blocks';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
