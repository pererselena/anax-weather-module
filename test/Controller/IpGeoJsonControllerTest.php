<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpGeoJsonController.
 */
class IpGeoJsonControllerTest extends TestCase
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

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new IpGeoJsonController();
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
        $request->setGet("ip", "127.0.0");
        $res = $this->controller->indexActionGet();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertFalse($json["geoInfo"]["isValid"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionNoIp()
    {
        $request = $this->di->get("request");
        $request->setServer("REMOTE_ADDR", "127.0.0.1");
        $res = $this->controller->indexActionGet();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertTrue($json["geoInfo"]["isValid"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionClientIp()
    {
        $request = $this->di->get("request");
        $request->setServer("HTTP_CLIENT_IP", "127.0.0.1");
        $res = $this->controller->indexActionGet();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertTrue($json["geoInfo"]["isValid"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionForwaredIp()
    {
        $request = $this->di->get("request");
        $request->setServer("HTTP_X_FORWARDED_FOR", "127.0.0.1");
        $res = $this->controller->indexActionGet();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertTrue($json["geoInfo"]["isValid"]);
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
