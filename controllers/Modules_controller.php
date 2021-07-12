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

class Modules_controller extends CI_Controller
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
            $this->load->model('modules_model', '_modules_model');
            $this->actions                                                      =   $this->_modules_model->actions_by_role($this->session->userdata['id_role'], 'MODULES');
            $this->breadcrumb                                                   =   $this->_modules_model->get_breadcrumb('MODULES');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('MODULES', $this->session->userdata['modules']) == FALSE)
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
            $this->_view->assign('act_edit',                                    in_array('EDIT', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));

            $this->_view->assign('path_view',                                   site_url('modules/datatable'));
            $this->_view->assign('path_add',                                    site_url('modules/add'));
            $this->_view->assign('path_edit',                                   site_url('modules/edit'));
            $this->_view->assign('path_trace',                                  site_url('modules/trace'));
            $this->_view->assign('path_export_xlsx',                            site_url('modules/exportxlsx'));
            $this->_view->assign('path_users_permission',                       site_url('modules/userspermissions'));

            $this->_view->display('admin/modules.tpl');
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
    * @return    array json_data
    **/
    public function datatable()
    {
        if(in_array('VIEW', $this->actions))
        {
            if ($this->session->userdata['mobile'] == 0)
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_module',
                    1                                                                   =>  'name_module',
                    2                                                                   =>  'name_es_module',
                    3                                                                   =>  'url_module',
                    4                                                                   =>  'icon_module'
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_module',
                    1                                                                   =>  'name_es_module',
                    2                                                                   =>  'url_module',
                    3                                                                   =>  'icon_module'
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_modules_model->count_rows($search);
            $all_rows                                                           =   $this->_modules_model->all_rows($limit, $start, $search, $order, $dir);

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
                header("Location: " . site_url('modules'));
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
                $exist_module                                                   =   $this->_modules_model->exist_module($params);

                if ($exist_module['data'])
                {
                    $add                                                        =   $this->_modules_model->add($params);

                    echo json_encode($add);
                    exit();
                }
                else
                {
                    echo json_encode($exist_module);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('modules'));
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
                header("Location: " . site_url('modules'));
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

                if ($params['name'] == 'icon_module' || ($params['name'] == 'url_module' && trim($params['value']) == '#'))
                {
                    $edit                                                       =   $this->_modules_model->edit($params);

                    echo json_encode($edit);
                    exit();
                }
                else
                {
                    $exist_module                                               =   $this->_modules_model->exist_module($params);

                    if ($exist_module['data'])
                    {
                        $edit                                                   =   $this->_modules_model->edit($params);

                        echo json_encode($edit);
                        exit();
                    }
                    else
                    {
                        echo json_encode($exist_module);
                        exit();
                    }
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('modules'));
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
                header("Location: " . site_url('modules'));
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
    public function users_permissions()
    {
        $param                                                                 =   $this->security->xss_clean($_POST);

        if ($param)
        {
            $users                                                              =   $this->_modules_model->users_permissions($param);

            echo json_encode($users);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('modules'));
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
    public function trace()
    {
        if(in_array('TRACE', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $trace                                                          =   $this->_modules_model->trace_register($params);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('modules'));
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
                header("Location: " . site_url('modules'));
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
                  ->setCellValue('B1', 'Nombre')
                  ->setCellValue('C1', 'Significado')
                  ->setCellValue('D1', 'URL')
                  ->setCellValue('E1', 'Icono');

            $sheet->getStyle('A1:E1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(30);
            $sheet->getColumnDimension('E')->setWidth(20);

            $export_xlsx                                                        =   $this->_modules_model->export_xlsx($search);
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
            $sheet->setTitle('Modulos');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_modulos_' . date('dmY') . '.xlsx"');
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
                header("Location: " . site_url('modules'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}