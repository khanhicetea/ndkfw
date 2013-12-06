<?php
namespace Security;
defined('DS') or die();

class KSES5 {

    private $allowed_protocols;
    private $allowed_html;

    public function __construct() {
        $this->allowed_protocols = array(
            'http',
            'ftp',
            'mailto'
        );
        $this->allowed_html = array();
    }

    public function Parse($string = "") {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        $string = $this->removeNulls($string);
        $string = preg_replace('%&\s*\{[^}]*(\}\s*;?|$)%', '', $string);
        $string = $this->normalizeEntities($string);
        $string = $this->filterKsesTextHook($string);
        $string = preg_replace('%(<' . '[^>]*' . '(>|$)' . '|>)%e', "\$this->stripTags('\\1')", $string);
        return $string;
    }

    public function AddProtocols() {
        $c_args = func_num_args();
        if ($c_args != 1) {
            trigger_error("kses5::AddProtocols() did not receive an argument.", E_USER_WARNING);
            return false;
        }
        $protocol_data = func_get_arg(0);
        if (is_array($protocol_data) && count($protocol_data) > 0) {
            foreach ($protocol_data as $protocol) {
                $this->AddProtocol($protocol);
            }
            return true;
        } elseif (is_string($protocol_data)) {
            $this->AddProtocol($protocol_data);
            return true;
        } else {
            trigger_error("kses5::AddProtocols() did not receive a string or an array.", E_USER_WARNING);
            return false;
        }
    }

    public function Protocols() {
        $c_args = func_num_args();
        if ($c_args != 1) {
            trigger_error("kses5::Protocols() did not receive an argument.", E_USER_WARNING);
            return false;
        }
        return $this->AddProtocols(func_get_arg(0));
    }

    public function AddProtocol($protocol = "") {
        if (!is_string($protocol)) {
            trigger_error("kses5::AddProtocol() requires a string.", E_USER_WARNING);
            return false;
        }
        if (substr($protocol, strlen($protocol) - 1, 1) == ":") {
            $protocol = substr($protocol, 0, strlen($protocol) - 1);
        }
        $protocol = strtolower(trim($protocol));
        if ($protocol == "") {
            trigger_error("kses5::AddProtocol() tried to add an empty/NULL protocol.", E_USER_WARNING);
            return false;
        }
        if (!in_array($protocol, $this->allowed_protocols)) {
            array_push($this->allowed_protocols, $protocol);
            sort($this->allowed_protocols);
        }
        return true;
    }

    public function RemoveProtocol($protocol = "") {
        if (!is_string($protocol)) {
            trigger_error("kses5::RemoveProtocol() requires a string.", E_USER_WARNING);
            return false;
        }
        if (substr($protocol, strlen($protocol) - 1, 1) == ":") {
            $protocol = substr($protocol, 0, strlen($protocol) - 1);
        }
        $protocol = strtolower(trim($protocol));
        if ($protocol == "") {
            trigger_error("kses5::RemoveProtocol() tried to remove an empty/NULL protocol.", E_USER_WARNING);
            return false;
        }
        if (in_array($protocol, $this->allowed_protocols)) {
            $this->allowed_protocols = array_diff($this->allowed_protocols, array(
                $protocol
            ));
            sort($this->allowed_protocols);
        }
        return true;
    }

    public function RemoveProtocols() {
        $c_args = func_num_args();
        if ($c_args != 1) {
            return false;
        }
        $protocol_data = func_get_arg(0);
        if (is_array($protocol_data) && count($protocol_data) > 0) {
            foreach ($protocol_data as $protocol) {
                $this->RemoveProtocol($protocol);
            }
        } elseif (is_string($protocol_data)) {
            $this->RemoveProtocol($protocol_data);
            return true;
        } else {
            trigger_error("kses5::RemoveProtocols() did not receive a string or an array.", E_USER_WARNING);
            return false;
        }
    }

