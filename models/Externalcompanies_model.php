<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Externalcompanies_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     int $role, varchar $submodule
    * @return    array
    **/
    public function actions_by_role($role, $submodule)
    {
        return $this->_trabajandofet_model->actions_by_role($role, $submodule);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $submodule
    * @return    array | boolean
    **/
    public function get_breadcrumb($submodule)
    {
        return $this->_trabajandofet_model->get_breadcrumb($submodule);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function count_rows($search)
    {
        $result                                                                 =   array();

        $this->db->where('fet_cv_ec.flag_drop', 0);
        $this->db->where('fet_cv_ec.id_cv_ec !=', 1);
        $this->db->from('fet_cv_ec');

        $result['total']                                                        =   $this->db->count_all_results();

        if (isset($search) && !empty($search)) 
        {
            $this->db->select('fet_cv_ec.name_cv_ec, fet_cv_ec.nit_cv_ec, fet_cv_ec.type_cv_ec, fet_cv_ec.email_cv_ec, fet_cv_ec.phone_cv_ec, fet_cv_ec.address_cv_ec, git_countries.name_country, git_departments.name_department, git_cities.name_city');
            $this->db->join('git_countries', 'fet_cv_ec.country_cv_ec = git_countries.id_country');
            $this->db->join('git_departments', 'fet_cv_ec.department_cv_ec = git_departments.id_department');
            $this->db->join('git_cities', 'fet_cv_ec.city_cv_ec = git_cities.id_city');

            if (is_array($search) && count($search) > 0) 
            {
                $this->db->like($search[0]['name'], $search[0]['value']);
                $search_data                                                        = [];

                $this->db->group_start();
                foreach ($search as $element) {
                    $name = $element['name'];
                    $value = $element['value'];
                    $search_data[$name] = $value;
                }

                $this->db->or_like($search_data);
                $this->db->group_end();
                $this->db->from('fet_cv_ec');
                $this->db->where('fet_cv_ec.flag_drop', 0);

                $result['total_filtered']                                           =   $this->db->count_all_results();
            } 
            else 
            {
                $this->db->group_start();
                $this->db->like('fet_cv_ec.name_cv_ec', $search);
                $this->db->or_like('fet_cv_ec.nit_cv_ec', $search);
                $this->db->or_like('fet_cv_ec.type_cv_ec', $search);
                $this->db->or_like('fet_cv_ec.email_cv_ec', $search);
                $this->db->or_like('fet_cv_ec.phone_cv_ec', $search);
                $this->db->or_like('fet_cv_ec.address_cv_ec', $search);
                $this->db->or_like('git_countries.name_country', $search);
                $this->db->or_like('git_cities.name_city', $search);
                $this->db->group_end();

                $this->db->where('fet_cv_ec.flag_drop', 0);
                $this->db->from('fet_cv_ec');

                $result['total_filtered']                                           =   $this->db->count_all_results();
            }
        } 
        else 
        {
            $result['total_filtered']                                           =   $result['total'];
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     int $limit, int $start, string $search, int $col, string $dir
    * @return    array $query->result_array()
    **/
    public function all_rows($limit, $start, $search, $col, $dir)
    {
        $this->db->select('id_cv_ec, name_cv_ec, nit_cv_ec, type_cv_ec, email_cv_ec, phone_cv_ec');
        $this->db->from('fet_cv_ec');
        $this->db->where('fet_cv_ec.flag_drop', 0);
        $this->db->where('fet_cv_ec.id_cv_ec !=', 1);


        if ( ! empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_cv_ec', $search);
            $this->db->or_like('nit_cv_ec', $search);
            $this->db->or_like('type_cv_ec', $search);
            $this->db->or_like('email_cv_ec', $search);
            $this->db->or_like('phone_cv_ec', $search);            
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get();

        $companies                                                              =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($companies as $key => $action)
            {
                $count++;
                $companies[$key]['number']                                      =   $count;
            }
        }

        return $companies;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params, array $table, array|null $parent_table
    * @return    array $result
    **/
    public function location_select($params, $table, $parent_table = NULL)
    {
        $result                                                                 =   array();
        $page                                                                   =   $params['page'];
        $range                                                                  =   10;
        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select("{$table['id']} AS id, {$table['text']} AS text");
        $this->db->where('flag_drop', 0);
        

        if ( ! is_null($parent_table))
        { 
            $this->db->where($parent_table['id'], $params['parentId']);
        }

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like("{$table['text']}", $params['q']);
        }

        $this->db->order_by("{$table['text']}", 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get("{$table['name']}");

        $result['total_count']                                                  =   $query->num_rows();

        if ($result['total_count'] > 0)
        {
            $result['items']                                                    =   $query->result_array();
        }
        else
        {
            $result['items']                                                    =   array();
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function exist_company($params)
    {
        $result                                                                 =   array();

        if (isset($params['pk'])) 
        {
            $this->db->select($params['name']);

            if ($params['name'] == 'nit_cv_ec' || $params['name'] == 'type_cv_ec' || $params['name'] == 'email_cv_ec' || $params['name'] == 'phone_cv_ec')
            {
            $params['name'] == 'nit_cv_ec' && empty($params['value'])    ?  $this->db->where('nit_cv_ec', '25513223')           :   $this->db->where('nit_cv_ec', $params['value']);
            $params['name'] == 'email_cv_ec' && empty($params['value'])  ?  $this->db->where('email_cv_ec', '25513223')         :   $this->db->where('email_cv_ec', $params['value']);
            $params['name'] == 'phone_cv_ec' && empty($params['value'])  ?  $this->db->where('phone_cv_ec', '25513223')         :   $this->db->where('phone_cv_ec', $params['value']);
            $params['name'] == 'type_cv_ec'                              ?  $this->db->where('type_cv_ec !=', $params['value']) :   NULL;

            }
            else
            {
              $this->db->where($params['name'], trim($params['value']));
            }

            $this->db->where('flag_drop', 0);
            $this->db->where('fet_cv_ec.id_cv_ec !=', 1);
            $this->db->where('id_cv_ec !=', $params['pk']);
        } 
        else 
        {
            $name_is_set                                                        =   isset($params['name_cv_ec'])  && strlen($params['name_cv_ec']) > 0;
            $nit_is_set                                                         =   isset($params['nit_cv_ec'])   && strlen($params['nit_cv_ec']) > 0   && is_numeric($params['nit_cv_ec']);
            $email_is_set                                                       =   isset($params['email_cv_ec']) && strlen($params['email_cv_ec']) > 0;
            $phone_is_set                                                       =   isset($params['phone_cv_ec']) && strlen($params['phone_cv_ec']) > 0 && is_numeric($params['phone_cv_ec']);

            $select_query                                                       =   '';

            $name_is_set  ?   $select_query .=  'name_cv_ec, '  : NULL;
            $nit_is_set   ?   $select_query .=  'nit_cv_ec, '   : NULL;
            $email_is_set ?   $select_query .=  'email_cv_ec, ' : NULL;
            $phone_is_set ?   $select_query .=  'phone_cv_ec, ' : NULL;

            $select_query                                                       =  rtrim(trim($select_query), ',');
            $this->db->select($select_query);
            $this->db->where('flag_drop', 0);

            $this->db->group_start();
            $name_is_set  ?   $this->db->where('name_cv_ec', $params['name_cv_ec'])       :   NULL;
            $nit_is_set   ?   $this->db->or_where('nit_cv_ec', $params['nit_cv_ec'])      :   NULL;
            $email_is_set ?   $this->db->or_where('email_cv_ec', $params['email_cv_ec'])  :   NULL;
            $phone_is_set ?   $this->db->or_where('phone_cv_ec', $params['phone_cv_ec'])  :   NULL;
            $this->db->group_end();
        }

        $query                                                                  =   $this->db->get('fet_cv_ec');

        if (count($query->result_array()) > 0)
        {

            $message                                                            =   '';
            $error_by_field                                                     =   isset($params['name']) && strlen($params['name']) > 0;

            if ($error_by_field) 
            {
                foreach ($query->row_array() as $key => $value) 
                {
                    $params['name']  === 'name_cv_ec'  && strtolower($value) == strtolower(trim($params['value']))  ?   $message = 'este nombre'   :   NULL;
                    $params['name']  === 'nit_cv_ec'   && strtolower($value) == strtolower(trim($params['value']))  ?   $message = 'este NIT'      :   NULL;
                    $params['name']  === 'email_cv_ec' && strtolower($value) == strtolower(trim($params['value']))  ?   $message = 'este email'    :   NULL;
                    $params['name']  === 'phone_cv_ec' && strtolower($value) == strtolower(trim($params['value']))  ?   $message = 'este teléfono' :   NULL;
                }
            }
            else
            {
                foreach ($query->row_array() as $key => $value)
                {   
                    isset($params['name_cv_ec'])  && strtolower($value) == strtolower(trim($params['name_cv_ec']))   ?   $message = 'este nombre'   :   NULL;
                    isset($params['nit_cv_ec'])   && strtolower($value) == strtolower(trim($params['nit_cv_ec']))    ?   $message = 'este NIT'      :   NULL;
                    isset($params['email_cv_ec']) && strtolower($value) == strtolower(trim($params['email_cv_ec']))  ?   $message = 'este email'    :   NULL;
                    isset($params['phone_cv_ec']) && strtolower($value) == strtolower(trim($params['phone_cv_ec']))  ?   $message = 'este teléfono' :   NULL;
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe una empresa con ' . $message;
        }
        else
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   FALSE;
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function find_by_id($params)
    {
        $result                                                                 =   array();

        if (isset($params['get_aspirants']) && $params['get_aspirants'] === 'true') 
        {
            $this->db->select('CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv) AS full_name, fet_cv.number_dcv');
            $this->db->from('fet_cv_we');
            $this->db->join('fet_cv', 'fet_cv_we.id_cv = fet_cv.id_cv');
            $this->db->where('id_cv_ec', $params['value']);
            $this->db->group_by('fet_cv.number_dcv');
            $query = $this->db->get();
        } 
        else 
        {
            $this->db->where($params['pk'], $params['value']);
            $this->db->where('flag_drop', 0);

            $query                                                              =   $this->db->get($params['table']);
        }

        if (count($query->result_array()) > 0)
        {
            count($query->result_array()) === 1 ? $result['data']               =   $query->row_array()     : NULL;
            count($query->result_array()) > 1   ? $result['data']               =   $query->result_array()  : NULL;
            $result['message']                                                  =   FALSE;
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No se podido realizar la operación.';
        }
        return $result;
        exit();
    }
    
    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function add($params)
    {
        $result                                                                 =   array();

        $entries = [
            [
                'field' => 'name_cv_ec',
                'label' => 'nombre de la empresa',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El %s no puede quedar en blanco.',
                ]
            ],
            [
                'field' => 'nit_cv_ec',
                'label' => 'NIT de la empresa',
                'rules' => 'numeric',
                'errors' => [
                    'numeric' => 'El %s solo puede contener números.',
                ]
            ],
            [
                'field' => 'type_cv_ec',
                'label' => 'tipo de empresa',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Por favor seleccione el %s',
                ]
            ],
            [
                'field' => 'email_cv_ec',
                'label' => 'correo electrónico',
                'rules' => 'is_unique[fet_cv_ec.email_cv_ec]|valid_email',
                'errors' => [
                    'is_unique' => 'El %s ya existe',
                    'valid_email' => 'El %s no tiene un formato válido',
                ]
            ],
            [
                'field' => 'phone_cv_ec',
                'label' =>   'teléfono',
                'rules' => 'numeric|min_length[7]|max_length[10]',
                'errors' =>  [
                    'numeric' => 'El %s solo puede contener números.',
                    'min_length' => 'El %s debe contener al menos 7 caracteres.',
                    'max_length' => 'El %s debe contener máximo 10 caracteres.',
                ]
            ],
            [
                'field' => 'address_cv_ec',
                'label' =>   'dirección',
                'rules' => 'min_length[5]|max_length[80]',
                'errors' =>  [
                    'min_length' => 'La %s debe contener al menos 5 caracteres.',
                    'max_length' => 'La %s debe contener máximo 80 caracteres.',
                ]
            ],
            [
                'field' => 'country_cv_ec',
                'label' =>   'país',
                'rules' => 'required',
                'errors' =>  [
                    'required' => 'Por favor seleccione el %s',
                ]
            ],
            [
                'field' => 'department_cv_ec',
                'label' =>   'departamento',
                'rules' => 'required',
                'errors' =>  [
                    'required' => 'Por favor seleccione el %s',
                ]
            ],
            [
                'field' => 'city_cv_ec',
                'label' =>   'ciudad',
                'rules' => 'required',
                'errors' =>  [
                    'required' => 'Por favor seleccione la %s',
                ]
            ],
        ];

        $this->form_validation->set_rules($entries);

        if ($this->form_validation->run())

        {
            $data                                                               =   array();
            $data['name_cv_ec']                                                 =   isset($params['name_cv_ec'])       && strlen($params['name_cv_ec']) > 0               ? mb_strtoupper($this->_trabajandofet_model->accents(trim($params['name_cv_ec']))) : NULL;
            $data['nit_cv_ec']                                                  =   isset($params['nit_cv_ec'])        && strlen($params['nit_cv_ec']) > 0                ? trim($params['nit_cv_ec']) : NULL;
            $data['type_cv_ec']                                                 =   isset($params['type_cv_ec'])       && strlen($params['type_cv_ec']) > 0               ? trim($params['type_cv_ec']) : NULL;
            $data['email_cv_ec']                                                =   isset($params['email_cv_ec'])      && strlen($params['email_cv_ec']) > 0       ? $this->_trabajandofet_model->user_name(trim($params['email_cv_ec'])) : NULL;
            $data['phone_cv_ec']                                                =   isset($params['phone_cv_ec'])      && strlen($params['phone_cv_ec']) > 0       ? trim($params['phone_cv_ec']) : NULL;
            $data['address_cv_ec']                                              =   isset($params['address_cv_ec'])    && strlen($params['address_cv_ec']) > 0     ? trim($params['address_cv_ec']) : NULL;
            $data['country_cv_ec']                                              =   isset($params['country_cv_ec'])    && strlen($params['country_cv_ec']) > 0     ? trim($params['country_cv_ec']) : NULL;
            $data['department_cv_ec']                                           =   isset($params['department_cv_ec']) && strlen($params['department_cv_ec']) > 0  ? trim($params['department_cv_ec']) : NULL;
            $data['city_cv_ec']                                                 =   isset($params['city_cv_ec'])       && strlen($params['city_cv_ec']) > 0              ? trim($params['city_cv_ec']) : NULL;
            $data['user_insert']                                                =   $this->session->userdata['id_user'];
            $data['date_insert']                                                =   date('Y-m-d H:i:s');

            $query                                                              =   $this->_trabajandofet_model->insert_data($data, 'fet_cv_ec');

            if ($query)
            {
                $data_history                                                   =   $data;
                $data_history['id_cv_ec']                                       =   $query;
                $data_history['user_update']                                    =   $data['user_insert'];
                $data['date_update']                                            =   date('Y-m-d H:i:s');
                unset($data_history['date_insert']);
                unset($data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'fet_cv_ec_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'La empresa se ha registrado correctamente';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Error al registrar la empresa';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   $this->form_validation->error_array();
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function edit($params)
    {
        $result                                                                 =   array();
        $data                                                                   =   array();

        if (count($params) >= 2) 
        {

            $data['id']                                                         =   $params['pk'];
            $data['user_update']                                                =   $this->session->userdata['id_user'];
            $data['date_update']                                                =   date('Y-m-d H:i:s');
            $value_is_valid                                                     =   isset($params['value']) ? $params['value'] != '' || $params['value'] != NULL : FALSE;

            if ($value_is_valid) 
            {
                $params['name'] == 'name_cv_ec'  ?  $data['name_cv_ec']         =   mb_strtoupper($this->_trabajandofet_model->accents($params['value'])) : NULL;
                $params['name'] == 'nit_cv_ec'   ?  $data['nit_cv_ec']          =   $params['value']                                                      : NULL;
                $params['name'] == 'type_cv_ec'  ?  $data['type_cv_ec']         =   $this->_trabajandofet_model->user_name($params['value'])              : NULL;
                $params['name'] == 'email_cv_ec' ?  $data['email_cv_ec']        =   $this->_trabajandofet_model->user_name($params['value'])              : NULL;
                $params['name'] == 'phone_cv_ec' ?  $data['phone_cv_ec']        =   $params['value'] : NULL;

                $query                                                          =   $this->_trabajandofet_model->update_data($data, 'id_cv_ec', 'fet_cv_ec');
            }
            else 
            {
                if (isset($params['name']))
                {
                    $params[$params['name']]                                    =   NULL;
                    unset($params['name']);
                }

                if (isset($params['pk']))    unset($params['pk']);
                if (isset($params['value'])) unset($params['value']);

                $data                                                           =   array_merge($data, $params);
                $query                                                          =   $this->_trabajandofet_model->update_data($data, 'id_cv_ec', 'fet_cv_ec');
            }

            $data_history                                                       =   $data;
            $data_history['id_cv_ec']                                           =   $data_history['id'];
            unset($data_history['id']);

            $this->_trabajandofet_model->insert_data($data_history, 'fet_cv_ec_history');

            if ($query) 
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al editar el rol.';
            }
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Completa todos los campos.';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function details($params)
    {
        $result                                                                 =   array();
        $valid_params                                                           =   isset($params['pk']) && isset($params['value']);

        if ($valid_params)
        {
            $this->db->select('fet_cv_ec.name_cv_ec, fet_cv_ec.nit_cv_ec, fet_cv_ec.type_cv_ec, fet_cv_ec.phone_cv_ec, fet_cv_ec.email_cv_ec, fet_cv_ec.address_cv_ec, git_countries.name_country, git_departments.name_department, git_cities.name_city');
            $this->db->from('fet_cv_ec');
            $this->db->join('git_countries', 'git_countries.id_country = fet_cv_ec.country_cv_ec');
            $this->db->join('git_departments', 'git_departments.id_department = fet_cv_ec.department_cv_ec');
            $this->db->join('git_cities', 'git_cities.id_city = fet_cv_ec.city_cv_ec');
            $this->db->where($params['pk'], $params['value']);
            $this->db->where('fet_cv_ec.flag_drop', 0);

            $query                                                              =   $this->db->get();

            if (count($query->result_array()) > 0) 
            {
                $result['data']                                                 =   $query->row_array();
                $result['message']                                              =   FALSE;
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al mostrar los detalles de la empresa.';
            }
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Completa todos los campos.';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     arraay $param
    * @return    array $result
    **/
    public function udrop($param)
    {
        $data                                                                   =   array(
            'id'                                                                        =>  $param['id_cv_ec'],
            'flag_drop'                                                                 =>  1,
            'user_update'                                                               =>  $this->session->userdata['id_user'],
            'date_update'                                                               =>  date('Y-m-d H:i:s')
                                                                                    );

        $result                                                                 =   array();

        $answer                                                                 =   $this->_trabajandofet_model->update_data($data, 'id_cv_ec', 'fet_cv_ec');

        if ($answer)
        {
            $data_history                                                       =   $data;
            $data_history['id_cv_ec']                                           =   $data_history['id'];
            unset($data_history['id']);

            $this->_trabajandofet_model->insert_data($data_history, 'fet_cv_ec_history');

            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Acción realizada con éxito!';
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Problemas al eliminar la empresa.';
        }

        return $result;
        exit();
    }

     /**
     * @author    Innovación y Tecnología
     * @copyright 2021 Fabrica de Desarrollo
     * @since     v2.0.1
     * @param     array
     * @return    array
     **/
    public function row_by_search($search)
    {
        $this->db->select('id_cv_ec, name_cv_ec, nit_cv_ec, type_cv_ec, email_cv_ec, phone_cv_ec');
        $this->db->where('flag_drop', 0);
        $this->db->where('fet_cv_ec.id_cv_ec !=', 1);

        $this->db->like($search[0]['name'], $search[0]['value']);

        if (count($search) > 0) {
            $this->db->group_start();
            $search_data = [];

            foreach ($search as $element) {
                $name = $element['name'];
                $value = $element['value'];
                $search_data[$name] = $value;
            }

            $this->db->or_like($search_data);
            $this->db->group_end();
        }

        $query                                                                  =   $this->db->get('fet_cv_ec');
        $actions                                                                =   $query->result_array();

        return $actions;
        exit();
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fábrica de Desarrollo
    *@since     v2.0.1
    *@param     array $param
    *@return    array $result
    **/
    public function trace_register($param)
    {
        $result                                                                 =   array();

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('fet_cv_ec', 'id_cv_ec', $param['id_cv_ec']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('fet_cv_ec_history', 'id_cv_ec', $param['id_cv_ec']);

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;

            if (count($result['data_global']) == 0)
            {
                $result['data_global']                                          =   FALSE;
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['data_global']                                              =   FALSE;
            $result['message']                                                  =   'No hay histórico de este registro.';
        }

        return $result;
        exit();
    }

        /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function export_xlsx($search)
    {
        $this->db->select('fet_cv_ec.name_cv_ec, fet_cv_ec.nit_cv_ec, fet_cv_ec.type_cv_ec, fet_cv_ec.email_cv_ec, fet_cv_ec.phone_cv_ec, fet_cv_ec.address_cv_ec, git_countries.name_country, git_departments.name_department, git_cities.name_city');
        $this->db->join('git_countries', 'fet_cv_ec.country_cv_ec = git_countries.id_country');
        $this->db->join('git_departments', 'fet_cv_ec.department_cv_ec = git_departments.id_department');
        $this->db->join('git_cities', 'fet_cv_ec.city_cv_ec = git_cities.id_city');
        $this->db->where('fet_cv_ec.flag_drop', 0);
        $this->db->where('fet_cv_ec.id_cv_ec !=', 1);

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('fet_cv_ec.name_cv_ec', $search);
            $this->db->or_like('fet_cv_ec.nit_cv_ec', $search);
            $this->db->or_like('fet_cv_ec.type_cv_ec', $search);
            $this->db->or_like('fet_cv_ec.email_cv_ec', $search);
            $this->db->or_like('fet_cv_ec.phone_cv_ec', $search);
            $this->db->or_like('fet_cv_ec.address_cv_ec', $search);
            $this->db->or_like('git_countries.name_country', $search);
            $this->db->or_like('git_cities.name_city', $search);
            $this->db->group_end();
        }

        $this->db->order_by('fet_cv_ec.name_cv_ec', 'ASC');
        $query                                                                  =   $this->db->get('fet_cv_ec');

        $result                                                                 =   array();

        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay usuarios para exportar.';
        }

        return $result;
        exit();
    }
}