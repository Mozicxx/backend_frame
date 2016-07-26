<?php

/**
 * Description of MetaScore
 *
 * @author jiwei
 */
class MetaScore {

    /**
     * 计算两组tag相关度
     * @param type $arr1
     * @param type $arr2
     */
    public static function getscore(&$arr1, &$arr2) {
        $a = array();
        $b = array();
        $at = $bt = 1.0;
        $rate = 0;
        foreach ($arr1 as $a1) {
            $a[$a1["tag"]] = $a1["weight"];
            $at = $at + $a1["weight"];
        }
        $bt = 1;
        foreach ($arr2 as $a2) {
            $b[$a2["tag"]] = $a2["weight"];
            $bt = $bt + $a2["weight"];
        }

        foreach ($a as $k => $v) {
            if (isset($b[$k])) {
                $rate = $rate + $v / $at + $b[$k] / $bt;
            }
        }
        return $rate;
    }

}
