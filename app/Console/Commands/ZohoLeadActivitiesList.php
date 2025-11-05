<?php

namespace App\Console\Commands;

use App\ModelsZoho\LeadZoho;
use Illuminate\Console\Command;
use Throwable;

class ZohoLeadActivitiesList extends Command
{
    protected $signature = 'zoho:leads:activities:list {leadId}';
    protected $description = 'List rows of the Lead Activities subform for a given lead';

    public function handle(): int
    {
        $leadId = (string) $this->argument('leadId');

        try {
            /** @var \ZohoCrmSDK\ModelsZoho\System\ObjectModel|null $lead */
            $lead = LeadZoho::find($leadId);
            if (!$lead) {
                $this->error("Lead not found: {$leadId}");
                return self::FAILURE;
            }

            $rows = $lead->getRowsSubform('lead_activities') ?? [];
            if (empty($rows)) {
                $this->info('No rows in subform.');
                return self::SUCCESS;
            }

            // Display formatted list of subform rows
            $this->line(str_pad('Row ID', 20) . str_pad('Date', 14) . str_pad('Type', 10) . 'Note');
            $this->line(str_repeat('-', 80));

            foreach ($rows as $rowId => $row) {
                $this->line(
                    str_pad((string)$rowId, 20) .
                    str_pad((string)($row['activity_date'] ?? ''), 14) .
                    str_pad((string)($row['activity_type'] ?? ''), 10) .
                    (string)($row['note'] ?? '')
                );
            }

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('ERROR: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}