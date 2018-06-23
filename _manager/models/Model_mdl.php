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
        $models = array();
        foreach(glob($this->models_dir . "*.json") as $key => $value) {
            $model = json_decode(file_get_contents($value));
            $models[$model->order . "-" . $model->name] = $model;
        }
        ksort($models);
        return $models;
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
        $file_path = $this->models_dir . $name . ".json";
        if (file_exists($file_path)) {
            return json_decode(file_get_contents($file_path));
        } else {
            return array();
        }
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
        $file_path = $this->models_dir . $data['name'] . ".json";
        if (file_put_contents($file_path, json_encode($data))) {
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
        $file_path = $this->models_dir . $data['name'] . ".json";
        $model = $this->get_model_by_name($data['name']);
        $data['fields'] = $model->fields;
        if (file_put_contents($file_path, json_encode($data))) {
            $this->load->dbforge();
            $old_table_name = $target_model->name;
            if ($old_table_name != $data['name']) {
                $this->dbforge->rename_table('' . $old_table_name, '' . $data['name']);
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
        $file_path = $this->models_dir . $model->name . ".json";
        unlink($file_path);
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
        $model = $this->get_model_by_name($name);
        $fields = array();
        if (isset($model->fields)) {
            foreach ($model->fields as $key => $value) {
                $fields[$value->order . "-" . $value->name] = $value;
            }
        }
        ksort($fields);
        return $fields;
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

        $file_path = $this->models_dir . $model->name . ".json";
        $model = $this->get_model_by_name($model->name);
        $model->fields[] = $data;

        if (file_put_contents($file_path, json_encode($model))) {
            $this->dbforge->add_column('' . $model->name, $this->field_behavior->on_info($data));
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
        $fields = array();
        $model = $this->get_model_by_name($model_name);
        if (isset($model->fields)) {
            foreach ($model->fields as $key => $value) {
                $fields[$value->name] = $value;
            }
        }

        return isset($fields[$field_name]) ? $fields[$field_name] : array();
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

        $file_path = $this->models_dir . $model->name . ".json";
        $model = $this->get_model_by_name($model->name);
        $fields = array();
        if (isset($model->fields)) {
            foreach ($model->fields as $key => $value) {
                if ($value->name != $old_name) {
                    $fields[$value->name] = $value;
                }
            }
            $fields[$field->name] = $data;
        }
        $model->fields = $fields;

        if (file_put_contents($file_path, json_encode($model))) {
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
        $this->db->where('id', $field->name)->delete($this->db->dbprefix('_model_fields'));

        $file_path = $this->models_dir . $model->name . ".json";
        $model = $this->get_model_by_name($model->name);
        $fields = array();
        if (isset($model->fields)) {
            foreach ($model->fields as $key => $value) {
                if ($value->name != $field->name)
                $fields[$value->name] = $value;
            }
        }
        $model->fields = $fields;

        file_put_contents($file_path, json_encode($model));

    }
    // ------------------------------------------------------------------------
    
}
