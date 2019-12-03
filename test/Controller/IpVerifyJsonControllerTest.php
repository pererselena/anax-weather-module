<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpVerifyJsonController.
 */
class IpVerifyJsonControllerTest extends TestCase
{
    
    // Create the di container.
    protected $di;
    protected $controller;



    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new IpVerifyJsonController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
    }



    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $request = $this->di->get("request");
        //$response = $this->di->get("response");
        //$request->setGet("ip", "8.8.8.8");
        $res = $this->controller->indexActionGet();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertFalse($json["isValid"]);
        $request->setGet("ip", "8.8.8.8");
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithIp()
    {
        $request = $this->di->get("request");
        //$response = $this->di->get("response");
        $request->setGet("ip", "8.8.8.8");
        $res = $this->controller->indexActionGet();
        $this->assertIsArray($res);

        $json = $res[0];
        $exp = "8.8.8.8";
        $this->assertContains($exp, $json["ip"]);
    }
}
