<?php

namespace Selim;

class SecurityChecker {
    private $sspage;

    function __construct(SilverstripePage $sspage){
        $this->sspage = $sspage;
    }

    function findVulnerabilities(){
        $version = $this->sspage->getVersion();
        if(Util::VersionStringsGreaterThenOrEqual($version,"3.0")){
            echo "IMPORTANT: It seems as if you are running Silverstripe 2. This Version of Silverstripe will only be supported until March 31st 2015".PHP_EOL;
        }
        return self::findVulnerabilitiesForVersion($version);
    }

    public static function findVulnerabilitiesForVersion($version){
        $scv = SelimConfig::getInstance()->getVulnarabilityDb();
        $vulnerabilities = array();
        foreach($scv as $vuln){
            //Check if this version is affected by any vulnerabilities
            foreach($vuln["affected"] as $aff){
                if(strpos($version,$aff) === 0){
                    $isfixed = false;
                    //Check if vulnarability is fixed in this version
                    foreach($vuln["fixed"] as $fix){
                        if(Util::VersionStringsGreaterThenOrEqual($version,$fix)){
                            $isfixed = true;
                            break;
                        }
                    }
                    if(!$isfixed){
                        array_push($vulnerabilities,$vuln);
                    }
                }
            }
        }
        return $vulnerabilities;
    }
}