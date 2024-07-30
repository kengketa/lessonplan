<?php

namespace App\Actions;

use App\Models\Agenda;
use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\isEmpty;

class ImportLessonPlanFromExcelAction
{
    protected array $rows;
    protected School $school;

    public function execute(School $school, Collection $rows)
    {
        $this->rows = $rows[0]->toArray();
        $this->school = $school;
        $data = [];
        $grade = $this->findGradeFromString($this->rows[0][0]);
        $subject = $this->getSubjectFromString($this->rows[0][0]);
        foreach ($this->rows[1] as $role1Index => $weekNumber) {
            if ($role1Index == 0) {
                continue;
            }
            foreach ($this->rows[2] as $role2Index => $lessonNumber) {
                if ($role2Index == 0) {
                    continue;
                }
                $data[(integer)$weekNumber][(integer)$lessonNumber]['grade_id'] = $grade->id;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['subject'] = $subject;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['week'] = (integer)$weekNumber;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['lesson'] = (integer)$lessonNumber;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['date'] = null;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['topic'] = null;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['vocabularies'] = null;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['plans'] = null;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['teaching_materials'] = null;
                $data[(integer)$weekNumber][(integer)$lessonNumber]['activity'] = null;
            }
        }
        foreach ($this->rows as $rowIndex => $row) {
            if ($rowIndex <= 2) {
                continue;
            }
            foreach ($row as $columnIndex => $column) {
                if ($columnIndex == 0) {
                    continue;
                }
                switch ($rowIndex) {
                    case 3:
                        $header = "date";
                        break;
                    case 4:
                        $header = "topic";
                        break;
                    case 5:
                        $header = "vocabularies";
                        break;
                    case 6:
                        $header = "plans";
                        break;
                    case 7:
                        $header = "teaching_materials";
                        break;
                    case 8:
                        $header = "activity";
                        break;
                    default:
                        $header = null;
                        break;
                }
                if (!$header) {
                    continue;
                }
                $weekNumber = $this->rows[1][$columnIndex];
                $lessonNumber = $this->rows[2][$columnIndex];
                $data[$weekNumber][$lessonNumber][$header] = $column;
                if ($header == 'vocabularies') {
                    $data[$weekNumber][$lessonNumber][$header] = explode(',', $column);
                }
                if ($header == 'date' && $column != null) {
                    $parsedDate = Carbon::createFromFormat('d/m/Y', $column);
                    $formattedDate = $parsedDate->format('Y-m-d');
                    $data[$weekNumber][$lessonNumber][$header] = $formattedDate;
                }
            }
        }
        $flattenedData = collect($data)->flatMap(function ($value, $key) {
            return is_array($value)
                ? collect($value)->mapWithKeys(function ($subValue, $subKey) use ($key) {
                    return [$key . '.' . $subKey => $subValue];
                })
                : [$key => $value];
        })->toArray();
        $onlyFilledPlans = array_filter($flattenedData, function ($item) {
            return !is_null($item['plans']) || !empty($item['plans']);
        });
        $readyInputs = [];
        foreach ($onlyFilledPlans as $plan) {
            $readyInputs[] = $this->makeReadyInput($plan);
        }
        foreach ($readyInputs as $readyInput) {
            $saveReportAction = new SaveReportAction();
            $report = Report::where([
                'grade_id' => $readyInput['report']['for_grades'][0]['id'],
                'academic_year' => getCurrentAcademicYear(),
                'semester' => getCurrentSemester(),
                'week_number' => $readyInput['week_number'],
                'lesson_number' => $readyInput['lesson_number'],
                'subject' => $readyInput['subject'],
            ])->first();
            if (!$report) {
                $report = new Report();
            }
            $saveReportAction->execute($report, $this->school, $readyInput);
        }
    }

    private function findGradeFromString($str): null|Grade
    {
        $parts = explode("/", $str);
        $first_part = $parts[0];
        $class = $parts[1];
        $type = $first_part[0];
        $level = substr($first_part, 1);
        switch ($type) {
            case "M":
                $typeId = Grade::SECONDARY_TYPE;
                break;
            case "K":
                $typeId = Grade::KINDERGATEN_TYPE;
                break;
            case "P":
                $typeId = Grade::PRIMARY_TYPE;
                break;
            default:
                $typeId = null;
                break;
        }
        $grade = Grade::where([
            'school_id' => $this->school->id,
            'type' => $typeId,
            'level' => $level,
            'room_number' => $class,
        ])->first();
        if (!$grade) {
            return null;
        }
        return $grade;
    }

    private function getSubjectFromString($str)
    {
        $parts = explode(":", $str);
        $subject = $parts[1];
        if (!$parts[1]) {
            return null;
        }
        $collection = collect($this->school->subjects);
        $result = $collection->filter(function ($item) use ($subject) {
            return strtolower($item['name']) === strtolower($subject);
        })->first();
        if (!$result) {
            return null;
        }
        return $result;
    }

    private function makeReadyInput(array $input)
    {
        $transformedInput = [
            "school_id" => $this->school->id,
            "subject" => $input['subject']['id'],
            "week_number" => $input['week'],
            "lesson_number" => $input['lesson'],
            "teaching_materials" => $input['teaching_materials'],
            "activities" => $input['activity'],
            "outcome" => null,
            "outstanding_students" => null,
            "need_improvement_students" => null,
            "misbehavior_students" => [],
            "report" => [
                "for_grades" => [
                    [
                        "id" => $input['grade_id'],
                        "date" => $input['date']
                    ]
                ],
                "plans" => [
                    [
                        "type" => 2, // Learning Area
                        "topic" => $input['topic'],
                        "vocabs" => $input['vocabularies'],
                        "details" => $input['plans']
                    ]
                ]
            ]
        ];
        return $transformedInput;
    }

}
