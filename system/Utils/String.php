<?php
namespace Utils;
defined('DS') or die();

class String {

    public static function random($length = 8, $alpha = true, $number = true, $specialChar = false) {
        $chars = '';
        if ($alpha == true) {
            $chars .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($number == true) {
            $chars .= '0123456789';
        }
        if ($specialChar == true) {
            $chars .= '!@#$%^&*()_+{}:<>?';
        }

        $size = strlen($chars);
        $rnd_str = '';

        for ($i = 0; $i < $length; $i++) {
            $rnd_str .= $chars[rand(0, $size - 1)];
        }

        return $rnd_str;
    }

    public static function limitCharacters($value, $limit, $suffix = '...') {
        return substr($value, 0, $limit) . (strlen($value) > $limit ? ' ' . $suffix : '');
    }

    public static function limitWords($value, $limit, $suffix = '...') {
        $end_pos = 0;

        for ($i = 0; $i < $limit && $end_pos !== false; $i++) {
            $end_pos = strpos($value, ' ', $end_pos + 1);
        }

        if ($end_pos === false) {
            return $value;
        }

        return substr($value, 0, $end_pos + 1) . ' ' . $suffix;
    }

    public static function toAnsi($value) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $value);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    public static function toUrl($value) {
        $str = strtolower(self::toAnsi($value));
        $str = str_replace(' ', '-', $str);
        $str = str_replace('_', '-', $str);
        $str = preg_replace("/[^A-Za-z0-9\-]/", '', $str);

        return $str;
    }

}
