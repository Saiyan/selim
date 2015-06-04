<?php

class SilverstripePageTest extends PHPUnit_Framework_TestCase
{
    public function testPage1()
    {
        $tp = TestPage::getPage1();

        $sspage = $tp->getSSpage();
        $projpath = $tp->getProjectPath();

        $this->assertEquals('2.4.3', $sspage->getVersion());
        $this->assertTrue($sspage->hasDefaultAdmin());
        $this->assertFalse($sspage->hasEmailLogging());
        $this->assertEquals('dev', $sspage->getEnvironmentType());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$projpath.'/_config.php'), $sspage->getConfigPhpPath());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$projpath.'/_config/config.yml'), $sspage->getConfigYmlPath());
        $this->assertFalse($sspage->hasModule("/mysite/"));
        $this->assertTrue($sspage->hasModule("/module1/"));
    }

    public function testPage2()
    {
        $tp = TestPage::getPage2();

        $sspage = $tp->getSSpage();
        $projpath = $tp->getProjectPath();

        $this->assertEquals('3', $sspage->getVersion());
        $this->assertFalse($sspage->hasDefaultAdmin());
        $this->assertFalse($sspage->hasEmailLogging());
        $this->assertEquals('live', $sspage->getEnvironmentType());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$projpath.'/_config.php'), $sspage->getConfigPhpPath());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$projpath.'/_config/config.yml'), $sspage->getConfigYmlPath());
        $this->assertFalse($sspage->hasModule("/proj/"));
    }

    public function testPage3()
    {
        $tp = TestPage::getPage3();

        $sspage = $tp->getSSpage();
        $projpath = $tp->getProjectPath();

        $this->assertEquals('3.1.10', $sspage->getVersion());
        $this->assertFalse($sspage->hasDefaultAdmin());
        $this->assertFalse($sspage->hasEmailLogging());
        $this->assertEquals('live', $sspage->getEnvironmentType());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$projpath.'/_config.php'), $sspage->getConfigPhpPath());
        $this->assertEquals(realpath(__DIR__.'/pages/'.$projpath.'/_config/config.yml'), $sspage->getConfigYmlPath());
        $this->assertFalse($sspage->hasModule("/proj/"));
    }
}
