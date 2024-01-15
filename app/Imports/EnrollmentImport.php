<?php

namespace App\Imports;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class EnrollmentImport implements ToCollection
{
    public Grade $grade;

    public function __construct(Grade $grade)
    {
        $this->grade = $grade;
    }

    public function collection(Collection $rows)
    {
        $columnHeader[0] = 'number';
        $columnHeader[2] = 'prefix';
        $columnHeader[3] = 'first_name';
        $columnHeader[4] = 'last_name';
        $columnHeader[5] = 'nick_name';
        $columnHeader[6] = 'email';
        $columnHeader[7] = 'phone';
        $newStudents = [];
        foreach ($rows as $key => $columnData) {
            if ($key == 0) {
                continue;
            }
            //skip the empty rows
            if ($columnData[2] == null || $columnData[3] == null || $columnData[4] == null) {
                continue;
            }
            $newStudent = [];
            foreach ($columnData as $columnIndex => $columnDatum) {
                if (isset($columnHeader[$columnIndex])) {
                    $newStudent[$columnHeader[$columnIndex]] = $columnDatum;
                }
            }
            $newStudents[] = $newStudent;
        }
        $toEnrollStudentIds = [];
        foreach ($newStudents as $newStudent) {
            //create new student case
            if ($newStudent["number"] == null) {
                $student = Student::where([
                    'first_name' => $newStudent['first_name'],
                    'last_name' => $newStudent['last_name'],
                ])->first();
                if (!$student) {
                    $newStudent['number'] = Student::where('school_id', $this->grade->school->id)->max('number') + 1;
                    $newStudent['school_id'] = $this->grade->school->id;
                    $student = Student::create($newStudent);
                }
                $toEnrollStudentIds[] = $student->id;
            }
            //case update student data
            if ($newStudent['number'] != null) {
                $student = Student::where('number', $newStudent['number'])->first();
                $student->update([
                    'prefix' => $newStudent['prefix'],
                    'first_name' => $newStudent['first_name'],
                    'last_name' => $newStudent['last_name'],
                    'nick_name' => $newStudent['nick_name'],
                    'email' => $newStudent['email'],
                    'phone' => $newStudent['phone'],
                ]);
                $toEnrollStudentIds[] = $student->id;
            }
        }
        $toEnrollStudentIds = array_unique($toEnrollStudentIds);
        foreach ($toEnrollStudentIds as $studentId) {
            $foundDuplicatedEnrollment = Enrollment::where([
                'student_id' => $studentId,
                'grade_id' => $this->grade->id,
                'academic_year' => getCurrentAcademicYear(),
                'semester' => getCurrentSemester()
            ])->first();
            if ($foundDuplicatedEnrollment) {
                continue;
            }
            $maxNumberInGrade = Enrollment::where([
                'grade_id' => $this->grade->id,
                'academic_year' => getCurrentAcademicYear(),
                'semester' => getCurrentSemester()
            ])->max('number_in_grade') ?? 0;

            Enrollment::create([
                'student_id' => $studentId,
                'number_in_grade' => $maxNumberInGrade + 1,
                'grade_id' => $this->grade->id,
                'academic_year' => getCurrentAcademicYear(),
                'semester' => getCurrentSemester()
            ]);
        }
    }
}
