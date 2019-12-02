<?php
/**
 * Load the sample controller.
 */
return [
    "routes" => [
        [
            "info" => "Väder.",
            "mount" => "weather",
            "handler" => "\Anax\Controller\WeatherController",
        ],
    ]
];
