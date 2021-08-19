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
    public function count_rows($search) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        $this->db->where('flag_drop', 0);
        $this->db->from('git_worker_extensions');

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            // $this->db->select('git_users.id_user');
            // $this->db->from('git_users');
            // $this->db->join('git_roles', 'git_roles.id_role = git_users.id_role');
            // $this->db->join('fet_aspirants', 'fet_aspirants.id_aspirant = git_users.id_aspirant', 'left');
            // $this->db->where('git_users.git_company != ', 'G');
            // $this->db->where('git_users.flag_drop', 0);
            // $this->db->where('git_users.id_user !=', $this->session->userdata['id_user']);

            // if($this->session->userdata['id_role'] != "11")
            // {
            //     $this->db->where('git_users.id_role !=', 11);
            // }

            // $this->db->group_start();
            // $this->db->like('CONCAT(git_users.name_user, " ", git_users.lastname_user)', $search);
            // $this->db->or_like('git_roles.name_role', $search);
            // $this->db->or_like('git_users.user', $search);
            // $this->db->or_like('git_users.email_user', $search);
            // $this->db->or_like('DATE_FORMAT(git_users.date_keepalive, \'%d-%m-%Y\')', $search);
            // $this->db->or_like('CONCAT(fet_aspirants.name_aspirant, " ", fet_aspirants.first_last_name_aspirant, " ", fet_aspirants.second_last_name_aspirant)', $search);
            // $this->db->group_end();

            // $result['total_filtered']                                           =   $this->db->count_all_results();
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
        $this->db->select('CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv) AS id_worker, email_extension, internal_extension, external_extension');
        $this->db->join('fet_workers', 'git_worker_extensions.id_worker = fet_workers.id_worker');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
        $this->db->where('git_worker_extensions.flag_drop', 0);

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv)', $search);
            $this->db->or_like('email_extension', $search);
            $this->db->or_like('internal_extension', $search);
            $this->db->or_like('external_extension', $search);
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

        $this->db->select('fet_workers.id_cv AS id, CONCAT(name_cv, " ", first_lcv, " ", second_lcv, " - ", number_dcv) AS text');
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
    public function areas_select($params) // Eliminar -> Pendiente
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
    public function exist_extension($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        if (isset($params['pk']))
        {
            // $this->db->select($params['name']);
            // $this->db->where('git_company != ', 'G');
            // $this->db->where('flag_drop', 0);
            // $this->db->where($params['name'], trim($params['value']));
            // $this->db->where('id_user !=', $params['pk']);
        }
        else
        {
            $this->db->select('id_worker, id_element1, id_element2, external_extension, internal_extension, email_extension, phone_extension, ip_extension');
            $this->db->where('flag_drop', 0);
            $this->db->group_start();
            $this->db->where('id_worker', trim($params['id_worker']));
            $this->db->or_where('id_element1', trim($params['id_element1']));
            $this->db->or_where('id_element2', trim($params['id_element2']));
            $this->db->or_where('external_extension', trim($params['external_extension']));
            $this->db->or_where('internal_extension', trim($params['internal_extension']));
            $this->db->or_where('email_extension', trim($params['email_extension']));
            $this->db->or_where('phone_extension', trim($params['phone_extension']));
            $this->db->or_where('ip_extension', trim($params['ip_extension']));
            $this->db->group_end();
        }

        $query                                                                  =   $this->db->get('git_worker_extensions');

        if (count($query->result_array()) > 0)
        {
            $message                                                            =   ' alguno de estos datos';

            if (isset($params['pk']))
            {
                $params[$params['name']]                                        =   trim($params['value']);
                unset( $params['name'], $params['value'], $params['pk'] );
            }

            foreach ($query->row_array() as $key => $value)
            {
                $entries = [
                    'id_worker'                                                 =>  ' este trabajador.',
                    'id_element1'                                               =>  ' este dispositivo (teléfono).',
                    'id_element2'                                               =>  ' este dispositivo (celular).',
                    'external_extension'                                        =>  ' este número externo.',
                    'internal_extension'                                        =>  ' este número interno.',
                    'email_extension'                                           =>  ' este correo corporativo.',
                    'phone_extension'                                           =>  ' este número de celular.',
                    'ip_extension'                                              =>  ' esta IP.',
                ];

                if (isset($entries[$key]) && strtolower($value) == strtolower(trim($params[$key]))) {
                    $message                                                    =   $entries[$key];
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe una extensión con ' . $message;
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
    public function add($params) // Eliminar -> Revisado
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('id_worker', 'Trabajador', 'required');
        $this->form_validation->set_rules('id_element1', 'Teléfono', 'required');
        $this->form_validation->set_rules('id_element2', 'Celular', 'required');
        $this->form_validation->set_rules('id_area', 'Área', 'required');
        $this->form_validation->set_rules('external_extension', 'Extensión externa', 'required');
        $this->form_validation->set_rules('internal_extension', 'Extensión interna', 'required');
        $this->form_validation->set_rules('email_extension', 'Correo corporativo', array('required', 'valid_email'));
        $this->form_validation->set_rules('phone_extension', 'Número de celular', 'required');
        $this->form_validation->set_rules('ip_extension', 'IP', 'required');

        if ($this->form_validation->run())
        {
            $params['id_worker']                                                =   trim($params['id_worker']);
            $params['id_element1']                                              =   trim($params['id_element1']);
            $params['id_element2']                                              =   trim($params['id_element2']);
            $params['id_area']                                                  =   trim($params['id_area']);
            $params['external_extension']                                       =   trim($params['external_extension']);
            $params['internal_extension']                                       =   trim($params['internal_extension']);
            $params['email_extension']                                          =   $this->_trabajandofet_model->user_name($params['email_extension']);
            $params['phone_extension']                                          =   trim($params['phone_extension']);
            $params['ip_extension']                                             =   trim($params['ip_extension']);
            $params['git_company']                                              =   trim($params['git_company']) === 'checked' ? 'A' : 'T';
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['date_insert']                                              =   date('Y-m-d H:i:s');

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
    * @return    array $result
    **/
    public function edit($params) // Eliminar -> Revisado
    {
        $result                                                                 =   array();

        if ($params['value'] != '' || $params['value'] != null)
        {
            $data                                                               =   array();

            switch ($params['name'])
            {
                case 'id_worker':
                    $data['id_worker']                                          =   trim($params['value']);
                    break;

                case 'id_element1':
                    $data['id_element1']                                        =   trim($params['value']);
                    break;

                case 'id_element2':
                    $data['id_element2']                                        =   trim($params['value']);
                    break;
                
                case 'id_area':
                    $data['id_area']                                            =   trim($params['value']);
                    break;
                
                case 'external_extension':
                    $data['external_extension']                                 =   trim($params['value']);
                    break;
                
                case 'internal_extension':
                    $data['internal_extension']                                 =   trim($params['value']);
                    break;

                case 'email_extension':
                    $data['email_extension']                                    =   $this->_trabajandofet_model->user_name($params['value']);
                    break;

                case 'phone_extension':
                    $data['phone_extension']                                    =   trim($params['value']);
                    break;

                case 'ip_extension':
                    $data['ip_extension']                                       =   trim($params['value']);
                    break;
                
                case 'git_company':
                    $data['git_company']                                        =   trim($params['value']) === 'checked' ? 'A' : 'T';
                    break;

                default:
                    $data[$params['name']]                                      =   $params['value'];
                    break;
            }

            $data['id']                                                         =   $params['pk'];
            $data['user_update']                                                =   $this->session->userdata['id_user'];
            $data['date_update']                                                =   date('Y-m-d H:i:s');

            $answer                                                             =   $this->_trabajandofet_model->update_data($data, 'id_extension', 'git_worker_extensions');

            if ($answer)
            {
                $data_history                                                   =   $data;
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
    public function udrop($param) // Eliminar -> Revisado
    {
        $data                                                                   =   array(
            'id'                                                                        =>  $param['id_extension'],
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
    public function trace_register($param) // Eliminar -> Revisado
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
        $this->db->select('fu.name_user, fu.lastname_user, fr.name_role, fu.user, fu.email_user, DATE_FORMAT(fu.date_keepalive, \'%d-%m-%Y %h:%i %p\') AS date_keepalive, CONCAT(fa.name_aspirant, " ", fa.first_last_name_aspirant, " ", fa.second_last_name_aspirant) AS name_aspirant');
        $this->db->join('git_roles fr', 'fr.id_role = fu.id_role');
        $this->db->join('fet_aspirants fa', 'fa.id_aspirant = fu.id_aspirant', 'left');
        $this->db->where('fu.git_company != ', 'G');
        $this->db->where('fu.flag_drop', 0);
        $this->db->where('fu.id_user !=', $this->session->userdata['id_user']);

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('fu.id_role !=', 11);
        }

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('fu.name_user', $search);
            $this->db->or_like('fu.lastname_user', $search);
            $this->db->or_like('fr.name_role', $search);
            $this->db->or_like('fu.user', $search);
            $this->db->or_like('fu.email_user', $search);
            $this->db->or_like('DATE_FORMAT(fu.date_keepalive, \'%d-%m-%Y\')', $search);
            $this->db->or_like('CONCAT(fa.name_aspirant, " ", fa.first_last_name_aspirant, " ", fa.second_last_name_aspirant)', $search);
            $this->db->group_end();
        }

        $this->db->order_by('fu.name_user', 'ASC');
        $query                                                                  =   $this->db->get('git_users fu');

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