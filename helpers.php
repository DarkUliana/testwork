<?php

if (!function_exists('getDateTimeFromNode')) {

    function getDateTimeFromNode($node)
    {
        $date = $node->attributes->getNamedItem('Date')->nodeValue;
        $time = $node->attributes->getNamedItem('Time')->nodeValue;
        $datetime = strtotime($date . ' ' . $time);

        return $datetime;
    }
}
if (!function_exists('getArrivalTimeFromNode')) {

    function getArrivalTimeFromNode($node)
    {
        return getDateTimeFromNode($node->getElementsByTagName('Arrival')->item(0));
    }
}
if (!function_exists('getDepartureTimeFromNode')) {

    function getDepartureTimeFromNode($node)
    {
        return getDateTimeFromNode($node->getElementsByTagName('Departure')->item(0));
    }
}