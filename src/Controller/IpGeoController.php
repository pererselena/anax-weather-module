<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\IpGeo\IpGeo;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class IpGeoController implements ContainerInjectableInterface
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
        $this->ip = new IpGeo();
    }



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function indexAction(): object
    {
        $page = $this->di->get("page");
        $title = "Ip Geolokalisering";
        $ipAddress = $this->di->request->getGet("ip");
        if ($ipAddress) {
            $ipGeoInfo = $this->ip->getLocation($ipAddress);
        } else {
            $req = $this->di->get("request");
            if (!empty($req->getServer("HTTP_CLIENT_IP"))) {
                $ipAddress = $req->getServer("HTTP_CLIENT_IP");
            } elseif (!empty($req->getServer("HTTP_X_FORWARDED_FOR"))) {
                $ipAddress = $req->getServer("HTTP_X_FORWARDED_FOR");
            } else {
                $ipAddress = $req->getServer("REMOTE_ADDR");
            }
            $ipGeoInfo = $this->ip->getLocation($ipAddress);
        }


        $page->add("ipGeo/index", [
            "geoInfo" => $ipGeoInfo,
            "ip" => $ipAddress,
            "title" => $title,
        ]);

        return $page->render();
    }


    /**
     * This sample method action it the handler for route:
     * POST mountpoint/create
     *
     * @return object
     */
    public function indexActionPost(): object
    {
        $ipAddress = $this->di->request->getPost("ipAddress");

        return $this->di->response->redirect("geo_ip?ip=$ipAddress");
    }
}
