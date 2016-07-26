<?php

define('CRYPT_XXTEA_DELTA', 0x9E3779B9);
/**
 * The main class
 *
 * @category   Encryption
 * @package    Crypt_XXTEA
 * @author     Ma Bingyao <andot@ujn.edu.cn>
 * @author     Wudi Liu <wudicgi@yahoo.de>
 * @copyright  2005-2006 Coolcode.CN
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Crypt_XXTEA
 */
class CryptXXTEA {
    // {{{ properties

    /**
     * The long array of secret key
     *
     * @access private
     *
     * @var array
     */
    var $_key;

    // }}}
    // {{{ setKey()

    /**
     * Sets the secret key
     *
     * The key must be non-empty, and less than or equal to 16 characters
     *
     * @access public
     *
     * @param string $key  the secret key
     *
     * @return bool  true on success, PEAR_Error on failure
     */
    function setKey($key) {
        if (!is_string($key)) {
            throw new Exception('The secret key must be a string.');
        }
        $k = $this->_str2long($key, false);
        if (count($k) > 4) {
            throw new Exception('The secret key cannot be more than 16 characters.');
        } elseif (count($k) == 0) {
            throw new Exception('The secret key cannot be empty.');
        } elseif (count($k) < 4) {
            for ($i = count($k); $i < 4; $i++) {
                $k[$i] = 0;
            }
        }
        $this->_key = $k;
        return true;
    }

    // }}}
    // {{{ encrypt()

    /**
     * Encrypts a plain text
     *
     * @access public
     *
     * @param string $str  the plain text
     *
     * @return string  the cipher text on success, PEAR_Error on failure
     */
    function encrypt($str) {
        if (!is_string($str)) {
            throw new Exception('The plain text must be a string.');
        }
        if ($this->_key == null) {
            throw new Exception('Secret key is undefined.');
        }
        if ($str == '') {
            return '';
        }
        $v = $this->_str2long($str, true);
        $n = count($v) - 1;
        $z = $v[$n];
        $y = $v[0];
        $q = floor(6 + 52 / ($n + 1));
        $sum = 0;
        while (0 < $q--) {
            $sum = $this->_int32($sum + CRYPT_XXTEA_DELTA);
            $e = $sum >> 2 & 3;
            for ($p = 0; $p < $n; $p++) {
                $y = $v[$p + 1];
                $mx = $this->_int32((($z >> 5 & 0x07FFFFFF) ^ $y << 2) + (($y >> 3 & 0x1FFFFFFF) ^ $z << 4)) ^ $this->_int32(($sum ^ $y) + ($this->_key[$p & 3 ^ $e] ^ $z));
                $z = $v[$p] = $this->_int32($v[$p] + $mx);
            }
            $y = $v[0];
            $mx = $this->_int32((($z >> 5 & 0x07FFFFFF) ^ $y << 2) + (($y >> 3 & 0x1FFFFFFF) ^ $z << 4)) ^ $this->_int32(($sum ^ $y) + ($this->_key[$p & 3 ^ $e] ^ $z));
            $z = $v[$n] = $this->_int32($v[$n] + $mx);
        }
        return $this->_long2str($v, false);
    }

    // }}}
    // {{{ decrypt()

    /**
     * Decrypts a cipher text
     *
     * @access public
     *
     * @param string $str  the cipher text
     *
     * @return string  the plain text on success, PEAR_Error on failure
     */
    function decrypt($str) {
        if (!is_string($str)) {
            throw new Exception('The cipher text must be a string.');
        }
        if ($this->_key == null) {
            throw new Exception('Secret key is undefined.');
        }
        if ($str == '') {
            return '';
        }
        $v = $this->_str2long($str, false);
        $n = count($v) - 1;
        $z = $v[$n];
        $y = $v[0];
        $q = floor(6 + 52 / ($n + 1));
        $sum = $this->_int32($q * CRYPT_XXTEA_DELTA);
        while ($sum != 0) {
            $e = $sum >> 2 & 3;
            for ($p = $n; $p > 0; $p--) {
                $z = $v[$p - 1];
                $mx = $this->_int32((($z >> 5 & 0x07FFFFFF) ^ $y << 2) + (($y >> 3 & 0x1FFFFFFF) ^ $z << 4)) ^ $this->_int32(($sum ^ $y) + ($this->_key[$p & 3 ^ $e] ^ $z));
                $y = $v[$p] = $this->_int32($v[$p] - $mx);
            }
            $z = $v[$n];
            $mx = $this->_int32((($z >> 5 & 0x07FFFFFF) ^ $y << 2) + (($y >> 3 & 0x1FFFFFFF) ^ $z << 4)) ^ $this->_int32(($sum ^ $y) + ($this->_key[$p & 3 ^ $e] ^ $z));
            $y = $v[0] = $this->_int32($v[0] - $mx);
            $sum = $this->_int32($sum - CRYPT_XXTEA_DELTA);
        }
        return $this->_long2str($v, true);
    }

    // }}}
    // {{{ _long2str()

    /**
     * Converts long array to string
     *
     * @access private
     *
     * @param array $v  the long array
     * @param bool  $w  whether the long array contains the length of
     *                  original plain text
     *
     * @return string  the string
     */
    function _long2str($v, $w) {
        $len = count($v);
        $s = '';
        for ($i = 0; $i < $len; $i++) {
            $s .= pack('V', $v[$i]);
        }
        if ($w) {
            return substr($s, 0, $v[$len - 1]);
        } else {
            return $s;
        }
    }

    // }}}
    // {{{ _str2long()

    /**
     * Converts string to long array
     *
     * @access private
     *
     * @param string $s  the string
     * @param bool   $w  whether to append the length of string
     *
     * @return string  the long array
     */
    function _str2long($s, $w) {
        $v = array_values(unpack('V*', $s . str_repeat("\0", (4 - strlen($s) % 4) & 3)));
        if ($w) {
            $v[] = strlen($s);
        }
        return $v;
    }

    // }}}
    // {{{ _int32()

    /**
     * Fixes overflow problem
     *
     * @access private
     *
     * @param int $n  the integer
     *
     * @return int  the correct integer
     */
    function _int32($n) {
        while ($n >= 2147483648)
            $n -= 4294967296;
        while ($n <= -2147483649)
            $n += 4294967296;
        return (int) $n;
    }

}