    public function SetProtocols() {
        $c_args = func_num_args();
        if ($c_args != 1) {
            trigger_error("kses5::SetProtocols() did not receive an argument.", E_USER_WARNING);
            return false;
        }
        $protocol_data = func_get_arg(0);
        if (is_array($protocol_data) && count($protocol_data) > 0) {
            $this->allowed_protocols = array();
            foreach ($protocol_data as $protocol) {
                $this->AddProtocol($protocol);
            }
            return true;
        } elseif (is_string($protocol_data)) {
            $this->allowed_protocols = array();
            $this->AddProtocol($protocol_data);
            return true;
        } else {
            trigger_error("kses5::SetProtocols() did not receive a string or an array.", E_USER_WARNING);
            return false;
        }
    }

    public function DumpProtocols() {
        return $this->allowed_protocols;
    }

    public function DumpElements() {
        return $this->allowed_html;
    }

    public function AddHTML($tag = "", $attribs = array()) {
        if (!is_string($tag)) {
            trigger_error("kses5::AddHTML() requires the tag to be a string", E_USER_WARNING);
            return false;
        }
        $tag = strtolower(trim($tag));
        if ($tag == "") {
            trigger_error("kses5::AddHTML() tried to add an empty/NULL tag", E_USER_WARNING);
            return false;
        }
        if (!is_array($attribs)) {
            trigger_error("kses5::AddHTML() requires an array (even an empty one) of attributes for '$tag'", E_USER_WARNING);
            return false;
        }
        $new_attribs = array();
        if (is_array($attribs) && count($attribs) > 0) {
            foreach ($attribs as $idx1 => $val1) {
                $new_idx1 = strtolower($idx1);
                $new_val1 = $attribs[$idx1];
                if (is_array($new_val1) && count($attribs) > 0) {
                    $tmp_val = array();
                    foreach ($new_val1 as $idx2 => $val2) {
                        $new_idx2 = strtolower($idx2);
                        $tmp_val[$new_idx2] = $val2;
                    }
                    $new_val1 = $tmp_val;
                }
                $new_attribs[$new_idx1] = $new_val1;
            }
        }
        $this->allowed_html[$tag] = $new_attribs;
        return true;
    }

    private function removeNulls($string) {
        $string = preg_replace('/\0+/', '', $string);
        $string = preg_replace('/(\\\\0)+/', '', $string);
        return $string;
    }

    private function normalizeEntities($string) {
        $string = str_replace('&', '&amp;', $string);
        $string = preg_replace('/&amp;([A-Za-z][A-Za-z0-9]{0,19});/', '&\\1;', $string);
        $string = preg_replace('/&amp;#0*([0-9]{1,5});/e', '\$this->normalizeEntities16bit("\\1")', $string);
        $string = preg_replace('/&amp;#([Xx])0*(([0-9A-Fa-f]{2}){1,2});/', '&#\\1\\2;', $string);
        return $string;
    }

    private function normalizeEntities16bit($i) {
        return (($i > 65535) ? "&amp;#$i;" : "&#$i;");
    }

    private function filterKsesTextHook($string) {
        return $string;
    }

    private function _hook($string) {
        return $this->filterKsesTextHook($string);
    }

    private function makeArrayKeysLowerCase($in_array) {
        $out_array = array();
        if (is_array($in_array) && count($in_array) > 0) {
            foreach ($in_array as $in_key => $in_val) {
                $out_key = strtolower($in_key);
                $out_array[$out_key] = array();
                if (is_array($in_val) && count($in_val) > 0) {
                    foreach ($in_val as $in_key2 => $in_val2) {
                        $out_key2 = strtolower($in_key2);
                        $out_array[$out_key][$out_key2] = $in_val2;
                    }
                }
            }
        }
        return $out_array;
    }

