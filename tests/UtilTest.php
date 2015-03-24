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
        Util::forceStringMinLength($str, 10);
        $this->assertGreaterThanOrEqual(10, strlen($str));

        $str2 = "two";
        Util::forceStringMinLength($str, 100);
        $this->assertGreaterThanOrEqual(100, strlen($str));

        $str3 = "three";
        Util::forceStringMinLength($str, 5);
        $this->assertGreaterThanOrEqual(5, strlen($str));
    }

    public function testFilterPagesByModules(){
        $pages = array(
            TestPage::getPage1()->sspage,
            TestPage::getPage2()->sspage,
            TestPage::getPage3()->sspage,
        );

        $filtered = Util::filterPagesByModules($pages,"moduleX");

        $this->assertCount(1,$filtered);
        $this->assertEquals($filtered[0]->getName(),"page2");
    }
}
