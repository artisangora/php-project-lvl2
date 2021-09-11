<?php

namespace Differ\Formatters;

use function Funct\Strings\strip;

use const Differ\DiffGenerator\TYPE_ADD;
use const Differ\DiffGenerator\TYPE_EQUAL;
use const Differ\DiffGenerator\TYPE_NODE;
use const Differ\DiffGenerator\TYPE_REMOVE;
use const Differ\DiffGenerator\TYPE_UPDATE;

const FORMAT_JSON = 'json';
const FORMAT_STYLISH = 'stylish';

function format(array $diff, string $format): string
{
    switch ($format) {
        case FORMAT_JSON:
            return formatAsJson($diff);
        case FORMAT_STYLISH:
            return formatAsStylish($diff);
        default:
            throw new \InvalidArgumentException("Format '$format' is not supports");
    }
}

function formatAsJson(array $diff): string //todo
{
    $result = array_map(static fn(array $row): string => formatDiffRow($row), $diff);

    return "{\n" . implode("\n", $result) . "\n}";
}

function formatAsStylish(array $diff, int $depth = 1): string
{
    $result = array_map(
        function (array $diffRow) use ($depth) {
            switch ($diffRow['type']) {
                case TYPE_ADD:
                    return sprintf(
                        "%s+ %s: %s",
                        getIndent($depth),
                        $diffRow['key'],
                        prepareValue($diffRow['valueAfter'], $depth)
                    );
                case TYPE_REMOVE:
                    return sprintf(
                        "%s- %s: %s",
                        getIndent($depth),
                        $diffRow['key'],
                        prepareValue($diffRow['valueBefore'], $depth)
                    );
                case TYPE_UPDATE:
                    return sprintf(
                        "%s- %s: %s\n%s+ %s: %s",
                        getIndent($depth),
                        $diffRow['key'],
                        prepareValue($diffRow['valueBefore'], $depth),
                        getIndent($depth),
                        $diffRow['key'],
                        prepareValue($diffRow['valueAfter'], $depth)
                    );
                case TYPE_EQUAL:
                    return sprintf(
                        "%s  %s: %s",
                        getIndent($depth),
                        $diffRow['key'],
                        prepareValue($diffRow['valueAfter'], $depth)
                    );
                case TYPE_NODE:
                    return sprintf(
                        "%s  %s: %s",
                        getIndent($depth),
                        $diffRow['key'],
                        formatAsStylish($diffRow['children'], $depth + 1)
                    );
                default:
                    throw new \InvalidArgumentException("Type '{$diffRow['type']}' is not supports");
            }
        },
        $diff
    );

    return "{\n" . implode("\n", $result) . "\n" . getIndent($depth, 4) . "}";
}

function prepareValue($value, int $depth): string
{
    if (is_object($value)) {
        $keys = array_keys(get_object_vars($value));

        $resultRows = array_map(
            function ($key) use ($value, $depth) {
                $indent = getIndent($depth + 1);
                return "{$indent}  {$key}: " . prepareValue($value->$key, $depth + 1);
            },
            $keys
        );

        $result = implode("\n", $resultRows);
        $indent = getIndent($depth, 0);
        return "{\n{$result}\n{$indent}}";
    }


    return strip(json_encode($value), '"');
}

function getIndent(int $depth, $symbolsCount = 2): string
{
    return str_repeat(' ', $depth * 4 - $symbolsCount);
}
