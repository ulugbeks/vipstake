<?php

namespace App\PageBuilder\Service;

use App\PageBuilder\Interface\LayoutInterface;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class ClassFinderService
{
    public static function getClassesImplementingInterface(string $directory, string $interface): array
    {
        $classes = [];
        $finder = new Finder();

        $finder->files()->in($directory)->name('*.php');

        foreach ($finder as $file) {
            $className = self::getClassFromFile($file->getRealPath());

            if(!$className){
                continue;
            }

            $classInterfaces = class_implements($className);

            if(in_array($interface, $classInterfaces)){
                $classes[] = $className;
            }

        }

        return $classes;
    }

    private static function getClassFromFile(string $filePath): ?string
    {
        $namespace = null;
        $tokens = token_get_all(file_get_contents($filePath));
        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                $namespace = self::getNamespace($tokens, $i);
            }

            if ($tokens[$i][0] === T_CLASS) {
                $className = self::getClassName($tokens, $i);
                if ($className) {
                    return $namespace ? $namespace . '\\' . $className : $className;
                }
            }
        }

        return null;
    }


    private static function getNamespace(array $tokens, int &$i): ?string
    {
        $namespace = '';
        for ($j = $i + 1, $jMax = count($tokens); $j < $jMax; $j++) {
            if ($tokens[$j] === ';') {
                break;
            }
            $namespace .= is_array($tokens[$j]) ? $tokens[$j][1] : $tokens[$j];
        }
        return trim($namespace);
    }

    private static function getClassName(array $tokens, int &$i): ?string
    {
        for ($j = $i + 1, $jMax = count($tokens); $j < $jMax; $j++) {
            if ($tokens[$j] === '{') {
                return $tokens[$i + 2][1];
            }
        }
        return null;
    }
}