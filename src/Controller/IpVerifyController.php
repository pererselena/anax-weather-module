<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\IpVerify\IpVerify;

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
class IpVerifyController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    protected $ipVerify;
    protected $request;
    protected $session;
    protected $response;


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
        $this->session = $this->di->get("session");
        $this->response = $this->di->get("response");
    }



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function indexAction() : object
    {
        $page = $this->di->get("page");
        $title = "Ip Validering";
        $ipAddress = $this->session->get("ipAddress");
        if ($ipAddress) {
            $protocol = $this->ipVerify->getIpInfo($ipAddress);
            $isValid = $this->ipVerify->ipVerify($ipAddress) ? "true" : "false";
            $domain = $this->ipVerify->getDomain($ipAddress);
            $this->session->set("ipAddress", null);
        } else {
            $protocol = "";
            $domain = "";
            $isValid = "";
        }
        
        $page->add("ipVerify/index", [
            "protocol" => $protocol,
            "domain" => $domain,
            "title" => $title,
            "ip" => $ipAddress,
            "isValid" => $isValid,
        ]);

        return $page->render();
    }


    /**
     * This sample method action it the handler for route:
     * POST mountpoint/create
     *
     * @return object
     */
    public function indexActionPost() : object
    {
        $ipAddress = $this->request->getPost("ipAddress");
        $this->session->set("ipAddress", $ipAddress);

        return $this->response->redirect("verify_ip/index");
    }
}
