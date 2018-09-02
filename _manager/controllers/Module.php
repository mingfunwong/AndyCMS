<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 模块插件执行控制器
 */
class Module extends Admin_Controller {
    protected $plugin = null;
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
        $this->initialize();
    }
    private function initialize() {
        $plugin = $this->input->get('plugin', TRUE);
        $this->_check_permit();
        $controller = $this->input->get('c', true);
        $method = $this->input->get('m', TRUE);
        $path = APPPATH . '_plugins/' . $plugin . '/controllers/' . $plugin . '_' . $controller . '.php';
        if ($controller and file_exists($path)) {
            include $path;
            $controller = ucfirst($plugin . '_' . $controller);
            $this->plugin = new $controller($plugin);
            $data['content'] = $this->plugin->$method();
            $this->_template('', $data);
            exit($this->output->get_output());
        } else {
            $this->_message('未找到处理程序!', '', FALSE);
        }
    }
}
