<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\IpVerify\IpVerify;

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
class IpVerifyJsonController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    protected $ipVerify;
    protected $request;


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
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->ipVerify = new IpVerify();
        $this->request = $this->di->get("request");
    }



    /**
     * This is the index method action, it handles:
     * GET METHOD mountpoint
     * GET METHOD mountpoint/
     * GET METHOD mountpoint/index
     *
     * @return array
     */
    public function indexActionGet() : array
    {
        $ipAddress = $this->request->getGet("ip");
        if ($ipAddress) {
            $protocol = $this->ipVerify->getIpInfo($ipAddress);
            $isValid = $this->ipVerify->ipVerify($ipAddress);
            $domain = $this->ipVerify->getDomain($ipAddress);
        } else {
            $protocol = "";
            $domain = "";
            $isValid = false;
        }

        
        $json = [
            "protocol" => $protocol,
            "domain" => $domain,
            "ip" => $ipAddress,
            "isValid" => $isValid,
        ];
        return [$json];
    }
}
