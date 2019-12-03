<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpVerifyController.
 */
class IpVerifyControllerTest extends TestCase
{

    /**
     * Setup the controller, before each testcase, just like the router
     * would set it up.
     */
    protected function setUp(): void
    {
        global $di;
        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $di = $this->di;
        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // Setup the controller
        $this->controller = new IpVerifyController();
        $this->controller->setDI($di);
        $this->controller->initialize();
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $session = $this->di->get("session");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("<h2>Ip Validering</h2>", $res->getBody());
        $session->get("ipAdress");
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithIp()
    {
        $session = $this->di->get("session");
        $session->set("ipAddress", "8.8.8.8");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("Ip: 8.8.8.8", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionPost()
    {
        $session = $this->di->get("session");
        $request = $this->di->get("request");
        $response = $this->di->get("response");
        $session->set("ipAddress", "8.8.8.8");
        $res = $this->controller->indexActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $request->setGet("ip", null);
        $this->assertEquals(null, $response->getBody());
    }
}
