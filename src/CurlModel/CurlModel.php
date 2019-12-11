<?php

/**
 * CurlModel.
 */

namespace Anax\CurlModel;

use PhpParser\Node\Expr\Cast\Array_;

/**
 * Showing off a standard class with methods and properties.
 */
class CurlModel
{
    protected $testMode;

    /**
     * Fetch data.
     *
     * @return array
     */

    public function getData(string $link)
    {
        if ($this->testMode == true) {
            $file = preg_replace("/[^[:alnum:][:space:]]/u", '', $link);
            $cache = ANAX_INSTALL_PATH . "/test/cache/weather/$file.cahce";
            $forceRefresh = false;
            $refresh = 60 * 60 * 13;
            if (!is_file(($cache))) {
                $handle = fopen($cache, 'wb');
                if ($handle === false) {
                    return array();
                }
                fclose($handle);
                $forceRefresh = true;
            }
            if ($forceRefresh === true || ((time() - filectime($cache)) > ($refresh) || 0 == filesize($cache))) {
                $jsonCache = $this->singleFetch($link);
                file_put_contents($cache, $jsonCache);
            } else {
                $jsonCache = file_get_contents($cache);
            }
            if (gettype($jsonCache) == "array") {
                $jsonCache = $jsonCache[0];
            }
            return json_decode($jsonCache, true);
        } else {
            return json_decode($this->singleFetch($link), true);
        }
    }

    public function singleFetch(string $link)
    {
        $curl = curl_init();

        if ($curl == false) {
            return array();
        }
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    /**
     * Fetch multidata.
     *
     * @return array
     */

    public function getMultiData(array $links)
    {
        $outputArr = array();
        if ($this->testMode == true) {
            $forceRefresh = false;
            $refresh = 60 * 60 * 13;
            $url = $links[1];
            $tempUrl = preg_replace("/[,][0-9]*[?]/u", "", $url);
            $file = preg_replace("/[^[:alnum:][:space:]]/u", '', $tempUrl);
            $cache = ANAX_INSTALL_PATH . "/test/cache/weather/multi-$file.cache";
            if (!is_file(($cache))) {
                $handle = fopen($cache, 'wb');
                if ($handle === false) {
                    return array();
                }
                fclose($handle);
                $forceRefresh = true;
            }

            if ($forceRefresh === true || ((time() - filectime($cache)) > ($refresh) || 0 == filesize($cache))) {
                $outputArr = $this->fetchMultiData($links);
                file_put_contents($cache, $outputArr);
            } else {
                $jsonCache = file_get_contents($cache);
                array_push($outputArr, json_decode($jsonCache[0], true));
            }
        } else {
            $outputArr = $this->fetchMultiData($links);
        }
        return $outputArr;
    }
    /*
    * Fetch multi data from API
    * @return array
    */
    private function fetchMultiData(array $links)
    {
        $multiCurl = array();
        $outputArr = array();
        //create the multiple cURL handle
        $multiHandler = curl_multi_init();
        foreach ($links as $link) {
            $curlHandler = curl_init();
            if ($curlHandler === false) {
                return array();
            }
            // set URL and other appropriate options
            curl_setopt($curlHandler, CURLOPT_URL, $link);
            curl_setopt($curlHandler, CURLOPT_HEADER, 0);
            curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
            //add the two handles
            curl_multi_add_handle($multiHandler, $curlHandler);
            array_push($multiCurl, $curlHandler);
        }
        //execute the multi handle
        $active = null;
        do {
            curl_multi_exec($multiHandler, $active);
        } while ($active);

        //close the handles
        foreach ($multiCurl as $handler) {
            curl_multi_remove_handle($multiHandler, $handler);
        }
        curl_multi_close($multiHandler);
        foreach ($multiCurl as $handler) {
            $data = curl_multi_getcontent($handler);
            array_push($outputArr, json_decode($data, true));
        }
        return $outputArr;
    }

    /**
     * Set test mode.
     *
     */

    public function setTestMode(bool $mode)
    {
        $this->testMode = $mode;
    }
}
