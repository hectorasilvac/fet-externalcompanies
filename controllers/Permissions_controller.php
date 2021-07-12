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

class Permissions_controller extends CI_Controller
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
            $this->load->model('permissions_model', '_permissions_model');
            $this->actions                                                      =   $this->_permissions_model->actions_by_role($this->session->userdata['id_role'], 'PERMISSIONS');
            $this->breadcrumb                                                   =   $this->_permissions_model->get_breadcrumb('PERMISSIONS');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('PERMISSIONS', $this->session->userdata['modules']) == FALSE)
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
            $this->_view->assign('act_drop',                                    in_array('DROP', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));

            $this->_view->assign('path_view',                                   site_url('permissions/datatable'));
            $this->_view->assign('path_actions_submodule',                      site_url('permissions/actions'));
            $this->_view->assign('path_add',                                    site_url('permissions/add'));
            $this->_view->assign('path_drop',                                   site_url('permissions/drop'));
            $this->_view->assign('path_export_xlsx',                            site_url('permissions/exportxlsx'));
            $this->_view->assign('path_trace',                                  site_url('permissions/trace'));
            $this->_view->assign('submodules',                                  site_url('permissions/submodules'));
            $this->_view->assign('roles',                                       site_url('permissions/roles'));

            $this->_view->display('admin/permissions.tpl');
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
    * @return    $array json_data
    **/
    public function datatable()
    {
        if(in_array('VIEW', $this->actions))
        {
            if ($this->session->userdata['mobile'] == 0)
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_role',
                    1                                                                   =>  'name_role',
                    2                                                                   =>  'name_submodule',
                    3                                                                   =>  'name_action'
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_role',
                    1                                                                   =>  'name_submodule',
                    2                                                                   =>  'name_action'
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_permissions_model->count_rows($search);
            $all_rows                                                           =   $this->_permissions_model->all_rows($limit, $start, $search, $order, $dir);

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
                header("Location: " . site_url('permissions'));
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
    public function roles()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $roles                                                              =   $this->_permissions_model->roles_select($params);

            echo json_encode($roles);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('permissions'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
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
    public function submodules()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $submodules                                                         =   $this->_permissions_model->submodules_select($params);

            echo json_encode($submodules);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('permissions'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     $params[]
    * @return    $array edit | exist_submodule
    **/
    public function actions()
    {
        if(in_array('ADD', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            $columns                                                            =   array(
                0                                                                       =>  'name_es_action',
                1                                                                       =>  'id_action'
                                                                                    );

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   'id_action';
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_permissions_model->count_rows_actions($search, $params);
            $all_rows                                                           =   $this->_permissions_model->all_rows_actions($limit, $start, $search, $order, $dir, $params);

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
                header("Location: " . site_url('permissions'));
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
                $add                                                            =   $this->_permissions_model->add($params);

                echo json_encode($add);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('permissions'));
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
                header("Location: " . site_url('permissions'));
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
    public function drop()
    {
        if(in_array('DROP', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $drop                                                           =   $this->_permissions_model->drop($params);

                echo json_encode($drop);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('permissions'));
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
                header("Location: " . site_url('permissions'));
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
    public function trace()
    {
        if(in_array('TRACE', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $trace                                                          =   $this->_permissions_model->trace_register($params);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('permissions'));
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
                header("Location: " . site_url('permissions'));
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
    * @return    $output excel | $array export_xlsx
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
                  ->setCellValue('B1', 'Rol')
                  ->setCellValue('C1', 'Submodulo')
                  ->setCellValue('D1', 'Acción');

            $sheet->getStyle('A1:D1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(30);

            $export_xlsx                                                        =   $this->_permissions_model->export_xlsx($search);
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
            $sheet->setTitle('Permisos');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_permisos_' . date('dmY') . '.xlsx"');
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
                header("Location: " . site_url('permissions'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}