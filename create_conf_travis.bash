cat << EOF > config/api_keys.php
<?php

/**
 * API keys
 */

return [
    "ipstack" => "$IPSTACK",
    "darkSky" => "$DARKSKY",
];
EOF