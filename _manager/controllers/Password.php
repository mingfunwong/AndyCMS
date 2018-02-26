<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 系统相关控制器
 */
class password extends Admin_Controller {
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
    }
    /**
     * 用户修改密码表单页入口
     *
     * @access  public
     * @return  void
     */
    public function edit() {
        $this->_edit_post();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 用户修改密码表单页呈现/处理函数
     *
     * @access  public
     * @return  void
     */
    public function _edit_post() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('old_pass', "旧密码", 'required');
        $this->form_validation->set_rules('new_pass', "新密码", 'required|min_length[6]|max_length[16]|matches[new_pass_confirm]');
        $this->form_validation->set_rules('new_pass_confirm', "确认新密码", 'required|min_length[6]|max_length[16]');
        if ($this->form_validation->run() == FALSE) {
            $data['bread'] = make_bread(Array('修改密码' => ''));
            $this->_template('sys_password', $data);
        } else {
            $old_pass = hash('sha256', trim($this->input->post('old_pass', TRUE)) . $this->_admin->salt);
            $stored = $this->user_mdl->get_user_by_uid($this->session->userdata('uid'));
            $new = $this->input->post('new_pass', TRUE);
            $new_two = $this->input->post('new_pass_confirm', TRUE);
            if ($stored AND $old_pass == $stored->password) {
                if ($new == $new_two) {
                    $this->user_mdl->update_user_password();
                    $this->_message("密码更新成功！", '', TRUE);
                } else {
                    $this->_message("新密码与确认新密码不一致,请重新输入！", '', TRUE);
                }
            } else {
                $this->_message("密码验证失败！", '', TRUE);
            }
        }
    }
    // ------------------------------------------------------------------------
    
}
