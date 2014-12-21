<?php

use Selim\SilverstripePage;

class SilverstripePageTest extends PHPUnit_Framework_TestCase {
    public function testPage1(){
        $projectpath = "/pages/page1/mysite";

        $config = new \Selim\SiteConfig("page1",realpath(__DIR__.$projectpath));
        $sspage = new SilverstripePage($config);

        $this->assertEquals('2.4.3', $sspage->getVersion());
        $this->assertTrue($sspage->hasDefaultAdmin());
        $this->assertFalse($sspage->hasEmailLogging());
        $this->assertEquals('dev',$sspage->getEnvironmentType());
        $this->assertEquals(realpath(__DIR__.$projectpath.'/_config.php'),$sspage->getConfigPhpPath());
        $this->assertEquals(realpath(__DIR__.$projectpath.'/_config/config.yml'),$sspage->getConfigYmlPath());

    }
}