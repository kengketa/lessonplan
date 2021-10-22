<?php

function snakeCaseToText($text)
{
    return implode(' ', explode('_', $text));
}

function getYears()
{
    $years = [
        ['id' => 2019, 'name' => '2019'],
        ['id' => 2020, 'name' => '2020'],
        ['id' => 2021, 'name' => '2021'],
        ['id' => 2022, 'name' => '2022'],
        ['id' => 2023, 'name' => '2023'],
        ['id' => 2024, 'name' => '2024'],
        ['id' => 2025, 'name' => '2025'],
        ['id' => 2026, 'name' => '2026'],
        ['id' => 2027, 'name' => '2027'],
        ['id' => 2028, 'name' => '2028'],
        ['id' => 2029, 'name' => '2029'],
        ['id' => 20230, 'name' => '2030'],
    ];
    return $years;
}

function getSemesters()
{
    $semester = [
        ['id' => 1, 'name' => 'semseter 1'],
        ['id' => 2, 'name' => 'semester 2'],
    ];
    return $semester;
}
