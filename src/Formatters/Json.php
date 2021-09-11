<?php

namespace Differ\Formatters\Json;

function formatJson(array $diff): string //todo
{
    $result = array_map(static fn(array $row): string => formatDiffRow($row), $diff);

    return "{\n" . implode("\n", $result) . "\n}";
}
