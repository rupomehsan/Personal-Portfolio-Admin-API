<?php

namespace App\Helpers;

class ConvertHelper
{
    public static function convertToBanglaNumbers($number)
    {
        $englishToBanglaMap = [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
        ];

        return strtr($number, $englishToBanglaMap);
    }

    public static function convertToBanglaText($text)
    {
        $englishToBanglaTextMap = [
            'a' => 'অ', 'b' => 'ব', 'c' => 'চ', 'd' => 'দ', 'e' => 'এ',
            'f' => 'ফ', 'g' => 'গ', 'h' => 'হ', 'i' => 'ই', 'j' => 'জ',
            'k' => 'ক', 'l' => 'ল', 'm' => 'ম', 'n' => 'ন', 'o' => 'ও',
            'p' => 'প', 'q' => 'কিউ', 'r' => 'র', 's' => 'স', 't' => 'ত',
            'u' => 'উ', 'v' => 'ভ', 'w' => 'ডব্লিউ', 'x' => 'এক্স', 'y' => 'য়', 'z' => 'জেড',
            'A' => 'অ', 'B' => 'ব', 'C' => 'চ', 'D' => 'দ', 'E' => 'এ',
            'F' => 'ফ', 'G' => 'গ', 'H' => 'হ', 'I' => 'ই', 'J' => 'জ',
            'K' => 'ক', 'L' => 'ল', 'M' => 'ম', 'N' => 'ন', 'O' => 'ও',
            'P' => 'প', 'Q' => 'কিউ', 'R' => 'র', 'S' => 'স', 'T' => 'ত',
            'U' => 'উ', 'V' => 'ভ', 'W' => 'ডব্লিউ', 'X' => 'এক্স', 'Y' => 'য়', 'Z' => 'জেড',
        ];

        return strtr($text, $englishToBanglaTextMap);
    }
}