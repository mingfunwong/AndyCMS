<?php

function CI() {
    $CI = & get_instance();
    return $CI;
}

// curl 数据
function curl($url, $method = "GET", $data = ''){
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 

    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $info = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Errno'.curl_error($ch);
    }
    curl_close($ch);
    return $info;
}

/**
 * 创建面包屑导航
 */
function make_bread($flour)
{
	$bread = array();
	foreach($flour as $name => $link)
	{
		if(empty($link))
		{
			// $bread[] = "<span class=\"bread_name\"><a href=\"javascript:void(0);\">$name</a></span>";
			$bread[] = "<span class=\"bread_name\">$name</span>";
		}
		else
		{
			$bread[] = "<span class=\"bread_name\"><a href=\"$link\" target=\"_self\">$name</a></span>";
		}
	}
	return implode('<span class="bread_gt"> &gt; </span>', $bread);
}

/**
* 获得数组指定键的值
*
* @param array,object $array
* @param string $key
* @param mixed $default
* @return mixed
*/
function from($array, $key, $default = FALSE)
{
    $return = $default;
    if (is_object($array)) $return = (isset($array->$key) === TRUE && empty($array->$key) === FALSE) ? $array->$key : $default;
    if (is_array($array))  $return = (isset($array[$key]) === TRUE && empty($array[$key]) === FALSE) ? $array[$key] : $default;
    
    return $return;
}


// 文件上传
function file_upload($from = '', $to = '') {
    $target_path = dirname($to);
    if (!is_dir($target_path) AND !mkdir($target_path, 0755, TRUE)) {
        return FALSE;
    } else {
        return move_uploaded_file($from, $to);
    }
}
// 获取设置
function setting($key, $default = null) {
    $ci = & get_instance();
    $sequences = explode('.', $key);
    $key = array_shift($sequences);
    $tmp_result = $ci->settings->item($key);
    for ($i = 0, $total = count($sequences);$i < $total;) {
        if (isset($tmp_result[$sequences[$i]])) {
            $tmp_result = $tmp_result[$sequences[$i]];
            $i++;
        } else {
            return $default;
        }
    }
    return $tmp_result;
}
/**
 * 后台URI生成函数
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
function backend_url($uri = '', $qs = '') {
    return site_url('/' . $uri) . ($qs == '' ? '' : '?' . $qs);
}
/**
 * 插件URI生成函数
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
function plugin_url($plugin, $controller, $method = 'index', $qs = array()) {
    $ci = & get_instance();
    $qs['plugin'] = $plugin;
    $qs['c'] = $controller;
    $qs['m'] = $method;
    return backend_url('module/run', http_build_query($qs));
}
/**
 * 后台链接重定向
 */
function backend_redirect($uri = '', $method = 'auto', $code = NULL) {
    if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE) {
        $method = 'refresh';
    } elseif ($method !== 'refresh' && (empty($code) OR !is_numeric($code))) {
        if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
            $code = ($_SERVER['REQUEST_METHOD'] !== 'GET') ? 303 // reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
             : 307;
        } else {
            $code = 302;
        }
    }
    switch ($method) {
        case 'refresh':
            header('Refresh:0;url=' . $uri);
        break;
        default:
            header('Location: ' . $uri, TRUE, $code);
        break;
    }
    exit;
}
