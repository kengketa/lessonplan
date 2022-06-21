<?php

namespace App\Transformers;

use App\Models\Grade;
use App\Models\Report;
use Illuminate\Support\Arr;
use League\Fractal\TransformerAbstract;

class VocabTransformer extends TransformerAbstract
{
    public function transform(Report $report): array
    {
        $vocabs = [];
        foreach ($report->plans as $plan) {
            $vocabs[] = $plan['vocabs'];
        }
        $data = [
            'report_id' => $report->id,
            'grade' => $this->getGrade($report->grade),
            'subject' => $report->getSubjectName(),
            'vocabs' => Arr::flatten($vocabs)
        ];
        return $data;
    }

    public function getGrade(Grade $grade)
    {
        $prefix = "";
        switch ($grade->type) {
            case Grade::NURSERY_TYPE:
                $prefix = 'Preschool';
                break;
            case Grade::KINDERGATEN_TYPE:
                $prefix = 'Kindergarten';
                break;
            case Grade::PRIMARY_TYPE:
                $prefix = 'Primary';
                break;
            case Grade::SECONDARY_TYPE:
                $prefix = 'Secondary';
                break;
        }
        return $prefix.'-'.$grade->level;
    }
}
