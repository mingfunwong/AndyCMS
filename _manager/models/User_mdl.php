<?php

/**
 * CMS 用户操作模型
 */
class User_mdl extends CI_Model {
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
     * 根据用户名或者用户UID称获取该用户完整的信息
     *
     * @access  public
     * @param   mixed
     * @return  object
     */
    public function get_full_user_by_username($username = '', $type = 'username') {
        $table_admins = $this->db->dbprefix('_admins');
        $table_roles = $this->db->dbprefix('_roles');
        if ($type == 'uid') {
            $this->db->where($table_admins . '.uid', $username);
        } else {
            $this->db->where($table_admins . '.username', $username);
        }
        return $this->db->select("$table_admins.uid, $table_admins.username, $table_admins.password, $table_admins.salt, $table_admins.role, $table_roles.name, $table_admins.status")->from($table_admins)->join($table_roles, "$table_roles.id = $table_admins.role")->get()->row();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据用户ID获取用户信息
     *
     * @access  public
     * @param   int
     * @return  object
     */
    public function get_user_by_uid($uid = 0) {
        return $this->db->where('uid', $uid)->get($this->db->dbprefix('_admins'))->row();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据用户名获取用户信息
     *
     * @access  public
     * @param   string
     * @return  object
     */
    public function get_user_by_name($name) {
        return $this->db->where('username', $name)->get($this->db->dbprefix('_admins'))->row();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 用户自己密码
     *
     * @access  public
     * @return  bool
     */
    public function update_user_password() {
        $data['password'] = $this->input->post('new_pass', TRUE);
        $data['salt'] = substr(hash('sha256', rand()), -10);
        $data['password'] = hash('sha256', $data['password'] . $data['salt']);
        return $this->db->where('uid', $this->session->userdata('uid'))->update($this->db->dbprefix('_admins'), $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取用户组列表
     *
     * @access  public
     * @return  object
     */
    public function get_roles() {
        $roles = array();
        foreach ($this->db->select('id, name')->where('id <>', 1)->get($this->db->dbprefix('_roles'))->result_array() as $v) {
            $roles[$v['id']] = $v['name'];
        }
        return $roles;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取用户数
     *
     * @access  public
     * @param   int
     * @return  int
     */
    public function get_users_num($role_id = 0) {
        $this->db->where('uid <>', 1);
        if ($role_id) {
            $this->db->where('role', $role_id);
        }
        return $this->db->count_all_results($this->db->dbprefix('_admins'));
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取某个用户组下所有用户
     *
     * @access  public
     * @param   int
     * @param   int
     * @param   int
     * @return  object
     */
    public function get_users($role_id = 0, $limit = 0, $offset = 0) {
        $table_admins = $this->db->dbprefix('_admins');
        $table_roles = $this->db->dbprefix('_roles');
        $this->db->where("$table_admins.uid <>", 1);
        if ($role_id) {
            $this->db->where("$table_admins.role", $role_id);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        if ($offset) {
            $this->db->offset($offset);
        }
        return $this->db->from($table_admins)->join($table_roles, "$table_roles.id = $table_admins.role")->get()->result();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 添加用户
     *
     * @access  public
     * @param   array
     * @return  bool
     */
    public function add_user($data) {
        $data['salt'] = substr(hash('sha256', rand()), -10);
        $data['password'] = hash('sha256', $data['password'] . $data['salt']);
        return $this->db->insert($this->db->dbprefix('_admins'), $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 修改用户
     *
     * @access  public
     * @param   int
     * @param   array
     * @return  bool
     */
    public function edit_user($uid, $data) {
        if (isset($data['password'])) {
            $data['salt'] = substr(hash('sha256', rand()), -10);
            $data['password'] = hash('sha256', $data['password'] . $data['salt']);
        }
        return $this->db->where('uid', $uid)->update($this->db->dbprefix('_admins'), $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 删除用户
     *
     * @access  public
     * @param   uid
     * @return  bool
     */
    public function del_user($uid) {
        return $this->db->where('uid', $uid)->delete($this->db->dbprefix('_admins'));
    }
    // ------------------------------------------------------------------------
    
}
