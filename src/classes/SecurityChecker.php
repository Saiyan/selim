<?php

namespace Selim;

class SecurityChecker
{
    protected static $_instance = null;
    public static function getInstance()
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    protected function __clone() {}

    protected function __construct() {}

    public function findIssues(SilverstripePage $sspage, $is_cli = false)
    {
        $issues = [];

        $version = $sspage->getVersion();
        if ($is_cli && !Util::VersionStringsGreaterThenOrEqual($version, "3.0")) {
            array_push($issues, "IMPORTANT: It seems as if you are running Silverstripe 2. Support for this version of Silverstripe ended March 31st 2015");
        }

        if($sspage->hasDefaultAdmin()){
            array_push($issues, "IMPORTANT: Security::setDefaultAdmin() is used.");
        }

        if($sspage->getEnvironmentType() == "dev"){
            array_push($issues, "WARNING: Director.environment_type is set to 'dev'");
        }

        return $issues;
    }
}
