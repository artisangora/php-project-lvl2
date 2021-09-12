<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffGeneratorTest extends TestCase
{
    /**
     * @dataProvider filesProvider
     * @param string $path1
     * @param string $path2
     * @param string $resultPath
     * @param string $format
     */
    public function testGenDiff(string $path1, string $path2, string $resultPath, string $format): void
    {
        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $diff = genDiff($path1, $path2, $format);

        $expects = file_get_contents($resultPath);
        $this->assertNotEmpty($expects);
        $this->assertEquals($expects, $diff);
    }

    public function filesProvider(): iterable
    {
        yield 'stylish' => [
            'path1'  => __DIR__ . '/fixtures/file1.yml',
            'path2'  => __DIR__ . '/fixtures/file2.yml',
            'resultPath'  => __DIR__ . '/fixtures/resultStylish.txt',
            'format' => 'stylish'
        ];
        yield 'plain' => [
            'path1'  => __DIR__ . '/fixtures/file1.yml',
            'path2'  => __DIR__ . '/fixtures/file2.yml',
            'resultPath'  => __DIR__ . '/fixtures/resultPlain.txt',
            'format' => 'plain'
        ];
        yield 'json from yml' => [
            'path1'  => __DIR__ . '/fixtures/file1.yml',
            'path2'  => __DIR__ . '/fixtures/file2.yml',
            'resultPath'  => __DIR__ . '/fixtures/resultJson.txt',
            'format' => 'json'
        ];
        yield 'json from yaml' => [
            'path1'  => __DIR__ . '/fixtures/file1.yaml',
            'path2'  => __DIR__ . '/fixtures/file2.yaml',
            'resultPath'  => __DIR__ . '/fixtures/resultJson.txt',
            'format' => 'json'
        ];
    }
}
