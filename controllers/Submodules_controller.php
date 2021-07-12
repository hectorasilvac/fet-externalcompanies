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

class Submodules_controller extends CI_Controller
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
            $this->load->model('submodules_model', '_submodules_model');
            $this->actions                                                      =   $this->_submodules_model->actions_by_role($this->session->userdata['id_role'], 'SUBMODULES');
            $this->breadcrumb                                                   =   $this->_submodules_model->get_breadcrumb('SUBMODULES');

            if($this->session->userdata['id_role'] != "11")
            {
               if(in_array('SUBMODULES', $this->session->userdata['modules']) == FALSE)
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
    * @param     array $this->actions
    * @return    booleans
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
            $this->_view->assign('act_change',                                  in_array('ASSIGN', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));

            $this->_view->assign('path_view',                                   site_url('submodules/datatable'));
            $this->_view->assign('path_modules',                                site_url('submodules/modules/select'));
            $this->_view->assign('path_add',                                    site_url('submodules/add'));
            $this->_view->assign('path_update_state_action',                    site_url('submodules/updatestateaction'));
            $this->_view->assign('path_update_actions_all',                     site_url('submodules/updateactionsall'));
            $this->_view->assign('path_edit',                                   site_url('submodules/edit'));
            $this->_view->assign('path_actions_submodule',                      site_url('submodules/actions'));
            $this->_view->assign('path_trace',                                  site_url('submodules/trace'));
            $this->_view->assign('path_export_xlsx',                            site_url('submodules/exportxlsx'));

            $this->_view->display('admin/submodules.tpl');
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
    * @param     integer $limit, integer $start, string $search, string $order, string $dir
    * @return    $array json_data
    **/
    public function datatable()
    {
        if(in_array('VIEW', $this->actions))
        {
            if ($this->session->userdata['mobile'] == 0)
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_submodule',
                    1                                                                   =>  'name_submodule',
                    2                                                                   =>  'name_es_submodule',
                    3                                                                   =>  'url_submodule',
                    4                                                                   =>  'name_es_module',
                    5                                                                   =>  'id_submodule',
                    6                                                                   =>  'id_module',
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_submodule',
                    1                                                                   =>  'name_es_submodule',
                    2                                                                   =>  'url_submodule',
                    3                                                                   =>  'name_es_module',
                    4                                                                   =>  'id_submodule',
                    5                                                                   =>  'id_module',
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_submodules_model->count_rows($search);
            $all_rows                                                           =   $this->_submodules_model->all_rows($limit, $start, $search, $order, $dir);

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
                header("Location: " . site_url('submodules'));
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
    * @param     $params[]
    * @return    $array modules
    **/
    public function modules()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if (count($params) > 0)
        {
            $modules                                                            =   $this->_submodules_model->modules_select($params);

            echo json_encode($modules);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('submodules'));
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
    * @param     integer $limit, integer $start, string $search, string $order, string $dir
    * @return    $array json_data
    **/
    public function actions()
    {
        if(in_array('ADD', $this->actions) || in_array('ASSIGN', $this->actions))
        {
            $param                                                              =   $this->security->xss_clean($_POST);

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   'name_es_action';
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_submodules_model->count_rows_actions($search);
            $all_rows                                                           =   $this->_submodules_model->all_rows_actions($limit, $start, $search, $order, $dir, $param['id_submodule']);

            $json_data                                                          =   array(
                "draw"                                                                  =>  intval($this->input->post('draw')),
                "recordsTotal"                                                          =>  intval($count_rows['total']),
                "recordsFiltered"                                                       =>  intval($count_rows['total_filtered']),
                "data"                                                                  =>  $all_rows,
                "data_count"                                                            =>  count($all_rows)
                                                                                    );

            echo json_encode($json_data);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('submodules'));
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
    * @param     $params[]
    * @return    $array add | exist_submodule
    **/
    public function add()
    {
        if(in_array('ADD', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $exist_submodule                                                =   $this->_submodules_model->exist_submodule($params);

                if ($exist_submodule['data'])
                {
                    $params['sort_submodule']                                   =   $this->_submodules_model->sort_submodule($params);
                    $add                                                        =   $this->_submodules_model->add($params);

                    echo json_encode($add);
                    exit();
                }
                else
                {
                    echo json_encode($exist_submodule);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('submodules'));
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
                header("Location: " . site_url('submodules'));
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
    * @param     $params[]
    * @return    $array update_state_action
    **/
    public function update_state_action()
    {
        if(in_array('ADD', $this->actions) || in_array('ASSIGN', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $update_state_action                                            =   $this->_submodules_model->update_state_action($params);

                echo json_encode($update_state_action);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('submodules'));
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
                header("Location: " . site_url('submodules'));
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
    * @param     $params[]
    * @return    $array update_actions_all
    **/
    public function update_actions_all()
    {
        if(in_array('ADD', $this->actions) || in_array('ASSIGN', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $update_actions_all                                             =   $this->_submodules_model->update_actions_all($params);

                echo json_encode($update_actions_all);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('submodules'));
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
                header("Location: " . site_url('submodules'));
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
    * @param     $params[]
    * @return    $array edit | exist_submodule
    **/
    public function edit()
    {
        if(in_array('EDIT', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $exist_submodule                                                =   $this->_submodules_model->exist_submodule($params);

                if ($exist_submodule['data'])
                {
                    $edit                                                       =   $this->_submodules_model->edit($params);

                    echo json_encode($edit);
                    exit();
                }
                else
                {
                    echo json_encode($exist_submodule);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('submodules'));
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
                header("Location: " . site_url('submodules'));
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
                $trace                                                          =   $this->_submodules_model->trace_register($param);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('submodules'));
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
                header("Location: " . site_url('submodules'));
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
    * @param     search date_update
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
                  ->setCellValue('B1', 'Nombre')
                  ->setCellValue('C1', 'Significado')
                  ->setCellValue('D1', 'URL')
                  ->setCellValue('E1', 'Módulo');

            $sheet->getStyle('A1:E1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(30);
            $sheet->getColumnDimension('E')->setWidth(30);

            $export_xlsx                                                        =   $this->_submodules_model->export_xlsx($search);
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
            $sheet->setTitle('Submodulos');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_submodulos_' . date('dmY') . '.xlsx"');
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
                header("Location: " . site_url('submodules'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}