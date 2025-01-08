<?php

// app/Console/Commands/SyncLocations.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LocationService;

class SyncLocations extends Command
{
    protected $signature = 'locations:sync';
    protected $description = 'Sync locations from API to database';

    public function handle(LocationService $locationService)
    {
        $this->info('Starting location sync...');
        $locationService->syncLocations();
        $this->info('Location sync completed!');
    }
}