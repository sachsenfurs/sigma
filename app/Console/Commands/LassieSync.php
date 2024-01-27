<?php

namespace App\Console\Commands;

use App\Models\LostFoundItem;
use Illuminate\Console\Command;

class LassieSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lassie:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync LASSIE API (currently only Lost&Found Database)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        LostFoundItem::syncFromApi();
    }
}
