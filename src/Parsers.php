<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const FORMAT_JSON = 'json';
const FORMAT_YAML = 'yaml';

function parseData(string $content, string $format): array
{
    switch ($format) {
        case FORMAT_JSON:
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        case FORMAT_YAML:
            return (array)Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    throw new \InvalidArgumentException("Format '{$format}' is not supports");
}
