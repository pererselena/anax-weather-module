<?php
/**
 * Load the sample controller.
 */
return [
    "routes" => [
        [
            "info" => "Väder.",
            "mount" => "json_weather",
            "handler" => "\Anax\Controller\WeatherJsonController",
        ],
    ]
];
