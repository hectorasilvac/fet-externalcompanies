<?php
/**
*@author 	Innovación y Tecnología
*@copyright 2021 Fabrica de Desarrollo
*@version 	v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Trabajandofet_controller extends CI_Controller 
{
	private $actions;

    public function __construct()
    {
        parent::__construct();

        if(isset($this->session->userdata['id_user']) || isset($this->session->userdata['id_aspirant']) || isset($this->session->userdata['id_contributor']))
        { 
            $this->load->model('login_model', '_login_model');
        }
        else
        {
           header("Location: " . site_url('login'));
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
    public function user_edit()
    {
        $this->_view->assign('path_edit',                                       site_url('useredit/edit'));
        $this->_view->assign('path_dashboard',                                  $this->session->userdata['initial_site']);
        $this->_view->assign('txt_user',                                        $this->_login_model->get_user());

        $this->_view->display('admin/user_edit.tpl');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json array
    **/
    public function edit()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            $edit                                                               =   $this->_login_model->edit($params);

            echo json_encode($edit);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . $this->session->userdata['initial_site']);
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param
    *@return
    **/
    public function logout()
    {
        $this->session->sess_destroy();
        header("Location: " . site_url('login'));
        exit();
    }
}
