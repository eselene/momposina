<?php

namespace App\Utils;

class DateUtils
{
    public static function convertirDateEnLettres($date)
    {
        $mois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Qvril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        $timestamp = strtotime($date);
        $annee = date('Y', $timestamp);
        $moisNum = date('n', $timestamp);
        $jour = date('j', $timestamp);

        return $jour . ' ' . $mois[$moisNum] . ' ' . $annee;
    }
}
