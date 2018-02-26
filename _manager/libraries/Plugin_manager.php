<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 插件管理类
 */
class Plugin_manager {
    protected $app = null;
    protected $classmap = array();
    protected $plugins = array();
    protected $menus = array();
    private $instances = array();
    public function __construct() {
        $this->app = get_instance();
        $this->initialize();
    }
    private function initialize() {
        //加载插件
        $setting['plugins'] = $this->app->settings->item('plugins');
        if (isset($setting['plugins']) and is_array($setting['plugins'])) {
            $this->plugins = $setting['plugins'];
            unset($setting['plugins']);
        }
        if ($this->app->uri->rsegment(1) == 'category_content' || $this->app->uri->rsegment(1) == 'content') {
            $model = $this->app->input->get('model', true);
            //重新分析classmap，加入autoloader
            foreach ($this->plugins as $name => $plugin) {
                foreach ($plugin['classmap'] as $filename => $hook) {
                    if ($model == $filename) {
                        $filename = ($name . '_hook_' . $model);
                        $this->classmap[$filename] = $hook;
                    }
                }
            }
        }
        //加载菜单
        foreach ($this->plugins as $name => $plugin) {
            $this->menus[$name] = $plugin['menus'];
        }
    }
    private function autoloader($class_name) {
        if (class_exists(ucfirst($class_name))) {
            return true;
        }
        $path = APPPATH . $this->classmap[$class_name];
        if (isset($this->classmap[$class_name]) and file_exists($path)) {
            include $path;
        }
        if (!class_exists(ucfirst($class_name))) {
            throw new RuntimeException("Can't Find Class $class_name.");
        }
    }
    public function trigger($method, &$data = null, $other = null) {
        $args = array(&$data, $other);
        foreach ($this->classmap as $class => $path) {
            if (!isset($this->instances[$class])) {
                $this->autoloader($class);
                $this->instances[$class] = new $class;
            }
            return call_user_func_array(array($this->instances[$class], $method), $args);
        }
    }
    public function get_menus() {
        return $this->menus;
    }
}
interface CMS_Model_Hook_Interface {
    /**
     * 为操作工具栏新增按钮
     */
    public function buttons();
    /**
     * 模型数据新增入库前
     */
    public function inserting(&$data);
    /**
     * 模型数据新增入库后
     */
    public function inserted($data, $id);
    /**
     * 模型数据修改入库前
     */
    public function updating(&$data, $id);
    /**
     * 模型数据修改入库后
     */
    public function updated($data, $id);
    /**
     * 模型数据删除操作前
     */
    public function deleting(&$ids);
    /**
     * 模型数据删除操作后
     */
    public function deleted($ids);
    /**
     * 模型数据表单展示前
     */
    public function rendering(&$data);
    /**
     * 模型数据表单展示后
     */
    public function rendered($content);
    /**
     * 模型数据列表执行查询前
     */
    public function querying(&$where);
    /**
     * 模型数据列表展示前
     */
    public function listing(&$results);
    /**
     * 模型数据列表展示后
     */
    public function listed($results);
    /**
     * 模型数据列表各记录操作位置
     */
    public function row_buttons($data);
    /**
     * 模型数据进入列表页面开始处理前
     *
     * 可用于更细化的权限判断
     */
    //注册模型信息进入列表信息动作
    public function reached();
}
/**
 * CMS 插件基类
 *
 * @package     CMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.cms.com
 */
abstract class CMS_Plugin_Controller {
    protected $name = '';
    protected $app = null;
    protected $path = '';
    public function __construct($name) {
        $this->name = $name;
        $this->app = & get_instance();
        $this->path = APPPATH . 'plugins/' . $this->name . '/';
        $this->add_packages();
    }
    private function add_packages() {
        $this->load->add_package_path($this->path);
    }
    protected function plugin_url($controller, $method, $qs = array()) {
        return plugin_url($this->name, $controller, $method, $qs);
    }
    public function get_path() {
        return $this->path;
    }
    public function __get($name) {
        if (property_exists($this->app, $name)) {
            return $this->app->$name;
        }
    }
}
