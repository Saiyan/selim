<?php

class SecurityCheckerTest extends PHPUnit_Framework_TestCase
{
    public function test249()
    {
        $version = "2.4.9";
        $vulnerabilities = \Selim\SecurityChecker::findVulnerabilitiesForVersion($version);
        $expected = array("SS-2014-012","SS-2013-005","SS-2013-004","SS-2013-003","SS-2013-002","SS-2013-001","`\$allowed_actions` overrides");

        $this->assertCount(count($expected), $vulnerabilities);
        $this->checkVulnerabilityTitlesContainStrings($vulnerabilities, $expected);
    }

    public function test315()
    {
        $version = "3.1.5";
        $vulnerabilities = \Selim\SecurityChecker::findVulnerabilitiesForVersion($version);
        $expected = array("SS-2015-016","SS-2015-015","SS-2015-014","SS-2015-013","SS-2015-012","SS-2015-011","SS-2015-010","SS-2015-009","SS-2014-017","SS-2014-015","SS-2014-018","SS-2014-016","SS-2014-014","SS-2014-012","SS-2015-007","SS-2015-006","SS-2015-005","SS-2015-004","SS-2015-003","SS-2015-001","SS-2015-008");

        $this->assertCount(count($expected), $vulnerabilities);
        $this->checkVulnerabilityTitlesContainStrings($vulnerabilities, $expected);
    }

    private function checkVulnerabilityTitlesContainStrings($vulnerabilities, $expected)
    {
        $vuln_text = "";
        foreach ($vulnerabilities as $vuln) {
            $vuln_text .= $vuln["title"];
        }

        foreach ($expected as $exp) {
            $this->assertContains($exp, $vuln_text);
        }
    }
}
