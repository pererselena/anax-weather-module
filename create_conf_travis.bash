cat << EOF > config/api_keyes.php
<?php

/**
 * API keys
 */

return [
    "ipstack" => "$IPSTACK",
    "darkSky" => "$DARKSKY",
];
EOF
echo $PWD