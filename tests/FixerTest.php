<?php

/*
 * This file is part of the awesomite/phpunit-4.8-fixer package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\Phpunit48Fixer;

/**
 * @internal
 */
class FixerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerFixEachInFile
     *
     * @param $fileName
     * @param $expected
     */
    public function testFixEachInFile($fileName, $expected)
    {
        $fixer = new Fixer();
        $reflection = new \ReflectionClass($fixer);
        $reflectionMethod = $reflection->getMethod('fixEachFnInFile');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke($fixer, $fileName);

        $this->assertSame($expected, \file_get_contents($fileName));
    }

    public function providerFixEachInFile()
    {
        $file = self::getTmpFileName();
        $input = <<<'INPUT'
<?php

$data = range(1, 100);
while (list($i, $arg) = each($data)) {
    printf("data[%s] = %s\n", $i, $arg);
}

INPUT;

        $output = <<<'OUTPUT'
<?php

$data = range(1, 100);
while (list($i, $arg) = \Awesomite\Phpunit48Fixer\Fixer::awesomiteEach($data)) {
    printf("data[%s] = %s\n", $i, $arg);
}

OUTPUT;

        \file_put_contents($file, $input);

        return array(array($file, $output));
    }

    /**
     * @dataProvider providerAwesomiteEach
     *
     * @param array $array
     */
    public function testAwesomiteEach(array $array)
    {
        $copy = array();
        while (list($key, $value) = Fixer::awesomiteEach($array)) {
            $copy[$key] = $value;
        }

        $this->assertSame($array, $copy);
    }

    public function providerAwesomiteEach()
    {
        return array(
            array(\range(1, 100)),
            array(array(null, new \stdClass(), 'key1' => INF, 'key2' => M_PI)),
        );
    }

    protected function setUp()
    {
        $this->expectOutputString('');
    }

    public static function tearDownAfterClass()
    {
        $file = self::getTmpFileName();
        parent::tearDownAfterClass();
        if (\file_exists($file)) {
            \unlink($file);
        }
    }

    private static function getTmpFileName()
    {
        return __FILE__ . '.tmp';
    }
}
