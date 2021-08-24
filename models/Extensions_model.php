<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Extensions_model extends CI_Model
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
    public function actions_by_role($role, $submodule) // Eliminar -> Revisado
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
    public function get_breadcrumb($submodule) // Eliminar -> Revisado
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
    public function count_rows($search) // Eliminar -> Revisado 23/08/2021
    {
        $result                                                                 =   array();

        $this->db->where('git_worker_extensions.flag_drop', 0);
        $this->db->from('git_worker_extensions');

        $result['total']                                                        =   $this->db->count_all_results();

        if ( ! empty($search))
        {
            $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
            $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');
            $this->db->join('git_areas', 'git_worker_extensions.id_area = git_areas.id_area');

            $this->db->group_start();
            $this->db->like('CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv)', $search);
            $this->db->or_like('CONCAT(gel1.serial_element, " - ", gel1.name_element)', $search);
            $this->db->or_like('CONCAT(gel2.serial_element, " - ", gel2.name_element)', $search);
            $this->db->or_like('name_area', $search);
            $this->db->or_like('email_extension', $search);
            $this->db->or_like('internal_extension', $search);
            $this->db->or_like('external_extension', $search);
            $this->db->or_like('ip_extension', $search);
            $this->db->group_end();

            $this->db->where('git_worker_extensions.flag_drop', 0);
            $this->db->from('git_worker_extensions');

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
    public function all_rows($limit, $start, $search, $col, $dir) // Eliminar -> Pendiente
    {
        if ( $this->session->userdata('id_role') === "11" )
        {
            $this->db->select('"TRUE" as allowed_edition');
        }
        else
        {
            $this->db->select("IF(STRCMP(git_worker_extensions.user_insert, {$this->session->userdata['id_user']}) = 0, 'TRUE', 'FALSE') as allowed_edition");
        }
        $this->db->select('id_extension, CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv) AS id_worker, email_extension, internal_extension, external_extension, ip_extension, name_area');
        $this->db->select('CONCAT(gel1.serial_element, " - ", gel1.name_element) AS element1');
        $this->db->select('CONCAT(gel2.serial_element, " - ", gel2.name_element) AS element2');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
        $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');
        $this->db->join('git_areas', 'git_worker_extensions.id_area = git_areas.id_area');
        $this->db->where('git_worker_extensions.flag_drop', 0);

        if ( ! empty($search))
        {
            $this->db->group_start();
            $this->db->like('CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv)', $search);
            $this->db->or_like('CONCAT(gel1.serial_element, " - ", gel1.name_element)', $search);
            $this->db->or_like('CONCAT(gel2.serial_element, " - ", gel2.name_element)', $search);
            $this->db->or_like('name_area', $search);
            $this->db->or_like('email_extension', $search);
            $this->db->or_like('internal_extension', $search);
            $this->db->or_like('external_extension', $search);
            $this->db->or_like('ip_extension', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('git_worker_extensions');

        $extensions                                                             =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($extensions as $key => $action)
            {
                $count++;
                $extensions[$key]['number']                                     =   $count;
            }
        }

        return $extensions;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function flags_select()
    {
        $result                                                                 =   array();

        $this->db->select('id_flag, name_flag, name_es_flag');
        $this->db->where('git_company != ', 'G');
        $this->db->where('flag_drop', 0);

        $query                                                                  =   $this->db->get('git_flags');

        return $query->result_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function workers_select($params) // Eliminar -> Revisado
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('fet_workers.id_worker AS id, CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv) AS text');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->where('fet_workers.flag_drop', 0);


        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('name_cv', $params['q']);
            $this->db->or_like('first_lcv', $params['q']);
            $this->db->or_like('second_lcv', $params['q']);
            $this->db->or_like('number_dcv', $params['q']);
        }

        $this->db->order_by('id_worker', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('fet_workers');

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
    public function areas_select($params) // Eliminar -> Revisado
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_area AS id, name_area AS text');
        $this->db->where('flag_drop', 0);


        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('name_area', $params['q']);
        }

        $this->db->order_by('id_area', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_areas');

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
    public function telephones_select($params) // Eliminar -> Revisado
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_element AS id, CONCAT(serial_element, " - ", name_element) AS text');
        $this->db->where('id_element_type', 11);

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('name_element', $params['q']);
            $this->db->or_like('serial_element', $params['q']);
        }

        $this->db->order_by('id_element', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_elements');

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
    public function cellphones_select($params) // Eliminar -> Revisado
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_element AS id, CONCAT(serial_element, " - ", name_element) AS text');
        $this->db->where('id_element_type', 17);

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('name_element', $params['q']);
            $this->db->or_like('serial_element', $params['q']);
        }

        $this->db->order_by('id_element', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_elements');

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
    public function exist_extension($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        if (isset($params['git_company'])) {
            unset($params['git_company']);
        }

        if (isset($params['id_area'])) {
            unset($params['id_area']);
        }

        $this->db->select('id_worker, id_element1, id_element2, external_extension, internal_extension, email_extension, phone_extension, ip_extension');
        $this->db->where('flag_drop', 0);

        $additional_params                                                      =   count(array_filter($params)) > 2;

        if (isset($params['name']) || $additional_params)
        {
            if (isset($params['name'])) 
            {
                $this->db->select($params['name']);
                $this->db->where($params['name'], trim($params['value']));
            } 
            else 
            {
                $this->db->group_start();

                if (isset($params['id_worker']) && !empty(trim($params['id_worker'])))
                {
                    $this->db->or_where('id_worker', trim($params['id_worker']));
                }

                if (isset($params['email_extension']) && !empty(trim($params['email_extension']))) 
                {
                    $this->db->or_where('email_extension', trim($params['email_extension']));
                }

                if (isset($params['id_element1']) && !empty(trim($params['id_element1']))) 
                {
                    $this->db->or_where('id_element1', trim($params['id_element1']));
                }

                if (isset($params['id_element2']) && !empty(trim($params['id_element2']))) 
                {
                    $this->db->or_where('id_element2', trim($params['id_element2']));
                }

                if (isset($params['phone_extension']) && !empty(trim($params['phone_extension']))) 
                {
                    $this->db->or_where('phone_extension', trim($params['phone_extension']));
                }

                if (isset($params['ip_extension']) && !empty(trim($params['ip_extension']))) 
                {
                    $this->db->or_where('ip_extension', trim($params['ip_extension']));
                }

                if (isset($params['external_extension']) && !empty(trim($params['external_extension']))) 
                {
                    $this->db->or_where('external_extension', trim($params['external_extension']));
                }

                if (isset($params['internal_extension']) && !empty(trim($params['internal_extension']))) 
                {
                    $this->db->or_where('internal_extension', trim($params['internal_extension']));
                }

                $this->db->group_end();
            }

            if (isset($params['pk'])) {
                $this->db->where('id_extension !=', $params['pk']);
            }

            $query                                                              =   $this->db->get('git_worker_extensions');

            if (count($query->result_array()) > 0) 
            {
                $message                                                        =   'alguno de estos datos';

                if (isset($params['pk']) && isset($params['name'])) 
                {
                    $params[$params['name']]                                    =   trim($params['value']);
                    unset($params['name'], $params['value'], $params['pk']);
                }

                foreach ($query->row_array() as $key => $value) 
                {
                    $entries = [
                        'id_worker'                                             =>  'este trabajador.',
                        'id_element1'                                           =>  'este dispositivo (teléfono).',
                        'id_element2'                                           =>  'este dispositivo (celular).',
                        'external_extension'                                    =>  'este número externo.',
                        'internal_extension'                                    =>  'este número interno.',
                        'email_extension'                                       =>  'este correo corporativo.',
                        'phone_extension'                                       =>  'este número de celular.',
                        'ip_extension'                                          =>  'esta IP.',
                    ];

                    if (isset($params[$key]) && !empty($params[$key]) && isset($entries[$key]) && strtolower($value) === strtolower(trim($params[$key]))) 
                    {
                        $message                                                =   $entries[$key];
                    }
                }

                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Ya existe una extensión con ' . $message;
            }
            else
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   FALSE;
            }
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
    public function add($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('id_worker', 'Trabajador', 'required');
        $this->form_validation->set_rules('id_area', 'Área', 'required');
        $this->form_validation->set_rules('id_element1', 'Teléfono', 'numeric');
        $this->form_validation->set_rules('id_element2', 'Celular', 'numeric');
        $this->form_validation->set_rules('external_extension', 'Extensión externa', 'numeric');
        $this->form_validation->set_rules('internal_extension', 'Extensión interna', 'numeric');
        $this->form_validation->set_rules('email_extension', 'Correo corporativo', array('required', 'valid_email'));
        $this->form_validation->set_rules('phone_extension', 'Número de celular', 'numeric');
        $this->form_validation->set_rules('ip_extension', 'IP', 'valid_ip[ipv4]');

        if ($this->form_validation->run())
        {
            $params['id_worker']                                                =   trim($params['id_worker']);
            $params['id_area']                                                  =   trim($params['id_area']);
            $params['external_extension']                                       =   trim($params['external_extension']) ?: NULL;
            $params['internal_extension']                                       =   trim($params['internal_extension']) ?: NULL;
            $params['email_extension']                                          =   $this->_trabajandofet_model->user_name($params['email_extension']);
            $params['phone_extension']                                          =   trim($params['phone_extension']) ?: NULL;
            $params['ip_extension']                                             =   trim($params['ip_extension']) ?: NULL;
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['date_insert']                                              =   date('Y-m-d H:i:s');

            if (isset($params['git_company']) && !empty(trim($params['git_company'])))
            {
                $params['git_company']                                          =   trim($params['git_company']);
            }

            if (isset($params['id_element1']) && !empty(trim($params['id_element1']))) 
            {
                $params['id_element1']                                           =   trim($params['id_element1']);
            }

            if (isset($params['id_element2']) && !empty(trim($params['id_element2'])))
            {
                $params['id_element2']                                          =   trim($params['id_element2']);
            }

            $query                                                              =   $this->_trabajandofet_model->insert_data($params, 'git_worker_extensions');

            if ($query) 
            {
                $data_history                                                   =   $params;
                $data_history['id_extension']                                   =   $query;
                $data_history['user_update']                                    =   $params['user_insert'];
                $data_history['date_update']                                    =   date('Y-m-d H:i:s');
                unset($data_history['date_insert'], $data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'git_worker_extensions_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'La extensión se ha registrado correctamente';
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Error al registrar la extensión';
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
     **/
    public function detail($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        if (isset($params['value']))
        {
            $this->db->select('id_extension, ip_extension, name_area, git_worker_extensions.id_area, id_element1, id_element2, phone_extension, git_worker_extensions.git_company');
            $this->db->select('CONCAT(gel1.serial_element, " - ", gel1.name_element) AS name_element1');
            $this->db->select('CONCAT(gel2.serial_element, " - ", gel2.name_element) AS name_element2');
            $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
            $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');
            $this->db->join('git_areas', 'git_worker_extensions.id_area = git_areas.id_area');
            $this->db->where('id_extension', $params['value']);
            $this->db->where('git_worker_extensions.flag_drop', 0);

            $query                                                              =   $this->db->get('git_worker_extensions');

            if (count($query->result_array()) > 0) {
                $result['data']                                                 =   $query->row_array();
                $result['message']                                              =   FALSE;
            } 
            else 
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al mostrar los detalles de la extension.';
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
    public function edit($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        if (isset($params['pk']) && !empty(trim($params['pk'])))
        {
            if (isset($params['value']))
            {
                switch ($params['name'])
                {
                    case 'id_worker':
                        $params['id_worker']                                          =   trim($params['value']);
                        break;

                    case 'email_extension':
                        $params['email_extension']                                    =   $this->_trabajandofet_model->user_name($params['value']);
                        break;

                    case 'internal_extension':
                        $params['internal_extension']                                 =   trim($params['value']) ?: NULL;
                        break;

                    case 'external_extension':
                        $params['external_extension']                                 =   trim($params['value']) ?: NULL;
                        break;
                }
                unset($params['value'], $params['name']);
            } 
            else 
            {
                $params['id_area']                                              =   $params['id_area'];
                $params['git_company']                                          =   $params['git_company'] ?? 'T';
                $params['id_element1']                                          =   $params['id_element1'] ?? NULL;
                $params['id_element2']                                          =   $params['id_element2'] ?? NULL;
                $params['ip_extension']                                         =   $params['ip_extension'] ?: NULL;
                $params['phone_extension']                                      =   $params['phone_extension'] ?: NULL;

            } 

            $params['id']                                                       =   $params['pk'];
            $params['user_update']                                                =   $this->session->userdata['id_user'];
            $params['date_update']                                                =   date('Y-m-d H:i:s');         
            unset($params['pk']);

            $answer                                                             =   $this->_trabajandofet_model->update_data($params, 'id_extension', 'git_worker_extensions');

            if ($answer)
            {
                $data_history                                                   =   $params;
                $data_history['id_extension']                                   =   $data_history['id'];
                unset($data_history['id']);

                $this->_trabajandofet_model->insert_data($data_history, 'git_worker_extensions_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al editar la extensión.';
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
    * @param     array $param
    * @return    array $result
    **/
    public function user_flags($param) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        $this->db->select('id_flag');
        $this->db->where('id_user', $param['id_user']);

        $query                                                                  =   $this->db->get('git_users_flags');

        if (count($query->result_array()) > 0)
        {
            $result['data']                                                     =   $query->result_array();
        }
        else
        {
            $result['data']                                                     =   FALSE;
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
    public function udrop($param) // Eliminar -> Revisado 20/08/2021
    {
        $data                                                                   =   array(
            'id'                                                                        =>  $param['id_extension'],
            'id_element1'                                                               =>  NULL,
            'id_element2'                                                               =>  NULL,
            'email_extension'                                                           =>  NULL,
            'ip_extension'                                                              =>  NULL,
            'phone_extension'                                                           =>  NULL,
            'internal_extension'                                                        =>  NULL,
            'external_extension'                                                        =>  NULL,
            'flag_drop'                                                                 =>  1,
            'user_update'                                                               =>  $this->session->userdata['id_user'],
            'date_update'                                                               =>  date('Y-m-d H:i:s')
                                                                                    );

        $result                                                                 =   array();

        $answer                                                                 =   $this->_trabajandofet_model->update_data($data, 'id_extension', 'git_worker_extensions');

        if ($answer)
        {
            $data_history                                                       =   $data;
            $data_history['id_extension']                                       =   $data_history['id'];
            unset($data_history['id']);

            $this->_trabajandofet_model->insert_data($data_history, 'git_worker_extensions_history');

            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Acción realizada con éxito!';
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Problemas al eliminar la extensión.';
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
    public function trace_register($param) // Eliminar -> Revisado 20/08/2021
    {
        $result                                                                 =   array();

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('git_worker_extensions', 'id_extension', $param['id_extension']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('git_worker_extensions_history', 'id_extension', $param['id_extension']);

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
    public function export_xlsx($search) // Eliminar -> Pendiente
    {
        $this->db->select('CONCAT(name_cv, " ", first_lcv, " ", second_lcv) AS worker_name');
        $this->db->select('number_dcv, name_area, internal_extension, external_extension, phone_extension, email_extension');
        $this->db->select('gel1.serial_element AS telephone_serial, gel1.name_element AS telephone_name');
        $this->db->select('gel2.serial_element AS cellphone_serial, gel2.name_element AS cellphone_name, ip_extension');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
        $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');
        $this->db->join('git_areas', 'git_worker_extensions.id_area = git_areas.id_area');
        $this->db->where('git_worker_extensions.flag_drop', 0);
        // $this->db->where('git_worker_extensions.flag_drop', 0);
        // $this->db->where('fu.id_user !=', $this->session->userdata['id_user']);

        // if($this->session->userdata['id_role'] != "11")
        // {
        //     $this->db->where('fu.id_role !=', 11);
        // }

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('CONCAT(name_cv, " ", first_lcv, " ", second_lcv)', $search);
            $this->db->or_like('number_dcv', $search);
            $this->db->or_like('name_area', $search);
            $this->db->or_like('internal_extension', $search);
            $this->db->or_like('external_extension', $search);
            $this->db->or_like('phone_extension', $search);
            $this->db->or_like('email_extension', $search);
            $this->db->or_like('ip_extension', $search);
            $this->db->or_like('gel1.serial_element', $search);
            $this->db->or_like('gel1.name_element', $search);
            $this->db->or_like('gel2.serial_element', $search);
            $this->db->or_like('gel2.name_element', $search);
            $this->db->group_end();
        }

        $this->db->order_by('fet_cv.name_cv', 'ASC');
        $query                                                                  =   $this->db->get('git_worker_extensions');

        $result                                                                 =   array();

        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay extensiones para exportar.';
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
    public function export_pdf($search, $id, $multiple_pdf)
    {
        $options                                                                =   array();

        // if ($id) 
        // {
        //     $this->db->select('A.*');
        //     $this->db->select('CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv) AS name_worker, C.number_dcv');
        //     $this->db->join('fet_workers B', 'B.id_worker = A.worker_insert', 'left');
        //     $this->db->join('fet_cv C', 'C.id_cv = B.id_cv', 'left');
        //     $this->db->where('A.id_biomechanical', $id);

        //     $query                                                              =   $this->db->get('fet_biomechanical_form A');
        //     $options                                                            =   $query->result_array();

        //     if (count($options) > 0) 
        //     {
        //         $html                                                           =   $this->template_pdf($options);

        //         $mpdf                                                           =   new \Mpdf\Mpdf(array('mode' => 'utf-8', 'format' => 'Letter', 'margin_header' => 0, 'margin_footer' => 0));
        //         $mpdf->showImageErrors                                          =   true;                

        //         $mpdf->WriteHTML($html);
        //         $mpdf->Output($options[0]['name_worker'] . ' CC ' . $options[0]['number_dcv'] . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        //     }
        // }
        // else
        // {
            $this->db->select('CONCAT(name_cv, " ", first_lcv, " ", second_lcv) AS worker_name');
            $this->db->select('number_dcv, name_area, internal_extension, external_extension, phone_extension, email_extension');
            $this->db->select('gel1.serial_element AS telephone_serial, gel1.name_element AS telephone_name');
            $this->db->select('gel2.serial_element AS cellphone_serial, gel2.name_element AS cellphone_name, ip_extension');
            $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
            $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');
            $this->db->join('git_areas', 'git_worker_extensions.id_area = git_areas.id_area');
            $this->db->where('git_worker_extensions.flag_drop', 0);
            $this->db->where('git_worker_extensions.flag_drop', 0);

            // if (isset($this->session->userdata['user_project']))
            // {
            //     $this->db->where('B.id_project', $this->session->userdata['user_project']);
            // }
            // else if (isset($this->session->userdata['user_company']))
            // {
            //     $this->db->where('git_companies.id_company', $this->session->userdata['user_company']);
            // }


            if (!empty($search))
            {
                // $this->db->group_start();
                // $this->db->like('CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv)', $search);
                // $this->db->or_like('C.number_dcv', $search);
                // $this->db->or_like('git_projects.name_project', $search);
                // $this->db->or_like('git_companies.code_company', $search);            
                // $this->db->group_end();
            }

            $query                                                              =   $this->db->get('git_worker_extensions');
            $options                                                            =   $query->result_array();

            // if (boolval($multiple_pdf)) 
            // {
            //     $zip                                                            =   new ZipArchive();
            //     $file_name                                                      =   'TRABAJANDOFET - ENCUESTA OSTEOMUSCULAR_' . date('dmY') . '.zip';
            //     $zip_file                                                       =   tempnam('/tmp', 'zip');

            //     $zip->open($zip_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

            //     foreach ($options as $key => $option) 
            //     {
            //         $html                                                       =   $this->template_pdf(array($option));

            //         $mpdf                                                       =   new \Mpdf\Mpdf(array('mode' => 'utf-8', 'format' => 'Letter', 'margin_header' => 0, 'margin_footer' => 0));

            //         $mpdf->WriteHTML($html);
            //         $pdf_data                                                   =   $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

            //         $zip->addFromString($option['name_worker'] . ' CC ' . $option['number_dcv'] . '.pdf', $pdf_data);
            //     }

            //     $zip->close();

            //     header("Content-Type: application/pdf");
            //     header("Content-Disposition: attachment;filename=$file_name");
            //     header("Cache-Control: max-age=0");
            //     header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            //     header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
            //     header("Cache-Control: cache, must-revalidate");
            //     header("Pragma: public");

            //     readfile($zip_file);
            //     unlink($zip_file);
            // }
            // else
            // {
                $html                                                           =   $this->template_pdf($options);

                $mpdf                                                           =   new \Mpdf\Mpdf(array('mode' => 'utf-8', 'format' => 'Letter', 'margin_header' => 0, 'margin_footer' => 0));
                $mpdf->showImageErrors                                          =   true;                

                $mpdf->WriteHTML($html);
                $mpdf->Output('TRABAJANDOFET - DIRECTORIO_' . date('dmY') . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
            // }
        // }

        return true;
        exit();
    }


    public function template_pdf($options)
    {
        $workers_by_area = [];

        foreach ($options as $key => $value)
        {
            if (array_key_exists($value['name_area'], $workers_by_area))
            {
                array_push($workers_by_area[$value['name_area']], $value);
            }
            else
            {
                $workers_by_area[$value['name_area']] = [$value];
            }
        }

        $html = '<!DOCTYPE html>
        <html lang="es">
        <head>
            <style>
                @page {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    margin: 15px;
                }

                body {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }

                .banner {
                    margin-bottom: 15px;
                    text-align: center;
                }

                .phone-book {
                    width: 683px;
                    margin: 0 auto;
                }

                .table-container {
                    width: 100%;
                    font-family: sans-serif;
                    font-size: 12px;
                }

                .table-container-row {
                    padding-left: 7.5px;
                    padding-right: 7.5px;
                    width: 50%;
                }

                .table-area {
                    width: 100%;
                    border-collapse: collapse;
                    border: 3px solid #004f9f;
                    margin-bottom: 15px;
                }

                .table-area-row {
                    background-color: #004f9f;
                }

                .table-area-name {
                    border: 1px solid #004f9f;
                    padding: 4px;
                }

                .table-area-extension {
                    border: 1px solid #004f9f;
                    padding: 4px;
                    text-align: center;
                }

                .text-spacing {
                    color: white;
                    font-style: italic;
                    padding: 6px;
                    text-transform: uppercase;
                }
            </style>
        </head>
        <body>';

        $html .= '<div class="banner">
                    <img src="resources/img/header.png" alt="Directorio Teléfonico" class="phone-book">
                    </div>

                    <table class="table-container">
                    <tbody>
                    <tr>';

        $first_column  = "<td class='table-container-row'>";
        $second_column = "<td class='table-container-row'>";
        $count = 0;

        foreach ($workers_by_area as $key => $value) 
        {
            if ($count % 2 == 0)
            {
                $first_column .= "<table class='table-area'>
                                    <thead>
                                            <tr class='table-area-row'>
                                            <th class='text-spacing'>{$key}</th>
                                            <th class='text-spacing'>Ext</th>
                                        </tr>
                                    </thead>
                                <tbody>";

                foreach ($value as $name => $content)
                {
                    $first_column .= "<tr>
                                        <td class='table-area-name'>{$content['worker_name']}</td>
                                        <td class='table-area-extension'>{$content['internal_extension']}</td>
                                    </tr>";
                }

                $first_column .= "</tbody>
                                </table>";
            }
            else
            {
                $second_column .= "<table class='table-area'>
                                        <thead>
                                            <tr class='table-area-row'>
                                                <th class='text-spacing'>{$key}</th>
                                                <th class='text-spacing'>Ext</th>
                                            </tr>
                                        </thead>
                                    <tbody>";

                foreach ($value as $name => $content)
                {
                    $second_column .= "<tr>
                                            <td class='table-area-name'>{$content['worker_name']}</td>
                                            <td class='table-area-extension'>{$content['internal_extension']}</td>
                                        </tr>";
                }

                $second_column .= "</tbody>
                                 </table>";
            }
            $count++;
        }

        $first_column .= "</td>";
        $html .= $first_column;
        $second_column .= "</td>";
        $html .= $second_column;

        $html .= "</tr>
                        </tbody>
                    </table>
                </body>
            </html>";

        return $html;
        exit();
    }
}