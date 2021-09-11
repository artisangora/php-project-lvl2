<?php

namespace Differ\Formatters\Plain;

use function Funct\Strings\len;

use const Differ\DiffGenerator\TYPE_ADD;
use const Differ\DiffGenerator\TYPE_EQUAL;
use const Differ\DiffGenerator\TYPE_NODE;
use const Differ\DiffGenerator\TYPE_REMOVE;
use const Differ\DiffGenerator\TYPE_UPDATE;

function formatPlain(array $diff, array $path = []): string
{
    $result = array_map(fn(array $diffRow) => formatRow($diffRow, $path), $diff);
    $result = array_filter($result, fn($row) => len($row) > 0);

    return implode("\n", $result);
}

function formatRow(array $diffRow, array $path): string
{
    switch ($diffRow['type']) {
        case TYPE_ADD:
            return sprintf(
                "Property '%s' was added with value: %s",
                prepareKey($path, $diffRow['key']),
                prepareValue($diffRow['valueAfter'])
            );
        case TYPE_REMOVE:
            return sprintf(
                "Property '%s' was removed",
                prepareKey($path, $diffRow['key']),
            );
        case TYPE_UPDATE:
            return sprintf(
                "Property '%s' was updated. From %s to %s",
                prepareKey($path, $diffRow['key']),
                prepareValue($diffRow['valueBefore']),
                prepareValue($diffRow['valueAfter'])
            );
        case TYPE_EQUAL:
            return '';
        case TYPE_NODE:
            $path[] = $diffRow['key'];
            return formatPlain($diffRow['children'], $path);
        default:
            throw new \InvalidArgumentException("Type '{$diffRow['type']}' is not supports");
    }
}

function prepareKey(array $path, string $key): string
{
    $path[] = $key;
    return implode('.', $path);
}


function prepareValue($value): string
{
    if (is_object($value)) {
        return '[complex value]';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return "'{$value}'";
}
