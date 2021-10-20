<?php

namespace App\Actions;

use App\Models\Report;
use Illuminate\Support\Str;

class SaveReportAction
{
    protected Report $report;

    public function execute(Report $report, array $data): Report
    {
        $this->report = $report;

        if (! empty($this->report->id)) {
            $this->report->update($data);
            return $this->report;
        }

        //create case
        $this->report = $this->report->create($data);
        return $this->report;
    }

}
