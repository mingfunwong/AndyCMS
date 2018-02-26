<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 附件上传
 */
class Attachment extends Admin_Controller {
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 编辑器文件上传接口
     *
     * @access  public
     * @return  void
     *
     */
    public function save() {
        $this->_save_post();
    }

    public function _save_post() {
        $fn = $this->input->get('CKEditorFuncNum');
        $field = from($_GET, 'field', 'upload');
        $this->load->library('session');
        if (!$this->session->userdata('uid')) {
            $this->mkhtml($fn, "", "啊哦，登陆超时了。");
        }
        if (!isset($_FILES[$field]) || $_FILES[$field]['error']) {
            $this->mkhtml($fn, "", "上传的文件不存在");
        }
        $this->load->helper('date');
        $_timestamp = now();
        $upload['folder'] = date('Y/m', $_timestamp);
        $target_path = FCPATH . setting('attachment_dir') . '/' . $upload['folder'];
        $realname = explode('.', $_FILES[$field]['name']);
        $upload['type'] = strtolower(array_pop($realname));
        $upload['realname'] = implode('.', $realname);
        $upload['name'] = $_timestamp . substr(md5($upload['realname'] . rand()), 0, 16);
        $upload['posttime'] = $_timestamp;
        $target_file = $target_path . '/' . $upload['name'] . '.' . $upload['type'];
        if (in_array($upload['type'], array('php'))) {
            $this->mkhtml(1, "", "对不起，不支持上传此文件类型。");
        }
        if (file_upload($_FILES[$field]['tmp_name'], $target_file)) {
            $fileurl = '/' . setting('attachment_dir') . '/' . $upload['folder'] . '/' . $upload['name'] . '.' . $upload['type'];
            $this->mkhtml($fn, $fileurl, "上传成功");
        }
    }
    function mkhtml($fn, $fileurl, $message) {
        $responseType = $this->input->get('responseType');
        if ($responseType == 'json') {
            $str = json_encode(array('uploaded' => 1, 'url' => $fileurl));
        } else {
            $str = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $fn . ', \'' . $fileurl . '\', \'' . $message . '\');</script>';
        }
        exit($str);
    }
}
