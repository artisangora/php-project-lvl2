<?php

namespace Differ\Cli;

use Docopt;

use function Differ\Differ\genDiff;

const DOC =  <<<DOC
Generate diff

Usage:
    gendiff (-h|--help)
    gendiff (-v|--version)
    gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
    -h --help                     Show this screen
    -v --version                  Show version
    --format <fmt>                Report format [default: stylish]
DOC;


function run(): void
{
    $arguments = Docopt::handle(DOC, ['version' => 'Generate diff 1.0.0']);

    $pathToFile1 = $arguments['<firstFile>'];
    $pathToFile2 = $arguments['<secondFile>'];
    $format = $arguments['--format'];

    echo genDiff($pathToFile1, $pathToFile2, $format);
}
