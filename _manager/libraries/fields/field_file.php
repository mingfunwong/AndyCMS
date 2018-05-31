<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS 扩展字段
 */
class field_file {
    /**
     * $k
     * 自定义的字段标识，需要唯一
     *
     * @var string
     * @access  public
     *
     */
    public $k = 'file';
    /**
     * $v
     * 自定义的字段名称
     *
     * @var string
     * @access  public
     *
     */
    public $v = '文件上传(VARCHAR)';
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        //可以根据需求初始化数据
        
    }
    /**
     * 生成字段的创建信息
     *
     * @access  public
     * @param   array  $data 该值为新建或修改字段时候表单提交的POST数组
     * @return  array
     */
    public function on_info($data) {
        return array('type' => 'VARCHAR', 'constraint' => from($data, 'length', 100), 'default' => '');
    }
    /**
     * 生成字段的表单控件
     *
     * 此处，我仅仅是加了个链接
     *
     * @access  public
     * @param   array  $field 该值为字段的基本信息，结构见settings/model下的缓存文件，或者查看数据库表结构
     * @param   string $default 默认值，用于提供默认值，或者表单回填所需
     * @param   bool   $tip 是否显示,若是，则输出字段的验证规则
     * @return  void
     */
    public function on_form($field, $default = '', $has_tip = TRUE) {
        echo '<div class="form-group is-empty is-fileinput">
    <input type="file" multiple="" name="' . $field['name'] . '">
    <div class="input-group">
      <input type="text" readonly="" class="form-control" placeholder="请选择文件">
        <span class="input-group-btn input-group-sm">
          <button type="button" class="btn btn-fab btn-fab-mini">
            <i class="material-icons">attach_file</i>
          </button>
        </span>
    </div>
</div>';
        if ($field['ruledescription']) {
            echo '<label>' . $field['ruledescription'] . '</label>';
        }
        if ($default) {
            echo '<p>当前文件：<a target="_blank" href="' . $default . '">' . $default . '</a>';
            echo ' <label><input type="checkbox" name="' . $field['name'] . '_delete" value="true" >删除</label></p>';
        }
    }
    /**
     * 生成字段的列表的控件
     *
     * 这里简单的输出字段的值
     *
     * @access  public
     * @param   array  $field 同上
     * @param   object  $record 一条数据库记录
     * @return  void
     */
    public function on_list($field, $record) {
        return $record->$field['name'];
    }
    /**
     * 生成字段的搜索表单的控件
     *
     * 此字段不支持搜索
     *
     * @access  public
     * @param   array $field 同上
     * @param   string $default 同上上
     * @return  void
     */
    public function on_search($field, $default) {
        echo '对不起，此字段不支持搜索';
    }
    /**
     * 执行字段在搜索操作的行为
     *
     * 不支持搜索
     *
     * @access  public
     * @param   array $field 同上
     * @param   array $condition ,引用传递，记录搜索条件的数组，此数组直接用于$this->db->where(),区别于下面的$where
     * @param   array $where, 引用传递， 简单的对于GET数据的过滤后的产物，用于回填搜索的表单
     * @param   string $suffix 引用传递，用于拼接搜索条件，此货的产生现在看来完全没有必要，下个版本必将消失
     * @return  void
     */
    public function on_do_search($field, &$condition, &$where, &$suffix) {
        //do nothing
        
    }
    /**
     * 执行字段提交的行为
     *
     * 对于上传文件的处理，这里我将使上传的文件可以存入附件表，将会对$_POST进行进行操作,具体看代码。
     * 为了不影响表单的提交被阻断，如果上传失败，则维持该字段的值不变.
     * 为了兼容SAE平台，未使用CI上传类.
     *
     * @access  public
     * @param   array $field 同上
     * @param   array $post 引用传递, 用于记录post过来的值，用于插入数据库，处理请小心
     * @return  void
     */
    public function on_do_post($field, &$post) {
    	// 删除文件
    	if (isset($_POST[$field['name'] . '_delete']) && $_POST[$field['name'] . '_delete'] == 'true') {
    		$post[$field['name']] = '';
    	}

        if (!$_FILES[$field['name']]['error']) {
            $CI = & get_instance();
            $CI->load->helper('date');
            $_timestamp = now();
            $upload['folder'] = date('Y/m', $_timestamp);
            $target_path = FCPATH . setting('attachment_dir') . '/' . $upload['folder'];
            $realname = explode('.', $_FILES[$field['name']]['name']);
            $upload['type'] = strtolower(array_pop($realname));
            $upload['realname'] = implode('.', $realname);
            $upload['name'] = $_timestamp . substr(md5($upload['realname'] . rand()), 0, 16);
            $target_file = $target_path . '/' . $upload['name'] . '.' . $upload['type'];
            // 检查类型
            if ($field['values']) {
                if (!in_array($upload['type'], explode('|', $field['values']))) {
                    return;
                }
            } else {
                if (in_array($upload['type'], array('php')))
                    return;
            }
            if (file_upload($_FILES[$field['name']]['tmp_name'], $target_file)) {
                $post[$field['name']] = '/' . setting('attachment_dir') . '/' . $upload['folder'] . '/' . $upload['name'] . '.' . $upload['type'];
            }
        }
    }
}
