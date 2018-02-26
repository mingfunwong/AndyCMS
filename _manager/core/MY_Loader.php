<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS Loader 扩展CI_Loader
 */
class My_Loader extends CI_Loader {
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
     * 切换视图路径
     *
     * @access  public
     * @return  void
     */
    public function switch_theme($theme = 'default') {
        $theme = 'default';
        $this->_ci_view_paths = array(APPPATH . 'views/' . $theme . '/' => TRUE);
    }
    // ------------------------------------------------------------------------
    
}
