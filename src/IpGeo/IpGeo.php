<?php

/**
 * Ip verifier.
 */

namespace Anax\IpGeo;

use Anax\IpVerify\IpVerify;

/**
 * Showing off a standard class with methods and properties.
 */
class IpGeo
{

    protected $apiKey;
    protected $ipVerify;
    protected $output;

    /**
     * Constructor for object.
     *
     *
     */

    public function __construct()
    {
        $this->ipVerify = new IpVerify();
        $this->output = [
            "ip" => "",
            "protocol" => "",
            "isValid" => "",
            "domain" => "",
            "long" => "",
            "lat" => "",
            "country" => "",
            "city" => "",
            "map" => "",
            "flag" => "",
            "embed" => "",
        ];
        $allKeys = require ANAX_INSTALL_PATH . "/config/api_keys.php";
        $this->apiKey = $allKeys["ipstack"];
    }

    /**
     * Get locaation.
     *
     * @return array
     */

    public function getLocation(string $ipAdress)
    {
        $this->output["ip"] = $ipAdress;

        if ($this->ipVerify->ipVerify($ipAdress)) {
            $geoData = $this->getData($ipAdress);

            $this->output["isValid"] = true;
            $this->output["protocol"] = $this->ipVerify->getIpInfo($ipAdress);
            $this->output["domain"] = $this->ipVerify->getDomain($ipAdress);
            if ($geoData["longitude"] && $geoData["latitude"]) {
                $this->output["map"] = $this->createMapLink($geoData["longitude"], $geoData["latitude"]);
                $this->output["long"] = $geoData["longitude"];
                $this->output["lat"] = $geoData["latitude"];
                $this->output["embed"] = $this->createEmbedMap($geoData["longitude"], $geoData["latitude"]);
            } else {
                $this->output["map"] = "Missing";
                $this->output["long"] = "Missing";
                $this->output["lat"] = "Missing";
            }
            $this->output["country"] = $geoData["country_name"];
            $this->output["city"] = $geoData["city"];
            $this->output["flag"] = $geoData["location"]["country_flag"];

            return $this->output;
        } else {
            $this->output["isValid"] = false;
            return $this->output;
        }
    }

    /**
     * Fetch data.
     *
     * @return array
     */

    public function getData(string $ipAdress)
    {
        $curl = curl_init();

        if ($curl == false) {
            return array();
        }
        curl_setopt($curl, CURLOPT_URL, "http://api.ipstack.com/$ipAdress?access_key=$this->apiKey");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data, true);
    }

    /**
     * Create map link.
     *
     * @return string
     */

    public function createMapLink(string $long, string $lat)
    {
        return "https://www.openstreetmap.org/#map=15/$lat/$long";
    }

    /**
     * Create embeded map.
     *
     * @return string
     */

    public function createEmbedMap(string $long, string $lat)
    {
        $latOffset = 0.01338;
        $longOffset = 0.01451;
        $lat1 = $lat + $latOffset;
        $lat2 = $lat - $latOffset;
        $long1 = $long + $longOffset;
        $long2 = $long - $longOffset;
        $box = "$long1%2C$lat1%2C$long2%2C$lat2";

        return $box;
    }
}
