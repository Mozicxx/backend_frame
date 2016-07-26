<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserToken
 *
 * @author jiwei
 */
class UserToken {

    public static function encode($uid) {
        $tea = new CryptXXTEA();
        $tea->setKey("285B73BF6D78CDCA");
        $t = str_pad($uid, 10, 0, STR_PAD_LEFT) . rand(100, 999);
        return base64_encode($tea->encrypt($t));
    }

    public static function decode($token) {
        if (!$token) {
            return null;
        }
        $tea = new CryptXXTEA();
        $tea->setKey("285B73BF6D78CDCA");
        $e = $tea->decrypt(base64_decode($token));
        $u = intval(substr($e, 0, -3));
        return $u;
    }

}
