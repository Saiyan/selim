<?php

use Selim\SilverstripePage;

class TestPage {
    public $projectpath;
    public $config;
    public $sspage;

    /**
     * @return TestPage
     */
    public static function getPage1(){
        $tp = new TestPage();
        $tp->projectpath = "/../pages/page1/mysite";
        $tp->config = new \Selim\SiteConfig("page1", realpath(__DIR__.$tp->projectpath));
        $tp->sspage = new SilverstripePage($tp->config);
        return $tp;
    }

    /**
     * @return TestPage
     */
    public static function getPage2(){
        $tp = new TestPage();
        $tp->projectpath = "/../pages/page2/proj";
        $tp->config = new \Selim\SiteConfig("page2", realpath(__DIR__.$tp->projectpath));
        $tp->sspage = new SilverstripePage($tp->config);
        return $tp;
    }

    /**
     * @return TestPage
     */
    public static function getPage3(){
        $tp = new TestPage();
        $tp->projectpath = "/../pages/page3/mysite";
        $tp->config = new \Selim\SiteConfig("page3", realpath(__DIR__.$tp->projectpath));
        $tp->sspage = new SilverstripePage($tp->config);
        return $tp;
    }
}