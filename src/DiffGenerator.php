<?php

namespace Differ\DiffGenerator;

use function Differ\Formatters\format;
use function Differ\Parsers\parseFile;
use function Funct\Collection\sortBy;

const TYPE_ADD = 'add';
const TYPE_REMOVE = 'remove';
const TYPE_UPDATE = 'update';
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
        $diff[] = createDiffRow($key, $data1, $data2);
    }

    return $diff;
}

function prepareValue($value)
{
    return $value;
}

function createDiffRow(string $key, $data1, $data2): array
{
    if (!array_key_exists($key, $data1)) {
        return [
            'type' => TYPE_ADD,
            'key' => $key,
            'valueBefore' => prepareValue(null),
            'valueAfter' => prepareValue($data2[$key]),
        ];
    }

    if (!array_key_exists($key, $data2)) {
        return [
            'type' => TYPE_REMOVE,
            'key' => $key,
            'valueBefore' => $data1[$key],
            'valueAfter' => null,
        ];
    }

    if (is_array($data1[$key]) && is_array($data2[$key])) {
        return [
            'type' => TYPE_EQUAL,
            'key' => $key,
            'valueBefore' => prepareValue($data1[$key]),
            'valueAfter' => prepareValue($data2[$key]),
        ];
    }

    if ($data1[$key] !== $data2[$key]) {
        return [
            'type' => TYPE_UPDATE,
            'key' => $key,
            'valueBefore' => prepareValue($data1[$key]),
            'valueAfter' => prepareValue($data2[$key]),
        ];
    }

    if ($data1[$key] === $data2[$key]) {
        return [
            'type' => TYPE_EQUAL,
            'key' => $key,
            'valueBefore' => prepareValue($data1[$key]),
            'valueAfter' => prepareValue($data1[$key]),
        ];
    }

    throw new \LogicException('Не удалось создать diff');
}

