<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fabrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Externalcompanies_controller extends CI_Controller 
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
            $this->load->model('externalcompanies_model', '_externalcompanies_model');
            $this->actions                                                      =   $this->_externalcompanies_model->actions_by_role($this->session->userdata['id_role'], 'EXTERNALCOMPANIES');
            $this->breadcrumb                                                   =   $this->_externalcompanies_model->get_breadcrumb('EXTERNALCOMPANIES');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('EXTERNALCOMPANIES', $this->session->userdata['modules']) == FALSE)
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
            $this->_view->assign('act_details',                                 in_array('DETAILS', $this->actions));
            $this->_view->assign('act_assign',                                  in_array('ASSIGN', $this->actions));
            $this->_view->assign('act_drop',                                    in_array('UDROP', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));

            $this->_view->assign('path_view',                                   site_url('externalcompanies/datatable'));
            $this->_view->assign('path_add',                                    site_url('externalcompanies/add'));
            $this->_view->assign('path_edit',                                   site_url('externalcompanies/edit'));
            $this->_view->assign('path_userflags',                              site_url('externalcompanies/userflags'));
            $this->_view->assign('path_editflags',                              site_url('externalcompanies/editflags'));
            $this->_view->assign('path_drop',                                   site_url('externalcompanies/udrop'));
            $this->_view->assign('path_trace',                                  site_url('externalcompanies/trace'));
            $this->_view->assign('path_display',                                site_url('externalcompanies/display'));
            $this->_view->assign('path_export_xlsx',                            site_url('externalcompanies/exportxlsx'));
            $this->_view->assign('path_roles',                                  site_url('externalcompanies/roles'));
            $this->_view->assign('path_location',                               site_url('externalcompanies/location'));

            $this->_view->display('admin/externalcompanies.tpl');
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
                    0                                                                   =>  'name_cv_ec',
                    1                                                                   =>  'nit_cv_ec',
                    2                                                                   =>  'type_cv_ec',
                    3                                                                   =>  'email_cv_ec',
                    4                                                                   =>  'phone_cv_ec'
                                                                                    );
            }
            else
            {
                $columns                                                        =   array(
                    0                                                                   =>  'name_cv_ec',
                    1                                                                   =>  'nit_cv_ec',
                    2                                                                   =>  'type_cv_ec',
                    3                                                                   =>  'email_cv_ec',
                    4                                                                   =>  'phone_cv_ec'
                                                                                    );
            }

            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];

            $count_rows                                                         =   $this->_externalcompanies_model->count_rows($search);
            $all_rows                                                           =   $this->_externalcompanies_model->all_rows($limit, $start, $search, $order, $dir);

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
                header("Location: " . site_url('externalcompanies'));
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
                $exist_company                                                  =   $this->_externalcompanies_model->exist_company($params);

                if ($exist_company['data'])
                {
                    $add                                                        =   $this->_externalcompanies_model->add($params);

                    echo json_encode($add);
                    exit();
                }
                else
                {
                    echo json_encode($exist_company);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('externalcompanies'));
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
                header("Location: " . site_url('externalcompanies'));
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
                $exist_company                                                  =   $this->_externalcompanies_model->exist_company($params);

                if ($exist_company['data'])
                {
                    $edit                                                        =   $this->_externalcompanies_model->edit($params);

                    echo json_encode($edit);
                    exit();
                }
                else
                {
                    echo json_encode($exist_company);
                    exit();
                }
            }
            else
            {
                if ($this->input->method(TRUE) == 'GET')
                {
                    header("Location: " . site_url('externalcompanies'));
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
                header("Location: " . site_url('externalcompanies'));
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
    * @return    json array
    **/
    public function location()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);
        $table_name                                                             =   $params['name'];

        $tables = [
            'countries' => [
                'name'                                                          =>  'git_countries',
                'id'                                                            =>  'id_country',
                'text'                                                          =>  'name_country',
            ],
            'departments' => [
                'name'                                                          =>  'git_departments',
                'id'                                                            =>  'id_department',
                'text'                                                          =>  'name_department',
            ],
            'cities' => [
                'name'                                                          =>  'git_cities',
                'id'                                                            =>  'id_city',
                'text'                                                          =>  'name_city',
            ],
        ];

        if (count($tables[$table_name]) > 0 && $params['parentId'] != 0 && strlen($params['parentName']) > 0)
        {
            $parent_table_name                                                  =   $params['parentName'];
            $location                                                           =   $this->_externalcompanies_model->location_select($params, $tables[$table_name], $tables[$parent_table_name]);
            
            echo json_encode($location);
            exit();
        }

        if (count($tables[$table_name]) > 0) {
            $location                                                           =   $this->_externalcompanies_model->location_select($params, $tables[$table_name]);

            echo json_encode($location);
            exit();
        } 
        else 
        {
            if ($this->input->method(TRUE) == 'GET') {
                header("Location: " . site_url('externalcompanies'));
            } else {
                echo json_encode(array('data' => FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}