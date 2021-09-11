<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Json\formatJson;
use function Differ\Formatters\Stylish\formatStylish;

const FORMAT_JSON = 'json';
const FORMAT_STYLISH = 'stylish';
const FORMAT_PLAIN = 'plain';

function format(array $diff, string $format): string
{
    switch ($format) {
        case FORMAT_JSON:
            return formatJson($diff);
        case FORMAT_PLAIN:
            return formatPlain($diff);
        case FORMAT_STYLISH:
            return formatStylish($diff);
        default:
            throw new \InvalidArgumentException("Format '$format' is not supports");
    }
}
