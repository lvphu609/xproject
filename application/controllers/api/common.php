
<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Base_Controller.php';

class common extends Rest_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/common');

    }

    function
}