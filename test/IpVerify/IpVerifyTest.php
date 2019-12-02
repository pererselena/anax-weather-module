<?php

namespace Anax;

use PHPUnit\Framework\TestCase;

//use Anax\IpVerify;

/**
 * Example test class.
 */
class IpVerifyTest extends TestCase
{
    /**
     * Just assert something is true.
     */
    public function testIpVerifyTrue()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->ipVerify("8.8.8.8");
        $this->assertTrue($res);
    }

    /**
     * Just assert something is false.
     */
    public function testIpVerifyFalse()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->ipVerify("8.8.8");
        $this->assertFalse($res);
    }

    /**
     * Just assert something is equals.
     */
    public function testGetIpInfoIPV4()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->getIpInfo("8.8.8.8");
        $this->assertEquals($res, "IPV4");
    }

    /**
     * Just assert something is equals.
     */
    public function testGetIpInfoIPV6()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->getIpInfo("2001:4860:4860::8888");
        $this->assertEquals($res, "IPV6");
    }

    /**
     * Just assert something is equals.
     */
    public function testGetIpInfoNa()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->getIpInfo("2001:4860:4860");
        $this->assertEquals($res, "n/a");
    }

    /**
     * Just assert something is equals.
     */
    public function testGetDomain()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->getDomain("8.8.8.8");
        $this->assertEquals($res, "dns.google");
    }

    /**
     * Just assert something is equals.
     */
    public function testGetDomainMissing()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->getDomain("255.255.255.255");
        $this->assertEquals("255.255.255.255", $res);
    }

    /**
     * Just assert something is equals.
     */
    public function testGetDomainNa()
    {
        $ipVerify = new IpVerify\IpVerify();
        $res = $ipVerify->getDomain("255.255.255");
        $this->assertEquals("n/a", $res);
    }
}
