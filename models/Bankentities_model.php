<?php

/**
 * @author    Innovación y Tecnología
 * @copyright 2021 Fábrica de Desarrollo
 * @version   v 2.0
 **/

defined('BASEPATH') or exit('No direct script access allowed');

class Bankentities_model extends CI_Model
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

        $this->db->where('flag_drop', 0);
        $this->db->from('fet_bankentities');

        $result['total']                                                        =   $this->db->count_all_results();

        if (isset($search) && !empty($search)) 
        {
            $this->db->select('name_bankentity, nit_bankentity, digit_bankentity, code_bankentity, address_bankentity, contact_bankentity, phone_bankentity, email_bankentity');

            if (is_array($search) && count($search) > 0) 
            {
                $this->db->like($search[0]['name'], $search[0]['value']);
                $search_data                                                    = [];

                $this->db->group_start();
                foreach ($search as $element) 
                {
                    $name = $element['name'];
                    $value = $element['value'];
                    $search_data[$name] = $value;
                }

                $this->db->or_like($search_data);
                $this->db->group_end();
                $this->db->from('fet_bankentities');
                $this->db->where('flag_drop', 0);

                $result['total_filtered']                                       =   $this->db->count_all_results();
            } 
            else 
            {
                $this->db->group_start();
                $this->db->like('name_bankentity', $search);
                $this->db->or_like('nit_bankentity', $search);
                $this->db->or_like('digit_bankentity', $search);
                $this->db->or_like('code_bankentity', $search);
                $this->db->or_like('address_bankentity', $search);
                $this->db->or_like('contact_bankentity', $search);
                $this->db->or_like('phone_bankentity', $search);
                $this->db->or_like('email_bankentity', $search);
                $this->db->group_end();

                $this->db->where('flag_drop', 0);
                $this->db->from('fet_bankentities');

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
        $this->db->select('id_bankentity, name_bankentity, nit_bankentity, digit_bankentity, code_bankentity, address_bankentity, contact_bankentity, phone_bankentity, email_bankentity'); // Falta agregar campo de correo
        $this->db->from('fet_bankentities');
        $this->db->where('flag_drop', 0);

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_bankentity', $search);
            $this->db->or_like('nit_bankentity', $search);
            $this->db->or_like('digit_bankentity', $search);
            $this->db->or_like('code_bankentity', $search);
            $this->db->or_like('address_bankentity', $search);
            $this->db->or_like('contact_bankentity', $search);
            $this->db->or_like('phone_bankentity', $search);
            $this->db->or_like('email_bankentity', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get();

        $banks                                                                  =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0) {
            $count                                                              =   $start;
            foreach ($banks as $key => $action) {
                $count++;
                $banks[$key]['number']                                          =   $count;
            }
        }

        foreach ($banks as $key => $bank) {
            $this->db->select('COUNT(DISTINCT fet_cv.name_cv) as workers');
            $this->db->from('fet_workers');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->where('fet_workers.id_bankentity', $bank['id_bankentity']);
            
            $query                                                              = $this->db->get();

            $banks[$key]['workers'] = $query->row_array()['workers'];
        }

        return $banks;
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


        if (!is_null($parent_table)) {
            $this->db->where($parent_table['id'], $params['parentId']);
        }

        if (isset($params['q']) && $params['q'] != '') {
            $this->db->like("{$table['text']}", $params['q']);
        }

        $this->db->order_by("{$table['text']}", 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get("{$table['name']}");

        $result['total_count']                                                  =   $query->num_rows();

        if ($result['total_count'] > 0) {
            $result['items']                                                    =   $query->result_array();
        } else {
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
    public function find_by_id($params)
    {
        $result                                                                 =   array();

        if (isset($params['get_workers']) && $params['get_workers'] === 'true')
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $this->db->select('CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv) AS full_name, fet_cv.number_dcv');
            $this->db->from('fet_workers');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->where('fet_workers.id_bankentity', $params['value']);
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

        $data                                                                   =   array();
        $data['name_bankentity']                                                =   mb_strtoupper($this->_trabajandofet_model->accents(trim($params['name_bankentity'])));
        $data['nit_bankentity']                                                 =   trim($params['nit_bankentity']);
        $data['digit_bankentity']                                               =   trim($params['digit_bankentity']);
        $data['code_bankentity']                                                =   trim($params['code_bankentity']);
        $data['contact_bankentity']                                             =   $this->_trabajandofet_model->accents(trim($params['contact_bankentity']));
        $data['phone_bankentity']                                               =   trim($params['phone_bankentity']);
        $data['email_bankentity']                                               =   trim($params['email_bankentity']);
        $data['address_bankentity']                                             =   trim($params['address_bankentity']);
        $data['user_insert']                                                    =   $this->session->userdata['id_user'];
        $data['date_insert']                                                    =   date('Y-m-d H:i:s');

        $query                                                                  =   $this->_trabajandofet_model->insert_data($data, 'fet_bankentities');

        if ($query) {
            $data_history                                                       =   $data;
            $data_history['id_bankentity']                                      =   $query;
            $data_history['user_update']                                        =   $data['user_insert'];
            $data_history['date_update']                                        =   date('Y-m-d H:i:s');
            unset($data_history['date_insert'], $data_history['user_insert']);

            $this->_trabajandofet_model->insert_data($data_history, 'fet_bankentities_history');

            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'La entidad bancaria se ha registrado correctamente';
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Error al registrar la entidad bancaria';
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

        $data['id']                                                             =   $params['pk'];
        $data['name_bankentity']                                                =   mb_strtoupper($this->_trabajandofet_model->accents(trim($params['name_bankentity'])));
        $data['nit_bankentity']                                                 =   trim($params['nit_bankentity']);
        $data['digit_bankentity']                                               =   trim($params['digit_bankentity']);
        $data['code_bankentity']                                                =   trim($params['code_bankentity']);
        $data['contact_bankentity']                                             =   $this->_trabajandofet_model->accents(trim($params['contact_bankentity']));
        $data['phone_bankentity']                                               =   trim($params['phone_bankentity']);
        $data['email_bankentity']                                               =   trim($params['email_bankentity']);
        $data['address_bankentity']                                             =   trim($params['address_bankentity']);
        $data['user_update']                                                    =   $this->session->userdata['id_user'];
        $data['date_update']                                                    =   date('Y-m-d H:i:s');

         $query                                                                 =   $this->_trabajandofet_model->update_data($data, 'id_bankentity', 'fet_bankentities');

        if ($query) 
        {
            $data_history                                                       =   $data;
            $data_history['id_bankentity']                                      =   $data_history['id'];
            unset($data_history['id']);

            $this->_trabajandofet_model->insert_data($data_history, 'fet_bankentities_history');

            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Acción realizada con éxito!';
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Problemas al editar el rol.';
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
            $this->db->select('name_bankentity, nit_bankentity, digit_bankentity, code_bankentity, address_bankentity, contact_bankentity, phone_bankentity, email_bankentity');
            $this->db->where($params['pk'], $params['value']);
            $this->db->where('flag_drop', 0);

            $query                                                              =   $this->db->get('fet_bankentities');

            if (count($query->result_array()) > 0) 
            {
                $result['data']                                                 =   $query->row_array();
                $result['message']                                              =   FALSE;
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al mostrar los detalles de la entidad bancaria.';
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
            'id'                                                                        =>  $param['id_bankentity'],
            'flag_drop'                                                                 =>  1,
            'user_update'                                                               =>  $this->session->userdata['id_user'],
            'date_update'                                                               =>  date('Y-m-d H:i:s')
        );

        $result                                                                 =   array();

        $answer                                                                 =   $this->_trabajandofet_model->update_data($data, 'id_bankentity', 'fet_bankentities');

        if ($answer)
        {
            $data_history                                                       =   $data;
            $data_history['id_bankentity']                                      =   $data_history['id'];
            unset($data_history['id']);

            $this->_trabajandofet_model->insert_data($data_history, 'fet_bankentities_history');

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
     *@author    Innovación y Tecnología
     *@copyright 2021 Fábrica de Desarrollo
     *@since     v2.0.1
     *@param     array $param
     *@return    array $result
     **/
    public function trace_register($param)
    {
        $result                                                                 =   array();

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('fet_bankentities', 'id_bankentity', $param['id_bankentity']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('fet_bankentities_history', 'id_bankentity', $param['id_bankentity']);

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
        $this->db->select('name_bankentity, nit_bankentity, digit_bankentity, code_bankentity, contact_bankentity, phone_bankentity, email_bankentity, address_bankentity');
        $this->db->where('flag_drop', 0);

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_bankentity', $search);
            $this->db->or_like('nit_bankentity', $search);
            $this->db->or_like('digit_bankentity', $search);
            $this->db->or_like('code_bankentity', $search);
            $this->db->or_like('address_bankentity', $search);
            $this->db->or_like('contact_bankentity', $search);
            $this->db->or_like('phone_bankentity', $search);
            $this->db->or_like('email_bankentity', $search);
            $this->db->group_end();
        }

        $this->db->order_by('name_bankentity', 'ASC');
        $query                                                                  =   $this->db->get('fet_bankentities');

        $result                                                                 =   array();

        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay entidades bancarias para exportar.';
        }

        return $result;
        exit();
    }


}
