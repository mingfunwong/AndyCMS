<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 系统相关控制器
 */
class System extends Admin_Controller {
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
     * 后台默认首页
     *
     * @access  public
     * @return  void
     */
    public function home() {
        $data['bread'] = make_bread(Array('后台首页' => ''));
        $this->_template('sys_default', $data);
    }
    // ------------------------------------------------------------------------
    
}
