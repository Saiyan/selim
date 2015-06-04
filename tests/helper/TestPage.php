<?php

use Selim\SilverstripePage;
use Selim\SiteConfig;

class TestPage {

    private $projectpath;
    private $config;
    private $sspage;

    /**
     * @return TestPage
     */
    public static function getPage1(){
        $tp = new TestPage();
        $tp->setProjectPath("/../pages/page1/mysite");
        $tp->setConfig(new SiteConfig("page1", realpath(__DIR__.$tp->projectpath)));
        $tp->setSSpage(new SilverstripePage($tp->config));
        return $tp;
    }

    /**
     * @return TestPage
     */
    public static function getPage2(){
        $tp = new TestPage();
        $tp->setProjectPath("/../pages/page2/proj");
        $tp->setConfig(new SiteConfig("page2", realpath(__DIR__.$tp->projectpath)));
        $tp->setSSpage(new SilverstripePage($tp->config));
        return $tp;
    }

    /**
     * @return TestPage
     */
    public static function getPage3(){
        $tp = new TestPage();
        $tp->setProjectPath("/../pages/page3/mysite");
        $tp->setConfig(new SiteConfig("page3", realpath(__DIR__.$tp->projectpath)));
        $tp->setSSpage(new SilverstripePage($tp->config));
        return $tp;
    }

    /**
     * @return SilverstripePage
     */
    public function getSSpage() {
        return $this->sspage;
    }

    /**
     * @param SilverstripePage $sspage
     */
    public function setSSpage($sspage) {
        $this->sspage = $sspage;
    }

    /**
     * @return SiteConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param SiteConfig $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getProjectPath() {
        return $this->projectpath;
    }

    /**
     * @param string $projectpath
     */
    public function setProjectPath($projectpath) {
        $this->projectpath = $projectpath;
    }
}