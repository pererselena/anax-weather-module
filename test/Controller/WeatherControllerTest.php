<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the WeatherController.
 */
class WeatherControllerTest extends TestCase
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

        $di = $this->di;
        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // Setup the controller
        $this->controller = new WeatherController();
        $this->controller->setDI($di);
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
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("<h2>Väderprognos</h2>", $res->getBody());
        $this->assertContains("Oops, platsinformation saknas", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionClientIP()
    {
        $request = $this->di->get("request");
        $request->setServer("HTTP_CLIENT_IP", "127.0.0.1");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("<h2>Väderprognos</h2>", $res->getBody());
        $this->assertContains("Oops, platsinformation saknas", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionForwaredIP()
    {
        $request = $this->di->get("request");
        $request->setServer("HTTP_X_FORWARDED_FOR", "127.0.0.1");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("<h2>Väderprognos</h2>", $res->getBody());
        $this->assertContains("Oops, platsinformation saknas", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithIp()
    {
        $request = $this->di->get("request");
        $request->setGet("search_type", "history");
        $request->setGet("ip", "8.8.8.8");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("8.8.8.8", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithIpForecast()
    {
        $request = $this->di->get("request");
        $request->setGet("search_type", "forecast");
        $request->setGet("ip", "8.8.8.8");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("8.8.8.8", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithCity()
    {
        $request = $this->di->get("request");
        $request->setGet("search_type", "currently");
        $request->setGet("city", "Bålsta");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("Håbo", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionWithoutLocation()
    {
        $request = $this->di->get("request");
        $request->setGet("search_type", "forecast");
        $request->setGet("ip", "::1");
        $res = $this->controller->indexAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("::1", $res->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testDocActionGet()
    {
        $request = $this->di->get("request");
        $res = $this->controller->docActionGet();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains("<h3>Exempel på API svar med forecast.</h3>", $res->getBody());
        $request->setGet("search_type", "forecast");
        $request->setGet("ip", "::1");
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionPost()
    {
        $request = $this->di->get("request");
        $response = $this->di->get("response");
        $request->setGet("search_type", "currently");
        $request->setGet("ipAddress", "8.8.8.8");
        $res = $this->controller->indexActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertEquals(null, $response->getBody());
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionPostWithCity()
    {
        $request = $this->di->get("request");
        $response = $this->di->get("response");
        $request->setPost("search_type", "currently");
        $request->setPost("city", "Bålsta");
        $res = $this->controller->indexActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertEquals(null, $response->getBody());
    }
}
