<?php

/**
 * class that handles the URL finding
 *
 */
class UrlFinder
{

    public $url;
    private $html;
    private $aTags = [];
    private $tagsSplitted = [];

    /**
     * __constructor: fills relevante params, needs url that should be searched through in string format
     *
     * @param  string $url
     *
     * @return void
     */
    public function __construct($url)
    {

        $this->url = $this->testInput($this->trimUrl($url));
        $this->tagsSplitted["Home"] = [];
        $this->tagsSplitted["Foreign"] = [];

        $this->checkIfHttps($this->url);
        $this->html = file_get_contents($this->checkIfHttps($this->url));
        $this->fillATags();
        $this->splitTags();
    }

    /**
     * console_log for debugging
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function console_log($data)
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    /**
     * checkIfHttps checks if posted URL is https or http
     *
     * @param  string $url
     *
     * @return string posted url with either http:// or https:// in front
     */
    private function checkIfHttps($url)
    {

        //$this->console_log($url);

        $checkUrl = parse_url('https://' . $url);
        $this->console_log($checkUrl);
        $ret;
        if ($checkUrl['scheme'] == 'https') {
            $ret = 'https://' . $url;
        } else {
            $ret = 'http://' . $url;
        }

        return $ret;
    }

    /**
     * Test Input so no harmful code can be pushed for Strings
     */
    protected function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * fillATags fills param aTags with all found <a>-Tags from Page
     *
     * @return array of all href-values from a-tags
     */
    private function fillATags()
    {

        $dom = new DOMDocument;

        @$dom->loadHTML($this->html);

        $links = $dom->getElementsByTagName('a');

        foreach ($links as $link) {

            array_push($this->aTags, $link->getAttribute('href'));

        }

        return $this->aTags;

    }

    /**
     * splitTags splits all found <a> Tags in Tags from home-url and from
     * foreign urls
     *
     * @return void
     */
    private function splitTags()
    {

        foreach ($this->aTags as $link) {
            $url = $this->trimUrl($link);

            if (!empty($url)) {

                $pos = strpos($url, $this->url);

                if ($pos === false) {
                    array_push($this->tagsSplitted["Foreign"], $url);
                } else {
                    array_push($this->tagsSplitted["Home"], $url);
                }

            }
        }

        $this->tagsSplitted["Home"] = $this->convertToArrayWithUniqueValues($this->tagsSplitted["Home"]);
        $this->tagsSplitted["Foreign"] = $this->convertToArrayWithUniqueValues($this->tagsSplitted["Foreign"]);

    }

    /**
     * trimUrl trims url from / and \ and http or https
     *
     * @param  string $url
     *
     * @return string
     */
    private function trimUrl($url)
    {
        $input = trim($url, '/');

        $urlParts = parse_url($input, PHP_URL_HOST);

        return $urlParts;
    }

    /**
     * getUrlsAsJSON: gets all URLS and puts it in JSON-Format
     *
     * @return JSON JSON with Keys "Home" and "Foreign" as defined in the constructor
     */
    public function getUrlsAsJSON()
    {
        return (json_encode($this->tagsSplitted));
    }

    public function getHomeUrls()
    {
        return ($this->tagsSplitted["Home"]);
    }

    public function getForeignUrls()
    {
        return ($this->tagsSplitted["Foreign"]);
    }

    private function convertToArrayWithUniqueValues($array)
    {
        return array_values(array_unique($array));

    }
}
