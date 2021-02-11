<?php

namespace Differ\DiffGenerator;

use function Differ\Parsers\parseData;
use function Differ\Render\render;
use function Funct\Collection\sortBy;

const SYMBOL_REMOVED = '-';
const SYMBOL_ADDED = '+';
const SYMBOL_NOT_CHANGED = ' ';

function genDiff(string $pathToFile1, string $pathToFile2, string $format): string
{
    $fileData1 = parseData(loadFile($pathToFile1), $format);
    $fileData2 = parseData(loadFile($pathToFile2), $format);

    $diff = diff($fileData1, $fileData2);

    return render($diff);
}

function diff(array $data1, array $data2): array
{
    $diff = [];

    $keys = array_keys(array_merge($data1, $data2));
    $keys = sortBy($keys, fn ($value) => $value);

    foreach ($keys as $key) {
        if (!array_key_exists($key, $data1)) {
            $diff[] = createDiffRow(SYMBOL_ADDED, $key, $data2[$key]);
        } elseif (!array_key_exists($key, $data2)) {
            $diff[] = createDiffRow(SYMBOL_REMOVED, $key, $data1[$key]);
        } elseif ($data1[$key] !== $data2[$key]) {
            $diff[] = createDiffRow(SYMBOL_REMOVED, $key, $data1[$key]);
            $diff[] = createDiffRow(SYMBOL_ADDED, $key, $data2[$key]);
        } elseif ($data1[$key] === $data2[$key]) {
            $diff[] = createDiffRow(SYMBOL_NOT_CHANGED, $key, $data1[$key]);
        }
    }

    return $diff;
}

function createDiffRow(string $symbol, string $key, $value): array
{
    return ['symbol' => $symbol, 'key' => $key, 'value' => $value];
}

function loadFile(string $filePath): string
{
    return file_get_contents($filePath);
}
