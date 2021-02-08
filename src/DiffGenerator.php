<?php

namespace Differ\DiffGenerator;

use function Differ\Render\render;

const SYMBOL_REMOVED = '-';
const SYMBOL_ADDED = '+';
const SYMBOL_NOT_CHANGED = ' ';

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $fileData1 = loadData($pathToFile1);
    $fileData2 = loadData($pathToFile2);

    $diff = diff($fileData1, $fileData2);

    return render($diff);
}

function diff(array $data1, array $data2): array
{
    $diff = [];

    $keys = array_keys(array_merge($data1, $data2));
    uasort($keys, static function ($a, $b) { //todo убрать мутацию
        return $a <=> $b;
    });

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

function loadData(string $path): array
{
    $content = file_get_contents($path);
    return json_decode($content, true);
}