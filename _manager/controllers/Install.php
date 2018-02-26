<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 后台安装
 */

class install extends CI_Controller {
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
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
        $data['fields'] = array();
        $data['fields'][] = array('name' => 'hostname', 'desc' => '数据库地址', 'type' => 'text', 'value' => 'localhost', 'icon' => 'fa fa-server');
        $data['fields'][] = array('name' => 'database', 'desc' => '数据库名称', 'type' => 'text', 'value' => 'andycms', 'icon' => 'fa fa-database');
        $data['fields'][] = array('name' => 'dbprefix', 'desc' => '数据库前缀', 'type' => 'text', 'value' => 'web_', 'icon' => 'fa fa-angle-left');
        $data['fields'][] = array('name' => 'username', 'desc' => '数据库用户名', 'type' => 'text', 'value' => 'root', 'icon' => 'fa fa-user');
        $data['fields'][] = array('name' => 'password', 'desc' => '数据库密码', 'type' => 'password', 'value' => '', 'icon' => 'fa fa-key');
        $data['fields'][] = array('name' => 'admin_username', 'desc' => '管理员用户名', 'type' => 'text', 'value' => 'admin', 'icon' => 'fa fa-user-circle-o');
        $data['fields'][] = array('name' => 'admin_password', 'desc' => '管理员密码', 'type' => 'password', 'value' => 'admin123', 'icon' => 'fa fa-key');
        $this->load->view('sys_install', $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 执行
     *
     * @access  public
     * @return  void
     */
    public function run() {
        $install_lock_file = APPPATH . 'config/install.lock';
        $install_sql_file = APPPATH . 'config/install.sql';
        if (file_exists($install_lock_file)) {
            $this->message('CMS已经安装过，要重新安装请先删除 install.lock');
        }
        $config['hostname'] = $this->input->post('hostname', TRUE);
        $config['username'] = $this->input->post('username', TRUE);
        $config['password'] = $this->input->post('password', TRUE);
        $config['database'] = $this->input->post('database', TRUE);
        $config['dbprefix'] = $this->input->post('dbprefix', TRUE);
        $search_array = array('{HOSTNAME}', '{USERNAME}', '{PASSWORD}', '{DATABASE}', '{DBPREFIX}');
        $replace_array = array($config['hostname'], $config['username'], $config['password'], $config['database'], $config['dbprefix']);
        $database_config = str_replace($search_array, $replace_array, file_get_contents(APPPATH . 'config/database.example.php'));
        file_put_contents(FCPATH . 'application/config/database.php', $database_config);
        $this->load->database();
        $sql = str_replace("{DBPREFIX}", $config['dbprefix'], file_get_contents($install_sql_file));
        $this->db->trans_start();
        foreach (explode(';', $sql) as $query) {
            if ($query) $this->db->query($query);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->message('安装失败，数据库信息有误');
        }
        $data['salt'] = substr(hash('sha256', rand()), -10);
        $data['username'] = $this->input->post('admin_username', TRUE);
        $data['password'] = hash('sha256', $this->input->post('admin_password', TRUE) . $data['salt']);
        $data['role'] = 1;
        $data['status'] = 1;
        $success = ($this->db->insert('_admins', $data) AND $this->db->affected_rows() == 1);
        if (!$success) {
            $this->message('管理员创建失败');
        }
        file_put_contents($install_lock_file, 'Welcome to CMS!');
        $this->message('安装成功！ <a href="/manager">点击进入管理后台</a>');
    }
    public function message($message, $uri = 'install') {
        $this->session->set_flashdata('error', $message);
        redirect($uri);
        exit();
    }
    // ------------------------------------------------------------------------
    
}
