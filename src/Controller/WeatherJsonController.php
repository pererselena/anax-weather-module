<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Weather\Weather;
use Anax\IpGeo\IpGeo;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample JSON controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 */
class WeatherJsonController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize(): void
    {
        // Use to initialise member variables.
        $this->weather = new Weather($this->di);
        $this->ipGeo = new IpGeo();
    }



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return array
     */
    public function indexAction(): array
    {
        $title = "Väderprognos";
        $ipAddress = $this->di->request->getGet("ip");
        $city = $this->di->request->getGet("city");
        $searchType = $this->di->request->getGet("search_type");
        $lat = $this->di->request->getGet("lat");
        $long = $this->di->request->getGet("long");

        if ($city) {
            $res = $this->weather->getCoords($city);
            $lat = $res["lat"];
            $long = $res["long"];
        } elseif ($ipAddress) {
            $ipInfo = $this->ipGeo->getLocation($ipAddress);
            $lat = $ipInfo["lat"];
            $long = $ipInfo["long"];
        } else {
            $req = $this->di->get("request");
            if (!empty($req->getServer("HTTP_CLIENT_IP"))) {
                $ipAddress = $req->getServer("HTTP_CLIENT_IP");
            } elseif (!empty($req->getServer("HTTP_X_FORWARDED_FOR"))) {
                $ipAddress = $req->getServer("HTTP_X_FORWARDED_FOR");
            } else {
                $ipAddress = $req->getServer("REMOTE_ADDR");
            }
            $ipInfo = $this->ipGeo->getLocation($ipAddress);
            $lat = $ipInfo["lat"];
            $long = $ipInfo["long"];
        }
        if ($lat == "Missing") {
            $json = [
                "err" => "Oops, platsinformation saknas",
                "title" => $title,
                "ip" => $ipAddress,
                "weather" => "",
            ];

            return [$json];
        }

        $weatherInfo = $this->weather->getWeather($lat, $long, $searchType);

        $json = [
            "weather" => $weatherInfo,
            "ip" => $ipAddress,
            "title" => $title,
        ];
        if ($searchType == "history") {
            $json["weather"] = $weatherInfo["history"];
            $json["city"] = $weatherInfo["city"];
        } elseif ($searchType == "forecast") {
            $json["weather"] = $weatherInfo["daily"]["data"];
            $json["city"] = $weatherInfo["city"];
        } elseif ($searchType == "currently") {
            $json["weather"] = $weatherInfo;
        }

        return [$json];
    }
}
