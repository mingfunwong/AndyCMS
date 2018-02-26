<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Excel 上传 下载
 */
class Excel extends Admin_Controller {
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        parent::__construct();
        require_once APPPATH . 'libraries/PHPExcel.php';
    }
    // 下载文件
    public function download() {
        $model = $this->input->get('model', TRUE);
        if (!$this->settings->item($model, 'models')) {
            $this->_message('不存在的模型！', '', TRUE);
        }
        $data['model'] = $this->settings->item('models');
        $data['model'] = $data['model'][$model];
        $this->load->library('form');
        $this->load->library('field_behavior');
        $array = $fields_temp = array();
        $fields = array('序号', '创建时间', '更新时间');
        foreach ($data['model']['fields'] as $key => $val) {
            $fields_temp[$val['name']] = $val;
            $fields[] = $val['description'];
        }
        $array[] = $fields;
        foreach ($this->db->order_by('id', 'ASC')->get($model)->result() as $key => $val) {
            $list = array($val->id, date('Y-m-d H:i:s', $val->create_time), date('Y-m-d H:i:s', $val->update_time));
            foreach ($data['model']['fields'] as $key2 => $val2) {
                if (isset($val->$val2['name'])) {
                    $list[] = $this->field_behavior->on_list($val2, $val);
                }
            }
            $array[] = $list;
        }
        $filename = $data['model']['description'] . date(" Ymd");
        $data = $array;
        $this->_download($filename, $data);
    }
    // 上传文件
    public function _upload_post() {
        if (!$_FILES['file']['tmp_name']) {
            $this->_message('上传失败', '', TRUE);
        }
        $model = $this->input->get('model', TRUE);
        $table = $model;
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $filename = $_FILES['file']['tmp_name'];
        $PHPExcel = $objReader->load($filename);
        $currentSheet = $PHPExcel->getSheet(0);
        $array = $currentSheet->ToArray();
        // 去除首行
        if (isset($array[0])) unset($array[0]);
        $data = array();
        foreach ($array as $key => $value) {
            $values = array();
            foreach ($value as $key => $value) {
                if (in_array($key, array(1, 2))) {
                    // 创建时间和修改时间
                    $datetime = ($value) ? strtotime($value) : time();
                    $values[] = $this->db->escape($datetime);
                } else {
                    $values[] = $this->db->escape($value);
                }
            }
            $data[] = $values;
        }
        $this->db->db_debug = false;
        $this->db->trans_start();
        echo "<!--\n";
        foreach ($data as $key => $values) {
            $sql = "INSERT INTO " . $this->db->dbprefix($table) . " VALUES (" . implode(', ', $values) . ");";
            echo "{$sql}\n";
            $this->db->query($sql);
        }
        echo "-->\n";
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->_message('导入失败，导入格式有误。\n可能发生的错误：\n序号已被使用。序号可不填，但不能填写重复的序号。', '', TRUE);
        } else {
            $this->_message('导入成功！', '', TRUE);
        }
    }
    private function _download($filename, $data) {
        $objPHPExcel = new PHPExcel();
        // 填充数据
        $l = 0; //行
        foreach ($data as $key => $value) {
            $count = count($value);
            for ($p = 0;$p < $count;$p++) { //列
                $skey = PHPExcel_Cell::stringFromColumnIndex($p) . ($l + 1); // 生成行列索引
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($skey, $value[$p]);
                // 首行加粗
                if ($l == 0) $objPHPExcel->setActiveSheetIndex(0)->getStyle($skey)->getFont()->setBold(true);
            }
            $l++;
        }
        // 指向第一格
        $objPHPExcel->setActiveSheetIndex()->getStyle('A1');
        // 导出
        // Redirect output to a client’s web browser (Excel5)
        $xlsTitle = iconv('utf-8', 'gb2312', $filename);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $xlsTitle . '.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
