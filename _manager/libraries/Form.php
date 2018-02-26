<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 表单控件类
 */
class Form {
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        //nothing to do!
        
    }
    // ------------------------------------------------------------------------
    
    /**
     * 输出控件HTML
     *
     * @access  public
     * @param   array
     * @param   string
     * @param   bool
     * @return  void
     */
    public function display(&$field, $default = '', $has_tip = TRUE, $allow_upload = FALSE) {
        $this->_find_real_value($field['name'], $default);
        $type = '_' . $field['type'];
        if ($has_tip) {
            $temp = $this->$type($field, $default, $allow_upload);
            echo $this->_add_tip($field['ruledescription'], $temp);
        } else {
            echo $this->$type($field, $default, $allow_upload);
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 检测表单元素的真正的值
     *
     * @access  private
     * @param   string
     * @param   string
     * @return  void
     */
    private function _find_real_value($name, &$default) {
        if (isset($_POST[$name])) {
            $default = $_POST[$name];
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 输出分类的HTML
     *
     * @access  public
     * @param   array
     * @param   string
     * @param   string
     * @return  void
     */
    public function show_class(&$category, $name, $default) {
        $this->_find_real_value($name, $default);
        $html = '<select name="' . $name . '" id="' . $name . '" class="form-control">' . '<option value="">请选择</option>';
        foreach ($category as $v) {
            $html.= '<option value="' . $v['class_id'] . '" ' . ($default == $v['class_id'] ? 'selected="selected"' : '') . '>';
            for ($i = 0;$i < $v['deep'];$i++) {
                $html.= "&nbsp;&nbsp;";
            }
            $html.= $v['class_name'] . '</option>';
        }
        $html.= '</select>';
        echo $html;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 输出隐藏控件的HTML
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
    public function show_hidden($name, $default = '', $lock = FALSE) {
        if ($lock == true) {
            $this->_find_real_value($name, $default);
        }
        echo '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $default . '" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 根据给定的类型输出控件的HTML
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @return  void
     */
    public function show($name, $type, $value = '', $default = '') {
        $this->_find_real_value($name, $default);
        $type = '_' . $type;
        $field = array('name' => $name, 'values' => $value, 'width' => 0, 'height' => 0);
        echo $this->$type($field, $default);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成INT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _int($field, $default) {
        return '<input class="form-control" name="' . $field['name'] . '" id="' . $field['name'] . '" type="text" autocomplete="off" value="' . $default . '" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成FLOAT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _float($field, $default) {
        return '<input class="form-control" name="' . $field['name'] . '" id="' . $field['name'] . '" type="text" autocomplete="off" value="' . $default . '" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成DOUBLE类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _double($field, $default) {
        return '<input class="form-control" name="' . $field['name'] . '" id="' . $field['name'] . '" type="text" autocomplete="off" value="' . $default . '" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成PASSWORD类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _password($field, $default) {
        return '<input class="form-control" name="' . $field['name'] . '" id="' . $field['name'] . '" type="password" autocomplete="off" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成INPUT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _input($field, $default) {
        $default = str_replace("\"", "&#34;", $default);
        return '<input class="form-control" name="' . $field['name'] . '" id="' . $field['name'] . '" type="text" autocomplete="off" value="' . $default . '" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成TEXTAREA类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _textarea($field, $default) {
        return '<textarea class="form-control" id="' . $field['name'] . '" name="' . $field['name'] . '" style="height: 100px" >' . $default . '</textarea>';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成SELECT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _select($field, $default) {
        $return = '<select name="' . $field['name'] . '" id="' . $field['name'] . '" class="form-control">' . '<option value="">请选择</option>';
        foreach ($field['values'] as $key => $v) {
            $pre_fix = '';
            if (isset($field['levels'][$key]) AND $field['levels'][$key] > 0) {
                for ($i = 0;$i < $field['levels'][$key];$i++) {
                    $pre_fix.= '&nbsp;&nbsp;';
                }
            }
            $return.= '<option value="' . $key . '" ' . ($default == $key ? 'selected="selected"' : '') . '>' . $pre_fix . $v . '</option>';
        }
        $return.= '</select>';
        return $return;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成RADIO类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _radio($field, $default) {
        $return = '';
        $count = 1;
        foreach ($field['values'] as $key => $v) {
            $return.= '<div class="radio"><label><input type="radio" name="' . $field['name'] . '" value="' . $key . '" ' . ($default == $key ? 'checked="checked"' : '') . '>' . $v . '</label></div>';
            $count++;
        }
        return $return;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成CHECKBOX类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _checkbox($field, $default) {
        $return = '';
        if (is_array($field['values'])) {
            if (!is_array($default)) {
                $default = ($default != '' ? explode(',', $default) : array());
            }
            $count = 1;
            foreach ($field['values'] as $key => $v) {
                $return.= '<div class="checkbox"><label><input id="chk_' . $field['name'] . '_' . $count . '" name="' . $field['name'] . '[]" type="checkbox" value="' . $key . '" ' . (in_array($key, $default) ? 'checked="checked"' : '') . ' />' . $v . '</label></div>';
                $count++;
            }
        } else {
            $return.= '<div class="checkbox"><label><input id="chk_' . $field['name'] . '" name="' . $field['name'] . '" type="checkbox" value="1" ' . ($default == 1 ? 'checked="checked"' : '') . ' />' . $field['values'] . '</label></div>';
        }
        $return.= '';
        return $return;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成WYSISYG类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @param   bool
     * @return  string
     */
    private function _wysiwyg($field, $default) {
        return '<textarea name="' . $field['name'] . '" id="' . $field['name'] . '" >' . $default . '</textarea><script type="text/javascript">CKEDITOR.replace( "' . $field['name'] . '", ckeditor_setting );</script>';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成DATETIME类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _datetime($field, $default) {
        $_datetime = $field['values'] ? $field['values'] : 'date';
        if ($default == $_datetime) {
            $default = '';
        }
        return '<input class="form-control" type="text" name="' . $field['name'] . '" id="' . $field['name'] . '" value="' . $default . '" /><script type="text/javascript">laydate.render({elem: "#' . $field['name'] . '",type: "' . $_datetime . '"});</script>';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成COLORPICKER类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _colorpicker($field, $default) {
        if (!$field['width']) {
            $field['width'] = 100;
        }
        return '<input class="field_colorpicker normal" name="' . $field['name'] . '" id="' . $field['name'] . '" type="text" style="width:' . $field['width'] . 'px" autocomplete="off" value="' . $default . '" />';
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成SELECT_FROM_MODEL类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _select_from_model($field, $default) {
        if (!$this->_get_data_from_model($field, TRUE)) {
            return '获取数据源时出错了!';
        }
        return $this->_select($field, $default);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成RADIO_FROM_MODEL类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _radio_from_model($field, $default) {
        if (!$this->_get_data_from_model($field)) {
            return '获取数据源时出错了!';
        }
        return $this->_radio($field, $default);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成CHECKBOX_FROM_MODEL类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _checkbox_from_model($field, $default) {
        if (!$this->_get_data_from_model($field)) {
            return '获取数据源时出错了!';
        }
        return $this->_checkbox($field, $default);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取缓存数据并处理，返回处理状态
     *
     * @access  private
     * @param   array
     * @param   bool
     * @return  bool
     */
    private function _get_data_from_model(&$field, $need_level = FALSE) {
        if (!$field['values']) {
            return FALSE;
        }
        if (count($options = explode('|', $field['values'])) != 2) {
            return FALSE;
        }
        $ci = & get_instance();
        $this->_ci = CI();
        $model_data[$options[0]] = $this->_ci->db->get($options[0])->result_array();
        $field['values'] = array();
        foreach ($model_data[$options[0]] as $v) {
            $field['values'][$v['id']] = $v[$options[1]];
        }
        return TRUE;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 生成控件的TIPS
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
    private function _add_tip(&$rules, &$html) {
        if ($rules) {
            $html.= '<label>' . $rules . '</lable>';
        }
        return $html;
    }
}
