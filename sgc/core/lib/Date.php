<?php

class Date
{
    public static function getMonthsArray($locale)
    {
        switch($locale)
        {
            case 'pt': default:
                return ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
            break;

            case 'en':
                return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            break;
        }
    }
}
