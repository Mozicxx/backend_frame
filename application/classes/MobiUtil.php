<?php

/**
 * 手机号获取地区
 *
 * @author jiwei
 */
class MobiUtil {

    static $citys = array("北京", "天津", "河北", "山西", "内蒙古", "辽宁", "吉林", "黑龙江", "上海", "江苏", "浙江", "安徽", "福建", "江西", "山东", "河南", "湖北", "湖南", "广东", "广西", "海南", "四川", "贵州", "云南", "西藏", "陕西", "甘肃", "青海", "宁夏", "新疆", "重庆");

    public static function getcity($tel) {
        $city = DB::select("province", "city")->from("dm_mobile")->where("mobile", "=", substr($tel, 0, 7))->execute("payment")->current();
        return $city["province"];
    }

    public static function gettel($imsi) {
        if (!$imsi) {
            return null;
        }
        $mob = DB::select("mobile")->from("dm_imsi")->where("imsi", "=", $imsi)->execute("payment")->current();
        try {
            if ($mob["mobile"]) {
                return $mob["mobile"];
            } else {
                $mob = Request::factory("http://115.28.47.179:8020/utils/mobile?imsi=$imsi")->execute()->body();
                return $mob;
            }
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * 根据 ICCID中地区位取城市
     * @param type $ac
     * @return type
     */
    public static function getcitybyac($ac) {
        if (strlen($ac) == 2) {//CMCC ICCID
            $ac = intval($ac);
            if ($ac > 0 && $ac < 32) {
                return self::$citys[$ac - 1];
            }
        } elseif (strlen($ac) == 3) {//CTCC ICCID
            if (intval($ac) > 99) {
                $ac = "0" . $ac;
            }
            $city = DB::select("province", "city")->from("dm_mobile")->where("area_code", "=", $ac)->limit(1)->execute("payment")->current();
            return $city["province"];
        }
        return NULL;
    }

}
