<?php

namespace Anax;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

//use Anax\Weather;

/**
 * Example test class.
 */
class WeatherTest extends TestCase
{
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

        // Setup the model
        $this->weather = new Weather\Weather($di);
    }

    /**
     * Just assert something is false.
     */
    public function testGetCityFalse()
    {
        $res = $this->weather->getWeather("82.3005493", "200.5202932", "history");
        $this->assertFalse($res["matched"]);
    }

    /**
     * Just assert something is false.
     */
    public function testGetFOrecastFalse()
    {
        $res = $this->weather->getWeather("82.3005493", "200.5202932", "forecast");
        $this->assertFalse($res["matched"]);
    }

    /**
     * Just assert something is false.
     */
    public function testGetCoordsFalse()
    {
        $res = $this->weather->getCoords("sdfgsdfgsdfgsdfgsdfgdsf");
        $this->assertEquals("Missing", $res["long"]);
    }
}
