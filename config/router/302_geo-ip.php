<?php
/**
 * Routes for ip verify.
 */
return [
    "routes" => [
        [
            "info" => "Ip geolokalisering.",
            "mount" => "geo_ip",
            "handler" => "\Anax\Controller\IpGeoController",
        ],
    ]
];
