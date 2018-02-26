<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 后台控制器基类
 */
abstract class Admin_Controller extends CI_Controller {
    /**
     * _admin
     * 保存当前登录用户的信息
     *
     * @var object
     * @access  public
     *
     */
    public $_admin = NULL;
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('settings');
        $this->load->library('session');
        $this->load->model('user_mdl');
        $this->load->switch_theme();
        $this->_check_login();
        $this->load->library('acl');
        $this->load->library('plugin_manager');
    }
    // ------------------------------------------------------------------------
    
    /**
     * 检查用户是否登录
     *
     * @access  protected
     * @return  void
     */
    protected function _check_login() {
        if (!$this->session->userdata('uid')) {
            redirect('login');
        } else {
            $this->_admin = $this->user_mdl->get_full_user_by_username($this->session->userdata('uid'), 'uid');
            if ($this->_admin->status != 1) {
                $this->session->set_flashdata('error', "此帐号已被冻结,请联系管理员!");
                redirect('login');
            }
            if (!$this->_admin) {
                $this->session->sess_destroy();
                redirect('login');
            }
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 加载视图
     *
     * @access  protected
     * @param   string
     * @param   array
     * @return  void
     */
    protected function _template($template, $data = array()) {
        $data['tpl'] = $template;
        $this->load->view('_layout', $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 检查权限
     *
     * @access  protected
     * @param string $action
     * @param string $folder
     * @return  void
     */
    protected function _check_permit($action = '', $folder = '') {
        if (!$this->acl->permit($action, $folder)) {
            $this->_message('对不起，你没有访问这里的权限！', 'system/home', FALSE);
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 信息提示
     *
     * @access  public
     * @param $msg
     * @param string $goto
     * @param bool $auto
     * @param string $fix
     * @param int $pause
     * @return  void
     */
    public function _message($msg, $goto = '', $auto = TRUE, $fix = '', $pause = 3000) {
        if ($goto == '') {
            $goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
        } else {
            $goto = strpos($goto, 'http') !== false ? $goto : backend_url($goto);
        }
        $goto.= $fix;
        $this->_template('sys_message', array('msg' => $msg, 'goto' => $goto, 'auto' => $auto, 'pause' => $pause));
        echo $this->output->get_output();
        exit();
    }
    public function _remap($method, $params = array()) {
        if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] !== 'GET') {
            $method = '_' . $method . '_' . strtolower($_SERVER['REQUEST_METHOD']);
        }
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();
    }
    // ------------------------------------------------------------------------
    
}
