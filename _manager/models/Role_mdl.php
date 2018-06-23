<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 用户组操作模型
 */
class Role_mdl extends CI_Model {

    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('model_mdl');
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取出ROOT以外的所有用户组
     *
     * @access  public
     * @return  object
     */
    public function get_roles() {
        return $this->db->where('id <>', '1')->get($this->db->dbprefix('_roles'))->result();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据用户组ID获取用户信息
     *
     * @access  public
     * @param   int
     * @return  object
     */
    public function get_role_by_id($id) {
        return $this->db->where('id', $id)->get($this->db->dbprefix('_roles'))->row();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据用户组名称获取用户组信息
     *
     * @access  public
     * @param   string
     * @return  object
     */
    public function get_role_by_name($name) {
        return $this->db->where('name', $name)->get($this->db->dbprefix('_roles'))->row();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 格式化数组成ASSOC方式
     *
     * @access  private
     * @param   array
     * @param   string
     * @param   string
     * @return  array
     */
    private function _re_parse_array($array, $key, $value) {
        $data = array();
        foreach ($array as $v) {
            $data[$v->$key] = $v->$value;
        }
        return $data;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取表单数据
     *
     * @access  public
     * @return  array
     */
    public function get_form_data() {
        $data['rights'] = array('role' => '用户组管理', 'user' => '用户管理',);
        $data['models'] = $this->_re_parse_array($this->model_mdl->get_models(), 'name', 'description');
        return $data;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 添加用户组
     *
     * @access  public
     * @param   array
     * @return  int
     */
    public function add_role($data) {
        $this->db->insert($this->db->dbprefix('_roles'), $data);
        return $this->db->insert_id();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 修改用户组
     *
     * @access  public
     * @param   int
     * @param   array
     * @return  bool
     */
    public function edit_role($id, $data) {
        return $this->db->where('id', $id)->update($this->db->dbprefix('_roles'), $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取用户组下用户数目
     *
     * @access  public
     * @param   int
     * @return  int
     */
    public function get_role_user_num($id) {
        return $this->db->where('role', $id)->count_all_results($this->db->dbprefix('_admins'));
    }
    // ------------------------------------------------------------------------
    
    /**
     * 删除用户组
     *
     * @access  public
     * @param   int
     * @return  void
     */
    public function del_role($id) {
        $this->db->where('id', $id)->delete($this->db->dbprefix('_roles'));
    }
    // ------------------------------------------------------------------------
    
}
