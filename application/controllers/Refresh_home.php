<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refresh_home extends MY_Controller {

    public function index()
    {
        redirect('/', 'refresh');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */