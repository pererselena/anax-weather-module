<?php

/**
 * CurlModel.
 */

namespace Anax\CurlModel;

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
            $cache = ANAX_INSTALL_PATH . "/test/cache/$file.cahce";
            $forceRefresh = false;
            $refresh = 60 * 60 * 3;
            if (!is_file(($cache))) {
                $handle = fopen($cache, 'wb');
                fclose($handle);
                $forceRefresh = true;
            }
            if ($forceRefresh || ((time() - filectime($cache)) > ($refresh) || 0 == filesize($cache))) {
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, $link);

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($curl);
                curl_close($curl);

                $handle = fopen($cache, 'wb');
                $jsonCache = $data;
                fwrite($handle, $jsonCache);
                fclose($handle);
            } else {
                $jsonCache = file_get_contents($cache);
            }
            return json_decode($jsonCache, true);
        } else {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $link);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);
            curl_close($curl);

            return json_decode($data, true);
        }
    }

    /**
     * Fetch multidata.
     *
     * @return array
     */

    public function getMultiData(array $links)
    {
        $multiCurl = array();
        $outputArr = array();
        //create the multiple cURL handle
        $multiHandler = curl_multi_init();

        foreach ($links as $url) {
            $curlHandler = curl_init();

            // set URL and other appropriate options
            curl_setopt($curlHandler, CURLOPT_URL, $url);
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
