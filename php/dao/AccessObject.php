<?php

require_once "Url.php";
require_once "ChildUrl.php";
require_once __DIR__ . "/../urlFinder/UrlFinder.php";

/**
 * Class that has touchpoints with Frontend,
 * therefore no queries should appear here.
 * Also if you change the Database (e.g. to no-sql)
 * this class should not be changed.
 */
class AccessObject
{
    private $urlTable;
    private $childUrlTable;
    public $post;

    /**
     * __construct creates new instances of all table-related classes.
     *
     * @param  string $postedUrl posted url we got from frontend
     *
     * @return void
     */
    public function __construct($postedUrl)
    {
        $this->urlTable = new Url;
        $this->childUrlTable = new ChildUrl;
        $this->post = $this->testInput($postedUrl);
    }

    /**
     * console_log for debugging
     *
     * @param  mixed $data
     *
     * @return void
     */
    protected function console_log($data)
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    /**
     * getUrlsAsJson checks if URL exists in database,
     * if so returns Child URLs as JSON (splitted in Home and Foreign)
     * if not, pings URL searches for all linked URLS, splits
     * them into Home and Foreign, puts that into the database,
     * and then returns values of database
     * (now THATS a busy function, but don't worry, it alls separeted nicely)
     *
     * @return void
     */
    public function getUrlsAsJson()
    {
        $url = $this->post;
        $urlTable = $this->urlTable;
        $childUrlTable = $this->childUrlTable;

        if (!$urlTable->checkIfUrlExists($url)) {

            $urlFinder = new UrlFinder($url);

            $homeUrls = $urlFinder->getHomeUrls();
            $foreignUrls = $urlFinder->getForeignUrls();

            $urlTable->addToDataBase($url);
            $childUrlTable->addHomeUrlToDatabase($url, $homeUrls);
            $childUrlTable->addForeignUrlToDatabase($url, $foreignUrls);

        }

        $urlsHome = $childUrlTable->getHomeUrls($url);
        $urlsForeign = $childUrlTable->getForeignUrls($url);

        $ret = [];
        $ret["Home"] = $urlsHome;
        $ret["Foreign"] = $urlsForeign;

        return json_encode($ret);
    }

    /**
     * testInput tests input so no harmful code can be pushed
     *
     * @param  string $data
     *
     * @return string $data = the tested input
     */
    private function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
