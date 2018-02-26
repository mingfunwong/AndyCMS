<?php

// 随机字符串
function rand_str($lenth = 10) {
    $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $s = '';
    $len = strlen($str)-1;
    for( $i=0 ; $i < $lenth; $i++ ){
        $s .= $str[rand(0,$len)];
    }
    return $s;
}

/**
 * 建立请求，以表单HTML形式构造
 */
function build_request_form($url, $data) {
    //待请求参数数组
    $sHtml = "<meta charset='utf-8' />";
    $sHtml .= "<form id='form' name='form' action='{$url}' method='POST'>";
    foreach ($data as $key => $value) {
        $sHtml.= "<input type='hidden' name='".$key."' value='".$value."'/>";
    }
    $sHtml = $sHtml."<input type='submit'  value='' style='display:none;'></form>";
    $sHtml = $sHtml."<script>document.forms['form'].submit();</script>";
    return $sHtml;
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


// html 转 images array
function html2images($html){
    $CI =& get_instance(); 
    $CI->load->library('LoadPhpquery');
    $CI->loadphpquery->init();
    $html = phpQuery::newDocument($html);
    $images = array();
    foreach($html['img'] as $key => $img) {
        $images[] = pq($img)->attr('src');
    }
    return $images;
}