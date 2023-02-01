<?php

function showjson($object) {
    header('Content-Type: application/json');
    echo json_encode($object);
}

function is_valid_domain_name($domain_name) {
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name) ); //length of each label
}

function is_validate_domain_name($domain_name) {
//    return (preg_match("/^((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9]+\.[a-z]+(\/[a-zA-Z0-9#]+\/?)*$/", $domain_name));
    return (preg_match("/^((https?|ftp|smtp):\/\/)?(www.)?([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name));
}

function guestID() {
    if (isset($_COOKIE['guest_id']) && $_COOKIE['guest_id']) {
        return $_COOKIE['guest_id'];
    }
    $guest_id = getToken(6);
//    $expire = (time() + 3600 * 24 * 365); // 365 day
    $expire = 2147483647; // 365 day
    setcookie("guest_id", $guest_id, $expire, '/', '.' . DOMAIN);
    return $guest_id;
}

function user_query_string() {
    $has_query = TRUE;
    $query_string = $_SERVER['QUERY_STRING'];
    if (!$query_string) {
        $has_query = FALSE;
    }
    if ($has_query) {
        $pos = strpos($query_string, 'utm_source');
        if ($pos || $pos === 0) {
            $expire = time() + (86400 * 7); // 7 day
            setcookie("user_query_string", $query_string, $expire, '/', '.' . DOMAIN);
        } else {
            $has_query = FALSE;
        }
    }
    if (!$has_query) {
        if (isset($_COOKIE['user_query_string']) && $_COOKIE['user_query_string']) {
            $query_string = $_COOKIE['user_query_string'];
        }
    }
    $user_query_string = [];
    if ($query_string) {
        $query_string = urldecode($query_string);
        $query_string = explode('&', $query_string);
        if ($query_string) {
            foreach ($query_string as $value) {
                if ($value) {
                    $query = explode('=', $value);
                    if (count($query) == 2) {
                        $user_query_string[$query[0]] = $query[1];
                    }
                }
            }
        }
    }
    return $user_query_string;
}

function intervals($startDate, $endDate, $format = 'Y-m-d') {
    $date_from = strtotime($startDate);
    $date_to = strtotime($endDate) + 7200;
    $list = [];
    for ($i = $date_from; $i <= $date_to; $i += 86400) {
        $list[] = date($format, $i);
    }
    return $list;
}

function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 0) {
        return $min;
    } // not so random...
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, strlen($codeAlphabet))];
    }
    return $token;
}

function render_coupon($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "0123456789";
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, strlen($codeAlphabet))];
    }
    return $token;
}

/**
 * 
 * @param string $error
 * @return array
 */
function error($error) {
    return ['error' => $error];
}

/**
 * Render password from string input
 * @param string $password
 * @return string
 */
function render_password($password) {
    if (!$password) {
        return false;
    }
    $hanlde = md5(md5($password . KEY_HANDLE_PASSWORD));
    return $hanlde;
}

/**
 * Render password from string input
 * @param type $password
 * @return type
 */
function password_salt($password) {
    $salt = sha1(md5($password));
    $password = md5($password . $salt);
    return $password;
}

/**
 * Render token for user use login
 * @param int $user_id
 * @return string
 */
function render_login_token($user_id) {
    if (!$user_id) {
        return false;
    }
    $token = md5(md5($user_id . KEY_LOGIN_TOKEN));
    return $token;
}

function is_html($string) {
    // Check if string contains any html tags.
    return preg_match('/<\s?[^\>]*\/?\s?>/i', $string);
}

/**
 * 
 * @param string $id
 * @param string $message
 * @return boolean
 */
function set_message($id, $message) {
    return setcookie("message_manage_$id", serialize($message), time() + 5, '/', '.' . DOMAIN);
}

/**
 * 
 * @param string $id
 * @return array
 */
function get_message($id) {
    if (isset($_COOKIE["message_manage_$id"]) && $_COOKIE["message_manage_$id"]) {
        $return = unserialize($_COOKIE["message_manage_$id"]);
        setcookie("message_manage_$id", "", time() - 3600, '/', '.' . DOMAIN);
        return $return;
    }
    return [];
}

