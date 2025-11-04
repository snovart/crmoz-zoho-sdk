<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// We'll use our SDK model in the next steps
use App\ModelsZoho\ContactZoho;

class ZohoContactsCreateDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run: php artisan zoho:contacts:create-demo
     */
    protected $signature = 'zoho:contacts:create-demo 
                            {--dry : Do not send to Zoho, just print payload}';

    /**
     * The console command description.
     */
    protected $description = 'Create a demo Contact in Zoho CRM via CRMOZ SDK (step-by-step)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating a demo Contact via CRMOZ SDK...');

        // 1) Минимальный валидный payload (Last Name обязателен в Zoho)
        $payload = [
            'last_name'  => 'SDK Demo',
            'first_name' => 'Artem',
            'email'      => 'sdk.demo.' . time() . '@example.test',
            'phone'      => '+380000000000',
            // можно добавить и другие поля по желанию:
            // 'lead_source' => 'Website',
        ];

        // 2) Режим dry-run — просто показать, что отправим
        if ($this->option('dry')) {
            $this->line('Dry-run: payload that would be sent:');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return self::SUCCESS;
        }

        try {
            // 3) Создание записи через модель SDK
            // NB: в SDK CRMOZ обычно поддержан статический метод createInZoho($attrs)
            /** @var \App\ModelsZoho\ContactZoho $contact */
            $contact = ContactZoho::createInZoho($payload);

            // 4) Пытаемся красиво показать ID, независимо от конкретного аксессора
            $id = null;
            if (is_object($contact)) {
                if (method_exists($contact, 'getZohoId')) {
                    $id = $contact->getZohoId();
                } elseif (property_exists($contact, 'zoho_id')) {
                    $id = $contact->zoho_id;
                } elseif (property_exists($contact, 'id')) {
                    $id = $contact->id;
                }
            }

            $this->info('[OK] Contact created in Zoho.');
            $this->line('Zoho ID: ' . ($id ?: '(unknown)'));

            // 5) Выведем то, что вернулось (на случай отладки)
            $this->line('Response snapshot:');
            $this->line(json_encode($contact, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('[ERROR] Failed to create contact in Zoho.');
            $this->line($e->getMessage());
            return self::FAILURE;
        }
    }

}
