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

class Shiftchange_controller extends CI_Controller
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
        else if (isset($this->session->userdata['id_project']) && ($this->session->userdata['id_project'] == "5") || isset($this->session->userdata['id_user']))
        {
            $this->load->model('shiftchange_model', '_shiftchange_model');
            $this->actions                                                      =   $this->_shiftchange_model->actions_by_role($this->session->userdata['id_role'], 'SHIFTCHANGE');
            $this->breadcrumb                                                   =   $this->_shiftchange_model->get_breadcrumb('SHIFTCHANGE');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('SHIFTCHANGE', $this->session->userdata['modules']) == FALSE)
                {
                    header("Location: " . $this->session->userdata['initial_site']);
                    exit();
                }
            }
        }
        else if (isset($this->session->userdata['id_project']) && ($this->session->userdata['id_project'] != "5"))
        {
            header("Location: " . site_url('scblock'));
            exit();
        }
        else
        {
            if ($this->session->userdata['initial_site'] != 'shiftchange')
            {
                header("Location: " . $this->session->userdata['initial_site']);
                exit();
            }
            else
            {
                header("Location: " . site_url('logout'));
                exit();
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
            $this->_view->assign('act_detail',                                  in_array('DETAILS', $this->actions));
            $this->_view->assign('act_assign',                                  in_array('ASSIGN', $this->actions));
            $this->_view->assign('act_drop',                                    in_array('UDROP', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));

            $this->_view->assign('path_workers',                                site_url('shiftchange/workers'));
            $this->_view->assign('path_coordinators',                           site_url('shiftchange/coordinators'));
            
            $this->_view->assign('path_view',                                   site_url('shiftchange/datatable'));
            $this->_view->assign('path_add',                                    site_url('shiftchange/add'));
            $this->_view->assign('path_detail',                                 site_url('shiftchange/detail'));
            $this->_view->assign('path_signature',                              site_url('shiftchange/signature/'));
            $this->_view->assign('path_assign',                                 site_url('shiftchange/assign'));
            $this->_view->assign('path_mail_replacement',                       site_url('shiftchange/mailreplacement'));
            $this->_view->assign('path_change_coordinator',                     site_url('shiftchange/changecoordinator'));
            $this->_view->assign('path_drop',                                   site_url('shiftchange/udrop'));
            $this->_view->assign('path_trace',                                  site_url('shiftchange/trace'));
            $this->_view->assign('path_export_xlsx',                            site_url('shiftchange/exportxlsx'));
            $this->_view->assign('path_application',                            APPLICATION);

            if (isset($this->session->userdata['id_user']))
            {
                $this->_view->assign('flag_filter',                             1);
                $this->_view->assign('flag_coordinator',                        1);
            }
            else
            {
                $this->_view->assign('flag_filter',                             0);
                $this->_view->assign('flag_coordinator',                        0);
            }

            if (isset($this->session->userdata['id_worker']))
            {
                $this->_view->assign('flag_email',                              $this->_shiftchange_model->email_worker_flag($this->session->userdata['id_worker']));
                $this->_view->assign('flag_worker',                             1);
            }
            else
            {
                $this->_view->assign('flag_email',                              TRUE);
                $this->_view->assign('flag_worker',                             0);
            }

            $this->_view->assign('day_now',                                     date("d-m-Y", strtotime(date("Y-m-d"))));
            $this->_view->assign('month',                                       $this->data_last_month_day());
            $this->_view->assign('worker',                                      (isset($this->session->userdata['id_worker']) ? $this->session->userdata['id_worker']: FALSE));

            $this->_view->display('admin/shiftchange.tpl');
        }
        else
        {
            header("Location: " . $this->session->userdata['initial_site']);
        }

        exit();
    }
    
    public function data_last_month_day()
    { 
        $month = date('m') + 1;
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
        
        return date('d-m-Y', mktime(0,0,0, $month, $day, $year));
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
                    0                                                                   =>  'fc1.name_cv',
                    1                                                                   =>  'fc1.name_cv',
                    2                                                                   =>  'fsc.date_shiftchange',
                    3                                                                   =>  'fc2.name_cv',
                    4                                                                   =>  'fsc.date_return_shiftchange',
                    5                                                                   =>  'fsc.id_shiftchange',
                    6                                                                   =>  'fsc.id_shiftchange'
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'fc1.name_cv',
                    1                                                                   =>  'fsc.date_shiftchange',
                    2                                                                   =>  'fc2.name_cv',
                    3                                                                   =>  'fsc.date_return_shiftchange',
                    4                                                                   =>  'fsc.id_shiftchange',
                    5                                                                   =>  'fsc.id_shiftchange'
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $state                                                              =   $this->input->post('shiftchange_state');
            $coordinator                                                        =   $this->input->post('shiftchange_coordinator');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_shiftchange_model->count_rows($search, $state, $coordinator);
            $all_rows                                                           =   $this->_shiftchange_model->all_rows($limit, $start, $search, $order, $dir, $state, $coordinator);

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
                header("Location: " . site_url('shiftchange'));
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
    public function workers()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $workers                                                            =   $this->_shiftchange_model->workers_select($params);

            echo json_encode($workers);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('shiftchange'));
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
    public function coordinators()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $coordinators                                                       =   $this->_shiftchange_model->coordinators_select($params);

            echo json_encode($coordinators);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('shiftchange'));
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
    public function add()
    {
        if(in_array('ADD', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                if (!isset($params['email_worker_applicant']))
                {
                    if (isset($this->session->userdata['id_worker']))
                    {
                        $worker['id_worker']                                    =   $this->session->userdata['id_worker'];

                        $worker_select                                          =   $this->_shiftchange_model->worker_select($worker);

                        $params['email_worker_applicant']                       =   $worker_select['email_ccv'];
                    }
                }

                if ($params['flag_signature_applicant'] == '')
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Debes firmar tu solicitud de cambio de turno para que esta pase a aprobación.';

                    echo json_encode($result);
                    exit();
                }

                if ($params['id_worker_applicant'] == $params['id_worker_replacement'])
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'No es posible crear el cambio de turno cuando el solicitante es la misma persona de reemplazo.';

                    echo json_encode($result);
                    exit();
                }

                if ($params['email_worker_applicant'] == $params['email_worker_replacement'])
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Verifica la información ingresada, el correo del solicitante no puede ser el mismo del reemplazo.';

                    echo json_encode($result);
                    exit();
                }

                if (($params['date_shiftchange'] == $params['date_return_shiftchange']) && ($params['type_shiftchange'] == $params['type_return_shiftchange']))
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Verifica la información ingresada, el turno de cambio y el turno de devolución no puede ser igual sobre la misma fecha.';

                    echo json_encode($result);
                    exit();
                }

                $exist_shiftchange                                              =   $this->_shiftchange_model->exist_shiftchange($params);

                if ($exist_shiftchange['data'])
                {
                    $add                                                        =   $this->_shiftchange_model->add($params);

                    echo json_encode($add);
                    exit();
                }
                else
                {
                    echo json_encode($exist_shiftchange);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
    public function detail()
    {
        if(in_array('DETAILS', $this->actions))
        {
            $param                                                              =   $this->security->xss_clean($_POST);

            if ($param)
            {
                $detail                                                         =   $this->_shiftchange_model->detail($param);

                echo json_encode($detail);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
    public function file_signature($file)
    {
        if(in_array('DETAILS', $this->actions))
        {
            if ($file)
            {
                $signature                                                      =   $this->_shiftchange_model->file_signature($file);

                echo json_encode($signature);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
    public function mail_replacement()
    {
        if(in_array('ASSIGN', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $mail_replacement                                               =   $this->_shiftchange_model->mail_replacement($params);

                echo json_encode($mail_replacement);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
    public function assign()
    {
        if(in_array('ASSIGN', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $assign                                                         =   $this->_shiftchange_model->assign($params);

                echo json_encode($assign);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
    public function change_coordinator()
    {
        if(in_array('ASSIGN', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $change                                                         =   $this->_shiftchange_model->change_coordinator($params);

                echo json_encode($change);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
                $udrop                                                          =   $this->_shiftchange_model->udrop($param);

                echo json_encode($udrop);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
                $trace                                                          =   $this->_shiftchange_model->trace_register($params);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('shiftchange'));
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
                header("Location: " . site_url('shiftchange'));
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
                  ->setCellValue('B1', 'Trabajador solicitante')
                  ->setCellValue('C1', 'Trabajador de reemplazo')
                  ->setCellValue('D1', 'Fecha de cambio')
                  ->setCellValue('E1', 'Turno de cambio')
                  ->setCellValue('F1', 'Fecha de reemplazo')
                  ->setCellValue('G1', 'Turno de reemplazo')
                  ->setCellValue('H1', 'Coordinadora')
                  ->setCellValue('I1', 'Estado');

            $sheet->getStyle('A1:I1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(50);
            $sheet->getColumnDimension('C')->setWidth(50);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(25);
            $sheet->getColumnDimension('F')->setWidth(25);
            $sheet->getColumnDimension('G')->setWidth(25);
            $sheet->getColumnDimension('H')->setWidth(35);
            $sheet->getColumnDimension('I')->setWidth(20);

            $export_xlsx                                                        =   $this->_shiftchange_model->export_xlsx($search);
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
            $sheet->setTitle('Cambio de turnos');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_turnos_' . date('dmY') . '.xlsx"');
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
                header("Location: " . site_url('shiftchange'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}