<?php

require_once __DIR__ .'/../classes/Util.php';

class UtilTests extends PHPUnit_Framework_TestCase {
    public function testFilterSitesByName()
    {
        $sites = array(
            'name1' => "VAL",
            'name2' => "VAL",
            'sitename3' => "VAL",
            'sitename4' => "VAL",
            'testFIVE' => "VAL",
            'testSIX' => "VAL",
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
 