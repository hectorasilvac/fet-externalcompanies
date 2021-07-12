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

class Users_controller extends CI_Controller
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
            $this->load->model('users_model', '_users_model');
            $this->actions                                                      =   $this->_users_model->actions_by_role($this->session->userdata['id_role'], 'USERS');
            $this->breadcrumb                                                   =   $this->_users_model->get_breadcrumb('USERS');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('USERS', $this->session->userdata['modules']) == FALSE)
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
            $this->_view->assign('act_drop',                                    in_array('UDROP', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));
            $this->_view->assign('act_display',                                 in_array('DISPLAY', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));

            $this->_view->assign('all_flags',                                   $this->_users_model->flags_select());

            $this->_view->assign('path_view',                                   site_url('users/datatable'));
            $this->_view->assign('path_add',                                    site_url('users/add'));
            $this->_view->assign('path_edit',                                   site_url('users/edit'));
            $this->_view->assign('path_userflags',                              site_url('users/userflags'));
            $this->_view->assign('path_editflags',                              site_url('users/editflags'));
            $this->_view->assign('path_drop',                                   site_url('users/udrop'));
            $this->_view->assign('path_trace',                                  site_url('users/trace'));
            $this->_view->assign('path_display',                                site_url('users/display'));
            $this->_view->assign('path_export_xlsx',                            site_url('users/exportxlsx'));
            $this->_view->assign('path_roles',                                  site_url('users/roles'));
            $this->_view->assign('path_aspirants',                              site_url('users/aspirants'));

            $this->_view->display('admin/users.tpl');
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
                    0                                                                   =>  'gu.name_user',
                    1                                                                   =>  'gu.name_user',
                    2                                                                   =>  'fr.name_role',
                    3                                                                   =>  'gu.user',
                    4                                                                   =>  'gu.email_user',
                    5                                                                   =>  'gu.date_keepalive',
                    6                                                                   =>  'fa.name_aspirant'
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'gu.name_user',
                    1                                                                   =>  'fr.name_role',
                    2                                                                   =>  'gu.user',
                    3                                                                   =>  'gu.email_user',
                    4                                                                   =>  'gu.date_keepalive',
                    5                                                                   =>  'fa.name_aspirant'
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_users_model->count_rows($search);
            $all_rows                                                           =   $this->_users_model->all_rows($limit, $start, $search, $order, $dir);

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
                header("Location: " . site_url('users'));
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
            $roles                                                              =   $this->_users_model->roles_select($params);

            echo json_encode($roles);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('users'));
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
    public function aspirants()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $aspirants                                                          =   $this->_users_model->aspirants_select($params);

            echo json_encode($aspirants);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('users'));
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
                $exist_user                                                     =   $this->_users_model->exist_user($params);

                if ($exist_user['data'])
                {
                    $add                                                        =   $this->_users_model->add($params);

                    echo json_encode($add);
                    exit();
                }
                else
                {
                    echo json_encode($exist_user);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
                if ($params['name'] == 'email_user' || $params['name'] == 'user' || $params['name'] == 'id_aspirant')
                {
                    $exist_user                                                 =   $this->_users_model->exist_user($params);

                    if ($exist_user['data'])
                    {
                        $edit                                                   =   $this->_users_model->edit($params);

                        echo json_encode($edit);
                        exit();
                    }
                    else
                    {
                        echo json_encode($exist_user);
                        exit();
                    }
                }
                else
                {
                    $edit                                                       =   $this->_users_model->edit($params);

                    echo json_encode($edit);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
    public function user_flags()
    {
        if(in_array('EDIT', $this->actions))
        {
            $param                                                              =   $this->security->xss_clean($_POST);

            if ($param)
            {
                $flags                                                          =   $this->_users_model->user_flags($param);

                echo json_encode($flags);
                exit();

            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
    public function edit_flags()
    {
        if(in_array('EDIT', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $edit                                                           =   $this->_users_model->edit_flags($params);

                echo json_encode($edit);
                exit();

            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
    public function display()
    {
        if(in_array('DISPLAY', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $display                                                        =   $this->_users_model->display($params);

                echo json_encode($display);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
                $udrop                                                          =   $this->_users_model->udrop($param);

                echo json_encode($udrop);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
                $trace                                                          =   $this->_users_model->trace_register($param);

                echo json_encode($trace);
                exit();
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('users'));
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
                header("Location: " . site_url('users'));
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
                  ->setCellValue('C1', 'Apellido')
                  ->setCellValue('D1', 'Rol')
                  ->setCellValue('E1', 'Usuario')
                  ->setCellValue('F1', 'Correo')
                  ->setCellValue('G1', 'Último ingreso')
                  ->setCellValue('H1', 'Aspirante');

            $sheet->getStyle('A1:H1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(30);
            $sheet->getColumnDimension('G')->setWidth(30);
            $sheet->getColumnDimension('H')->setWidth(30);

            $export_xlsx                                                        =   $this->_users_model->export_xlsx($search);
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
            $sheet->setTitle('Usuarios');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="trabajandofet_usuarios_' . date('dmY') . '.xlsx"');
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
                header("Location: " . site_url('users'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}