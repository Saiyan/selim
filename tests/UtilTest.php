<?php

use Selim\Util;
use Selim\SiteConfig;

class UtilTests extends PHPUnit_Framework_TestCase
{
    public function testFilterSitesByName()
    {
        $sites = array(
            new SiteConfig('name1', "VAL"),
            new SiteConfig('name2', "VAL"),
            new SiteConfig('sitename3', "VAL"),
            new SiteConfig('sitename4', "VAL"),
            new SiteConfig('testFIVE', "VAL"),
            new SiteConfig('testSIX', "VAL"),
        );

        $filter_startswith_name = "^name";
        $si = Util::filterSitesByName($sites, $filter_startswith_name);
        $this->assertEquals(2, count($si));

        $filter_anyname = "name";
        $si = Util::filterSitesByName($sites, $filter_anyname);
        $this->assertEquals(4, count($si));

        $filter_endswithdecimal = "\\d$";
        $si = Util::filterSitesByName($sites, $filter_endswithdecimal);
        $this->assertEquals(4, count($si));

        $filter_positivelookbehind = "(?<=te)\\w+";
        $si = Util::filterSitesByName($sites, $filter_positivelookbehind);
        $this->assertEquals(4, count($si));

        $filter_positivelookahead = "te(?=na)";
        $si = Util::filterSitesByName($sites, $filter_positivelookahead);
        $this->assertEquals(2, count($si));
    }

    public function testFindInArrayWithRegex()
    {
        $arr = array(
            'name1',
            'name2',
            'sitename3',
            'sitename4',
            'testFIVE',
            'testSIX',
        );

        $filter_startswith_name = "/^name/";
        $si = Util::findInArrayWithRegex($arr, $filter_startswith_name);
        $this->assertEquals($arr[0], $si);

        $filter_name2 = "/name2/";
        $si = Util::findInArrayWithRegex($arr, $filter_name2);
        $this->assertEquals($arr[1], $si);

        $filter_endswithdecimal = "/\\d$/";
        $si = Util::findInArrayWithRegex($arr, $filter_endswithdecimal);
        $this->assertEquals($arr[0], $si);

        $filter_positivelookbehind = "/(?<=te)\\w+/";
        $si = Util::findInArrayWithRegex($arr, $filter_positivelookbehind);
        $this->assertEquals($arr[2], $si);

        $filter_positivelookahead = "/te(?=na)/";
        $si = Util::findInArrayWithRegex($arr, $filter_positivelookahead);
        $this->assertEquals($arr[2], $si);
    }

    public function testForceStringMinLength()
    {
        $str1 = "one";
        Util::forceStringMinLength($str1, 10);
        $this->assertGreaterThanOrEqual(10, strlen($str1));

        $str2 = "two";
        Util::forceStringMinLength($str2, 100);
        $this->assertGreaterThanOrEqual(100, strlen($str2));

        $str3 = "three";
        Util::forceStringMinLength($str3, 5);
        $this->assertGreaterThanOrEqual(5, strlen($str3));
    }

    public function testFilterPagesByModules(){
        $pages = array(
            TestPage::getPage1()->getSSpage(),
            TestPage::getPage2()->getSSpage(),
            TestPage::getPage3()->getSSpage(),
        );

        $filtered = Util::filterPagesByModules($pages,"moduleX");
        /* @var $filtered \Selim\SilverstripePage[] */
        $this->assertCount(1,$filtered);
        $this->assertEquals($filtered[0]->getName(),"page2");
    }

    public function testFilterPagesByDefaultAdmin() {
        $pages = array(
            TestPage::getPage1()->getSSpage(),
            TestPage::getPage2()->getSSpage(),
            TestPage::getPage3()->getSSpage(),
        );

        $si = Util::filterPagesByDefaultAdmin($pages, true);
        $this->assertEquals(1, count($si));

        $si = Util::filterPagesByDefaultAdmin($pages, false);
        $this->assertEquals(2, count($si));
    }

    public function testFilterPagesByVersion(){
        $pages = array(
            TestPage::getPage1()->getSSpage(),
            TestPage::getPage2()->getSSpage(),
            TestPage::getPage3()->getSSpage(),
        );

        $filtered = Util::filterPagesByVersion($pages,"^2.");
        /* @var $filtered \Selim\SilverstripePage[] */
        $this->assertCount(1,$filtered);
        $this->assertEquals($filtered[0]->getName(),"page1");
    }

    public function testFilterPagesByEnvironmentType(){
        $pages = array(
            TestPage::getPage1()->getSSpage(),
            TestPage::getPage2()->getSSpage(),
            TestPage::getPage3()->getSSpage(),
        );

        $filtered = Util::filterPagesByEnvironmentType($pages,"dev");
        /* @var $filtered \Selim\SilverstripePage[] */
        $this->assertCount(1,$filtered);
        $this->assertEquals($filtered[0]->getName(),"page1");

        $filtered = Util::filterPagesByEnvironmentType($pages,"test");
        $this->assertCount(0,$filtered);

        $filtered = Util::filterPagesByEnvironmentType($pages,"live");
        $this->assertEquals($filtered[0]->getName(),"page2");
        $this->assertEquals($filtered[1]->getName(),"page3");
        $this->assertCount(2,$filtered);
    }
}
