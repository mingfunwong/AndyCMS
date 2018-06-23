<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 内容模型内容管理控制器
 */
class Content extends Admin_Controller {
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
     * 默认入口(列表页)
     *
     * @access  public
     * @return  void
     */
    public function view() {
        $this->_view_post();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 内容列表页
     *
     * @access  public
     * @return  void
     */
    public function _view_post() {
        $model = $this->input->get('model', TRUE);
        $this->_check_permit();
        if (!$this->settings->item($model, 'models')) {
            $this->_message('不存在的模型！', '', FALSE);
        }
        $this->plugin_manager->trigger('reached');
        $data['model'] = $this->settings->item($model, 'models');
        // 单页显示
        if ($data['model']['single']) {
            $_GET['id'] = 1;
            $this->form();
            return;
        }
        $this->load->library('form');
        $this->load->library('field_behavior');
        $data['provider'] = $this->_pagination($data['model']);
        $data['bread'] = make_bread(Array($data['model']['description'] => '',));
        $this->_template('content_list', $data);
    }
    // ------------------------------------------------------------------------
    
    /**
     * 分页处理
     *
     * @access  private
     * @param   array
     * @return  array
     */
    private function _pagination($model) {
        $this->load->library('pagination');
        $config['base_url'] = backend_url('content/view');
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
        $config['num_links'] = 10;
        $config['suffix'] = '?model=' . $model['name'];
        $condition = array('id >' => '0');
        $data['where'] = array();
        foreach ($model['searchable'] as $v) {
            $this->field_behavior->on_do_search($model['fields'][$v], $condition, $data['where'], $config['suffix']);
        }
        if ($model['level']) {
            $parentid = $this->input->get("parentid") ? $this->input->get("parentid") : 0;
            $data['where'] = $parentid;
            $condition['parentid ='] = $parentid;
        }
        $this->plugin_manager->trigger('querying', $condition);
        $config['total_rows'] = $this->db->where($condition)->count_all_results($model['name']);
        $this->db->from($model['name']);
        $this->db->select('id, create_time');
        $this->db->where($condition);
        $this->field_behavior->set_extra_condition();
        foreach ($model['listable'] as $v) {
            $this->db->select($model['fields'][$v]['name']);
        }
        foreach ($model['fields'] as $v) {
            if (isset($v['name']) && $v['name'] == 'order') {
                $this->db->order_by('order', 'ASC');
            }
        }
        $order = $model['level'] ? "ASC" : "DESC";
        $this->db->order_by('id', $order);
        $this->db->offset($this->uri->segment($config['uri_segment'], 0));
        $this->db->limit($config['per_page']);
        $data['list'] = $this->db->get()->result();
        $this->plugin_manager->trigger('listing', $data['list']);
        $config['first_url'] = $config['base_url'] . $config['suffix'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        return $data;
    }
    // ------------------------------------------------------------------------
    
    /**
     * 添加/修改入口
     *
     * @access  public
     * @return  void
     */
    public function form() {
        $this->_save_post('view');
    }
    // ------------------------------------------------------------------------
    
    /**
     * 添加/修改表单显示/处理函数
     *
     * @access  public
     * @return  void
     */
    public function _save_post($type = '') {
        $this->load->library('form_validation');
        $this->load->library('form');
        $this->load->library('field_behavior');
        $model = $this->input->get('model', TRUE);
        $this->session->set_userdata('model_type', 'model');
        $this->session->set_userdata('model', $model);
        $data['model'] = $this->settings->item($model, 'models');
        $id = $this->input->get('id');
        $data['button_name'] = $id ? '编辑' : '添加';
        $data['bread'] = make_bread(Array($data['model']['description'] => site_url('content/view?model=' . $data['model']['name']), $data['button_name'] => '',));
        if ($id) {
            $this->_check_permit('edit');
            $data['content'] = $this->db->where('id', $id)->get($model)->row_array();
        } else {
            $this->_check_permit('add');
            $data['content'] = array();
        }
        $data['parentid'] = from($data['content'], 'parentid', $this->input->get('parentid', true));
        $is_validation = false;
        foreach ($data['model']['fields'] as $v) {
            if ($v['rules'] != '') {
                $is_validation = true;
                $this->form_validation->set_rules($v['name'], $v['description'], str_replace(",", "|", $v['rules']));
            }
        }
        if ($is_validation && $this->form_validation->run() == FALSE) {
            $this->plugin_manager->trigger('rendering', $data);
            return $this->_template('content_form', $data);
        }
        if ($type == 'view') {
            $this->plugin_manager->trigger('rendering', $data);
            $this->_template('content_form', $data);
        } else {
            $modeldata = $data['model'];
            $data = array();
            foreach ($modeldata['fields'] as $v) {
                if ($v['editable']) {
                    $this->field_behavior->on_do_post($v, $data);
                }
            }
            if ($modeldata['level']) {
                $data['parentid'] = $this->input->post('parentid', true);
            }
            if ($id) {
                $this->db->where('id', $id);
                $data['update_time'] = time();
                $this->plugin_manager->trigger('updating', $data, $id);
                $this->db->update($model, $data);
                $this->plugin_manager->trigger('updated', $data, $id);
                $this->_message('修改成功！', 'content/form', TRUE, '?model=' . $modeldata['name'] . '&id=' . $id);
            } else {
                $data['create_time'] = $data['update_time'] = time();
                $this->plugin_manager->trigger('inserting', $data);
                $this->db->insert($model, $data);
                $id = $this->db->insert_id();
                $this->plugin_manager->trigger('inserted', $data, $id);
                $this->_message('添加成功！', 'content/view', TRUE, '?model=' . $modeldata['name']);
            }
        }
    }
    // ------------------------------------------------------------------------
    
    /**
     * 删除入口
     *
     * @access  public
     * @return  void
     */
    public function del() {
        $this->_check_permit();
        $this->_del_post();
    }
    // ------------------------------------------------------------------------
    
    /**
     * 删除处理函数
     *
     * @access  public
     * @return  void
     */
    public function _del_post() {
        $this->_check_permit();
        $ids = $this->input->get_post('id', TRUE);
        $model = $this->input->get('model', TRUE);
        if ($ids) {
            if (!is_array($ids)) {
                $ids = array($ids);
            }
            $this->plugin_manager->trigger('deleting', $ids);
            $this->db->where_in('id', $ids)->delete($model);
            $this->plugin_manager->trigger('deleted', $ids);
        }
        $this->_message('删除成功！', '', TRUE);
    }

}
