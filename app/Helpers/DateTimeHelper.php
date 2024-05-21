<?php

namespace App\Helpers;

final class DateTimeHelper
{
    /**
     * Converts a date from DB format (Y-m-d) to Brazil format (d/m/Y)
     * 
     * @param \Illuminate\Support\Carbon|string $date 
     * @param string $output Mascara de saida 
     * @return void|string 
     * 
     * @throws \Carbon\Exceptions\InvalidFormatException 
     * @throws \Exception 
     */
    public static function dbToDate($date, $output = "d/m/Y")
    {
        # Se estiver vazio retorna NULL
        if (\is_null($date) || empty($date)) {
            return null;
        }

        # Se for uma instancia de \Illuminate\Support\Carbon somente executa a rotina de formatação 
        if ($date instanceof \Illuminate\Support\Carbon) {
            return $date->format($output);
        }
        
        $d = \date_parse_from_format('Y-m-d', $date);
        return \Carbon\Carbon::create($d['year'], $d['month'], $d['day'], 0, 0, 0)->format($output);
    }

    /**
     * Converts a date from Brazil format (d/m/Y) to DB format (Y-m-d)
     * 
     * @param string $date 
     * @return null|string 
     */
    public static function dateToDb($date)
    {
        if (empty($date)) {
            return null;
        }

        $d = \date_parse_from_format('d/m/Y', $date);
        return \Carbon\Carbon::create($d['year'], $d['month'], $d['day'])->format('Y-m-d');
    }

    /**
     * Converts a date from DB format (Y-m-d H:i:s) to Brazil format (d/m/Y H:i:s)
     * 
     * @param \Illuminate\Support\Carbon|string $dateTime Data de entrada **no formato Y-m-d H:i:s** 
     * @param string $output Mascara de saida 
     * @return void|string 
     * 
     * @throws \Carbon\Exceptions\InvalidFormatException 
     * @throws \Exception 
     */
    public static function dbToDateTime($dateTime, $output = "d/m/Y H:i")
    {
        if (empty($dateTime)) {
            return null;
        }

        # Se for uma instancia de \Illuminate\Support\Carbon somente executa a rotina de formatação 
        if ($dateTime instanceof \Illuminate\Support\Carbon) {
            return $dateTime->format($output);
        }

        $d = \date_parse_from_format('Y-m-d H:i:s', $dateTime);
        $h = $d['hour'] ? $d['hour'] : 0;
        $m = $d['minute'] ? $d['minute'] : 0;
        $s = $d['second'] ? $d['second'] : 0;

        return \Carbon\Carbon::create($d['year'], $d['month'], $d['day'], $h, $m, $s)->format($output);
    }

    /**
     * Converts a date/time from Brazil format (d/m/Y H:i) to DB format (Y-m-d H:i)
     * 
     * @param string $date 
     * @return null|string 
     */
    public static function dateTimeToDb($dateTime, string $format = 'Y-m-d H:i')
    {
        if (empty($dateTime)) {
            return null;
        }

        $d = \date_parse_from_format('d/m/Y H:i:s', $dateTime);
        $h = $d['hour'] ? $d['hour'] : 0;
        $m = $d['minute'] ? $d['minute'] : 0;
        $s = $d['second'] ? $d['second'] : 0;

        return \Carbon\Carbon::create($d['year'], $d['month'], $d['day'], $h, $m, $s)->format($format);
    }

    /**
     * Returns the formatted date and time by default combo dateTime
     * 
     * @return string
     */
    public static function dateTimeForHumans($data) 
    {
        return \Carbon\Carbon::parse($data)->format('d/m/Y') . ' às ' . \Carbon\Carbon::parse($data)->format('H:i') .'h';
    }

    /**
     * Checks if a date is valid
     * 
     * @param string $date 
     * @param string $format 
     * @return bool 
     */
    public static function isValid($date, $format = 'Y-m-d H:i')
    {
        $d = \date_parse_from_format($format, $date);
        return \checkdate($d['month'], $d['day'], $d['year']);
    }
}
