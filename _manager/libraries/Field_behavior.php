<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 字段类型支配类
 */
class Field_behavior {
    /**
     * _ci
     * CI超级类的句柄
     *
     * @var object
     * @access  private
     *
     */
    private $_ci = NULL;
    /**
     * _extra_fields
     * 自定义的字段类型集合
     *
     * @var array
     * @access  private
     *
     */
    private $_extra_fields = array();
    /**
     * _extra_condition
     * 额外的条件，用于保持搜索条件
     *
     * @var array
     * @access  private
     *
     */
    private $_extra_condition = '';
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        $this->_ci = & get_instance();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 加载自定义字段类型
     *
     * @access  private
     * @param   string
     * @return  void
     */
    private function _load_extra_field($type) {
        if (!in_array($type, array_keys($this->_extra_fields))) {
            $extra_class = 'field_' . $type;
            if (file_exists(APPPATH . 'libraries/fields/' . $extra_class . '.php')) {
                // include APPPATH . 'libraries/fields/' . $extra_class . '.php';
                if (class_exists($extra_class)) {
                    $this->_extra_fields[$type] = new $extra_class();
                } else {
                    $this->_ci->_message('自定义的字段类型类不存在', '', FALSE);
                }
            } else {
                $this->_ci->_message('自定义的字段类型类文件不存在', '', FALSE);
            }
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 判断给定的字段是否为自定义字段
     *
     * @access  private
     * @param   string
     * @return  bool
     */
    private function _is_extra($type = '') {
        return !in_array($type, array_keys(setting('fieldtypes')));
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成字段的创建信息
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  array
     */
    public function on_info($data, $oldname = '') {
        if ($this->_is_extra($data['type'])) {
            $this->_load_extra_field($data['type']);
            $field = $this->_extra_fields[$data['type']]->on_info($data);
        } else {
            switch ($data['type']) {
                case 'select_from_model':
                case 'radio_from_model':
                case 'int':
                    $field = array('type' => 'INT', 'constraint' => 10, 'default' => 0);
                break;
                case 'float':
                    $field = array('type' => 'FLOAT', 'constraint' => 10, 'default' => 0);
                break;
                case 'double':
                    $field = array('type' => 'DOUBLE', 'constraint' => 20, 'default' => 0);
                break;
                case 'input':
                case 'select':
                case 'radio':
                case 'checkbox':
                case 'checkbox_from_model':
                case 'datetime':
                case 'colorpicker':
                    $field = array('type' => 'VARCHAR', 'constraint' => 255, 'default' => '');
                break;
                case 'textarea':
                case 'wysiwyg':
                    $field = array('type' => 'TEXT');
                break;
                case 'content':
                    $field = array('type' => 'INT', 'constraint' => 10, 'default' => 0);
                break;
            }
        }
        if (!isset($field['null'])) {
            $field['null'] = true;
        }
        if ($oldname != '') {
            $field['name'] = $data['name'];
            return array($oldname => $field);
        } else {
            return array($data['name'] => $field);
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成字段的表单控件
     *
     * @access  public
     * @param   array
     * @param   string
     * @param   bool
     * @return  void
     */
    public function on_form($field, $default = '', $has_tip = TRUE, $allow_upload = FALSE) {
        if ($this->_is_extra($field['type'])) {
            $this->_load_extra_field($field['type']);
            $this->_extra_fields[$field['type']]->on_form($field, $default, $has_tip);
        } else {
            //查看是否有指定默认值,以下字段类型支持
            $default_value_enabled = array('int', 'float', 'double', 'input', 'textarea', 'colorpicker', 'datetime');
            if (in_array($field['type'], $default_value_enabled) AND $default == '' AND $field['values'] != '') {
                $default = $field['values'];
            }
            $this->_ci->form->display($field, $default, $has_tip, $allow_upload);
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成字段的列表的控件
     *
     * @access  public
     * @param   array
     * @param   mixed
     * @return  void
     */
    public function on_list($field, $value) {
        if ($this->_is_extra($field['type'])) {
            $this->_load_extra_field($field['type']);
            return $this->_extra_fields[$field['type']]->on_list($field, $value);
        } else {
            switch ($field['type']) {
                case 'radio':
                case 'select':
                    return isset($field['values'][$value->$field['name']]) ? $field['values'][$value->$field['name']] : '';
                break;
                case 'checkbox':
                    $array = array();
                    foreach (explode(',', $value->$field['name']) as $t) {
                        if (isset($field['values'][$t])) {
                            $array[] = $field['values'][$t];
                        }
                    }
                    return implode(',', $array);
                break;
                case 'radio_from_model':
                case 'select_from_model':
                    $options = explode('|', $field['values']);
                    $row = $this->_ci->db->where('id', $value->$field['name'])->get('' . $options[0])->row_array();
                    return isset($row[$options[1]]) ? $row[$options[1]] : '';
                break;
                case 'checkbox_from_model':
                    $options = explode('|', $field['values']);
                    $checkbox_values = explode(',', $value->$field['name']);
                    $data = $this->_ci->db->select($options[1])->where_in('id', $checkbox_values)->get($options[0])->result_array();
                    $array = array();
                    foreach ($data as $key => $value) {
                        $array[] = $value[$options[1]];
                    }
                    return implode(',', $array);
                break;
                case 'content':
                    $options = explode('|', $field['values']);
                    if ($value->$field['name'] AND $row = $this->_ci->db->select('id, ' . $options[1])->where('id', $value->$field['name'])->get('' . $options[0])->row_array()) {
                        return $row[$options[1]];
                    } else {
                        return '-';
                    }
                break;
                default:
                    return $value->$field['name'];
            }
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成字段的搜索表单的控件
     *
     * @access  public
     * @param   array
     * @param   string
     * @return  void
     */
    public function on_search($field, $default) {
        if ($this->_is_extra($field['type'])) {
            $this->_load_extra_field($field['type']);
            $this->_extra_fields[$field['type']]->on_search($field, $default);
        } else {
            switch ($field['type']) {
                case 'select':
                case 'checkbox':
                case 'radio':
                case 'select_from_model':
                case 'radio_from_model':
                case 'checkbox_from_model':
                    $this->_ci->form->display($field, $default, FALSE);
                break;
                case 'datetime':
                case 'int':
                case 'float':
                case 'double':
                case 'content':
                    $field_min = $field_max = $field;
                    $field_min['name'] = $field_min['name'] . '_min';
                    $field_max['name'] = $field_max['name'] . '_max';
                    $this->_ci->form->display($field_min, $this->_ci->input->get_post($field['name'] . '_min') ? $this->_ci->input->get_post($field['name'] . '_min') : '', FALSE);
                    echo ' ---- ';
                    $this->_ci->form->display($field_max, $this->_ci->input->get_post($field['name'] . '_max') ? $this->_ci->input->get_post($field['name'] . '_max') : '', FALSE);
                break;
                default:
                    $field['type'] = 'input';
                    $this->_ci->form->display($field, $default);
            }
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 执行字段在搜索操作的行为
     *
     * @access  public
     * @param   array
     * @param   array
     * @param   array
     * @param   string
     * @return  void
     */
    public function on_do_search($field, &$condition, &$where, &$suffix) {
        if ($this->_is_extra($field['type'])) {
            $this->_load_extra_field($field['type']);
            $this->_extra_fields[$field['type']]->on_do_search($field, $condition, $where, $suffix);
        } else {
            switch ($field['type']) {
                case 'select':
                case 'radio':
                case 'select_from_model':
                case 'radio_from_model':
                case 'colorpicker':
                    if ($keyword = $this->_ci->input->get_post($field['name'], TRUE)) {
                        $condition[$field['name'] . ' ='] = $keyword;
                        $where[$field['name']] = $keyword;
                        $suffix.= '&' . $field['name'] . '=' . $keyword;
                    }
                break;
                case 'datetime':
                case 'int':
                case 'float':
                case 'double':
                case 'content':
                    if ($keyword_min = $this->_ci->input->get_post($field['name'] . '_min', TRUE)) {
                        $condition[$field['name'] . ' >='] = $keyword_min;
                        $where[$field['name'] . '_min'] = $keyword_min;
                        $suffix.= '&' . $field['name'] . '_min=' . $keyword_min;
                    }
                    if ($keyword_max = $this->_ci->input->get_post($field['name'] . '_max', TRUE)) {
                        $condition[$field['name'] . ' <='] = $keyword_max;
                        $where[$field['name'] . '_max'] = $keyword_max;
                        $suffix.= '&' . $field['name'] . '_max=' . $keyword_max;
                    }
                break;
                case 'input':
                case 'textarea':
                case 'wysiwyg':
                    if ($keyword = $this->_ci->input->get_post($field['name'], TRUE)) {
                        $condition[$field['name'] . ' LIKE'] = "%$keyword%";
                        $where[$field['name']] = $keyword;
                        $suffix.= '&' . $field['name'] . '=' . $keyword;
                    }
                break;
                case 'checkbox':
                case 'checkbox_from_model':
                    if ($keyword = $this->_ci->input->get_post($field['name'], TRUE)) {
                        $where[$field['name']] = $keyword;
                        $suffix.= '&' . $field['name'] . '=' . (is_array($keyword) ? implode(',', $keyword) : $keyword);
                        $keyword = is_array($keyword) ? $keyword : explode(',', $keyword);
                        $real_condition = array();
                        foreach ($keyword as $k) {
                            $real_condition[] = $field['name'] . " LIKE '%$k%' ";
                        }
                        if ($real_condition) {
                            $this->_extra_condition = implode(' AND ', $real_condition);
                            $this->set_extra_condition(FALSE);
                        }
                    }
                break;
                default:
                break;
            }
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 执行字段提交的行为
     *
     * @access  public
     * @param   array
     * @return  void
     */
    public function on_do_post($field, &$post) {
        if ($this->_is_extra($field['type'])) {
            $this->_load_extra_field($field['type']);
            $this->_extra_fields[$field['type']]->on_do_post($field, $post);
        } else {
            $return = $this->_ci->input->post($field['name']);
            switch ($field['type']) {
                case 'checkbox':
                case 'checkbox_from_model':
                    if (is_array($return) AND $return) {
                        $return = implode(',', $return);
                    } else {
                        $return = '';
                    }
                default:
                break;
            }
            $post[$field['name']] = $return;
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 设置/清除额外的查询条件
     *
     * @access  public
     * @param   bool
     * @return  void
     */
    public function set_extra_condition($clear = TRUE) {
        if (!$this->_extra_condition) {
            return FALSE;
        }
        $this->_ci->db->where($this->_extra_condition, '', FALSE);
        if ($clear) {
            $this->extra_condition = '';
        }
    }
    // ------------------------------------------------------------------------
    
}
