<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ModelsZoho\ContactZoho;

class ZohoContactsUpdateDemo extends Command
{
    protected $signature = 'zoho:contacts:update-demo {id : Zoho ID of contact}';
    protected $description = 'Update existing Zoho contact via CRMOZ SDK';

    public function handle(): int
    {
        $zohoId = $this->argument('id');
        $this->info("Updating contact $zohoId...");

        try {
            // Найдём контакт по ID
            $contact = ContactZoho::find($zohoId);
            if (!$contact) {
                $this->error('Contact not found by ID in Zoho');
                return self::FAILURE;
            }

            // Новые данные
            $updates = [
                'department' => 'SDK Testing',
                'phone' => '+380111111111',
            ];

            // Обновляем через SDK
            $contact->updateInZoho($updates);

            $this->info('[OK] Contact updated in Zoho.');
            $this->line('Updated fields: ' . json_encode($updates));

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Update failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
