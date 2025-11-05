<?php

namespace App\Console\Commands;

use App\ModelsZoho\LeadZoho;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class ZohoLeadActivitiesAddRow extends Command
{
    protected $signature = 'zoho:leads:add-activity
                            {leadId}
                            {--type=Call}
                            {--note=}
                            {--date=}
                            {--replace : Clear subform before adding a new row}
                            {--debug}';

    protected $description = 'Add a new row to the Lead Activities subform using the CRMOZ SDK';

    public function handle(): int
    {
        $leadId = trim((string)$this->argument('leadId'));
        $type   = (string)$this->option('type');
        $note   = (string)($this->option('note') ?? '');
        $dateIn = (string)($this->option('date') ?? Carbon::today()->toDateString());
        $replace = (bool)$this->option('replace');

        try {
            $date = Carbon::parse($dateIn)->toDateString();
        } catch (Throwable $e) {
            $this->error('Invalid --date format (expected Y-m-d).');
            return self::FAILURE;
        }

        // Build local subform row structure
        $row = [
            'activity_date' => $date,
            'activity_type' => $type,
            'note'          => $note,
        ];

        try {
            /** @var \ZohoCrmSDK\ModelsZoho\System\ObjectModel $lead */
            $lead = LeadZoho::find($leadId);
            if (!$lead) {
                $this->error("Lead not found: {$leadId}");
                return self::FAILURE;
            }

            if ($this->option('debug')) {
                $this->line('Row (local): ' . json_encode($row, JSON_UNESCAPED_UNICODE));
                $this->line($replace
                    ? 'Mode: replace existing rows (clear before add).'
                    : 'Mode: append new row (keep existing).'
                );
            }

            // Add or replace subform rows
            if ($replace) {
                // Replace: clear existing rows before adding
                $lead->addRowsSubform('lead_activities', [$row], true);
            } else {
                // Append: keep existing rows and add a new one
                $lead->addRowsSubform('lead_activities', [$row]);
            }

            // Push updates to Zoho CRM
            $lead->saveToZoho();

            $this->info('OK: subform row added successfully.');
            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('ERROR: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