/**
 * 
 * @param string $id
 * @param string $message
 * @param second $time
 * @return boolean
 */
if (!function_exists('set_cookie')) {

    function set_cookie($id, $message, $time = 3600) {
        return setcookie("kukki_$id", serialize($message), time() + $time, '/', '.' . DOMAIN);
    }

}

/**
 * 
 * @param string $id
 * @return array
 */
if (!function_exists('get_cookie')) {

    function get_cookie($id) {
        if (isset($_COOKIE["kukki_$id"]) && $_COOKIE["kukki_$id"]) {
            $return = unserialize($_COOKIE["kukki_$id"]);
            return $return;
        }
        return [];
    }

}
/**
 * remove_cookie
 * @param string $id
 * @return boolean
 */
if (!function_exists('remove_cookie')) {

    function remove_cookie($id) {
        return setcookie("kukki_$id", "", time() - 3600, '/', '.' . DOMAIN);
    }

}

/**
 * https://www.binarymoon.co.uk/2012/02/complete-timthumb-parameters-guide/
 * @param string $src
 * @param int $width
 * @param int $height
 * @param array $more
 * @return string
 */
function build_image_url($src, $width = false, $height = false, $more = []) {
    $options['src'] = $src;
    if ($width) {
        $options['w'] = $width;
    }
    if ($height) {
        $options['h'] = $height;
    }
    $query = array_merge($options, $more);
    $url = '/resize/?' . http_build_query($query);
    return $url;
}

/**
 * 
 * @param string $url
 * @return boolean
 */
function check_img_exist($url) {
    $header = get_headers($url);
    if ($header[0] == 'HTTP/1.0 200 OK') {
        return true;
    } elseif ($header[0] == 'HTTP/1.1 302 Found') {
        $header2 = get_headers(str_replace(['Location: '], [''], $header[3]));
        if ($header2[0]) {
            return true;
        }
    }
    return false;
}

/**
 * 
 * @return type
 */
//function get_calling_class() {
//    //get the trace
//    $trace = debug_backtrace();
//    // Get the class that is asking for who awoke it
//    $class = $trace[1]['class'];
//    // +1 to i cos we have to account for calling this function
//    for ($i = 1; $i < count($trace); $i++) {
//        if (isset($trace[$i])) { // is it set?
//            if ($class != $trace[$i]['class']) { // is it a different class
//                return $trace[$i]['class'] . '::getInstant()->' . $trace[$i]['function'] . '();';
//            }
//        }
//    }
//}

/**
 * 
 * @param object $pagination
 * @return string
 */
function show_pagination_li_home($pagination) {
    $url = $pagination->link;
    $start = $pagination->start;
    $total = $pagination->total;
    $limit = $pagination->limit;
    $neighbors = 2;
    if ($start >= $total) {
        $start = max(0, $total - (($total % $limit) == 0 ? $limit : ($total % $limit)));
    } else {
        $start = max(0, (int) $start - ((int) $start % (int) $limit));
    }
    $base_link = '<li><a href="' . strtr($url, array('%' => '%%')) . 'page=%d' . '">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, $start / $limit, 'Prev');
    if ($start > $limit * $neighbors) {
        $out[] = sprintf($base_link, 1, '1');
    }
    if ($start > $limit * ($neighbors + 1)) {
        $out[] = '<li><span style="font-weight: bold;">...</span></li>';
    }
    for ($nCont = $neighbors; $nCont >= 1; $nCont--) {
        if ($start >= $limit * $nCont) {
            $tmpStart = $start - $limit * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $limit + 1, $tmpStart / $limit + 1);
        }
    }
    $out[] = '<li class="active"><span class="currentpage"><strong>' . ($start / $limit + 1) . '</strong></span></li>';
    $tmpMaxPages = (int) (($total - 1) / $limit) * $limit;
    for ($nCont = 1; $nCont <= $neighbors; $nCont++) {
        if ($start + $limit * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $limit * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $limit + 1, $tmpStart / $limit + 1);
        }
    }
    if ($start + $limit * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li><span style="font-weight: bold;">...</span></li>';
    }
    if ($start + $limit * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, $tmpMaxPages / $limit + 1, $tmpMaxPages / $limit + 1);
    }
    if ($start + $limit < $total) {
        $display_page = ($start + $limit) > $total ? $total : ($start / $limit + 2);
        $out[] = sprintf($base_link, $display_page, 'Next');
    }
    return implode(' ', $out);
}

