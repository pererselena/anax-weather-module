<?php
/**
 * Routes for ip verify.
 */
return [
    "routes" => [
        [
            "info" => "Ip verifier.",
            "mount" => "verify_ip",
            "handler" => "\Anax\Controller\IpVerifyController",
        ],
    ]
];
