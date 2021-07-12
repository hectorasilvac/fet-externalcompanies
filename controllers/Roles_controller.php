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

class Roles_controller extends CI_Controller
{
    private $actions;

    public function __construct()
    {
        parent::__construct();

        if(!isset($this->session->userdata['id_user']))
        {
           header("Location: " . site_url('login'));
           exit();
        }
        else
        {
            $this->load->model('roles_model', '_roles_model');
            $this->actions                                                      =   $this->_roles_model->actions_by_role($this->session->userdata['id_role'], 'ROLES');
            $this->breadcrumb                                                   =   $this->_roles_model->get_breadcrumb('ROLES');
 
            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('ROLES', $this->session->userdata['modules']) == FALSE)
                {
                    if(!isset($this->session->userdata['initial_site']))
                    {
                        header("Location: " . $this->session->userdata['initial_site']);
                        exit();
                    }
                }
            }
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
            $this->_view->assign('act_view',                                    in_array('VIEW', $this->actions));
            $this->_view->assign('act_add',                                     in_array('ADD', $this->actions));
            $this->_view->assign('act_edit',                                    '0'); // Se bloquea por la definición por ID de cada rol
            $this->_view->assign('act_drop',                                    in_array('UDROP', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));

            $this->_view->assign('path_view',                                   site_url('roles/datatable'));
            $this->_view->assign('path_add',                                    site_url('roles/add'));
            $this->_view->assign('path_detail',                                 site_url('roles/detail'));
            $this->_view->assign('path_edit',                                   site_url('roles/edit'));
            $this->_view->assign('path_drop',                                   site_url('roles/udrop'));
            $this->_view->assign('path_trace',                                  site_url('roles/trace'));
            $this->_view->assign('path_export_xlsx',                            site_url('roles/exportxlsx'));

            $this->_view->display('admin/roles.tpl');
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
    * @param
    * @return    array json
    **/
    public function datatable()
    {
        if(in_array('VIEW', $this->actions)) 
        {
            if ($this->session->userdata['mobile'] == 0)
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_role',
                    1                                                                   =>  'name_role'
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_role'
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_roles_model->count_rows($search);
            $all_rows                                                           =   $this->_roles_model->all_rows($limit, $start, $search, $order, $dir);

            $json_data                                                          =   array(
                "draw"                                                                  =>  intval($this->input->post('draw')),
                "recordsTotal"                                                          =>  intval($count_rows['total']),
                "recordsFiltered"                                                       =>  intval($count_rows['total_filtered']),
                "data"                                                                  =>  $all_rows
                                                                                    );

            echo json_encode($json_data);
            exit();
        } 
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('roles'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
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
                $exist_role                                                     =   $this->_roles_model->exist_role($params);

                if ($exist_role['data'])
                {
                    $add                                                        =   $this->_roles_model->add($params);

                    echo json_encode($add);
                    exit();
                }
                else
                {
                    echo json_encode($exist_role);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('roles'));
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
                header("Location: " . site_url('roles'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
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
        if(in_array('EDIT', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $exist_role                                                     =   $this->_roles_model->exist_role($params);

                if ($exist_role['data'])
                {
                    $edit                                                       =   $this->_roles_model->edit($params);

                    echo json_encode($edit);
                    exit();
                }
                else
                {
                    echo json_encode($exist_role);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('roles'));
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
                header("Location: " . site_url('roles'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $param
    * @return    json array
    **/
    public function udrop()
    {
        if(in_array('UDROP', $this->actions))
        {
            $param                                                              =   $this->security->xss_clean($_POST);

            if ($param)
            {
                $drop                                                           =   $this->_roles_model->udrop($param);

                echo json_encode($drop);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('roles'));
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
                header("Location: " . site_url('roles'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $param
    * @return    json array
    **/
    public function trace()
    {
        if(in_array('TRACE', $this->actions))
        {
            $param                                                              =   $this->security->xss_clean($_POST);

            if ($param)
            {
                $trace                                                          =   $this->_roles_model->trace_register($param);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('roles'));
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
                header("Location: " . site_url('roles'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param
    * @return    file
    **/
    public function export_xlsx()
    {
        if(in_array('EXPORTXLSX', $this->actions))
        {
            if ((isset($_GET["search"])) && ($_GET["search"] != "null") && ($_GET["search"] != ""))
            {
                $search                                                         =   $_GET["search"];
            }
            else
            {
                $search                                                         =   "";
            }

            $spreadsheet                                                        =   new Spreadsheet();
            $sheet                                                              =   $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'No')
                  ->setCellValue('B1', 'Nombre');

            $sheet->getStyle('A1:B1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(20);

            $export_xlsx                                                        =   $this->_roles_model->export_xlsx($search);
            $count                                                              =   2;

            for ($i = 0; $i < count($export_xlsx['data']); $i++)
            {
                $column                                                         =   'B';

                foreach ($export_xlsx['data'][$i] as $key => $value)
                {
                    $sheet->setCellValue(''."A".''.$count.'', ''.($i + 1).'');
                    $sheet->setCellValue(''.$column.''.$count.'', ''.$export_xlsx['data'][$i][$key].'');
                    $column++;
                }

                $count++;
            }

            $writer                                                             =   new Xlsx($spreadsheet);
            $sheet->setTitle('Roles');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_roles_' . date('dmY') . '.xlsx"');
            header('Cache-Control: max-age=0');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer->save('php://output');
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET') 
            {
                header("Location: " . site_url('roles'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}