function time_ago($time_ago) {
//    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return LANG_JUST_NOW;
    }
    //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return LANG_ONE_MINUTE_AGO;
        } else {
            return "$minutes " . LANG_MINUTE_AGO;
        }
    }
    //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return LANG_AN_HOUR_AGO;
        } else {
            return "$hours " . LANG_HOUR_AGO;
        }
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return LANG_YESTERDAY;
        } else {
            return "$days " . LANG_DAY_AGO;
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return LANG_A_WEEK_AGO;
        } else {
            return "$weeks " . LANG_WEEK_AGO;
        }
    }
    //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return LANG_A_MONTH_AGO;
        } else {
            return "$months " . LANG_MONTH_AGO;
        }
    }
    //Years
    else {
        if ($years == 1) {
            return LANG_ONE_YEAR_AGO;
        } else {
            return "$years " . LANG_YEAR_AGO;
        }
    }
}

/**
 * Chuyển timestamp thành các mốc thời gian
 * INPUT:
 * echo time_elapsed_string('2013-05-01 00:22:35');
 * echo time_elapsed_string('@1367367755'); # timestamp input
 * echo time_elapsed_string('2013-05-01 00:22:35', true);
 * OUTPUT:
 * 4 months ago
 * 4 months, 2 weeks, 3 days, 1 hour, 49 minutes, 15 seconds ago
 */
if (!function_exists('time_elapsed_string')) {

    function time_elapsed_string($datetime, $full = false) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = new DateTime;
        if (is_numeric($datetime)) {
            $datetime = date("Y-m-d H:i:s", $datetime);
        }
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'ngay bây giờ';
    }

}


/**
 * Checks if multiple keys exist in an array
 *
 * @param array $array
 * @param array|string $keys
 *
 * @return bool
 */
if (!function_exists('time_elapsed_string')) {

    function array_keys_exist(array $array, $keys) {
        $count = 0;
        if (!is_array($keys)) {
            $keys = func_get_args();
            array_shift($keys);
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $count ++;
            }
        }

        return count($keys) === $count;
    }

}

/**
 * Checks if string is JSON string
 * @input string $string
 * @return bool
 */
