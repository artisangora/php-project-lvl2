<?php

namespace Differ\Render;

use function Funct\Strings\strip;

const TEMPLATE = '  %s %s: %s';

function render(array $diff): string
{
    $result = array_map(static fn(array $row): string => renderDiffRow($row), $diff);

    return "{\n" . implode("\n", $result) . "\n}";
}
function renderDiffRow(array $diffRow): string
{
    return sprintf(TEMPLATE, $diffRow['symbol'], $diffRow['key'], prepareValue($diffRow['value']));
}
function prepareValue($value): string
{
    return strip(json_encode($value), '"');
}
