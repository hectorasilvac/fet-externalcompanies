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

class Biomechanicalform_controller extends CI_Controller
{
    private $actions;

    public function __construct()
    {

        parent::__construct();

        if (isset($this->session->userdata['id_worker']))
        {
            $this->load->model('biomechanicalform_model', '_biomechanicalform_model');

            $this->actions                                                      =   $this->_biomechanicalform_model->actions_by_role($this->session->userdata['id_role'], 'BIOMECHANICALFORM');
            $this->breadcrumb                                                   =   $this->_biomechanicalform_model->get_breadcrumb('BIOMECHANICALFORM');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('BIOMECHANICALFORM', $this->session->userdata['modules']) == FALSE)
                {
                    header("Location: " . $this->session->userdata['initial_site']);
                    exit();
                }
            }
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
    public function view()
    {
        if ($this->breadcrumb != FALSE)
        {
            $this->_view->assign('module_layout',                               $this->breadcrumb['name_es_module']);
            $this->_view->assign('submodule_layout',                            $this->breadcrumb['name_es_submodule']);
            $this->_view->assign('module_active',                               '"'.$this->breadcrumb['name_module']);
            $this->_view->assign('submodule_active',                            '"'.$this->breadcrumb['url_submodule']);
        }
        else
        {
            $this->_view->assign('module_layout',                               '');
            $this->_view->assign('submodule_layout',                            '');
            $this->_view->assign('module_active',                               'noreplace');
            $this->_view->assign('submodule_active',                            'noreplace');
        }

        if ($this->actions != FALSE)
        {
            $this->_view->assign('participate',                                 $this->_biomechanicalform_model->participate());
            $this->_view->assign('worker_name',                                 $this->_biomechanicalform_model->worker_name());
            $this->_view->assign('act_view',                                    in_array('VIEW', $this->actions));
            $this->_view->assign('act_add',                                     in_array('ADD', $this->actions));
            $this->_view->assign('random',                                      random_int(1, 3));

            $this->_view->assign('path_add',                                    site_url('biomechanicalform/add'));

            if ($this->session->userdata['id_project'] == "5")
            {
                $this->_view->assign('path_finish',                             site_url('shiftchange'));
            }
            else
            {
                $this->_view->assign('path_finish',                             site_url('logout'));
            }

            $this->_view->display('admin/biomechanicalform.tpl');
        }
        else
        {
            header("Location: " . $this->session->userdata['initial_site']);
        }

        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json array
    **/
    public function add()
    {
        if(in_array('ADD', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $params['worker_insert']                                        =   $this->session->userdata['id_worker'];
                $params['axa_2']                                                =   date('Y-m-d');

                $add                                                            =   $this->_biomechanicalform_model->add($params);

                echo json_encode($add);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('biomechanicalform'));
                }
                else
                {
                    echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
                }

                exit();
            }
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('biomechanicalform'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}