<?php

namespace App\Actions;

use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use App\Transformers\GradeTransformer;
use Illuminate\Support\Str;

class PrepareReportAction
{
    public function execute(School $school): array
    {
        $report['school'] = $school->toArray();
        $report['all_grades'] = fractal($school->grades, new GradeTransformer())->toArray()['data'];
        $report['all_subjects'] = $school->subjects;
        $report['types'] = [
            ['id' => Report::TOPIC_PHONIC, 'name' => 'phonic'],
            ['id' => Report::TOPIC_LEARNING_AREA, 'name' => 'learning area'],
        ];
        $report['for_grades'] = [];
        $report['plans'] = [];
        return $report;
    }

}
