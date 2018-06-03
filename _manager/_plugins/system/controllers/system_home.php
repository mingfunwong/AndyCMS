<?php

class System_home extends CMS_Plugin_Controller
{
    
    public function hello()
    {
        $this->load->model('system_model');
        $this->config->load('system_app');
        // echo $this->config->item('system_app');
        $data['content'] = $this->system_model->say_hello();
        $data['bread'] = $this->system_model->say_hello();
        return $this->load->view('index.php', $data, true);
    }
       
}