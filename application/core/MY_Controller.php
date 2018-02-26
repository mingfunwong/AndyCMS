<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class My_Controller extends CI_Controller {

    // 模板主题
    public $theme = 'default';

    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper("util");
    }

    // ------------------------------------------------------------------------
    
    /**
     * 加载视图
     *
     * @access  protected
     * @param   string
     * @param   array
     * @return  void
     */
    protected function view($template, $data = array()) {
        $data['theme'] = $this->theme;
        $data['tpl'] = $template;
        $data['system'] = $this->db->get("system")->row();
        $data['homepage'] = $this->db->get("homepage")->row();
        $data['title'] = from($data, 'title', $data['system']->name);
        $data['keywords'] = from($data, 'keywords', $data['system']->keywords);
        $data['description'] = from($data, 'description', $data['system']->description);

        header("Content-Type: text/html; charset=UTF-8");
        $this->load->view("{$this->theme}/_layout", $data);
    }

}
