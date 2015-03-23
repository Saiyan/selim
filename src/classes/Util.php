<?php

namespace Selim;

class Util
{
    public static function stripPhpComments($str)
    {
        $regex_multiline = "/\\/\\*[^\\Z]*\\*\\//m";
        $regex_singleline = "/\\/\\/.*/m";
        $result = preg_filter($regex_multiline, "", $str);
        if ($result) {
            $str = $result;
        }

        return preg_filter($regex_singleline, "", $str);
    }

    public static function boolToString($bool)
    {
        return $bool ? 'true' : 'false';
    }

    public static function filterSitesByName(array $sites, $filterRegex)
    {
        $arr = array();
        foreach ($sites as $sc) {
            if (!$sc instanceof SiteConfig) {
                continue;
            }
            if (preg_match("/$filterRegex/", $sc->name)) {
                array_push($arr, $sc);
            }
        }

        return $arr;
    }

    public static function findInArrayWithRegex(array $arr, $regex)
    {
        foreach ($arr as $a) {
            if (preg_match($regex, $a)) {
                return $a;
            }
        }

        return;
    }

    /**
     * @param array $sspages should contain only objects of class SilverstripePage
     *
     * @return array all SilverstripePages that match the regex
     */
    public static function filterPagesByModules(array $sspages, $filterRegex)
    {
        $arr = array();
        foreach ($sspages as $sspage) {
            if (!$sspage instanceof SilverstripePage) {
                continue;
            }
            if ($sspage->hasModule("/$filterRegex/")) {
                array_push($arr, $sspage);
            }
        }

        return $arr;
    }

    /**
     * @param $version "1.1.1" "2.1.4" "2.0"
     *
     * @return array [1,1,1] [2,1,4] [2,0,0]
     */
    public static function VersionStringsToArray($version)
    {
        $arr = array();
        $single = explode(".", $version);

        for ($i = 0;$i<3;$i++) {
            if (!isset($single[$i])) {
                $x = 0;
            } else {
                $x = intval($single[$i]);
            }
            array_push($arr, $x ? $x : 0);
        }

        return $arr;
    }

    public static function VersionStringsGreaterThenOrEqual($version1, $version2)
    {
        $v1 = Util::VersionStringsToArray($version1);
        $v2 = Util::VersionStringsToArray($version2);
        for ($i = 0; $i<3;$i++) {
            if ($v1[$i] < $v2[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * puts blanks at the end of a string until it reaches the minimum length.
     *
     * @param string $str
     * @param int    $min
     */
    public static function forceStringMinLength(&$str, $min)
    {
        while (strlen($str) < $min) {
            $str .= " ";
        }
    }

    public static function reportError($string,$die = true) {
        if($die) {
            exit($string);
        }else{
            echo($string);
        }
    }
}
