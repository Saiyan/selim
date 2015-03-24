<?php

use Selim\SilverstripePage;

class SilverstripePageTest extends PHPUnit_Framework_TestCase
{
    public function testPage1()
    {
        $tp = TestPage::getPage1();

        $this->assertEquals('2.4.3', $tp->sspage->getVersion());
        $this->assertTrue($tp->sspage->hasDefaultAdmin());
        $this->assertFalse($tp->sspage->hasEmailLogging());
        $this->assertEquals('dev', $tp->sspage->getEnvironmentType());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$tp->projectpath.'/_config.php'), $tp->sspage->getConfigPhpPath());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$tp->projectpath.'/_config/config.yml'), $tp->sspage->getConfigYmlPath());
        $this->assertFalse($tp->sspage->hasModule("/mysite/"));
        $this->assertTrue($tp->sspage->hasModule("/module1/"));
    }

    public function testPage2()
    {
        $tp = TestPage::getPage2();

        $this->assertEquals('3', $tp->sspage->getVersion());
        $this->assertFalse($tp->sspage->hasDefaultAdmin());
        $this->assertFalse($tp->sspage->hasEmailLogging());
        $this->assertEquals('live', $tp->sspage->getEnvironmentType());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$tp->projectpath.'/_config.php'), $tp->sspage->getConfigPhpPath());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$tp->projectpath.'/_config/config.yml'), $tp->sspage->getConfigYmlPath());
        $this->assertFalse($tp->sspage->hasModule("/proj/"));
    }

    public function testPage3()
    {
        $tp = TestPage::getPage3();

        $this->assertEquals('3.1.10', $tp->sspage->getVersion());
    }
}
