<?php

/**
 * Ip verifier.
 */

namespace Anax\IpVerify;

/**
 * Showing off a standard class with methods and properties.
 */
class IpVerify
{
    /**
     * Validates ip address.
     *
     * @return bool
     */

    public function ipVerify(string $ipAdress)
    {
        if (filter_var($ipAdress, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates ip address.
     *
     * @return string
     */

    public function getIpInfo(string $ipAdress)
    {

        if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return "IPV4";
        } elseif (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return "IPV6";
        } else {
            return "n/a";
        }
    }

    /**
     * Gets domain.
     *
     * @return string
     */

    public function getDomain(string $ipAdress)
    {
        if ($this->ipVerify($ipAdress)) {
            $host = gethostbyaddr($ipAdress);
            return $host;
        } else {
            return "n/a";
        }
    }
}
