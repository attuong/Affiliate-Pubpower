<?php

/**
 * Convert String to Array
 * @param type $str
 * @param type $delimiter
 * @return type
 */
if (!function_exists('strToArr')) {

    function strToArr($str, $delimiter = ",") {
        $returns = array();
        $arr = explode($delimiter, $str);
        if ($arr) {
            foreach (array_unique($arr) as $value) {
                $_value = strtolower(trim($value));
                if ($_value && !in_array($_value, $returns)) {
                    $returns[] = $_value;
                }
            }
        }
        return $returns;
    }

}

/**
 * Get Client IP
 * @return type
 */
if (!function_exists('getClientIp')) {

    function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}

/**
 * 
 * @param type $limit
 * @return type
 */
if (!function_exists('buildPaginationData')) {

    function buildPaginationData($limit, $page = false) {
        $return['limit'] = $limit;
        if (!$page) {
            $return['page'] = core\Input::getInstance()->get('page') ? core\Input::getInstance()->get('page') : 1;
        } else {
            $return['page'] = $page;
        }
        $return['skip'] = ($return['page'] - 1) * $return['limit'];
        $return['start'] = $return['page'] * $return['limit'] - $return['limit'];
        $return['limit_string'] = "{$return['start']},{$return['limit']}";
        return $return;
    }

}

/**
 * 
 * @param type $string
 * @param type $length
 * @return type
 */
if (!function_exists('truncate')) {

    function truncate($string, $length = 80) {
        $strlen = strlen(remove_unicode($string));
        if ($strlen <= $length) {
            return $string;
        } else {
            mb_internal_encoding("UTF-8");
            return mb_substr($string, 0, $length) . "...";
        }
    }

}

/**
 * 
 * @param type $url
 * @param type $timeout
 * @param type $referer
 * @param type $USERAGENT
 * @return type
 */
if (!function_exists('curlGet')) {

    function curlGet($url, $timeout = 3600, $referer = false, $USERAGENT = false) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($USERAGENT) {
            curl_setopt($curl, CURLOPT_USERAGENT, $USERAGENT);
        }
        if ($referer) {
            curl_setopt($curl, CURLOPT_REFERER, $referer);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $dataReturn = curl_exec($curl);
        curl_close($curl);
        return $dataReturn;
    }

}

/**
 * 
 * @param type $url
 * @param type $field
 * @param type $timeout
 * @param type $referer
 * @param type $USERAGENT
 * @return type
 */
if (!function_exists('curlPost')) {

    function curlPost($url, $field = array(), $timeout = 3600, $referer = false, $USERAGENT = false) {
        $post = $field ? http_build_query($field) : '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($USERAGENT) {
            curl_setopt($curl, CURLOPT_USERAGENT, $USERAGENT);
        }
        if ($referer) {
            curl_setopt($curl, CURLOPT_REFERER, $referer);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $dataReturn = curl_exec($curl);
        curl_close($curl);
        return $dataReturn;
    }

}

/**
 * Redirect
 * @param type $url
 */
if (!function_exists('redirect')) {

    function redirect($url, $replace = TRUE, $http_response_code = 301) {
        header("Location: $url", $replace, $http_response_code);
        exit();
    }

}

/**
 * Get Current url
 * @return string
 */
if (!function_exists('current_url')) {

    function current_url() {
        $protocol = getProtocol();
        $pageURL = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }

}

/**
 * 
 * @param type $text
 * @return type
 */
