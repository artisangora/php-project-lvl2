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
     */
    public function testGenDiff(string $path1, string $path2): void
    {
        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $diff = genDiff($path1, $path2);

        $expects = file_get_contents(__DIR__ . '/data/result.txt');
        $this->assertNotEmpty($expects);
        $this->assertEquals($expects, $diff);
    }

    public function filesProvider(): iterable
    {
//        yield 'relative' => ['path1' => 'data/file1.json', 'path2' => 'data/file2.json']; //todo
        yield 'absolute' => ['path1' => __DIR__ . '/data/file1.json', 'path2' => __DIR__ . '/data/file2.json'];
    }
}
