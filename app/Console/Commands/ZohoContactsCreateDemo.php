<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ModelsZoho\ContactZoho;
use Throwable;

class ZohoContactsCreateDemo extends Command
{
    /**
     * Run: php artisan zoho:contacts:create-demo [--dry] [--debug]
     */
    protected $signature = 'zoho:contacts:create-demo
                            {--dry : Do not send to Zoho, just print payload}
                            {--debug : Print debug snapshots}';

    /**
     * Command description.
     */
    protected $description = 'Create a demo Contact in Zoho CRM via CRMOZ SDK (step-by-step)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating a demo Contact via CRMOZ SDK...');

        // Minimal valid payload (Zoho requires Last Name)
        $payload = [
            'last_name'  => 'SDK Demo 3',
            'first_name' => 'Artem 3',
            'email'      => 'sdk.demo.' . time() . '@example.test',
            'phone'      => '+380000000003',
        ];

        // Dry-run: just show the payload
        if ($this->option('dry')) {
            $this->line('Dry-run: payload that would be sent:');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return self::SUCCESS;
        }

        try {
            // Create in Zoho via SDK model
            $contact = ContactZoho::createInZoho($payload);

            // Correct way to extract Zoho ID:
            // 1) SDK ObjectModel has magic __get, so $contact->id should work.
            $id = null;
            try {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $id = $contact->id ?? null; // triggers __get('id') internally
            } catch (\Throwable $e) {
                $id = null;
            }

            // 2) Fallback: read attributes array if available
            if (!$id && method_exists($contact, 'getAllAttributes')) {
                $attrs = $contact->getAllAttributes();
                $id = $attrs['id'] ?? null;
            }

            // Optional debug output
            if ($this->option('debug')) {
                $this->line('Debug: class = ' . get_class($contact));
                if (method_exists($contact, 'getAllAttributes')) {
                    $this->line('Debug attributes: ' . json_encode(
                        $contact->getAllAttributes(),
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    ));
                }
            }

            $this->info('[OK] Contact created in Zoho.');
            $this->line('Zoho ID: ' . ($id ?: '(unknown)'));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('[ERROR] Failed to create contact in Zoho.');
            $this->line($e->getMessage());
            return self::FAILURE;
        }
    }
}
