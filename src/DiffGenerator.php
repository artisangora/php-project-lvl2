<?php

namespace Differ\DiffGenerator;

use function Differ\Formatters\format;
use function Differ\Parsers\parseFile;
use function Funct\Collection\sortBy;

const TYPE_ADD = 'add';
const TYPE_REMOVE = 'remove';
const TYPE_EQUAL = 'equal';

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $fileData1 = parseFile($pathToFile1);
    $fileData2 = parseFile($pathToFile2);

    $diff = diff($fileData1, $fileData2);

    return format($diff, $format);
}

function diff(array $data1, array $data2): array
{
    $diff = [];

    $keys = array_keys(array_merge($data1, $data2));
    $keys = sortBy($keys, fn ($value) => $value);

    foreach ($keys as $key) { //todo вынести в ф-ю
        if (!array_key_exists($key, $data1)) {
            $diff[] = createDiffRow(TYPE_ADD, $key, handleValue($data2[$key]));
        } elseif (!array_key_exists($key, $data2)) {
            $diff[] = createDiffRow(TYPE_REMOVE, $key, handleValue($data1[$key]));
        } elseif ($data1[$key] !== $data2[$key]) {
            $diff[] = createDiffRow(TYPE_REMOVE, $key, handleValue($data1[$key]));
            $diff[] = createDiffRow(TYPE_ADD, $key, handleValue($data2[$key]));
        } elseif ($data1[$key] === $data2[$key]) {
            $diff[] = createDiffRow(TYPE_EQUAL, $key, handleValue($data1[$key]));
        }
    }

    return $diff;
}

function handleValue($value)
{
    if (is_object($value)) {
        $value = (array)$value;
        return array_map(function ($key, $value) {
            return createDiffRow(TYPE_EQUAL, $key, $value);
        }, array_keys($value), $value);
    }
    return $value;
}

function createDiffRow(string $type, string $key, $value): array
{
    return ['type' => $type, 'key' => $key, 'value' => $value];
}

