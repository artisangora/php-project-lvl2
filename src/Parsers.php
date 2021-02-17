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
function parseData(string $content, string $extension): array
{
    switch ($extension) {
        case EXTENSION_JSON:
            return (array)json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        case EXTENSION_YAML:
            return (array)Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    throw new \InvalidArgumentException("File extension '{$extension}' is not supports");
}

function parseFile(string $filePath)
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileContent = file_get_contents($filePath);
    return parseData($fileContent, $fileExtension);
}
