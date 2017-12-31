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
final class Fixer
{
    public static function awesomiteEach(&$array)
    {
        if (false !== $arg = \current($array)) {
            $i = \key($array);
            \next($array);

            return array(
                1 => $arg,
                'value' => $arg,
                0 => $i,
                'key' => $i,
            );
        }

        return false;
    }

    public static function fix()
    {
        $self = new static();
        $self->fixEachFn();
        echo "[phpunit-4.8-fixer] Fixes applied\n";
    }

    private function fixEachFn()
    {
        $reflection = new \ReflectionClass('PHPUnit_Util_Getopt');
        $this->fixEachFnInFile($reflection->getFileName());
    }

    private function fixEachFnInFile($fileName)
    {
        $contents = \file_get_contents($fileName);
        $result = '';
        foreach (\token_get_all($contents) as $token) {
            if (\is_string($token)) {
                $result .= $token;
                continue;
            }
            list($tokenId, $source) = $token;
            if (T_STRING === $tokenId && 'each' === $source) {
                $source = '\\' . __CLASS__ . '::awesomiteEach';
            }
            $result .= $source;
        }
        \file_put_contents($fileName, $result);
    }
}