if (!function_exists('build_slug')) {

    function build_slug($text) {

        $text = htmlspecialchars(trim(strip_tags($text)));

        $CLEAN_URL_REGEX = '*([\s$+,/:=\?@"\'<>%{}|\\^~[\]`\r\n\t\x00-\x1f\x7f]|(?(?<!&)#|#(?![0-9]+;))|&(?!#[0-9]+;)|(?<!&#\d|&#\d{2}|&#\d{3}|&#\d{4}|&#\d{5});)*s';
        $text = preg_replace($CLEAN_URL_REGEX, '-', strip_tags($text));
        $text = trim(preg_replace('#-+#', '-', $text), '-');

        $code_entities_match = array('\\', '&quot;', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '{', '}', '|', ':', '"', '<', '>', '?', '[', ']', '', ';', "'", ',', '.', '_', '/', '*', '+', '~', '`', '=', ' ', '---', '--', '--');
        $code_entities_replace = array('', '', '-', '-', '', '', '', '-', '-', '', '', '', '', '', '', '', '-', '', '', '', '', '', '', '', '', '', '-', '', '-', '-', '', '', '', '', '', '-', '-', '-', '-');
        $text = str_replace($code_entities_match, $code_entities_replace, $text);

        $chars = array("a", "A", "e", "E", "o", "O", "u", "U", "i", "I", "d", "D", "y", "Y");
        $uni [0] = array("á", "à", "ạ", "ả", "ã", "â", "ấ", "ầ", "ậ", "ẩ", "ẫ", "ă", "ắ", "ằ", "ặ", "ẳ", "� �", "ả", "á", "ặ");
        $uni [1] = array("Á", "À", "Ạ", "Ả", "Ã", "Â", "Ấ", "Ầ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ắ", "Ằ", "Ặ", "Ẳ", "� �");
        $uni [2] = array("é", "è", "ẹ", "ẻ", "ẽ", "ê", "ế", "ề", "ệ", "ể", "ễ", "ệ");
        $uni [3] = array("É", "È", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ế", "Ề", "Ệ", "Ể", "Ễ");
        $uni [4] = array("ó", "ò", "ọ", "ỏ", "õ", "ô", "ố", "ồ", "ộ", "ổ", "ỗ", "ơ", "ớ", "ờ", "ợ", "ở", "� �");
        $uni [5] = array("Ó", "Ò", "Ọ", "Ỏ", "Õ", "Ô", "Ố", "Ồ", "Ộ", "Ổ", "Ỗ", "Ơ", "Ớ", "Ờ", "Ợ", "Ở", "� �");
        $uni [6] = array("ú", "ù", "ụ", "ủ", "ũ", "ư", "ứ", "ừ", "ự", "ử", "ữ", "ù");
        $uni [7] = array("Ú", "Ù", "Ụ", "Ủ", "Ũ", "Ư", "Ứ", "Ừ", "Ự", "Ử", "Ữ");
        $uni [8] = array("í", "ì", "ị", "ỉ", "ĩ");
        $uni [9] = array("Í", "Ì", "Ị", "Ỉ", "Ĩ");
        $uni [10] = array("đ");
        $uni [11] = array("Đ");
        $uni [12] = array("ý", "ỳ", "ỵ", "ỷ", "ỹ");
        $uni [13] = array("Ý", "Ỳ", "Ỵ", "Ỷ", "Ỹ");

        for ($i = 0; $i <= 13; $i++) {
            $text = str_replace($uni[$i], $chars[$i], $text);
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyz-';
        $textReturn = '';
        for ($i = 0; $i <= strlen($text); $i++) {
            if (isset($text[$i])) {
                //$text[$i] = strtolower($text[$i]);
                if (preg_match("/{$text[$i]}/i", $characters)) {
                    $textReturn .= $text[$i];
                }
            }
        }

        $textReturn = strtolower($textReturn);
        return $textReturn;
    }

}


/**
 *
 * @param type $text
 * @return type
 */
if (!function_exists('build_slugify')) {

    function build_slugify($text, $option = []) {
        $text = str_replace("'", '', $text);
        $text = str_replace("’", '', $text);
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '_');
        $text = preg_replace('~-+~', '_', $text);
        $text = in_array('NotLower', $option) ? $text : strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}


/**
 * 
 * @param type $_text
 * @return type
 */
if (!function_exists('remove_unicode')) {

    function remove_unicode($_text) {
        $text = htmlspecialchars(trim(strip_tags($_text)));
        $chars = array("a", "A", "e", "E", "o", "O", "u", "U", "i", "I", "d", "D", "y", "Y");
        $uni [0] = array("á", "à", "ạ", "ả", "ã", "â", "ấ", "ầ", "ậ", "ẩ", "ẫ", "ă", "ắ", "ằ", "ặ", "ẳ", "� �", "ả", "á", "ặ");
        $uni [1] = array("Á", "À", "Ạ", "Ả", "Ã", "Â", "Ấ", "Ầ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ắ", "Ằ", "Ặ", "Ẳ", "� �", "Ạ", "Á", "À", "Ã", "Ả");
        $uni [2] = array("é", "è", "ẹ", "ẻ", "ẽ", "ê", "ế", "ề", "ệ", "ể", "ễ", "ệ");
        $uni [3] = array("É", "È", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ế", "Ề", "Ệ", "Ể", "Ễ", "É", "Ẽ");
        $uni [4] = array("ó", "ò", "ọ", "ỏ", "õ", "ô", "ố", "ồ", "ộ", "ổ", "ỗ", "ơ", "ớ", "ờ", "ợ", "ở", "� �");
        $uni [5] = array("Ó", "Ò", "Ọ", "Ỏ", "Õ", "Ô", "Ố", "Ồ", "Ộ", "Ổ", "Ỗ", "Ơ", "Ớ", "Ờ", "Ợ", "Ở", "� �", "Ọ", "Õ");
        $uni [6] = array("ú", "ù", "ụ", "ủ", "ũ", "ư", "ứ", "ừ", "ự", "ử", "ữ", "ù");
        $uni [7] = array("Ú", "Ù", "Ụ", "Ủ", "Ũ", "Ư", "Ứ", "Ừ", "Ự", "Ử", "Ữ", "Ú", "Ũ");
        $uni [8] = array("í", "ì", "ị", "ỉ", "ĩ");
        $uni [9] = array("Í", "Ì", "Ị", "Ỉ", "Ĩ", "Ỉ", "Ì", "Ĩ", "Í", "Ị");
        $uni [10] = array("đ");
        $uni [11] = array("Đ");
        $uni [12] = array("ý", "ỳ", "ỵ", "ỷ", "ỹ");
        $uni [13] = array("Ý", "Ỳ", "Ỵ", "Ỷ", "Ỹ");
        for ($i = 0; $i <= 13; $i++) {
            $text = str_replace($uni[$i], $chars[$i], $text);
        }
        return $text;
    }

}

/**
 * Displays structured information about one or more expressions that includes its type and value. 
 * 
 * @param mixed $str Structure to display.
 * @return void
 */
if (!function_exists('dump')) {

    function dump($args) {
        include_once ROOT_PATH . '/cores/class.dumper.php';
        ob_start();
        @header('Content-Type: text/html; charset=utf-8');
        $args = func_get_args();
        foreach ($args as $arg) {
            Dumper::dump($arg);
        }
    }

}