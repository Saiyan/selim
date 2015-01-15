<?php

class SecurityCheckerTest extends PHPUnit_Framework_TestCase {
    public function test249()
    {
        $version = "2.4.9";
        $vulnerabilities = \Selim\SecurityChecker::findVulnerabilitiesForVersion($version);
        $expected = array("SS-2014-012","SS-2013-005","SS-2013-004","SS-2013-003","SS-2013-002","SS-2013-001","`\$allowed_actions` overrides");

        $this->assertCount(7,$vulnerabilities);
        $this->checkVulnerabilityTitlesContainStrings($vulnerabilities,$expected);
    }

    public function test315()
    {
        $version = "3.1.5";
        $vulnerabilities = \Selim\SecurityChecker::findVulnerabilitiesForVersion($version);
        $expected = array("SS-2014-018","SS-2014-016","SS-2014-014","SS-2014-012");

        $this->assertCount(4,$vulnerabilities);
        $this->checkVulnerabilityTitlesContainStrings($vulnerabilities,$expected);
    }

    private function checkVulnerabilityTitlesContainStrings($vulnerabilities,$expected){
        $vuln_text = "";
        foreach($vulnerabilities as $vuln) {
            $vuln_text.=$vuln["title"];
        }

        foreach ($expected as $exp){
            $this->assertContains($exp,$vuln_text);
        }
    }
}