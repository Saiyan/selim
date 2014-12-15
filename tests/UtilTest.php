<?php

use Selim\Util;
use Selim\SiteConfig;

class UtilTests extends PHPUnit_Framework_TestCase {
    public function testFilterSitesByName()
    {
        $sites = array(
            new SiteConfig('name1',"VAL"),
            new SiteConfig('name2',"VAL"),
            new SiteConfig('sitename3',"VAL"),
            new SiteConfig('sitename4',"VAL"),
            new SiteConfig('testFIVE',"VAL"),
            new SiteConfig('testSIX',"VAL"),
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
}