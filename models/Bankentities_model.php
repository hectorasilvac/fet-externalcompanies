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

        if ( ! empty($search)) 
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
        $this->db->select('id_bankentity, name_bankentity, abbreviation_bankentity, nit_bankentity, digit_bankentity, code_bankentity, address_bankentity, contact_bankentity, phone_bankentity, email_bankentity');
        $this->db->select('(SELECT COUNT(DISTINCT fet_cv.name_cv) from fet_workers JOIN fet_cv ON fet_workers.id_cv = fet_cv.id_cv WHERE fet_workers.id_bankentity = fet_bankentities.id_bankentity) as workers');
        $this->db->from('fet_bankentities');
        $this->db->where('flag_drop', 0);

        if ( ! empty($search))
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

        if ($this->session->userdata['mobile'] == 0) 
        {
            $count                                                              =   $start;
            foreach ($banks as $key => $action) 
            {
                $count++;
                $banks[$key]['number']                                          =   $count;
            }
        }

        return $banks;
        exit();
    }

    /**
     * @author    Innovación y Tecnología
     * @copyright 2021 Fábrica de Desarrollo
     * @since     v2.0.1
     * @param     array $params
     * @return    array $result
     **/
    public function affiliated_workers($params)
    {
        $result                                                                 =   array();

        if (isset($params['pk']) && isset($params['value'])) 
        {
            $this->db->select('CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv) AS full_name, fet_cv.number_dcv');
            $this->db->from('fet_workers');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->where('fet_workers.id_bankentity', $params['value']);
            $this->db->group_by('fet_cv.number_dcv, full_name');

            $query                                                              =   $this->db->get();

            if (count($query->result_array()) > 0) 
            {
                $result['data']                                                 =   $query->result_array();
                $result['message']                                              =   FALSE;
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'No hay usuarios afiliados a esta entidad bancaria.';
            }
        }
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No se ha podido realizar la operación.';
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
    public function exist_bank($params)
    {
        $result                                                                 =   array();

        $this->db->select('name_bankentity, abbreviation_bankentity, nit_bankentity, code_bankentity, address_bankentity, contact_bankentity, phone_bankentity, email_bankentity');
        $this->db->group_start();
        $this->db->where('name_bankentity', trim($params['name_bankentity']));
        $this->db->or_where('abbreviation_bankentity', trim($params['abbreviation_bankentity']));
        $this->db->or_where('nit_bankentity', trim($params['nit_bankentity']));
        $this->db->or_where('code_bankentity', trim($params['code_bankentity']));
        $this->db->or_where('address_bankentity', trim($params['address_bankentity']));
        $this->db->or_where('contact_bankentity', trim($params['contact_bankentity']));
        $this->db->or_where('phone_bankentity', trim($params['phone_bankentity']));
        $this->db->or_where('email_bankentity', trim($params['email_bankentity']));
        $this->db->group_end();

        $this->db->where('flag_drop', 0);  

        if (isset($params['pk']))
        {
            $this->db->where('id_bankentity !=', $params['pk']);
        }

        $query                                                                  =   $this->db->get('fet_bankentities');

        if (count($query->result_array()) > 0)
        {
            $message                                                            =   ' alguno de estos datos';

            foreach ($query->row_array() as $key => $value) 
            {

                $inputs = [
                    'name_bankentity'                                           =>  ' este nombre.',
                    'abbreviation_bankentity'                                   =>  ' esta abreviatura.',
                    'nit_bankentity'                                            =>  ' este NIT.',
                    'code_bankentity'                                           =>  ' este código.',
                    'address_bankentity'                                        =>  ' esta dirección.',
                    'contact_bankentity'                                        =>  ' este contacto.',
                    'phone_bankentity'                                          =>  ' este teléfono.',
                    'email_bankentity'                                          =>  ' este correo.',
                ];

                if (isset($inputs[$key]) && strtolower($value) == strtolower(trim($params[$key]))) {
                    $message                                                    =   $inputs[$key];
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe un banco con ' . $message;
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
    public function add($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('name_bankentity', 'Nombre del banco', 'required');
        $this->form_validation->set_rules('abbreviation_bankentity', 'Abreviatura', 'required');
        $this->form_validation->set_rules('nit_bankentity', 'NIT', 'required');
        $this->form_validation->set_rules('digit_bankentity', 'Dígito de verificación', 'required');
        $this->form_validation->set_rules('code_bankentity', 'Código del banco', 'required');
        $this->form_validation->set_rules('contact_bankentity', 'Nombre del contacto', 'required');
        $this->form_validation->set_rules('phone_bankentity', 'Teléfono del contacto', 'required');
        $this->form_validation->set_rules('email_bankentity', 'Correo electrónico del contacto', 'required');
        $this->form_validation->set_rules('address_bankentity', 'Dirección del banco', 'required');

        if ($this->form_validation->run()) 
        {
            $params['name_bankentity']                                          =   mb_strtoupper($this->_trabajandofet_model->accents(trim($params['name_bankentity'])));
            $params['abbreviation_bankentity']                                  =   mb_strtoupper($this->_trabajandofet_model->accents(trim($params['abbreviation_bankentity'])));
            $params['contact_bankentity']                                       =   $this->_trabajandofet_model->accents(trim($params['contact_bankentity']));
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['date_insert']                                              =   date('Y-m-d H:i:s');

            $query                                                              =   $this->_trabajandofet_model->insert_data($params, 'fet_bankentities');

            if ($query) 
            {
                $data_history                                                   =   $params;
                $data_history['id_bankentity']                                  =   $query;
                $data_history['user_update']                                    =   $params['user_insert'];
                $data_history['date_update']                                    =   date('Y-m-d H:i:s');
                unset($data_history['date_insert'], $data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'fet_bankentities_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'La entidad bancaria se ha registrado correctamente.';
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Error al registrar la entidad bancaria.';
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
    public function edit($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('name_bankentity', 'Nombre del banco', 'required');
        $this->form_validation->set_rules('abbreviation_bankentity', 'Abreviatura', 'required');
        $this->form_validation->set_rules('nit_bankentity', 'NIT', 'required');
        $this->form_validation->set_rules('digit_bankentity', 'Dígito de verificación', 'required');
        $this->form_validation->set_rules('code_bankentity', 'Código del banco', 'required');
        $this->form_validation->set_rules('contact_bankentity', 'Nombre del contacto', 'required');
        $this->form_validation->set_rules('phone_bankentity', 'Teléfono del contacto', 'required');
        $this->form_validation->set_rules('email_bankentity', 'Correo electrónico del contacto', 'required');
        $this->form_validation->set_rules('address_bankentity', 'Dirección del banco', 'required');

        if ($this->form_validation->run()) 
        {
            $params['id']                                                       =   $params['pk'];
            $params['name_bankentity']                                          =   mb_strtoupper($this->_trabajandofet_model->accents(trim($params['name_bankentity'])));
            $params['abbreviation_bankentity']                                  =   mb_strtoupper($this->_trabajandofet_model->accents(trim($params['abbreviation_bankentity'])));
            $params['contact_bankentity']                                       =   $this->_trabajandofet_model->accents(trim($params['contact_bankentity']));
            $params['user_update']                                              =   $this->session->userdata['id_user'];
            $params['date_update']                                              =   date('Y-m-d H:i:s');
            unset($params['pk']);

            $query                                                              =   $this->_trabajandofet_model->update_data($params, 'id_bankentity', 'fet_bankentities');

            if ($query) 
            {
                $data_history                                                   =   $params;
                $data_history['id_bankentity']                                  =   $data_history['id'];
                unset($data_history['id']);

                $this->_trabajandofet_model->insert_data($data_history, 'fet_bankentities_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            } 
            else 
            {

                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al editar la entidad bancaria.';
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
    public function detail($params)
    {
        $result                                                                 =   array();

        if (isset($params['pk']) && isset($params['value'])) 
        {
            $this->db->select('id_bankentity, name_bankentity, abbreviation_bankentity, nit_bankentity, digit_bankentity, code_bankentity, address_bankentity, contact_bankentity, phone_bankentity, email_bankentity');
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
        $this->db->select('id_bankentity');
        $this->db->where('id_bankentity', $param['id_bankentity']);
        $query                                                                  =   $this->db->get('fet_workers');
        $affiliated_workers                                                     =   count($query->result_array()) > 0;

        if ( ! $affiliated_workers) 
        {
            $data                                                               =   array(
                'id'                                                                     =>  $param['id_bankentity'],
                'flag_drop'                                                              =>  1,
                'user_update'                                                            =>  $this->session->userdata['id_user'],
                'date_update'                                                            =>  date('Y-m-d H:i:s')
                                                                                        );

            $result                                                             =   array();

            $answer                                                             =   $this->_trabajandofet_model->update_data($data, 'id_bankentity', 'fet_bankentities');

            if ($answer) 
            {
                $data_history                                                   =   $data;
                $data_history['id_bankentity']                                  =   $data_history['id'];
                unset($data_history['id']);

                $this->_trabajandofet_model->insert_data($data_history, 'fet_bankentities_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al eliminar la entidad bancaria.';
            }
        } 
        else 
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No es posible eliminar la entidad bancaria porque tiene trabajadores afiliados.';
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
        $this->db->select('name_bankentity, abbreviation_bankentity, nit_bankentity, digit_bankentity, code_bankentity, contact_bankentity, phone_bankentity, email_bankentity, address_bankentity');
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
