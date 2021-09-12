<?php

namespace Differ\Formatters\Plain;

use const Differ\Differ\TYPE_ADD;
use const Differ\Differ\TYPE_EQUAL;
use const Differ\Differ\TYPE_NODE;
use const Differ\Differ\TYPE_REMOVE;
use const Differ\Differ\TYPE_UPDATE;

function formatPlain(array $diff, string $path = ''): string
{
    $formattedDiff = array_map(fn(array $diffRow) => formatRow($diffRow, $path), $diff);
    $result = array_filter($formattedDiff, fn($row) => strlen($row) > 0);

    return implode("\n", $result);
}

function formatRow(array $diffRow, string $path): string
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
            return formatPlain($diffRow['children'], prepareKey($path, $diffRow['key']));
        default:
            throw new \InvalidArgumentException("Type '{$diffRow['type']}' is not supports");
    }
}

function prepareKey(string $path, string $key): string
{
    return $path === '' ? $key : "{$path}.{$key}";
}

function prepareValue(mixed $value): string
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

    if (is_numeric($value)) {
        return (string)$value;
    }

    return "'{$value}'";
}
