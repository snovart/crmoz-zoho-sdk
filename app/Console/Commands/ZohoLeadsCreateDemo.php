<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ModelsZoho\LeadZoho;
use Throwable;

class ZohoLeadsCreateDemo extends Command
{
    /**
     * Run: php artisan zoho:leads:create-demo [--dry] [--debug]
     */
    protected $signature = 'zoho:leads:create-demo
                            {--dry : Do not send to Zoho, just print payload}
                            {--debug : Print debug snapshots}';

    /**
     * Command description.
     */
    protected $description = 'Create a demo Lead in Zoho CRM via CRMOZ SDK (step-by-step)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating a demo Lead via CRMOZ SDK...');

        // Minimal valid payload for Leads
        // Usually Zoho requires Last Name + Company
        $payload = [
            'last_name'  => 'SDK Lead',
            'first_name' => 'Artem',
            'company'    => 'SDK Demo Company',
            'email'      => 'sdk.lead.' . time() . '@example.test',
            'phone'      => '+380000000010',
        ];

        // Dry-run: only print payload without sending
        if ($this->option('dry')) {
            $this->line('Dry-run: payload that would be sent:');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return self::SUCCESS;
        }

        try {
            // Create Lead via SDK model
            $lead = LeadZoho::createInZoho($payload);

            // Try to get Zoho ID (ObjectModel uses magic __get)
            $id = null;
            try {
                $id = $lead->id ?? null;
            } catch (Throwable $e) {
                $id = null;
            }

            // Fallback via attributes if needed
            if (!$id && method_exists($lead, 'getAllAttributes')) {
                $attrs = $lead->getAllAttributes();
                $id = $attrs['id'] ?? null;
            }

            // Debug info if requested
            if ($this->option('debug')) {
                $this->line('Debug class: ' . get_class($lead));
                if (method_exists($lead, 'getAllAttributes')) {
                    $this->line('Debug attrs: ' . json_encode(
                        $lead->getAllAttributes(),
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    ));
                }
            }

            $this->info('[OK] Lead created in Zoho.');
            $this->line('Zoho ID: ' . ($id ?: '(unknown)'));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('[ERROR] Failed to create lead in Zoho.');
            $this->line($e->getMessage());
            return self::FAILURE;
        }
    }
}
