<?php
namespace Security;
defined('DS') or die();
use \Utils\String;

class Security {

    private $kses = null;
    private $kses_allowed_tags = array(
        'address' => array(),
        'a' => array(
            'href' => true,
            'rel' => true,
            'rev' => true,
            'name' => true,
            'target' => true,
        ),
        'abbr' => array(),
        'acronym' => array(),
        'area' => array(
            'alt' => true,
            'coords' => true,
            'href' => true,
            'nohref' => true,
            'shape' => true,
            'target' => true,
        ),
        'article' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'aside' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'b' => array(),
        'big' => array(),
        'blockquote' => array(
            'cite' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'br' => array(),
        'button' => array(
            'disabled' => true,
            'name' => true,
            'type' => true,
            'value' => true,
        ),
        'caption' => array(
            'align' => true,
        ),
        'cite' => array(
            'dir' => true,
            'lang' => true,
        ),
        'code' => array(),
        'col' => array(
            'align' => true,
            'char' => true,
            'charoff' => true,
            'span' => true,
            'dir' => true,
            'valign' => true,
            'width' => true,
        ),
        'del' => array(
            'datetime' => true,
        ),
        'dd' => array(),
        'details' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'open' => true,
            'xml:lang' => true,
        ),
        'div' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'dl' => array(),
        'dt' => array(),
        'em' => array(),
        'fieldset' => array(),
        'figure' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'figcaption' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'font' => array(
            'color' => true,
            'face' => true,
            'size' => true,
        ),
        'footer' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'h1' => array(
            'align' => true,
        ),
        'h2' => array(
            'align' => true,
        ),
        'h3' => array(
            'align' => true,
        ),
        'h4' => array(
            'align' => true,
        ),
        'h5' => array(
            'align' => true,
        ),
        'h6' => array(
            'align' => true,
        ),
        'header' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'hgroup' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'hr' => array(
            'align' => true,
            'noshade' => true,
            'size' => true,
            'width' => true,
        ),
        'i' => array(),
        'img' => array(
            'alt' => true,
            'align' => true,
            'border' => true,
            'height' => true,
            'hspace' => true,
            'longdesc' => true,
            'vspace' => true,
            'src' => true,
            'usemap' => true,
            'width' => true,
        ),
        'ins' => array(
            'datetime' => true,
            'cite' => true,
        ),
        'kbd' => array(),
        'label' => array(
            'for' => true,
        ),
        'legend' => array(
            'align' => true,
        ),
        'li' => array(
            'align' => true,
        ),
        'map' => array(
            'name' => true,
        ),
        'menu' => array(
            'type' => true,
        ),
        'nav' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'p' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'pre' => array(
            'width' => true,
        ),
        'q' => array(
            'cite' => true,
        ),
        's' => array(),
        'span' => array(
            'dir' => true,
            'align' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'section' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'small' => array(),
        'strike' => array(),
        'strong' => array(),
        'sub' => array(),
        'summary' => array(
            'align' => true,
            'dir' => true,
            'lang' => true,
            'xml:lang' => true,
        ),
        'sup' => array(),
        'table' => array(
            'align' => true,
            'bgcolor' => true,
            'border' => true,
            'cellpadding' => true,
            'cellspacing' => true,
            'dir' => true,
            'rules' => true,
            'summary' => true,
            'width' => true,
        ),
        'tbody' => array(
            'align' => true,
            'char' => true,
            'charoff' => true,
            'valign' => true,
        ),
        'td' => array(
            'abbr' => true,
            'align' => true,
            'axis' => true,
            'bgcolor' => true,
            'char' => true,
            'charoff' => true,
            'colspan' => true,
            'dir' => true,
            'headers' => true,
            'height' => true,
            'nowrap' => true,
            'rowspan' => true,
            'scope' => true,
            'valign' => true,
            'width' => true,
        ),
        'tfoot' => array(
            'align' => true,
            'char' => true,
            'charoff' => true,
            'valign' => true,
        ),
        'th' => array(
            'abbr' => true,
            'align' => true,
            'axis' => true,
            'bgcolor' => true,
            'char' => true,
            'charoff' => true,
            'colspan' => true,
            'headers' => true,
            'height' => true,
            'nowrap' => true,
            'rowspan' => true,
            'scope' => true,
            'valign' => true,
            'width' => true,
        ),
        'thead' => array(
            'align' => true,
            'char' => true,
            'charoff' => true,
            'valign' => true,
        ),
        'tr' => array(
            'align' => true,
            'bgcolor' => true,
            'char' => true,
            'charoff' => true,
            'valign' => true,
        ),
        'tt' => array(),
        'u' => array(),
        'ul' => array(
            'type' => true,
        ),
        'ol' => array(
            'start' => true,
            'type' => true,
        )
    );
    
    private static $instance = null;
    
    private function __construct() {
        $this->disableMagicQuotes();
        $this->cleanGetInput();
        $this->unregisterGlobals();

        $this->kses = new KSES5();

        foreach ($this->kses_allowed_tags as $tag => $attr) {
            $this->kses->AddHTML($tag, $attr);
        }
    }
    
    private function __clone() {
        // No code here
    }
    
    private function __wakeup() {
        // No code here
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }

    public function hash($value, $salt, $time = null) {
        if ($time === null) {
            $time = (string) time();
        }

        $hash = md5($value);
        $salt = $salt;
        $hash_salt = md5($time . $salt . $time);

        for ($i = 0; $i < 10; $i++) {
            $k = intval($time[$i]) * ($i % 2 == 0 ? 2 : 3);
            $hash[$k] = $hash_salt[31 - $k];
        }

        for ($i = 0; $i < 10; $i++) {
            if ($i % 2 == 0) {
                $hash .= $time[$i];
            } else {
                $hash = $time[$i] . $hash;
            }
        }

        return $hash;
    }

    public function checkHash($value, $hash, $salt) {
        $time = '';

        for ($i = 0; $i < 10; $i++) {
            $k = ($i % 2 == 0) ? (37 + $i / 2) : (4 - intval($i / 2));
            $time .= $hash[$k];
        }

        return ($hash == $this->hash($value, $salt, $time));
    }

    private function disableMagicQuotes() {
        if (get_magic_quotes_gpc()) {
            $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
            while (list($key, $val) = each($process)) {
                foreach ($val as $k => $v) {
                    unset($process[$key][$k]);
                    if (is_array($v)) {
                        $process[$key][stripslashes($k)] = $v;
                        $process[] = &$process[$key][stripslashes($k)];
                    } else {
                        $process[$key][stripslashes($k)] = stripslashes($v);
                    }
                }
            }
            unset($process);
        }
    }

    private function cleanGetInput() {
        $pattern = array("/ /", "/0x27/", "/%0a/", "/%0A/", "/%0d/", "/%0D/", "/0x3a/",
            "/union/i", "/concat/i", "/truncate/i", "/alter/i", "/information_schema/i",
            "/unhex/i", "/load_file/i", "/outfile/i", "/0xbf27/", "/mysql/i", "/select/i", "/where/i");

        foreach ($_GET as $key => $value) {
            $_GET[$key] = addslashes(preg_replace($pattern, '', $value));
        }
    }

    private function unregisterGlobals() {
        if (!ini_get('register_globals')) {
            return;
        }

        if (isset($_REQUEST['GLOBALS'])) {
            __error__('GLOBALS overwrite attempt detected');
        }

        $no_unset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV',
            '_FILES', 'table_prefix');

        $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array() );

        foreach ($input as $k => $v) {
            if (!in_array($k, $no_unset) && isset($GLOBALS[$k])) {
                $GLOBALS[$k] = null;
                unset($GLOBALS[$k]);
            }
        }
    }

    public function getToken($name) {
        $token = String::random(rand(32, 64), true, true, false);
        __('session')->set('TOKEN_' . $name, $token, TRUE);

        return $token;
    }

    public function checkToken($name, $token) {
        $session_token = __('session')->get('TOKEN_' . $name);
        __('session')->remove('TOKEN_' . $name);

        return ($session_token == $token);
    }

    public function cleanXss($value) {
        return $this->kses->Parse($value);
    }
}