<?php

namespace Diff\Cli;

use Docopt;

const DOC =  <<<DOC
Generate diff

Usage:
    gendiff (-h|--help)
    gendiff (-v|--version)

Options:
    -h --help                     Show this screen
    -v --version                  Show version
DOC;


function run(): void {
    Docopt::handle(DOC);
}