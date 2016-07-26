<?php

/**
 * Description of CodeLog
 *
 * @author jiwei
 */
class CodeLog {

    public static function add($filename, $log) {
        $filename = "log/code/" . $filename;
        file_put_contents($filename, $log . PHP_EOL, FILE_APPEND);
    }

    public static function logerr($odid, $fee_channel_id, $sp_name, $err) {
         $data = array(
            "odid" => $odid,
            "fee_channel_id" => $fee_channel_id,
            "sp_name" => $sp_name,
            "err" => $err);
        DB::insert("log_error")->columns(array_keys($data))->values(array_values($data))->execute("payment");
    }

}
