<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fabrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Scblock_controller extends CI_Controller
{
    private $actions;

    public function __construct()
    {
        parent::__construct();

        if (!isset($this->session->userdata['id_user']) && !isset($this->session->userdata['id_worker']))
        {
           header("Location: " . site_url('login'));
           exit();
        }
        else if (isset($this->session->userdata['id_project']) && ($this->session->userdata['id_project'] == "5"))
        {
            $this->load->model('shiftchange_model', '_shiftchange_model');

            header("Location: " . $this->session->userdata['initial_site']);
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param
    * @return    boolean
    **/
    public function view()
    {
        if (isset($this->session->userdata['name_worker']))
        {
            $name_user_whatsapp                                                 =   $this->_trabajandofet_model->to_camel($this->session->userdata['name_worker']);
            $name_user_whatsapp                                                 =   str_replace(' ', '%20', $name_user_whatsapp);

            $this->_view->assign('name_user_whatsapp',                          $name_user_whatsapp);
        }
        else
        {
            $name_user_whatsapp                                                 =   $this->_trabajandofet_model->to_camel($this->session->userdata['user']);
            $name_user_whatsapp                                                 =   str_replace(' ', '%20', $name_user_whatsapp);

            $this->_view->assign('name_user_whatsapp',                          $name_user_whatsapp);
        }

        $this->_view->display('admin/scblock.tpl');

        exit();
    }
}