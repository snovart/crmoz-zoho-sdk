<?php

namespace App\Console\Commands;

use App\ModelsZoho\LeadZoho;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class SyncLeadsCommand extends Command
{
    protected $signature = 'leads:sync';
    protected $description = 'Full page-by-page sync of Zoho Leads into local DB (auto-creates table if needed)';

    public function handle(): int
    {
        $this->info('Checking local DB structure...');

        // If the "leads" table does not exist — create it via SDK artisan command
        if (!Schema::hasTable('leads')) {
            $this->warn('Table "leads" not found. Creating via SDK...');
            Artisan::call('zoho-crm-sdk:sync-records', [
                'model' => 'LeadZoho',
            ]);

            // Show SDK command output
            $this->line(Artisan::output());
            $this->info('Table "leads" is ready.');
        }

        $page  = 1;
        $total = 0;

        $this->info('Starting full Leads sync (page-by-page)...');

        while (true) {
            $this->line("Fetching page {$page}...");
            $collection = LeadZoho::all($page);

            // Stop if there are no more records
            if ($this->isEmptySdkCollection($collection)) {
                $this->info('No more pages. Finishing...');
                break;
            }

            // Save current page records into DB
            $collection->saveToDB();

            $count  = $this->countSdkCollection($collection);
            $total += $count;

            $this->line("Saved {$count} leads from page {$page}.");
            $page++;
        }

        $this->info("Done. Total saved: {$total}");
        return self::SUCCESS;
    }

    /**
     * Determine if the Zoho SDK collection is empty.
     */
    private function isEmptySdkCollection($collection): bool
    {
        if ($collection === null) return true;
        if (method_exists($collection, 'isEmpty')) return $collection->isEmpty();
        if (method_exists($collection, 'count')) return $collection->count() === 0;
        if (method_exists($collection, 'toArray')) return count($collection->toArray()) === 0;
        foreach ($collection as $unused) return false;
        return true;
    }

    /**
     * Count records in the Zoho SDK collection safely.
     */
    private function countSdkCollection($collection): int
    {
        if ($collection === null) return 0;
        if (method_exists($collection, 'count')) return (int)$collection->count();
        if (method_exists($collection, 'toArray')) return count($collection->toArray());
        $i = 0;
        foreach ($collection as $unused) $i++;
        return $i;
    }
}
