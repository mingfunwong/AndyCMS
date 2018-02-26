<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 用户登录/退出控制器
 */
class Login extends CI_Controller {
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
    }
    // ------------------------------------------------------------------------
    
    /**
     * 默认入口
     *
     * @access  public
     * @return  void
     */
    public function index() {
        if ($this->session->userdata('uid')) {
            redirect('system/home');
        } else {
            $this->load->view('sys_login');
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 验证码
     *
     * @access  public
     * @return  void
     */
    public function captcha() {
        $this->load->library('captcha');
        $captcha = $this->captcha->getCaptcha();
        $this->session->set_flashdata('captcha', $captcha);
        $this->captcha->showImg();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 退出
     *
     * @access  public
     * @return  void
     */
    public function quit() {
        $this->session->sess_destroy();
        redirect('login');
    }
    // ------------------------------------------------------------------------
    
    /**
     * 用户登录验证
     *
     * @access  public
     * @return  void
     */
    public function submit() {
        $this->_do_post();
    }

    public function _do_post() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $captcha = $this->input->post('captcha', TRUE);
        if ($this->session->flashdata('captcha') != $captcha) {
            $this->save_throttle();
            $this->session->set_flashdata('error', "验证码错误");
            redirect('login');
        }
        if ($username AND $password) {
            $admin = $this->user_mdl->get_full_user_by_username($username);
            if ($admin) {
                $throttle = $this->db->where('created_at >', date('Y-m-d H:i:s', time() - 7200))->count_all_results('_throttles');
                if ($throttle > 20) {
                    $this->session->set_flashdata('error', "错误次数过多，账号被禁用2小时，将在" . date('Y-m-d H:i:s', strtotime($throttle->created_at) + 7200) . '解禁');
                    redirect('login');
                }
                if ($admin->password == hash('sha256', $password . $admin->salt)) {
                    if ($admin->status == 1) {
                        $this->session->set_userdata('uid', $admin->uid);
                        redirect('system/home');
                    } else {
                        $this->session->set_flashdata('error', "此帐号已被冻结,请联系管理员！");
                    }
                } else {
                    $this->save_throttle();
                    $this->session->set_flashdata('error', "密码不正确！");
                }
            } else {
                $this->save_throttle();
                $this->session->set_flashdata('error', '不存在的用户!');
            }
        } else {
            $this->session->set_flashdata('error', '用户名和密码不能为空!');
        }
        redirect('login');
    }

    private function save_throttle() {
        $username = $this->input->post('username', TRUE);
        $throttle_data['username'] = $username;
        $throttle_data['type'] = 'attempt_login';
        $throttle_data['ip'] = $this->input->ip_address();
        $throttle_data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('_throttles', $throttle_data);
        $this->session->set_flashdata('error', '不存在的用户!');
    }
    
}
