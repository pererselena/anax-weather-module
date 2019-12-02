<?php
/**
 * Load the sample json controller.
 */
return [
    "routes" => [
        [
            "info" => "Ip verify Json Controller.",
            "mount" => "json_ip",
            "handler" => "\Anax\Controller\IpVerifyJsonController",
        ],
    ]
];
