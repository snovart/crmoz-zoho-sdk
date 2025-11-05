<?php

namespace App\Console\Commands;

use App\ModelsZoho\LeadZoho;
use Illuminate\Console\Command;
use Throwable;

class ZohoLeadActivitiesUpdate extends Command
{
    protected $signature = 'zoho:leads:activities:update
                            {leadId}
                            {rowId}
                            {--type=}
                            {--note=}
                            {--date=}';

    protected $description = 'Update a specific row in the Lead Activities subform';

    public function handle(): int
    {
        $leadId = (string)$this->argument('leadId');
        $rowId  = (string)$this->argument('rowId');
        $type   = $this->option('type');
        $note   = $this->option('note');
        $date   = $this->option('date');

        try {
            /** @var \ZohoCrmSDK\ModelsZoho\System\ObjectModel|null $lead */
            $lead = LeadZoho::find($leadId);
            if (!$lead) {
                $this->error("Lead not found: {$leadId}");
                return self::FAILURE;
            }

            $rows = $lead->getRowsSubform('lead_activities');
            if (empty($rows) || !isset($rows[$rowId])) {
                $this->error("Row not found: {$rowId}");
                return self::FAILURE;
            }

            // Update fields selectively
            if ($type) $rows[$rowId]['activity_type'] = $type;
            if ($note) $rows[$rowId]['note'] = $note;
            if ($date) $rows[$rowId]['activity_date'] = $date;

            // Replace all rows with the updated set
            $lead->addRowsSubform('lead_activities', array_values($rows), true);
            $lead->saveToZoho();

            $this->info("Row {$rowId} updated successfully.");
            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('ERROR: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
