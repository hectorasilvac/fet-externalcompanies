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
            $this->_view->assign('act_details',                                 in_array('DETAILS', $this->actions));
            $this->_view->assign('act_assign',                                  in_array('ASSIGN', $this->actions));
            $this->_view->assign('act_drop',                                    in_array('UDROP', $this->actions));
            $this->_view->assign('act_trace',                                   in_array('TRACE', $this->actions));
            $this->_view->assign('act_export_xlsx',                             in_array('EXPORTXLSX', $this->actions));

            $this->_view->assign('path_view',                                   site_url('bankentities/datatable'));
            $this->_view->assign('path_add',                                    site_url('bankentities/add'));
            $this->_view->assign('path_edit',                                   site_url('bankentities/edit'));
            $this->_view->assign('path_details',                                site_url('bankentities/details'));
            $this->_view->assign('path_drop',                                   site_url('bankentities/udrop'));
            $this->_view->assign('path_trace',                                  site_url('bankentities/trace'));
            $this->_view->assign('path_display',                                site_url('bankentities/display'));
            $this->_view->assign('path_export_xlsx',                            site_url('bankentities/exportxlsx'));
            $this->_view->assign('path_location',                               site_url('bankentities/location'));
            $this->_view->assign('path_find',                                   site_url('bankentities/find'));


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
                    1                                                                   =>  'nit_bankentity',
                    2                                                                   =>  'contact_bankentity',
                    3                                                                   =>  'phone_bankentity',                                                                                    );
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

            $draw                                                               =   $this->input->post('draw');
            $limit                                                              =   $this->input->post('length');
            $start                                                              =   $this->input->post('start');
            $search                                                             =   $this->input->post('search')['value'];
            $order                                                              =   $columns[$this->input->post('order')[0]['column']];
            $dir                                                                =   $this->input->post('order')[0]['dir'];
            $filtered_columns                                                   =   $this->input->post('columns');

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
                $entries                                                        =   $this->form_rules();
                $this->form_validation->set_rules($entries);

                if ($this->form_validation->run()) 
                {
                    $add                                                        =   $this->_bankentities_model->add($params);
                    
                    echo json_encode($add);
                    exit();
                } 
                else 
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   $this->form_validation->error_array();

                    echo json_encode($result);
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
                $entries                                                        =   $this->form_rules(TRUE, $params['pk']);
                $this->form_validation->set_rules($entries);

                if ($this->form_validation->run()) 
                {
                    $edit                                                       =   $this->_bankentities_model->edit($params);
                    
                    echo json_encode($edit);
                    exit();
                } 
                else 
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   $this->form_validation->error_array();

                    echo json_encode($result);
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
    public function details()
    {
        if(in_array('DETAILS', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                    $details                                                    =   $this->_bankentities_model->details($params);

                    echo json_encode($details);
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
    public function find_by_id()
    {
        if (in_array('VIEW', $this->actions))
        {
            $params                                                             =   $this->security->xss_clean($_POST);

            if ($params)
            {
                $find                                                           =   $this->_bankentities_model->find_by_id($params);

                echo json_encode($find);
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
            $location                                                           =   $this->_bankentities_model->location_select($params, $tables[$table_name], $tables[$parent_table_name]);
            
            echo json_encode($location);
            exit();
        }

        if (count($tables[$table_name]) > 0) {
            $location                                                           =   $this->_bankentities_model->location_select($params, $tables[$table_name]);

            echo json_encode($location);
            exit();
        } 
        else 
        {
            if ($this->input->method(TRUE) == 'GET') {
                header("Location: " . site_url('bankentities'));
            } else {
                echo json_encode(array('data' => FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
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
                  ->setCellValue('C1', 'NIT')
                  ->setCellValue('D1', 'Dígito de verificación')
                  ->setCellValue('E1', 'Código del banco')
                  ->setCellValue('F1', 'Contacto')
                  ->setCellValue('G1', 'Teléfono')
                  ->setCellValue('H1', 'Correo corporativo')
                  ->setCellValue('I1', 'Dirección');


            $sheet->getStyle('A1:I1')->getFont()->setBold(true);

            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(40);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(30);
            $sheet->getColumnDimension('I')->setWidth(40);



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

        /**
     * @author    Innovación y Tecnología
     * @copyright 2021 Fábrica de Desarrollo
     * @since     v2.0.1
     * @param     
     * @return    array $entries
     **/
    public function form_rules($editForm = FALSE, $editFormId = 0)
    {
        $entries = [
            [
                'field' => 'name_bankentity',
                'label' => 'nombre del banco',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},name_bankentity]]|required|min_length[3]|max_length[50]" 
                                     : 'is_unique[fet_bankentities.name_bankentity]|required|min_length[3]|max_length[50]',
                'errors' => [
                    'is_unique' => 'El %s ya existe',
                    'required' => 'El %s no puede quedar en blanco.',
                    'min_length' => 'El %s debe contener al menos 3 caracteres.',
                    'max_length' => 'El %s debe contener máximo 50 caracteres.',
                ]
            ],
            [
                'field' => 'nit_bankentity',
                'label' => 'NIT del banco',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},nit_bankentity]]|required|numeric|min_length[3]|max_length[9]" 
                                     : 'is_unique[fet_bankentities.nit_bankentity]|required|numeric|min_length[3]|max_length[9]',
                'errors' => [
                    'is_unique' => 'El %s ya existe',
                    'required' => 'El %s no puede quedar en blanco.',
                    'numeric' => 'El %s solo puede contener números.',
                    'min_length' => 'El %s debe contener al menos 3 caracteres.',
                    'max_length' => 'El %s debe contener máximo 9 caracteres.',
                ]
            ],
            [
                'field' => 'digit_bankentity',
                'label' => 'dígito de verificación',
                'rules' => 'required|numeric|min_length[1]|max_length[2]',
                'errors' => [
                    'required' => 'El %s no puede quedar en blanco.',
                    'numeric' => 'El %s solo puede contener números.',
                    'min_length' => 'El %s debe contener al menos 1 caracteres.',
                    'max_length' => 'El %s debe contener máximo 2 caracteres.',
                ]
            ],
            [
                'field' => 'code_bankentity',
                'label' => 'código del banco',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},code_bankentity]]|required|numeric|min_length[1]|max_length[4]" 
                                     : 'is_unique[fet_bankentities.code_bankentity]|required|numeric|min_length[1]|max_length[4]',
                'errors' => [
                    'is_unique' => 'El %s ya existe',
                    'required' => 'El %s no puede quedar en blanco.',
                    'numeric' => 'El %s solo puede contener números.',
                    'min_length' => 'El %s debe contener al menos 1 caracteres.',
                    'max_length' => 'El %s debe contener máximo 4 caracteres.',
                ]
            ],
            [
                'field' => 'address_bankentity',
                'label' =>   'dirección',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},address_bankentity]]|required|min_length[5]|max_length[70]" 
                                     : 'is_unique[fet_bankentities.address_bankentity]|required|min_length[5]|max_length[70]',
                'errors' =>  [
                    'is_unique' => 'La %s ya existe',
                    'required' => 'La %s no puede quedar en blanco.',
                    'min_length' => 'La %s debe contener al menos 5 caracteres.',
                    'max_length' => 'La %s debe contener máximo 70 caracteres.',
                ]
            ],
            [
                'field' => 'contact_bankentity',
                'label' =>   'contacto',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},contact_bankentity]]|required|min_length[3]|max_length[50]" 
                                     : 'is_unique[fet_bankentities.contact_bankentity]|required|min_length[3]|max_length[50]',
                'errors' =>  [
                    'is_unique' => 'El %s ya existe',
                    'required' => 'El %s no puede quedar en blanco.',
                    'min_length' => 'El %s debe contener al menos 3 caracteres.',
                    'max_length' => 'El %s debe contener máximo 50 caracteres.',
                ]
            ],
            [
                'field' => 'phone_bankentity',
                'label' => 'número del contacto',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},phone_bankentity]]|required|numeric|min_length[7]|max_length[13]" 
                                     : 'is_unique[fet_bankentities.phone_bankentity]|required|numeric|min_length[7]|max_length[13]',
                'errors' => [
                    'is_unique' => 'El %s ya existe',
                    'required' => 'El %s no puede quedar en blanco.',
                    'numeric' => 'El %s solo puede contener números.',
                    'min_length' => 'El %s debe contener al menos 7 caracteres.',
                    'max_length' => 'El %s debe contener máximo 13 caracteres.',
                ]
            ],
            [
                'field' => 'email_bankentity',
                'label' =>   'correo corporativo',
                'rules' => $editForm ? "callback_value_exists[[{$editFormId},email_bankentity]]|required|min_length[3]|max_length[50]|valid_email" 
                                     : 'is_unique[fet_bankentities.email_bankentity]|required|min_length[3]|max_length[50]|valid_email',
                'errors' =>  [
                    'is_unique' => 'El %s ya existe',
                    'required' => 'El %s no puede quedar en blanco.',
                    'min_length' => 'El %s debe contener al menos 3 caracteres.',
                    'max_length' => 'El %s debe contener máximo 50 caracteres.',
                    'valid_email' => 'El %s no tiene un formato válido.'
                ]
            ],
        ];

        return $entries;
        exit();
    }

    /**
     * @author    Innovación y Tecnología
     * @copyright 2021 Fábrica de Desarrollo
     * @since     v2.0.1
     * @param     
     * @return    bool $result
     **/
    public function value_exists($value, $params)
    {
        $sanitized_params = trim($params, ' \t[]');
        $params = preg_split('/,/', $sanitized_params);
        list($id, $column) = $params;

        $this->db->select($column);
        $this->db->where($column, $value);
        $this->db->where('id_bankentity !=', $id);
        $this->db->where('flag_drop', 0);

        $query                                                                  =   $this->db->get('fet_bankentities');

        if (count($query->result_array()) > 0)
        {
            $this->form_validation->set_message('value_exists', 'El {field} ya existe.');
            return FALSE;
            exit();
        }

        return TRUE;
        exit();
    }
}