    private function stripTags($string) {
        $string = preg_replace('%\\\\"%', '"', $string);
        if (substr($string, 0, 1) != '<') {
            return '&gt;';
        }
        if (!preg_match('%^<\s*(/\s*)?([a-zA-Z0-9]+)([^>]*)>?$%', $string, $matches)) {
            return '';
        }
        $slash = trim($matches[1]);
        $elem = $matches[2];
        $attrlist = $matches[3];
        if (!isset($this->allowed_html[strtolower($elem)]) || !is_array($this->allowed_html[strtolower($elem)])) {
            return '';
        }
        if ($slash != '') {
            return "<$slash$elem>";
        }
        return $this->stripAttributes("$slash$elem", $attrlist);
    }

    private function stripAttributes($element, $attr) {
        $xhtml_slash = '';
        if (preg_match('%\s/\s*$%', $attr)) {
            $xhtml_slash = ' /';
        }
        if (!isset($this->allowed_html[strtolower($element)]) || count($this->allowed_html[strtolower($element)]) == 0) {
            return "<$element$xhtml_slash>";
        }
        $attrarr = $this->combAttributes($attr);
        $attr2 = '';
        if (is_array($attrarr) && count($attrarr) > 0) {
            foreach ($attrarr as $arreach) {
                if (!isset($this->allowed_html[strtolower($element)][strtolower($arreach['name'])])) {
                    continue;
                }
                $current = $this->allowed_html[strtolower($element)][strtolower($arreach['name'])];
                if (!is_array($current)) {
                    $attr2 .= ' ' . $arreach['whole'];
                } else {
                    $ok = true;
                    if (is_array($current) && count($current) > 0) {
                        foreach ($current as $currkey => $currval) {
                            if (!$this->checkAttributeValue($arreach['value'], $arreach['vless'], $currkey, $currval)) {
                                $ok = false;
                                break;
                            }
                        }
                    }
                    if ($ok) {
                        $attr2 .= ' ' . $arreach['whole'];
                    }
                }
            }
        }
        $attr2 = preg_replace('/[<>]/', '', $attr2);
        return "<$element$attr2$xhtml_slash>";
    }

    private function combAttributes($attr) {
        $attrarr = array();
        $mode = 0;
        $attrname = '';
        while (strlen($attr) != 0) {
            $working = 0;
            switch ($mode) {
                case 0:
                    if (preg_match('/^([-a-zA-Z]+)/', $attr, $match)) {
                        $attrname = $match[1];
                        $working = $mode = 1;
                        $attr = preg_replace('/^[-a-zA-Z]+/', '', $attr);
                    }
                    break;
                case 1:
                    if (preg_match('/^\s*=\s*/', $attr)) {
                        $working = 1;
                        $mode = 2;
                        $attr = preg_replace('/^\s*=\s*/', '', $attr);
                        break;
                    }
                    if (preg_match('/^\s+/', $attr)) {
                        $working = 1;
                        $mode = 0;
                        $attrarr[] = array(
                            'name' => $attrname,
                            'value' => '',
                            'whole' => $attrname,
                            'vless' => 'y'
                        );
                        $attr = preg_replace('/^\s+/', '', $attr);
                    }
                    break;
                case 2:
                    if (preg_match('/^"([^"]*)"(\s+|$)/', $attr, $match)) {
                        $thisval = $this->removeBadProtocols($match[1]);
                        $attrarr[] = array(
                            'name' => $attrname,
                            'value' => $thisval,
                            'whole' => $attrname . '="' . $thisval . '"',
                            'vless' => 'n'
                        );
                        $working = 1;
                        $mode = 0;
                        $attr = preg_replace('/^"[^"]*"(\s+|$)/', '', $attr);
                        break;
                    }
                    if (preg_match("/^'([^']*)'(\s+|$)/", $attr, $match)) {
                        $thisval = $this->removeBadProtocols($match[1]);
                        $attrarr[] = array(
                            'name' => $attrname,
                            'value' => $thisval,
                            'whole' => "$attrname='$thisval'",
                            'vless' => 'n'
                        );
                        $working = 1;
                        $mode = 0;
                        $attr = preg_replace("/^'[^']*'(\s+|$)/", '', $attr);
                        break;
                    }
                    if (preg_match("%^([^\s\"']+)(\s+|$)%", $attr, $match)) {
                        $thisval = $this->removeBadProtocols($match[1]);
                        $attrarr[] = array(
                            'name' => $attrname,
                            'value' => $thisval,
                            'whole' => $attrname . '="' . $thisval . '"',
                            'vless' => 'n'
                        );
                        $working = 1;
                        $mode = 0;
                        $attr = preg_replace("%^[^\s\"']+(\s+|$)%", '', $attr);
                    }
                    break;
            }
            if ($working == 0) {
                $attr = preg_replace('/^("[^"]*("|$)|\'[^\']*(\'|$)|\S)*\s*/', '', $attr);
                $mode = 0;
            }
        }
        if ($mode == 1) {
            $attrarr[] = array(
                'name' => $attrname,
                'value' => '',
                'whole' => $attrname,
                'vless' => 'y'
            );
        }
        return $attrarr;
    }

