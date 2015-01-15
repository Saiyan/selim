<?php

namespace Selim;

class SecurityChecker {
    private $sspage;

    function __construct(SilverstripePage $sspage){
        $this->sspage = $sspage;
    }

    function findVulnerabilities(){
        $scv = SelimConfig::getInstance()->getVulnarabilityDb();
        $version = $this->sspage->getVersion();
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