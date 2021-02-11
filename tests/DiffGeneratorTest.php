<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\DiffGenerator\genDiff;

class DiffGeneratorTest extends TestCase
{
    /**
     * @dataProvider filesProvider
     * @param string $path1
     * @param string $path2
     * @param string $format
     */
    public function testGenDiff(string $path1, string $path2, string $format): void
    {
        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $diff = genDiff($path1, $path2, $format);

        $expects = file_get_contents(__DIR__ . '/fixtures/result.txt');
        $this->assertNotEmpty($expects);
        $this->assertEquals($expects, $diff);
    }

    public function filesProvider(): iterable
    {
        yield 'json' => [
            'path1'  => __DIR__ . '/fixtures/file1.json',
            'path2'  => __DIR__ . '/fixtures/file2.json',
            'format' => 'json'
        ];
        yield 'yaml' => [
            'path1'  => __DIR__ . '/fixtures/file1.yml',
            'path2'  => __DIR__ . '/fixtures/file2.yml',
            'format' => 'yaml'
        ];
    }
}
