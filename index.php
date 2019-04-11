<?php

require 'helpers.php';

$doc = new DOMDocument();
$doc->loadXML(file_get_contents('php://input'));

if (!$doc->schemaValidate('schema.xsd')) {
    http_response_code(400);
    return;
}

$endPoint = [
    'city' => '',
    'time' => 0
];

$returnXml = '<Response>';
$breakPoints = '<BreakPoints>';

$segments = $doc->getElementsByTagName('AirSegment');

for ($i = 1; $i < $segments->length; ++$i) {

    $arrival = getArrivalTimeFromNode($segments->item($i - 1));
    $departure = getDepartureTimeFromNode($segments->item($i));

    $diff = $departure - $arrival;

    $offCity = $segments->item($i - 1)->getElementsByTagName('Off')->item(0)
        ->attributes->getNamedItem('City')->nodeValue;

    $boardCity = $segments->item($i)->getElementsByTagName('Board')->item(0)
        ->attributes->getNamedItem('City')->nodeValue;


    if ($diff > $endPoint['time']) {

        $endPoint['time'] = $diff;
        $endPoint['city'] = $offCity;
    }

    if ($offCity != $boardCity) {

        $breakPoints .= '<BreakPoint City="'. $offCity .'" />';
    }
}

$breakPoints .= '</BreakPoints>';
$returnXml .= '<EndPoint City="'. $endPoint['city'] .'" />';
$returnXml .= $breakPoints;
$returnXml .= '</Response>';

header('Content-type: text/xml');
echo $returnXml;

return;

// Так, як контексту не було, написала в процедурному стилі. В контексті фреймворка або модуля виглядало б краще, але для однозадачного API не бачу сенсу піднімати фреймворк.
// Можна було б скористатись DOM для створення xml, але на практиці такий код виявився громіздким і нечитабельним. Конкантенація виглядає значно легше і зрозуміліше, хоча і дещо костильно.
