<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ModelsZoho\ContactZoho;
use Throwable;

class ZohoContactsUpdateDemo extends Command
{
    /**
     * Run: php artisan zoho:contacts:update-demo {id}
     */
    protected $signature = 'zoho:contacts:update-demo {id : Zoho ID of the contact to update}';

    /**
     * Command description.
     */
    protected $description = 'Update an existing Contact in Zoho CRM via CRMOZ SDK';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $zohoId = $this->argument('id');
        $this->info("Updating contact in Zoho: {$zohoId}");

        try {
            // 1) Load the contact object from Zoho
            $contact = ContactZoho::find($zohoId);

            if (!$contact) {
                $this->error('Contact not found by that ID.');
                return self::FAILURE;
            }

            // 2) Prepare fields to update
            $updates = [
                'department' => 'SDK Testing',
                'phone' => '+380111111111',
            ];

            // 3) Update via SDK model (push changes to Zoho)
            $contact->updateInZoho($updates);

            $this->info('[OK] Contact successfully updated in Zoho.');
            $this->line('Updated fields: ' . json_encode($updates, JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Update failed.');
            $this->line($e->getMessage());
            return self::FAILURE;
        }
    }
}
