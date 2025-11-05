<?php

namespace App\Console\Commands;

use App\ModelsZoho\LeadZoho;
use Illuminate\Console\Command;
use Throwable;

class ZohoLeadActivitiesDelete extends Command
{
    protected $signature = 'zoho:leads:activities:delete
                            {leadId : Zoho Lead ID}
                            {rowId? : Subform row ID to delete (omit when using --all)}
                            {--all : Delete ALL rows of the subform for the given lead}
                            {--debug : Verbose output}';

    protected $description = 'Delete one row (by rowId) or all rows of the Lead Activities subform via CRMOZ SDK';

    public function handle(): int
    {
        $leadId    = (string) $this->argument('leadId');
        $rowId     = $this->argument('rowId') ? (string) $this->argument('rowId') : null;
        $deleteAll = (bool) $this->option('all');

        // Basic args validation
        if (!$deleteAll && !$rowId) {
            $this->error('Provide rowId or use --all.');
            return self::FAILURE;
        }

        try {
            /** @var \ZohoCrmSDK\ModelsZoho\System\ObjectModel $lead */
            $lead = LeadZoho::find($leadId);
            if (!$lead) {
                $this->error("Lead not found: {$leadId}");
                return self::FAILURE;
            }

            // Read current subform rows (local representation inside the SDK object)
            $current = $lead->getRowsSubform('lead_activities') ?? [];

            if ($deleteAll) {
                // Trick: mark subform as "updated" first, then clear it,
                // so createDataZoho() will serialize an empty array for the subform.
                if ($this->option('debug')) {
                    $this->line('Delete-all: mark as updated with a dummy row, then clear.');
                }

                // 1) add a dummy row to set update flag
                $lead->addRowsSubform('lead_activities', [[]], true);
                // 2) clear rows (keeps the "updated" flag set)
                $lead->clearSubform('lead_activities');
                // 3) push to Zoho
                $lead->saveToZoho();

                $this->info("All rows removed for lead {$leadId}.");
                return self::SUCCESS;
            }

            // Delete one row: keep all rows except the given rowId
            $filtered = array_values(array_filter($current, function ($r) use ($rowId) {
                return (string)($r['id'] ?? '') !== $rowId;
            }));

            if (count($filtered) === count($current)) {
                $this->info("Row {$rowId} not found in subform for lead {$leadId} (nothing to delete).");
                return self::SUCCESS;
            }

            if ($this->option('debug')) {
                $this->line('Delete-one: replacing subform with filtered rows.');
                $this->line('Filtered payload (local): ' . json_encode($filtered, JSON_UNESCAPED_UNICODE));
            }

            if (empty($filtered)) {
                // After deletion nothing remains -> use the same trick as for --all
                $lead->addRowsSubform('lead_activities', [[]], true);
                $lead->clearSubform('lead_activities');
            } else {
                // Replace with remaining rows; this sets the update flag automatically
                $lead->addRowsSubform('lead_activities', $filtered, true);
            }

            $lead->saveToZoho();

            $this->info("Row {$rowId} removed for lead {$leadId}.");
            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('ERROR: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
