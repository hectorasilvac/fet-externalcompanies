<?php
/**
*@author 	Innovación y Tecnología
*@copyright 2021 Fabrica de Desarrollo
*@version 	v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications_controller extends CI_Controller 
{
	private $actions;

    public function __construct()
    {
        parent::__construct();

        if(isset($this->session->userdata['id_user']) || isset($this->session->userdata['id_aspirant']) || isset($this->session->userdata['id_contributor']))
        { 
            $this->load->model('notifications_model', '_notifications_model');       
        }
        else
        {
            header("Location: " . site_url('login'));
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
    public function notifications()
    {
        $notifications                                                          =   $this->_notifications_model->show_notifications();

        echo json_encode($notifications);
        exit();        
    }
}