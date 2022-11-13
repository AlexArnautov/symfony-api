<?php

namespace App\Service;

use App\Entity\Sensor;
use JetBrains\PhpStorm\ArrayShape;

class SensorApiConverter
{
    #[ArrayShape(['type' => "string", 'geometry' => "array", 'properties' => "string[]"])]
    public function convert(Sensor $sensor): array
    {
        $description = '<strong>Traffic Light ID ' . $sensor->getId().'</strong></br>';
        if (!empty($sensor->getStats())) {
            $description .= 'Last 3 days: </br>';
            $description .= '<strong style="color: red">Red: </strong>'.$sensor->getStats()['last3days']['red'].'</br>';
            $description .= '<strong style="color: darkgoldenrod">Yellow: </strong>'.$sensor->getStats()['last3days']['yellow'].'</br>';
            $description .= '<strong style="color: green">Green: </strong>'.$sensor->getStats()['last3days']['green'].'</br>';
        }
        return [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [$sensor->getLongitude(), $sensor->getLatitude()]
            ],
            'properties' => [
                'title' => 'Light ID:' . $sensor->getId(),
                'description' => $description,
            ]
        ];

    }

}