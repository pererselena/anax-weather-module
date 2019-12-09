<?php
/**
 * Configuration file for curl service.
 */
return [
    // Services to add to the container.
    "services" => [
        "curl" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Anax\CurlModel\CurlModel();
                $obj->setTestMode(true);
                return $obj;
            }
        ],
    ],
];
