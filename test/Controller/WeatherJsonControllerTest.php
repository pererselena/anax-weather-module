<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the WeatherJsonController.
 */
class WeatherJsonControllerTest extends TestCase
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
        $this->controller = new WeatherJsonController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
    }



    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $request = $this->di->get("request");
        $request->setServer("REMOTE_ADDR", "127.0.0.1");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertEquals("Oops, platsinformation saknas", $json["err"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionClientIp()
    {
        $request = $this->di->get("request");
        $request->setServer("HTTP_CLIENT_IP", "127.0.0.1");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertEquals("Oops, platsinformation saknas", $json["err"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionForwaredIp()
    {
        $request = $this->di->get("request");
        $request->setServer("HTTP_X_FORWARDED_FOR", "127.0.0.1");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertEquals("Oops, platsinformation saknas", $json["err"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionIpNoLocation()
    {
        $request = $this->di->get("request");
        $request->setGet("ip", "::1");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $this->assertEquals("Oops, platsinformation saknas", $json["err"]);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionWithIp()
    {
        $request = $this->di->get("request");
        //$response = $this->di->get("response");
        $request->setGet("ip", "8.8.8.8");
        $request->setGet("search_type", "currently");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $exp = "8.8.8.8";
        $this->assertContains($exp, $json["ip"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithCity()
    {
        $request = $this->di->get("request");
        //$response = $this->di->get("response");
        $request->setGet("city", "Bålsta");
        $request->setGet("search_type", "forecast");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $exp = "Håbo";
        $this->assertContains($exp, $json["city"]);
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithCityHistory()
    {
        $request = $this->di->get("request");
        //$response = $this->di->get("response");
        $request->setGet("city", "Bålsta");
        $request->setGet("search_type", "history");
        $res = $this->controller->indexAction();
        $this->assertIsArray($res);

        $json = $res[0];
        $exp = "Håbo";
        $this->assertContains($exp, $json["city"]);
    }
}
