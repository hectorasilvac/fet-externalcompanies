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
    public function count_rows($search) // Eliminar -> Revisado 27/08/2021 -> OK
    {
        $result                                                                 =   array();

        $this->db->from('git_worker_extensions');

        $result['total']                                                        =   $this->db->count_all_results();

        if ( ! empty($search))
        {
            $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->join('git_areas', 'fet_workers.id_area = git_areas.id_area', 'left');
            $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
            $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');

            $this->db->group_start();
            $this->db->like('CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv)', $search);
            $this->db->or_like('CONCAT(gel1.serial_element, " - ", gel1.name_element)', $search);
            $this->db->or_like('CONCAT(gel2.serial_element, " - ", gel2.name_element)', $search);
            $this->db->or_like('git_areas.name_area', $search);
            $this->db->or_like('email_extension', $search);
            $this->db->or_like('internal_extension', $search);
            $this->db->or_like('external_extension', $search);
            $this->db->or_like('ip_extension', $search);
            $this->db->group_end();

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
    public function all_rows($limit, $start, $search, $col, $dir) // Eliminar -> Revisado 27/08/2021 -> OK
    {
        $this->db->select('id_extension, CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv) AS id_worker, email_extension, internal_extension, external_extension, ip_extension');
        $this->db->select('git_areas.name_area AS worker_area');
        $this->db->select('CONCAT(gel1.serial_element, " - ", gel1.name_element) AS element1');
        $this->db->select('CONCAT(gel2.serial_element, " - ", gel2.name_element) AS element2');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('git_areas', 'fet_workers.id_area = git_areas.id_area', 'left');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
        $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');

        if ( ! empty($search))
        {
            $this->db->group_start();
            $this->db->like('CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv)', $search);
            $this->db->or_like('CONCAT(gel1.serial_element, " - ", gel1.name_element)', $search);
            $this->db->or_like('CONCAT(gel2.serial_element, " - ", gel2.name_element)', $search);
            $this->db->or_like('git_areas.name_area', $search);
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
    * @param     
    * @return    array $result
    **/
    public function directory() // Eliminar -> Revisado 26/08/2021 -> OK
    {
        $result                                                                 =   array();

        $this->db->select('LOWER(CONCAT(name_cv, " ", first_lcv, " ", second_lcv)) AS worker_name');
        $this->db->select('internal_extension, phone_extension, email_extension');
        $this->db->select('LOWER(git_areas.name_area) AS worker_area');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('git_areas', 'fet_workers.id_area = git_areas.id_area', 'left');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->where('git_worker_extensions.flag_pdf', 0);

        $query                                                                  =   $this->db->get('git_worker_extensions');
        $data                                                                   =   $query->result_array();

        if (count($data) > 0)
        {
            $workers_by_area = [];

            foreach ($data as $key => $value)
            {
                if (array_key_exists($value['worker_area'], $workers_by_area))
                {
                    array_push($workers_by_area[$value['worker_area']], $value);
                }
                else
                {
                    $workers_by_area[$value['worker_area']] = [$value];
                }
            }

            $result['data']                                                     =   $workers_by_area;
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No se encontraron registros en el directorio.';
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

        if (isset($params['git_company']))
        {
            unset($params['git_company']);
        }

        if (isset($params['flag_pdf']))
        {
            unset($params['flag_pdf']);
        }

        $this->db->select('id_worker, id_element1, id_element2, external_extension, internal_extension, email_extension, phone_extension, ip_extension');

        $params_to_validate                                                     =   count(array_filter($params)) >= 2;

        if (isset($params['name']) || $params_to_validate)
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
    public function add($params) // Eliminar -> Revisado 25/08/2021
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('id_worker', 'Trabajador', 'required');
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
            $params['email_extension']                                          =   $this->_trabajandofet_model->user_name($params['email_extension']);
            $params['ip_extension']                                             =   trim($params['ip_extension']) ?: NULL;
            $params['phone_extension']                                          =   trim($params['phone_extension']) ?: NULL;
            $params['external_extension']                                       =   trim($params['external_extension']) ?: NULL;
            $params['internal_extension']                                       =   trim($params['internal_extension']) ?: NULL;
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['date_insert']                                              =   date('Y-m-d H:i:s');

            if (isset($params['id_element1']) && !empty(trim($params['id_element1']))) 
            {
                $params['id_element1']                                           =   trim($params['id_element1']);
            }

            if (isset($params['id_element2']) && !empty(trim($params['id_element2'])))
            {
                $params['id_element2']                                          =   trim($params['id_element2']);
            }

            if (isset($params['git_company']) && !empty(trim($params['git_company'])))
            {
                $params['git_company']                                          =   trim($params['git_company']);
            }

            if (isset($params['flag_pdf']) && !empty($params['flag_pdf']))
            {
                $params['flag_pdf']                                             =   0;
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
            $this->db->select('id_extension, ip_extension, id_element1, id_element2, phone_extension, git_worker_extensions.git_company, flag_pdf');
            $this->db->select('CONCAT(gel1.serial_element, " - ", gel1.name_element) AS name_element1');
            $this->db->select('CONCAT(gel2.serial_element, " - ", gel2.name_element) AS name_element2');
            $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
            $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');
            $this->db->where('id_extension', $params['value']);
            

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
                        $params['id_worker']                                    =   trim($params['value']);
                        break;

                    case 'email_extension':
                        $params['email_extension']                              =   $this->_trabajandofet_model->user_name($params['value']);
                        break;

                    case 'internal_extension':
                        $params['internal_extension']                           =   trim($params['value']) ?: NULL;
                        break;

                    case 'external_extension':
                        $params['external_extension']                           =   trim($params['value']) ?: NULL;
                        break;
                }
                unset($params['value'], $params['name']);
            } 
            else 
            {
                $params['git_company']                                          =   $params['git_company'] ?? 'T';
                $params['flag_pdf']                                             =   $params['flag_pdf'] ?? 1;
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
    public function drop($param) // Eliminar -> Revisado 25/08/2021
    {
        $result                                                                 =   array();
        $query                                                                  =   $this->db->delete('git_worker_extensions', array('id_extension' => $param['id_extension']));

        if ($query) 
        {
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
    public function trace_register($param) // Eliminar -> Revisado 25/08/2021
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
    public function export_xlsx($search) // Eliminar -> Revisado 27/08/2021 -> OK
    {
        $this->db->select('CONCAT(name_cv, " ", first_lcv, " ", second_lcv) AS worker_name');
        $this->db->select('number_dcv, name_area, internal_extension, external_extension, phone_extension, email_extension');
        $this->db->select('gel1.serial_element AS telephone_serial, gel1.name_element AS telephone_name');
        $this->db->select('gel2.serial_element AS cellphone_serial, gel2.name_element AS cellphone_name, ip_extension');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('git_areas', 'fet_workers.id_area = git_areas.id_area');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->join('git_elements AS gel1', 'git_worker_extensions.id_element1 = gel1.id_element', 'left');
        $this->db->join('git_elements AS gel2', 'git_worker_extensions.id_element2 = gel2.id_element', 'left');

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
    public function export_pdf() // Eliminar -> Revisado 27/08/2021 -> OK
    {
        $options                                                                =   array();

        $this->db->select('LOWER(CONCAT(name_cv, " ", first_lcv, " ", second_lcv)) AS worker_name');
        $this->db->select('internal_extension, phone_extension, email_extension');
        $this->db->select('LOWER(git_areas.name_area) AS worker_area');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('git_areas', 'fet_workers.id_area = git_areas.id_area', 'left');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->where('git_worker_extensions.flag_pdf', 0);

        $query                                                                  =   $this->db->get('git_worker_extensions');
        $options                                                                =   $query->result_array();

        if (count($options) > 0)
        {
            $workers_by_area = [];

            foreach ($options as $key => $value) 
            {
                if (array_key_exists($value['worker_area'], $workers_by_area)) 
                {
                    array_push($workers_by_area[$value['worker_area']], $value);
                } 
                else 
                {
                    $workers_by_area[$value['worker_area']]                     =   [$value];
                }
            }

            $html                                                               =   $this->template_pdf($workers_by_area);

            $mpdf                                                               =   new \Mpdf\Mpdf(array('mode' => 'utf-8', 'format' => 'Letter', 'margin_header' => 0, 'margin_footer' => 0));
            $mpdf->showImageErrors                                              =   true;
    
            $mpdf->WriteHTML($html);
            $mpdf->Output('TRABAJANDOFET - DIRECTORIO_' . date('dmY') . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        }
        else
        {
            return FALSE;
            exit();
        }

        return TRUE;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $workers_by_area
    * @return    string $html
    **/
    public function template_pdf($workers_by_area) // Eliminar -> Revisado 27/07/2021 -> OK
    {
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
                    font-family: sans-serif;
                }

                .header {
                    border-collapse: collapse;
                    width: 100%;
                    margin-bottom: 20px;
                }

                .header-logo {
                    width: 12%;
                    border: 0;
                    background-color: white;
                }

                .logo {
                  width: 120px;
                }

                .title {
                    background-color: #004f9f;
                    color: white;
                    border-top-right-radius: 30px;
                    font-weight: bold;
                    font-size: 30px;
                    text-transform: uppercase;
                    text-align: center;
                }

                .table-area {
                    width: 100%;
                    border-collapse: collapse;
                    border-spacing: 0;
                    border: 3px solid #004f9f;
                    font-size: 12px;
                }

                .table-area-row {
                    background-color: #004f9f;
                }

                .table-area-left {
                    border: 1px solid #004f9f;
                    padding: 4px;
                }

                .table-area-center {
                    border: 1px solid #004f9f;
                    padding: 4px;
                    text-align: center;
                }

                .table-header {
                    color: white;
                    font-style: italic;
                    padding: 6px;
                    text-transform: capitalize;
                }

                .text-capitalize {
                    text-transform: capitalize !important;
                  }

                .header-area {
                    width: 40%;
                }

                .header-phone {
                    width: 20%;
                }

                .header-extension {
                    width: 10%;
                }

                .header-email {
                    width: 30%;
                }
            </style>
        </head>
        <body>';

        $html .= '<table class="header">
                    <tr style="border-top-right-radius: 30px;">
                        <td class="header-logo"><img src="resources/img/logo_fet.png" alt="Directorio Teléfonico" class="logo"></td>
                        <td class="title">Directorio FET 2021</td>
                    </tr>
                </table>';

        foreach ($workers_by_area as $key => $value) {
            $html .= "<table class='table-area'>
                        <thead>
                            <tr class='table-area-row'>
                                <th class='table-header header-area text-capitalize'>{$key}</th>
                                <th class='table-header header-phone'>Línea Corporativa</th>
                                <th class='table-header header-extension'>EXT</th>
                                <th class='table-header header-email'>Correo Corporativo</th>
                            </tr>
                        </thead>
                        <tbody>";

            foreach ($value as $name => $content) {
                $html .= "<tr>
                            <td class='table-area-left text-capitalize'>{$content['worker_name']}</td>
                            <td class='table-area-center'>{$content['phone_extension']}</td>
                            <td class='table-area-center'>{$content['internal_extension']}</td>
                            <td class='table-area-left'>{$content['email_extension']}</td>
                        </tr>";
            }
            $html .= "</tbody>
                    </table>";
        }

        $html .= "</body>
                </html>";

        return $html;
        exit();
    }
}