if (!function_exists('is_json')) {

    function is_json($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

}
/**
 * Tạo breadcrumb chuẩn SEO
 * @input array $data
 * @return html
 */
if (!function_exists('build_breadcrumb')) {

    function build_breadcrumb($data) {
        if (!isset($data) || !$data || count($data) == 0) {
            return '';
        }
        $result = '<nav itemscope itemtype="http://schema.org/Breadcrumb">';
        $result .= '    <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
        $count = 0;
        foreach ($data as $key => $value) {
            if ($count < count($data) - 1) {
                $result .= '        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
                $result .= '            <a itemprop="item" href="' . $value['url'] . '">';
                $result .= '                <span itemprop="name">' . $value['title'] . '</span>';
                $result .= '            </a>';
                $result .= '            <meta itemprop="position" content="' . ($key + 1) . '">';
                $result .= '        </li>';
            } else {
                $result .= '        <li class="active">';
                $result .= '                <span>' . $value['title'] . '</span>';
                $result .= '        </li>';
            }
            $count++;
        }
        $result .= '    </ol>';
        $result .= '</nav>';
        return $result;
    }

}
/**
 * ---
 */
if (!function_exists('build_product_url')) {

    function build_product_url($category_slug, $product_slug) {
        $url = "/" . $category_slug . "/" . $product_slug . "/";
        return $url;
    }

}
/**
 * ---
 */
if (!function_exists('build_category_url')) {

    function build_category_url($category_name) {
        $url = "/danh-muc/" . build_slug($category_name) . "/";
        return $url;
    }

}
/**
 * ---
 */
if (!function_exists('build_category_url_from_slug')) {

    function build_category_url_from_slug($category_slug) {
        $url = "/" . $category_slug . "/";
        return $url;
    }

}
/**
 * ---
 */
if (!function_exists('build_page_url')) {

    function build_page_url($page_slug) {
        $url = "/page/" . $page_slug . "/";
        return $url;
    }

}
/**
 * ---
 */
if (!function_exists('build_policy_page_url')) {

    function build_policy_page_url($page_slug) {
        $url = "/chinh-sach/" . $page_slug . "/";
        return $url;
    }

}

/**
 * Generator news url
 * @input string $category_slug, $name
 * @return url
 */
if (!function_exists('build_news_url')) {

    function build_news_url($category_slug, $news_slug) {
        return "/blog/" . $category_slug . '/' . $news_slug . "/";
    }

}

/**
 * Generator news category url
 * @input string $name, $id
 * @return url
 */
if (!function_exists('build_news_category_url')) {

    function build_news_category_url($slug) {
        return "/blog/" . $slug . "/";
    }

}
/**
 * Generator resize image url
 * @input string $src, $w, $h, $a, $zc
 * @return url
 */
if (!function_exists('build_resize_image_url')) {

    function build_resize_image_url($src, $w, $h, $a = 'c', $zc = 1) {
        if ($src) {
            return "/resize/" . $w . "x" . $h . "/a-" . $a . "/zc-" . $zc . "/f" . $src;
        } else {
            return "/resize/" . $w . "x" . $h . "/a-" . $a . "/zc-" . $zc . "/f" . DEFAULT_NO_IMAGE;
        }
    }

}


/**
 * Hiển thị date bằng tiếng việt
 * @input string $format, $time
 * @return string
 */
if (!function_exists('vietnamese_date')) {

    function vietnamese_date($format, $time = 0) {
        if (!$time) {
            $time = time();
        }
        $lang = array();
        $lang['sun'] = 'CN';
        $lang['mon'] = 'T2';
        $lang['tue'] = 'T3';
        $lang['wed'] = 'T4';
        $lang['thu'] = 'T5';
        $lang['fri'] = 'T6';
        $lang['sat'] = 'T7';
        $lang['sunday'] = 'Chủ nhật';
        $lang['monday'] = 'Thứ hai';
        $lang['tuesday'] = 'Thứ ba';
        $lang['wednesday'] = 'Thứ tư';
        $lang['thursday'] = 'Thứ năm';
        $lang['friday'] = 'Thứ sáu';
        $lang['saturday'] = 'Thứ bảy';
        $lang['january'] = 'Tháng Một';
        $lang['february'] = 'Tháng Hai';
        $lang['march'] = 'Tháng Ba';
        $lang['april'] = 'Tháng Tư';
        $lang['may'] = 'Tháng Năm';
        $lang['june'] = 'Tháng Sáu';
        $lang['july'] = 'Tháng Bảy';
        $lang['august'] = 'Tháng Tám';
        $lang['september'] = 'Tháng Chín';
        $lang['october'] = 'Tháng Mười';
        $lang['november'] = 'Tháng Mười một';
        $lang['december'] = 'Tháng Mười hai';
        $lang['jan'] = 'T01';
        $lang['feb'] = 'T02';
        $lang['mar'] = 'T03';
        $lang['apr'] = 'T04';
        $lang['may2'] = 'T05';
        $lang['jun'] = 'T06';
        $lang['jul'] = 'T07';
        $lang['aug'] = 'T08';
        $lang['sep'] = 'T09';
        $lang['oct'] = 'T10';
        $lang['nov'] = 'T11';
        $lang['dec'] = 'T12';

        $format = str_replace("r", "D, d M Y H:i:s O", $format);
        $format = str_replace(array("D", "M"), array("[D]", "[M]"), $format);
        $return = date($format, $time);

        $replaces = array(
            '/\[Sun\](\W|$)/' => $lang['sun'] . "$1",
            '/\[Mon\](\W|$)/' => $lang['mon'] . "$1",
            '/\[Tue\](\W|$)/' => $lang['tue'] . "$1",
            '/\[Wed\](\W|$)/' => $lang['wed'] . "$1",
            '/\[Thu\](\W|$)/' => $lang['thu'] . "$1",
            '/\[Fri\](\W|$)/' => $lang['fri'] . "$1",
            '/\[Sat\](\W|$)/' => $lang['sat'] . "$1",
            '/\[Jan\](\W|$)/' => $lang['jan'] . "$1",
            '/\[Feb\](\W|$)/' => $lang['feb'] . "$1",
            '/\[Mar\](\W|$)/' => $lang['mar'] . "$1",
            '/\[Apr\](\W|$)/' => $lang['apr'] . "$1",
            '/\[May\](\W|$)/' => $lang['may2'] . "$1",
            '/\[Jun\](\W|$)/' => $lang['jun'] . "$1",
            '/\[Jul\](\W|$)/' => $lang['jul'] . "$1",
            '/\[Aug\](\W|$)/' => $lang['aug'] . "$1",
            '/\[Sep\](\W|$)/' => $lang['sep'] . "$1",
            '/\[Oct\](\W|$)/' => $lang['oct'] . "$1",
            '/\[Nov\](\W|$)/' => $lang['nov'] . "$1",
            '/\[Dec\](\W|$)/' => $lang['dec'] . "$1",
            '/Sunday(\W|$)/' => $lang['sunday'] . "$1",
            '/Monday(\W|$)/' => $lang['monday'] . "$1",
            '/Tuesday(\W|$)/' => $lang['tuesday'] . "$1",
            '/Wednesday(\W|$)/' => $lang['wednesday'] . "$1",
            '/Thursday(\W|$)/' => $lang['thursday'] . "$1",
            '/Friday(\W|$)/' => $lang['friday'] . "$1",
            '/Saturday(\W|$)/' => $lang['saturday'] . "$1",
            '/January(\W|$)/' => $lang['january'] . "$1",
            '/February(\W|$)/' => $lang['february'] . "$1",
            '/March(\W|$)/' => $lang['march'] . "$1",
            '/April(\W|$)/' => $lang['april'] . "$1",
            '/May(\W|$)/' => $lang['may'] . "$1",
            '/June(\W|$)/' => $lang['june'] . "$1",
            '/July(\W|$)/' => $lang['july'] . "$1",
            '/August(\W|$)/' => $lang['august'] . "$1",
            '/September(\W|$)/' => $lang['september'] . "$1",
            '/October(\W|$)/' => $lang['october'] . "$1",
            '/November(\W|$)/' => $lang['november'] . "$1",
            '/December(\W|$)/' => $lang['december'] . "$1");

        return preg_replace(array_keys($replaces), array_values($replaces), $return);
    }

}

/**
 * Custom function getimagesize() để sử dụng cho lazyload phần bài viết
 * @input string $filepath
 * @return array (width, heigth, type, attr);
 */
if (!function_exists('getimagesize_custom')) {

    function getimagesize_custom($filepath) {
        if (file_exists(PUBLIC_PATH . $filepath)) {
            $getimagesize = getimagesize(PUBLIC_PATH . $filepath);
            if ($getimagesize) {
                return $getimagesize;
            }
        } elseif (file_exists(PUBLIC_PATH . preg_replace("/^\/resize\/([0-9]+)x([0-9]+)\/a-(.*)\/zc-([0-9]+)\/f/", "", $filepath))) {
            $getimagesize = getimagesize(PUBLIC_PATH . preg_replace("/^\/resize\/([0-9]+)x([0-9]+)\/a-(.*)\/zc-([0-9]+)\/f/", "", $filepath));
            if ($getimagesize) {
                return $getimagesize;
            }
        } elseif (preg_match("/^http(.*)/", $filepath)) {
            $getimagesize = getimagesize($filepath);
            if ($getimagesize) {
                return $getimagesize;
            }
        }
        return [];
    }

}
/**
 * Thêm các thuộc tính cần thiết để chạy lazyload
 * @input string $html_string
 * @return string $html_string contain data-width & data-height;
 */
if (!function_exists('prepare_lazyload_content')) {

    function prepare_lazyload_content($html_string) {
        if ($html_string) {
            $dom = new \DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($html_string, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new \DOMXpath($dom);
            $all_images = $xpath->query("//img");
            foreach ($all_images as $node) {
                $src = $node->getAttribute('src');
                $widthset = $node->getAttribute('width');
                $heightset = $node->getAttribute('height');
                if ($widthset && $heightset) {
                    $node->setAttribute('data-width', $widthset);
                    $node->setAttribute('data-height', $heightset);
                } elseif ($getimagesize = getimagesize_custom($src)) {
                    if ($getimagesize) {
                        $node->setAttribute('data-width', $getimagesize[0]);
                        $node->setAttribute('data-height', $getimagesize[1]);
                    }
                }
            }
            $html_string = $dom->saveHTML();
            return $html_string;
        }
        return $html_string;
    }

}
/**
 * Bật lazyload bằng cách thêm class .lazy vào thẻ img
 * @input string $html_string
 * @return string $html_string contain class .lazy;
 */
if (!function_exists('get_lazyload_content')) {

    function get_lazyload_content($html_string) {
        if ($html_string) {
            $dom = new \DOMDocument();
            $libxml_previous_state = libxml_use_internal_errors(true);
            $dom->loadHTML(mb_convert_encoding($html_string, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_use_internal_errors($libxml_previous_state);
            $xpath = new \DOMXpath($dom);
            $lazy_load_images = $xpath->query("//img");
            foreach ($lazy_load_images as $node) {
                $oldclass = $node->getAttribute('class');
                $node->setAttribute('class', 'lazy ' . $oldclass);
                $oldsrc = $node->getAttribute('src');
                if (!preg_match("/^\/resize\//", $oldsrc)) {
                    $node->setAttribute('data-src', build_resize_image_url($oldsrc, 750, 0));
                } else {
                    $node->setAttribute('data-src', $oldsrc);
                }
                $node->removeAttribute('src');
                $data_width = $node->getAttribute('data-width');
                $data_height = $node->getAttribute('data-height');
                if ($data_width && $data_height) {
                    $node->parentNode->setAttribute('class', 'lazy--loading');
                    $node->parentNode->setAttribute('style', 'padding-bottom: ' . ($data_height / $data_width) * 100 . '%;');
                }
            }
            $html_string = $dom->saveHTML();
            return $html_string;
        }
        return $html_string;
    }

}

/**
 * Tính giá bán hiện tại của SP
 * Giá KM hoặc giá gốc
 * @input string $product
 * @return string $sell_price
 */
if (!function_exists('calculator_sell_price')) {

    function calculator_sell_price($product) {
        if ($product->promotional_price != 0 && $product->promotional_price < $product->price) {
            $product->sale_percent = round(($product->price - $product->promotional_price) / $product->price * 100);
        }
        if (!isset($product->sale_percent)) {
            return $product->price;
        } else {
            if ($product->sale_schedule == 1) {
                if ($product->sale_time_begin <= time() && $product->sale_time_end >= time()) {
                    return $product->promotional_price;
                } else {
                    return $product->price;
                }
            } else {
                return $product->promotional_price;
            }
        }
    }

}

/**
 * Subscribe email vào list mailchimp
 */
if (!function_exists('mailchimp_subscriber_status')) {

    function mailchimp_subscriber_status($email, $status, $list_id, $api_key, $merge_fields = array('FNAME' => '', 'LNAME' => '')) {
        $data = array(
            'apikey' => $api_key,
            'email_address' => $email,
            'status' => $status,
            'merge_fields' => $merge_fields
        );
        $mch_api = curl_init(); // initialize cURL connection

        curl_setopt($mch_api, CURLOPT_URL, 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address'])));
        curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . base64_encode('user:' . $api_key)));
        curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
        curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
        curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch_api, CURLOPT_POST, true);
        curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data)); // send data in json

        $result = curl_exec($mch_api);
        return $result;
    }

}
/**
 * Generator build code by variant
 * @input string product_id, item_id
 * @return code
 */