    private function removeBadProtocols($string) {
        $string = $this->RemoveNulls($string);
        $string = preg_replace('/\xad+/', '', $string);
        $string2 = $string . 'a';
        while ($string != $string2) {
            $string2 = $string;
            $string = preg_replace('/^((&[^;]*;|[\sA-Za-z0-9])*)' . '(:|&#58;|&#[Xx]3[Aa];)\s*/e', '\$this->filterProtocols("\\1")', $string);
        }
        return $string;
    }

    private function filterProtocols($string) {
        $string = $this->decodeEntities($string);
        $string = preg_replace('/\s/', '', $string);
        $string = $this->removeNulls($string);
        $string = preg_replace('/\xad+/', '', $string2);
        $string = strtolower($string);
        if (is_array($this->allowed_protocols) && count($this->allowed_protocols) > 0) {
            foreach ($this->allowed_protocols as $one_protocol) {
                if (strtolower($one_protocol) == $string) {
                    return "$string:";
                }
            }
        }
        return '';
    }

    private function checkAttributeValue($value, $vless, $checkname, $checkvalue) {
        $ok = true;
        $check_attribute_method_name = 'checkAttributeValue' . ucfirst(strtolower($checkname));
        if (method_exists($this, $check_attribute_method_name)) {
            $ok = $this->$check_attribute_method_name($value, $checkvalue, $vless);
        }
        return $ok;
    }

    private function checkAttributeValueMaxlen($value, $checkvalue) {
        if (strlen($value) > intval($checkvalue)) {
            return false;
        }
        return true;
    }

    private function checkAttributeValueMinlen($value, $checkvalue) {
        if (strlen($value) < intval($checkvalue)) {
            return false;
        }
        return true;
    }

    private function checkAttributeValueMaxval($value, $checkvalue) {
        if (!preg_match('/^\s{0,6}[0-9]{1,6}\s{0,6}$/', $value)) {
            return false;
        }
        if (intval($value) > intval($checkvalue)) {
            return false;
        }
        return true;
    }

    private function checkAttributeValueMinval($value, $checkvalue) {
        if (!preg_match('/^\s{0,6}[0-9]{1,6}\s{0,6}$/', $value)) {
            return false;
        }
        if (intval($value) < ($checkvalue)) {
            return false;
        }
        return true;
    }

    private function checkAttributeValueValueless($value, $checkvalue, $vless) {
        if (strtolower($checkvalue) != $vless) {
            return false;
        }
        return true;
    }

    private function decodeEntities($string) {
        $string = preg_replace('/&#([0-9]+);/e', 'chr("\\1")', $string);
        $string = preg_replace('/&#[Xx]([0-9A-Fa-f]+);/e', 'chr(hexdec("\\1"))', $string);
        return $string;
    }

    public function Version() {
        return 'PHP5 OOP 1.0.2';
    }

}
