<?php

namespace Differ\Differ;

use function Differ\Formatters\format;
use function Differ\Parsers\parseFile;
use function Functional\sort as fSort;

const TYPE_ADD = 'add';
const TYPE_REMOVE = 'remove';
const TYPE_UPDATE = 'update';
const TYPE_EQUAL = 'equal';
const TYPE_NODE = 'node';

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $fileData1 = parseFile($pathToFile1);
    $fileData2 = parseFile($pathToFile2);

    $diff = diff($fileData1, $fileData2);

    return format($diff, $format);
}

function diff(object $data1, object $data2): array
{
    $uniqueKeys = array_unique(
        array_merge(
            array_keys(get_object_vars($data1)),
            array_keys(get_object_vars($data2))
        )
    );

    $keys = array_values(
        fSort($uniqueKeys, fn ($a, $b) => strcmp($a, $b))
    );

    return array_map(function ($key) use ($data1, $data2): array {
        if (!property_exists($data1, $key)) {
            return [
                'type' => TYPE_ADD,
                'key' => $key,
                'valueBefore' => null,
                'valueAfter' => $data2->$key,
                'children' => null
            ];
        }

        if (!property_exists($data2, $key)) {
            return [
                'type' => TYPE_REMOVE,
                'key' => $key,
                'valueBefore' => $data1->$key,
                'valueAfter' => null,
                'children' => null
            ];
        }

        if (is_object($data1->$key) && is_object($data2->$key)) {
            return [
                'type' => TYPE_NODE,
                'key' => $key,
                'valueBefore' => null,
                'valueAfter' => null,
                'children' => diff($data1->$key, $data2->$key)
            ];
        }

        if ($data1->$key === $data2->$key) {
            return [
                'type' => TYPE_EQUAL,
                'key' => $key,
                'valueBefore' => $data1->$key,
                'valueAfter' => $data1->$key,
                'children' => null
            ];
        }

        return [
            'type' => TYPE_UPDATE,
            'key' => $key,
            'valueBefore' => $data1->$key,
            'valueAfter' => $data2->$key,
            'children' => null
        ];
    }, $keys);
}
