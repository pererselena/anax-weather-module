<?php

namespace Anax;

use PHPUnit\Framework\TestCase;

//use Anax\IpGeo;

/**
 * Example test class.
 */
class IpGeoTest extends TestCase
{
    /**
     * Just assert something is true.
     */
    public function testGetLocationTrue()
    {
        $ipGeo = new IpGeo\IpGeo();
        $res = $ipGeo->getLocation("8.8.8.8");
        $this->assertTrue($res["isValid"]);
    }

    /**
     * Just assert something is false.
     */
    public function testGetLocationFalse()
    {
        $ipGeo = new IpGeo\IpGeo();
        $res = $ipGeo->getLocation("8.8.8");
        $this->assertFalse($res["isValid"]);
    }

    /**
     * Just assert something is false.
     */
    public function testGetLocationMissing()
    {
        $ipGeo = new IpGeo\IpGeo();
        $res = $ipGeo->getLocation("10.0.0.1");
        $this->assertEquals("Missing", $res["map"]);
    }
}
