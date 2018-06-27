<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 内容模型操作模型
 */
class Model_mdl extends CI_Model {

    // 模型目录
    public $models_dir = APPPATH . "_models/";

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
     * 获得所有内容模型
     *
     * @access  public
     * @return  object
     */
    public function get_models() {
        return $this->db->get($this->db->order_by('`order`', 'ASC')->dbprefix('_models'))->result();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据内容模型name获取内容模型
     *
     * @access  public
     * @param   string
     * @return  object
     */
    public function get_model_by_name($name) {
        return $this->db->where('name', $name)->get($this->db->dbprefix('_models'))->row();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 新增内容模型
     *
     * @access  public
     * @param   array
     * @return  bool
     */
    public function add_new_model($data) {
        if ($this->db->insert($this->db->dbprefix('_models'), $data)) {
            $this->load->dbforge();
            $table = '' . $data['name'];
            $this->dbforge->drop_table($table, TRUE);
            $this->dbforge->add_field(array('id' => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE)));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_field(array('create_time' => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'default' => 0)));
            $this->dbforge->add_field(array('update_time' => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'default' => 0)));
            $this->dbforge->create_table($table);
            return TRUE;
        }
        return FALSE;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 修改内容模型
     *
     * @access  public
     * @param   object
     * @param   array
     * @return  bool
     */
    public function edit_model($target_model, $data) {
        if ($this->db->where('name', $target_model->name)->update($this->db->dbprefix('_models'), $data)) {
            $this->load->dbforge();
            $old_table_name = $target_model->name;
            if ($old_table_name != $data['name']) {
                $this->dbforge->rename_table($old_table_name, $data['name']);
                $this->db->where('model', $old_table_name)->update($this->db->dbprefix('_model_fields'), array('model' => $data['name']));
            }
            return TRUE;
        }
        return FALSE;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 删除内容模型
     *
     * @access  public
     * @param   object
     * @return  void
     */
    public function del_model($model) {
        $this->load->dbforge();
        //删除表
        $this->dbforge->drop_table($model->name, true);
        //删除字段
        $this->db->where('model', $model->name)->delete($this->db->dbprefix('_model_fields'));
        //删除记录
        $this->db->where('name', $model->name)->delete($this->db->dbprefix('_models'));
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取全部字段
     *
     * @access  public
     * @param   int
     * @return  object
     */
    public function get_model_fields($name) {
        return $this->db->where('model', $name)->order_by('order', 'ASC')->get($this->db->dbprefix('_model_fields'))->result();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 添加新内容模型字段
     *
     * @access  public
     * @param   object
     * @param   array
     * @return  bool
     */
    public function add_field($model, $data) {
        $this->load->dbforge();
        $this->load->library('field_behavior');
        $data['model'] = $model->name;
        if ($this->db->insert($this->db->dbprefix('_model_fields'), $data)) {
            $this->dbforge->add_column($model->name, $this->field_behavior->on_info($data));
            return TRUE;
        }
        return FALSE;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据字段name获取字段信息
     *
     * @access  public
     * @param   int
     * @return  object
     */
    public function get_field_by_name($model_name, $field_name) {
        return $this->db->where('model', $model_name)->where('name', $field_name)->get($this->db->dbprefix('_model_fields'))->row();
    }
    // ------------------------------------------------------------------------

    /**
     * 修改内容模型字段信息
     *
     * @access  public
     * @param   object
     * @param   object
     * @param   array
     * @return  bool
     */
    //
    public function edit_field($model, $field, $data) {
        $this->load->dbforge();
        $this->load->library('field_behavior');
        $old_name = $field->name;
        if ($this->db->where('model', $model->name)->where('name', $field->name)->update($this->db->dbprefix('_model_fields'), $data)) {
            $this->dbforge->modify_column($model->name, $this->field_behavior->on_info($data, $old_name));
            return TRUE;
        }
        return FALSE;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 删除字段
     *
     * @access  public
     * @param   int
     * @param   object
     * @return  void
     */
    //删除内容模型字段
    public function del_field($model, $field) {
        $this->load->dbforge();
        $this->dbforge->drop_column($model->name, $field->name);
        $this->db->where('model', $model->name)->where('name', $field->name)->delete($this->db->dbprefix('_model_fields'));
    }
    // ------------------------------------------------------------------------
    
}
