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

class Bankentities_controller extends CI_Controller 
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
            $this->load->model('bankentities_model', '_bankentities_model');
            $this->actions                                                      =   $this->_bankentities_model->actions_by_role($this->session->userdata['id_role'], 'BANKENTITIES');
            $this->breadcrumb                                                   =   $this->_bankentities_model->get_breadcrumb('BANKENTITIES');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('BANKENTITIES', $this->session->userdata['modules']) == FALSE)
                {
                    header("Location: " . $this->session->userdata['initial_site']);
                    exit();
                }
            }
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param       
    * @return    
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
            $this->_view->assign('act_detail',                                  in_array('DETAILS', $this->actions));
            $this->_view->assign('act_assign',                                  in_array('ASSIGN', $this->actions));
            $this->_view->assign('act_drop',                                    in_array('UDROP', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));

            $this->_view->assign('path_view',                                   site_url('bankentities/datatable'));
            $this->_view->assign('path_add',                                    site_url('bankentities/add'));
            $this->_view->assign('path_edit',                                   site_url('bankentities/edit'));
            $this->_view->assign('path_detail',                                 site_url('bankentities/detail'));
            $this->_view->assign('path_drop',                                   site_url('bankentities/udrop'));
            $this->_view->assign('path_trace',                                  site_url('bankentities/trace'));
            $this->_view->assign('path_export_xlsx',                            site_url('bankentities/exportxlsx'));
            $this->_view->assign('path_affiliated_workers',                     site_url('bankentities/affiliatedworkers'));

            $this->_view->display('admin/bankentities.tpl');
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
                    0                                                                   =>  'name_bankentity',
                    1                                                                   =>  'name_bankentity',
                    2                                                                   =>  'nit_bankentity',
                    3                                                                   =>  'contact_bankentity',
                    4                                                                   =>  'phone_bankentity',
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_bankentity',
                    1                                                                   =>  'nit_bankentity',
                    2                                                                   =>  'contact_bankentity',
                    3                                                                   =>  'phone_bankentity',  
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_bankentities_model->count_rows($search);
            $all_rows                                                           =   $this->_bankentities_model->all_rows($limit, $start, $search, $order, $dir);

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
                header("Location: " . site_url('bankentities'));
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
                $exist_bank                                                     =   $this->_bankentities_model->exist_bank($params);

                if ($exist_bank['data'])
                {
                    $add                                                        =   $this->_bankentities_model->add($params);
                    
                    echo json_encode($add);
                    exit();
                } 
                else 
                {
                    echo json_encode($exist_bank);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('bankentities'));
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
                header("Location: " . site_url('bankentities'));
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
        if (in_array('EDIT', $this->actions)) 
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params) 
            {
                $exist_bank                                                     =   $this->_bankentities_model->exist_bank($params);

                if ($exist_bank['data'])
                {
                    $edit                                                       =   $this->_bankentities_model->edit($params);
                    
                    echo json_encode($edit);
                    exit();
                } 
                else 
                {
                    echo json_encode($exist_bank);
                    exit();
                }
            } 
            else 
            {
                if ($this->input->method(TRUE) == 'GET') 
                {
                    header("Location: " . site_url('bankentities'));
                } 
                else 
                {
                    echo json_encode(array('data' => FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
                }

                exit();
            }
        } 
        else 
        {
            if ($this->input->method(TRUE) == 'GET') 
            {
                header("Location: " . site_url('bankentities'));
            } 
            else 
            {
                echo json_encode(array('data' => FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
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
    public function detail()
    {
        if(in_array('DETAILS', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                    $detail                                                     =   $this->_bankentities_model->detail($params);

                    echo json_encode($detail);
                    exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('bankentities'));
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
                header("Location: " . site_url('bankentities'));
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
    public function affiliated_workers()
    {
        if (in_array('VIEW', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $affiliated_workers                                             =   $this->_bankentities_model->affiliated_workers($params);

                echo json_encode($affiliated_workers);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('bankentities'));
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
                header("Location: " . site_url('bankentities'));
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
                $udrop                                                          =   $this->_bankentities_model->udrop($param);

                echo json_encode($udrop);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('bankentities'));
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
                header("Location: " . site_url('bankentities'));
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
                $trace                                                          =   $this->_bankentities_model->trace_register($param);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('bankentities'));
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
                header("Location: " . site_url('bankentities'));
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
                  ->setCellValue('C1', 'Abreviatura')
                  ->setCellValue('D1', 'NIT')
                  ->setCellValue('E1', 'Dígito de verificación')
                  ->setCellValue('F1', 'Código del banco')
                  ->setCellValue('G1', 'Contacto')
                  ->setCellValue('H1', 'Teléfono')
                  ->setCellValue('I1', 'Correo corporativo')
                  ->setCellValue('J1', 'Dirección');


            $sheet->getStyle('A1:J1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(40);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getColumnDimension('I')->setWidth(30);
            $sheet->getColumnDimension('J')->setWidth(40);



            $export_xlsx                                                        =   $this->_bankentities_model->export_xlsx($search);
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
            $sheet->setTitle('Entidades Bancarias');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_entidades_bancarias_' . date('dmY') . '.xlsx"');
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
                header("Location: " . site_url('bankentities'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}