<?php

namespace App\Helpers;

class Helper
{
    public static function arrayTree(array $elements, $parentId = null)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = self::arrayTree($elements, $element['index']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public static function getRandomFloat($min, $max)
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 1);
    }

    public static function splitPie($number, $numOfParts) {
        $result = [];

        if ($numOfParts <= 0) {
            return false; // Invalid number of parts
        }

        // Generate random numbers for the specified number of parts
        for ($i = 0; $i < $numOfParts - 1; $i++) {
            $maxRandomPart = min($number - array_sum($result) - ($numOfParts - $i - 1), $number / ($numOfParts - $i));
            $randomPart = rand(1, $maxRandomPart);
            $result[] = $randomPart;
        }

        // The last part is calculated to make the sum equal to the original number
        $result[] = $number - array_sum($result);
        shuffle($result);

        return $result;
    }

    public static function calculatePercentage($partial, $total) {
        if ($total == 0) {
            return false; // Avoid division by zero
        }

        $percentage = ($partial / $total) * 100;
        return round($percentage, 1);
    }

    public static function timeToRead($text, $wordsPerMinute = 200) {
        // Count the number of words in the input text
        $wordCount = str_word_count(strip_tags($text));

        // Calculate the estimated reading time in minutes
        $readingTime = ceil($wordCount / $wordsPerMinute);

        return $readingTime;
    }
}