if (!function_exists('build_code_by_variant')) {

    function build_code_by_variant($product_id, $item_id) {
        if ($product_id && $item_id) {
            return 'BL' . $product_id . 'V' . $item_id;
        }
        return 'BL' . $product_id;
    }

}

/**
 * Get array $a được lấy trong tất cả array con có key là $key nằm trong array $b
 * $key is string
 */
if (!function_exists('get_array_by_key_of_array')) {

    function get_array_by_key_of_array($b, $key, $type = '') {
        $a = [];
        if (!$b) {
            return $a;
        }
        foreach ($b as $k => $value) {
            if ($type == 'object') {
                $value = (array) $value;
            }
            $a[] = $value[$key];
        }
        return $a;
    }

}

/**
 * Get array $a được lấy trong tất cả array con có key là $key nằm trong array $b
 * $key is string
 */
if (!function_exists('mkdir_r')) {

    // create folder on server
    function mkdir_r($dirName, $rights = 0777) {
        $dirs = explode('/', $dirName);
        $dir = '';

        foreach ($dirs as $part) {
            $dir .= $part . '/';

            if (!@is_dir($dir) && strlen($dir) > 0) {
                @mkdir($dir, $rights);
            }
        }
        return true;
    }

}

//sắp xếp đc cả số thập phân (float), lưu ý: khi sort xong thì $key trong $data[$key] sẽ thay đổi về dạng 0,1,2,3,4,5,6...
function _orderBy($data, $field, $order = SORT_DESC) {     // SORT_ASC, SORT_DESC
    $price = array();
    foreach ($data as $key => $row) {
        $price[$key] = $row[$field];
    }
    array_multisort($price, $order, $data);
    return $data;
}

