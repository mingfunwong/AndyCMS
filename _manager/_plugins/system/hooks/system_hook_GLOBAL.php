<?php

class System_hook_GLOBAL implements CMS_Model_Hook_Interface {
    
    /**
     * 模型数据表单展示前
     */
    public function rendering(&$data){}
    
    /**
     * 模型数据表单展示后
     */
    public function rendered($content){}
        
    /**
     * 为操作工具栏新增按钮 
     */
    public function buttons(){}

    /**
     * 模型数据新增入库前
     */
    public function inserting(&$data){}
    
    /**
     * 模型数据新增入库后
     */
    public function inserted($data, $id){}
    
    /**
     * 模型数据修改入库前
     */
    public function updating(&$data, $id){}
    
    /**
     * 模型数据修改入库后
     */
    public function updated($data, $id){}
    
    /**
     * 模型数据删除操作前
     */
    public function deleting(&$ids){}
    
    /**
     * 模型数据删除操作后
     */
    public function deleted($ids){}
    
    
    /**
     * 模型数据列表执行查询前
     */
    public function querying(&$where){}
    
    /**
     * 模型数据列表展示前
     */
    public function listing(&$results){}
    
    /**
     * 模型数据列表展示后
     */
    public function listed($results){}
    
    /**
     * 模型数据列表各记录操作位置
     */
    public function row_buttons($data){}
    
    /**
     * 模型数据进入列表页面开始处理前
     * 
     * 可用于更细化的权限判断
     */
    public function reached(){}
    
}