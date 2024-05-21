<?php

namespace App\Helpers;

final class StringHelper
{
    /**
     * Clear alphanumeric characters
     * 
     * @param null|string $string 
     * @return array|string|null 
     */
    public static function onlyNumber(?string $string): array|string|null
    {
        return $string ? \preg_replace("/\D/", "", $string) : null;
    }

    /**
     * Tests if there are only numbers in the string
     * 
     * @param string $string 
     * @return bool 
     */
    public static function isOnlyNumber(string $string): bool
    {
        $match = \preg_match("/\D/", $string);
        return !(bool)$match;
    }

    /**
     * Returns a string with only numbers and letters.
     * 
     * @param null|string $input 
     * @return array|string|null 
     */
    public static function onlyAlpha(?string $input, $execptChars = ''): array|string|null
    {
        return $input ? \preg_replace("/[^0-9a-zA-Z\{$execptChars\}]/", "", $input) : null;
    }

    /**
     * Masks the value passed with (#)
     * 
     * @param string $val 
     * @param string $mask 
     * @return string 
     */
    public static function mask($val, $mask): string
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        
        return $maskared;
    }

    /**
     * Removes any accents from the string
     * 
     * @param null|string $string 
     * @return array|string|null 
     */
    public static function removeAccents(?string $string): array|string|null
    {
        return $string 
            ? \preg_replace(
                [
                    "/(á|à|ã|â|ä)/", 
                    "/(Á|À|Ã|Â|Ä)/", 
                    "/(é|è|ê|ë)/", 
                    "/(É|È|Ê|Ë)/", 
                    "/(í|ì|î|ï)/", 
                    "/(Í|Ì|Î|Ï)/", 
                    "/(ó|ò|õ|ô|ö)/", 
                    "/(Ó|Ò|Õ|Ô|Ö)/", 
                    "/(ú|ù|û|ü)/", 
                    "/(Ú|Ù|Û|Ü)/", 
                    "/(ñ)/", 
                    "/(Ñ)/",
                    "/(ç)/", 
                    "/(Ç)/",
                ],
                \explode(" ","a A e E i I o O u U n N c C"), 
                $string)
            : null;
    }

    /**
     * Removes any symbols from the string
     * 
     * @param null|string $string 
     * @return array|string|null 
     */
    public static function removeSymbols(?string $string): array|string|null
    {
        return $string ? \preg_replace('/[^\p{L}\p{N}\s]/u', '', $string) : null;
    }

    /**
     * Remove whitespace from string
     * 
     * @param null|string $string 
     * @return array|string|null 
     */
    public static function removeWhiteSpace(?string $string): array|string|null
    {
        return $string ? \str_replace(" ", "", $string) : null;
    }

    /**
     * Tests if the text is equal
     * 
     * @param null|string $right 
     * @param null|string $left 
     * @return bool 
     */
    public static function sameText(?string $right, ?string $left): bool
    {
        return ((!$right && !$left) || ($right && $left)) 
            && self::toLowerAndRemoveAccentsSymbolsWhiteSpace($right) === self::toLowerAndRemoveAccentsSymbolsWhiteSpace($left);
    }

    /**
     * Convert to lower case and remove accents, symbols and whitespace from string
     * 
     * @param null|string $string 
     * @return array|string|null
     */
    public static function toLowerAndRemoveAccentsSymbolsWhiteSpace(?string $string): array|string|null
    {
        return $string
            ? self::toLower(
                self::removeAccents(
                    self::removeSymbols(
                        self::removeWhiteSpace($string)
                    )
                )
            )
            : null;
    }

    /**
     * Convert to lower case and remove symbols and whitespace from string
     * 
     * @param null|string $string 
     * @return array|string|null
     */
    public static function toLowerAndRemoveSymbolsWhiteSpace(?string $string): array|string|null
    {
        return $string
            ? self::toLower(
                self::removeSymbols(
                    self::removeWhiteSpace($string)
                )
            )
            : null;
    }

    /**
     * Convert to lower case and remove accents and whitespace from string
     * 
     * @param null|string $string 
     * @return array|string|null
     */
    public static function toLowerAndRemoveAccentsWhiteSpace(?string $string): array|string|null
    {
        return $string
            ? self::toLower(
                self::removeAccents(
                    self::removeWhiteSpace($string)
                )
            )
            : null;
    }

    /**
     * Convert to lower case and remove whitespace from string
     * 
     * @param null|string $string 
     * @return array|string|null
     */
    public static function toLowerAndRemoveWhiteSpace(?string $string): array|string|null
    {
        return $string
            ? self::toLower(
                self::removeWhiteSpace($string)
            )
            : null;
    }

    /**
     * Fill with characters to the left
     * 
     * @param string $string 
     * @param int $length 
     * @param string $pad_string 
     * 
     * @return string 
     */
    public static function padLeft($string, $length, $pad_string = " "): string
    {
        return \str_pad($string, $length, $pad_string, \STR_PAD_LEFT);
    }

    /**
     * Fill with characters to the right
     * 
     * @param string $string 
     * @param int $length 
     * @param string $pad_string 
     * 
     * @return string 
     */
    public static function padRight($string, $length, $pad_string = " "): string
    {
        return \str_pad($string, $length, $pad_string, \STR_PAD_RIGHT);
    }

    /**
     * Converts a string to lower case
     * 
     * @param null|string $string 
     * @return array|string|null 
     */
    public static function toLower(?string $string): array|string|null
    {
        return $string ? \mb_strtolower($string) : null;
    }

    /**
     * Converts a string to upper case
     * 
     * @param null|string $string 
     * @return array|string|null 
     */
    public static function toUpper(?string $string): array|string|null
    {
        return $string ? \mb_strtoupper($string) : null;
    }
}