function render_new_js_tag($domainID) {
    return '//' . DOMAIN_AD . '/adv1/?d=' . $domainID;
}

function clearAllLine($string) {
    return trim(preg_replace('/\s+/', ' ', $string));
}

function printResponse($result) {
    if (empty($result)) {
        $result = ['success' => TRUE];
    }
    header('Content-Type: application/json');
    echo json_encode($result);
}

function number_shorten($number, $precision = 3, $divisors = null) {

    // Setup default $divisors if not provided
    if (!isset($divisors)) {
        $divisors = array(
            pow(1000, 0) => '', // 1000^0 == 1
            pow(1000, 1) => 'K', // Thousand
            pow(1000, 2) => 'M', // Million
            pow(1000, 3) => 'B', // Billion
            pow(1000, 4) => 'T', // Trillion
            pow(1000, 5) => 'Qa', // Quadrillion
            pow(1000, 6) => 'Qi', // Quintillion
        );
    }

    // Loop through each $divisor and find the
    // lowest amount that matches
    foreach ($divisors as $divisor => $shorthand) {
        if (abs($number) < ($divisor * 1000)) {
            // We found a match!
            break;
        }
    }

    // We found our match, or there were no matches.
    // Either way, use the last defined value for $divisor.
    return number_format($number / $divisor, $precision) . $shorthand;
}

