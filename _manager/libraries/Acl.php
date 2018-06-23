<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 后台权限控制类
 */
class Acl {
    /**
     * ci
     * CI超级类句柄
     *
     * @var object
     * @access  private
     *
     */
    private $ci = NULL;
    /**
     * _left_menus
     * 二三级菜单集合
     *
     * @var array
     * @access  private
     *
     */
    private $left_menus = array();
    /**
     * _rights
     * 权限集合
     *
     * @var array
     * @access  public
     *
     */
    public $rights = array();
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        $this->ci = & get_instance();
        if ($this->ci->_admin->role != 1) {
            $this->ci->settings->load_role($this->ci->_admin->role); //加载权限数据
            $this->rights = setting('current_role');
        }
        $this->_filter_menus();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 边栏菜单
     *
     * @access  public
     * @return  void
     */
    public function left_menus() {
        $menus = array();
        foreach ($this->left_menus as $key => $v) {
            if (!from($v, 'sub_menus')) continue;
            // 一级菜单
            $menu = array('name' => $v['menu_name'], 'icon' => from($v, 'icon'),);
            $menu_sub = array();
            foreach ($v['sub_menus'] as $j) {
                // 插件
                if (isset($j['plugin'])) {
                    $menu_sub[] = array('name' => $j['menu_name'], 'url' => plugin_url($key, $j['class_name'], $j['method_name']), 'active' => isset($j['current']), 'icon' => from($j, 'icon'),);
                    continue;
                }
                if (isset($j['model'])) {
                    // 内容
                    $menu_sub[] = array('name' => $j['menu_name'], 'url' => backend_url($j['class_name'] . '/' . $j['method_name'], 'model=' . $j['model']), 'active' => isset($j['current']), 'icon' => from($j, 'icon'));
                } else {
                    // 系统
                    $menu_sub[] = array('name' => $j['menu_name'], 'url' => backend_url($j['class_name'] . '/' . $j['method_name']), 'active' => isset($j['current']), 'icon' => from($j, 'icon'));
                }
            }
            $menu['sub'] = $menu_sub;
            $menus[] = $menu;
        }
        return $menus;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 过滤菜单
     *
     * @access  private
     * @return  void
     */
    private function _filter_menus() {
        $this->ci->load->library('plugin_manager');
        $class_name = $this->ci->uri->rsegment(1);
        $method_name = $this->ci->uri->rsegment(2);
        // 系统菜单
        $system_menus = $this->ci->settings->load_system_menus();
        foreach ($system_menus['sub_menus'] as $jkey => & $j) {
            if ($j['class_name'] == $class_name) {
                $j['current'] = TRUE;
            }
            if ($this->ci->_admin->role == 1) {
                continue;
            }
            if (!in_array($j['class_name'], $this->rights['rights']) AND !in_array($j['class_name'], array("system", "password"))) {
                unset($system_menus['sub_menus'][$jkey]);
            }
        }
        // 内容菜单
        $content_menus = $this->ci->settings->load_content_menus();
        $model = $this->ci->input->get('model');
        foreach ($content_menus['sub_menus'] as $jkey => & $j) {
            $j = (array) $j;
            if ($j['class_name'] == $class_name AND $j['model'] == $model) {
                $j['current'] = TRUE;
            }
            if ($this->ci->_admin->role == 1) {
                continue;
            }
            if (!in_array($j['model'], $this->rights['models'])) {
                unset($content_menus['sub_menus'][$jkey]);
            }
        }
        // 插件菜单
        $plugin_menus = $this->ci->plugin_manager->get_menus();
        foreach ($plugin_menus as $key => & $v) {
            if (isset($v['sub_menus']) AND $v['sub_menus']) {
                foreach ($v['sub_menus'] as & $j) {
                    $j['plugin'] = 'plugin=' . $key . '&action=' . $j['method_name'];
                    if ($key == $this->ci->input->get('plugin') AND $j['class_name'] == $this->ci->input->get('c') AND $j['method_name'] == $this->ci->input->get('m')) {
                        $j['current'] = TRUE;
                    }
                }
            } else {
                unset($plugin_menus[$key]);
            }
        }
        $this->left_menus = array_merge(array($system_menus, $content_menus), $plugin_menus);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 检测权限
     *
     * @access  public
     * @param   string
     * @return  void
     */
    public function permit($act = '', $folder = '') {
        if ($this->ci->_admin->role == 1) {
            return TRUE;
        }
        $class_method = $folder . $this->ci->uri->rsegment(1);
        if (!in_array($class_method, array("content", "module")) && !in_array($class_method, $this->rights['rights'])) {
            return FALSE;
        }
        if ($this->ci->uri->rsegment(1) == 'content') {
            if (!in_array($this->ci->input->get('model'), $this->rights['models'])) {
                return FALSE;
            }
        }
        return TRUE;
    }
    // ------------------------------------------------------------------------
    
}
