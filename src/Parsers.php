<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const EXTENSION_JSON = 'json';
const EXTENSION_YAML = 'yml';

/**
 * @param string $content
 * @param string $extension
 * @return mixed
 * @throws \JsonException
 */
function parseData(string $content, string $extension): object
{
    switch ($extension) {
        case EXTENSION_JSON:
            return json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        case EXTENSION_YAML:
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    throw new \InvalidArgumentException("File extension '{$extension}' is not supports");
}

function parseFile(string $filePath): object
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileContent = file_get_contents($filePath);
    return parseData($fileContent, $fileExtension);
}