function _count($array) {
    if (!$array) {
        return 0;
    }
    return count($array);
}

//number_format reports
function number_format_reports($reports) {
    foreach ($reports as $key => $value) {
        switch ($key) {
            case 'ecpm':
                $reports[$key] = round($value, 2);
                break;

            case 'impressions';
            case 'paid';
            case 'passback';
                $reports[$key] = $value ? number_format($value) : $value;
                break;

            case 'revenue';
            case 'publisher';
            case 'profit';
                $reports[$key] = $value ? number_format($value, 2) : $value;
                break;
        }
    }
    return $reports;
}

//ép kiểu dữ liệu boolean sang string: true => 'true', false => 'false'
function boolean_to_string($boolean) {
    if ($boolean === true) {
        return 'true';
    }
    if ($boolean === false) {
        return 'false';
    }
    return ['error' => 'data not boolean'];
}

//tính tổng của 1 field của array trong array
function sum_array_of_array($data, $field) {
    if (!$data) {
        return FALSE;
    }
    $result = 0;
    foreach ($data as $value) {
        $value = (array) $value;
        $result += $value[$field];
    }
    return $result;
}

/**
 * Get array $a được lấy trong tất cả array con có key là $key nằm trong array $b
 * $key is string
 */
if (!function_exists('ListCountry')) {

    function ListCountry() {
        $countries = array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.s.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        );
        return $countries;
    }

}

if (!function_exists('_curlPost')) {

    function _curlPost($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $result;
        }
    }

}