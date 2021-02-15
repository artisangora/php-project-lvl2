<?php

namespace Differ\DiffGenerator;

use function Differ\Parsers\parseFile;
use function Differ\Render\render;
use function Funct\Collection\sortBy;

const TYPE_ADD = 'add';
const TYPE_REMOVE = 'remove';
const TYPE_EQUAL = 'equal';

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $fileData1 = parseFile($pathToFile1);
    $fileData2 = parseFile($pathToFile2);

    $diff = diff($fileData1, $fileData2);

    return render($diff, $format);
}

function diff($data1, $data2): array
{
    $diff = [];

    $keys = array_keys(array_merge($data1, $data2));
    $keys = sortBy($keys, fn ($value) => $value);

    foreach ($keys as $key) { //todo вынести в ф-ю
        if (!array_key_exists($key, $data1)) {
            $diff[] = createDiffRow(TYPE_ADD, $key, $data2[$key]);
        } elseif (!array_key_exists($key, $data2)) {
            $diff[] = createDiffRow(TYPE_REMOVE, $key, $data1[$key]);
        } elseif ($data1[$key] !== $data2[$key]) {
            $diff[] = createDiffRow(TYPE_REMOVE, $key, $data1[$key]);
            $diff[] = createDiffRow(TYPE_ADD, $key, $data2[$key]);
        } elseif ($data1[$key] === $data2[$key]) {
            $diff[] = createDiffRow(TYPE_EQUAL, $key, $data1[$key]);
        }
    }

    return $diff;
}

function createDiffRow(string $type, string $key, $value): array
{
    return ['type' => $type, 'key' => $key, 'value' => $value];
}

