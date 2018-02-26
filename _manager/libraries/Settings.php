<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 设置
 */
class Settings {
    /**
     * _ci
     * CI超级类句柄
     *
     * @var object
     * @access  private
     *
     */
    private $_ci = NULL;
    /**
     * settings
     * 设置数组
     *
     * @var array
     * @access  private
     *
     */
    private $setting = array();
    /**
     * is_loaded
     * 已加载的配置文件的集合
     *
     * @var array
     * @access  private
     *
     */
    private $is_loaded = array();
    /**
     * _setting_paths
     * 加载源所在的文件夹的集合
     *
     * @var array
     * @access  private
     *
     */
    private $_setting_paths = array();
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->database();
        $this->_setting_paths = array(APPPATH . 'settings/');
        $this->setting['plugins'] = $this->load_plugins();
        $this->setting['models'] = $this->load_model();
        $this->setting = array_merge($this->setting, $this->load_fieldtypes());
        $this->setting = array_merge($this->setting, $this->load_backend());
    }
    // ------------------------------------------------------------------------
    
    /**
     * 获取配置值
     *
     * @access  private
     * @param   string
     * @param   string
     * @return  mixed
     */
    public function item($item, $index = '') {
        if ($index == '') {
            if (!isset($this->setting[$item])) {
                return FALSE;
            }
            $pref = $this->setting[$item];
        } else {
            if (!isset($this->setting[$index])) {
                return FALSE;
            }
            if (!isset($this->setting[$index][$item])) {
                return FALSE;
            }
            $pref = $this->setting[$index][$item];
        }
        return $pref;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 设置配置值
     *
     * @access  public
     * @param   string
     * @param   mixed
     * @return  void
     */
    function set_item($item, $value) {
        $this->setting[$item] = $value;
    }
    // 站点设置
    function load_backend() {
        $CI = $this->_ci;
        $data = $CI->db->get($CI->db->dbprefix('system'))->row_array();
        return $data;
    }
    // 系统菜单
    function load_system_menus() {
        $CI = $this->_ci;
        $level_2_menus = array(array('menu_name' => '后台首页', 'class_name' => 'system', 'method_name' => 'home',), array('menu_name' => '修改密码', 'class_name' => 'password', 'method_name' => 'edit',), array('menu_name' => '内容模型管理', 'class_name' => 'model', 'method_name' => 'view',), array('menu_name' => '用户组管理', 'class_name' => 'role', 'method_name' => 'view',), array('menu_name' => '用户管理', 'class_name' => 'user', 'method_name' => 'view',),);
        $system_menus = array('menu_name' => '后台首页', 'sub_menus' => $level_2_menus, 'icon' => 'fa-home');
        return $system_menus;
    }
    // 内容菜单
    function load_content_menus() {
        $CI = $this->_ci;
        $level_2_menus = $CI->db->select(" 'content' AS class_name, 'view' AS 'method_name', name AS model, description AS menu_name, icon", FALSE)->order_by('order asc, id asc')->get($CI->db->dbprefix('_models'))->result_array();
        $content_menus = array('menu_name' => '内容管理', 'sub_menus' => $level_2_menus, 'icon' => 'fa-navicon');
        return $content_menus;
    }
    // 加载插件
    function load_plugins() {
        $CI = $this->_ci;
        $CI->load->helper('file');
        $cached_plugins = array();
        $plugins = array();
        foreach (get_dir_file_info(APPPATH . 'plugins') as $key => $value) {
            if ($value['name'] != 'index.html') {
                $plugins[] = $value['name'];
            }
        }
        if ($plugins) {
            foreach ($plugins as $key => $plugin) {
                if (!isset($cached_plugins[$plugin])) {
                    $cached_plugins[$plugin] = array('classmap' => array(), 'menus' => array(),);
                }
                $this_plugin_path = APPPATH . 'plugins/' . $plugin . '/';
                $this_hook_path = 'plugins/' . $plugin . '/hooks/';
                if (file_exists($this_plugin_path . 'hooks')) {
                    foreach (glob($this_plugin_path . 'hooks/' . $plugin . "_hook_*.php") as $filename) {
                        $filename = basename($filename);
                        $model = str_replace(array('.php', $plugin . "_hook_"), array('', ''), $filename);
                        $cached_plugins[$plugin]['classmap'][$model] = $this_hook_path . $filename;
                    }
                }
                if (file_exists($this_plugin_path . 'menus.php')) {
                    $this_plugin_menus = include ($this_plugin_path . 'menus.php');
                    $cached_plugins[$plugin]['menus'] = $this_plugin_menus;
                }
            }
        }
        return $cached_plugins;
    }
    function load_fieldtypes() {
        $CI = $this->_ci;
        $CI->load->helper('file');
        $cached_fieldtypes = array();
        $cached_fieldtypes['fieldtypes'] = array('checkbox' => '复选框(VARCHAR)', 'checkbox_from_model' => '复选框(模型数据)(VARCHAR)', 'content' => '内容模型调用(INT)', 'datetime' => '日期时间(VARCHAR)', 'float' => '浮点型(FLOAT)', 'input' => '单行文本框(VARCHAR)', 'int' => '整数(INT)', 'radio' => '单选按钮(VARCHAR)', 'radio_from_model' => '单选按钮(模型数据)(INT)', 'select' => '下拉菜单(VARCHAR)', 'select_from_model' => '下拉菜单(模型数据)(INT)', 'textarea' => '文本区域(VARCHAR)', 'wysiwyg' => '编辑器(TEXT)');
        $cached_fieldtypes['extra_fieldtypes'] = array();
        $extra_path = APPPATH . 'libraries/fields/';
        $extra_files = get_filenames($extra_path);
        foreach ($extra_files as $v) {
            if (preg_match("/^field_(.*?)\.php$/", $v)) {
                include $extra_path . $v;
                if (class_exists($extra_class = str_replace('.php', '', $v))) {
                    $tmp = new $extra_class();
                    $cached_fieldtypes['extra_fieldtypes'][$tmp->k] = $tmp->v;
                }
            }
        }
        return $cached_fieldtypes;
    }
    function load_model() {
        $CI = $this->_ci;
        $data = array();
        $models = $CI->db->get($CI->db->dbprefix('_models'))->result_array();
        foreach ($models as $model) {
            $model['fields'] = array();
            $model['fields_org'] = $CI->db->where('model', $model['id'])->order_by('`order`', 'ASC')->get($CI->db->dbprefix('_model_fields'))->result_array();
            $model['listable'] = array();
            $model['searchable'] = array();
            foreach ($model['fields_org'] as $key => & $v) {
                if ($v['listable'] == 1) {
                    array_push($model['listable'], $v['id']);
                }
                if ($v['searchable'] == 1) {
                    array_push($model['searchable'], $v['id']);
                }
                if (in_array($v['type'], array('select', 'checkbox', 'radio'))) {
                    if ($v['values'] == '') {
                        $v['values'] = array();
                    } else {
                        $value = array();
                        foreach (explode('|', $v['values']) as $vt) {
                            if (strpos($vt, '=') > - 1) {
                                $vt = explode('=', $vt);
                                $value[$vt[0]] = $vt[1];
                            } else {
                                $value[$vt] = $vt;
                            }
                        }
                        $v['values'] = $value;
                    }
                }
                $model['fields'][$v['id']] = $v;
            }
            unset($model['fields_org']);
            $data[$model['name']] = $model;
        }
        return $data;
    }
    function load_role($target = '') {
        $CI = $this->_ci;
        $target = is_array($target) ? $target : array($target);
        $CI->db->where_in('id', $target);
        $role = $CI->db->get($CI->db->dbprefix('_roles'))->row_array();
        $role['rights'] = explode(',', $role['rights']);
        $role['models'] = explode(',', $role['models']);
        $this->setting['current_role'] = $role;
        return $role;
    }
    // ------------------------------------------------------------------------
    
}
