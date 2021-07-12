<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles_model extends CI_Model 
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

        $this->db->where('git_company =', 'T');
        $this->db->where('flag_drop', 0);

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('id_role !=', $this->session->userdata['id_role']);
            $this->db->where('id_role !=', 11);
        }

        $this->db->from('git_roles');
        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('id_role');
            $this->db->from('git_roles');
            $this->db->where('git_company =', 'T');            
            $this->db->where('flag_drop', 0);

            if($this->session->userdata['id_role'] != "11")
            {
                $this->db->where('id_role !=', $this->session->userdata['id_role']);
                $this->db->where('id_role !=', 11);
            }

            $this->db->like('name_role', $search);
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
        $this->db->select('id_role, name_role');
        $this->db->where('git_company =', 'T');
        $this->db->where('flag_drop', 0);

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('id_role !=', $this->session->userdata['id_role']);
            $this->db->where('id_role !=', 11);
        }

        if (!empty($search))
        {
            $this->db->like('name_role', $search);
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('git_roles');

        $roles                                                                  =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($roles as $key => $action)
            {
                $count++;
                $roles[$key]['number']                                          =   $count;
            }
        }

        return $roles;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function exist_role($params)
    {
        $result                                                                 =   array();

        $this->db->select('name_role');
        $this->db->where('git_company =', 'T');
        $this->db->where('flag_drop', 0);

        if (isset($params['pk']))
        {
            $this->db->where('name_role', trim($params['value']));
            $this->db->where('id_role !=', $params['pk']);
        }
        else
        {
            $this->db->where('name_role', trim($params['name_role']));
        }

        $query                                                                  =   $this->db->get('git_roles');

        if (count($query->result_array()) > 0)
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe este rol';
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

        $this->form_validation->set_rules('name_role', 'Rol', 'required');

        if($this->form_validation->run())
        {
            $params['git_company']                                              =   'T';
            $params['name_role']                                                =   mb_strtoupper($this->_trabajandofet_model->accents($params['name_role']));
            $params['user_insert']                                              =   $this->session->userdata['id_user'];

            $this->db->trans_start();

            $answer                                                             =   $this->_trabajandofet_model->insert_data($params, 'git_roles');

            // if ($answer)
            // {
            //     $permission['id_role']                                          =   $answer;
            //     $permission['id_submodule']                                     =   1;
            //     $permission['git_company']                                      =   'T';
            //     $permission['name_submodule']                                   =   'DASHBOARD';
            //     $permission['id_action']                                        =   1;
            //     $permission['name_action']                                      =   'VIEW';
            //     $permission['user_insert']                                      =   $params['user_insert'];

            //     $this->_trabajandofet_model->insert_data($permission, 'git_permissions');
            // }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
            {
                $data_history                                                   =   $params;
                $data_history['id_role']                                        =   $answer;
                $data_history['user_update']                                    =   $params['user_insert'];
                unset($data_history['git_company']);
                unset($data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'git_roles_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al guardar el rol.';
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

        if($params['value'] != '' || $params['value'] != null)
        {
            $data['id']                                                         =   $params['pk'];
            $data['name_role']                                                  =   mb_strtoupper($this->_trabajandofet_model->no_accents($params['value']));
            $data['user_update']                                                =   $this->session->userdata['id_user'];
            $data['date_update']                                                =   date('Y-m-d H:i:s');

            $answer                                                             =   $this->_trabajandofet_model->update_data($data, 'id_role', 'git_roles');

            $data_history                                                       =   $data;
            $data_history['id_role']                                            =   $data_history['id'];
            unset($data_history['id']);

            $this->_trabajandofet_model->insert_data($data_history, 'git_roles_history');

            if ($answer)
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
    * @param     array $param
    * @return    array $result
    **/
    public function udrop($param)
    {
        $result                                                                 =   array();

        $this->db->where('git_company =', 'T');
        $this->db->where('flag_drop', 0);
        $this->db->where('id_role', $param['id_role']);
        $this->db->from('git_users');

        $total                                                                  =   $this->db->count_all_results();

        if ($total > 0)
        {
            $result['message']                                                  =   'Existen usuarios que tienen asignado este rol, por lo tanto no se puede eliminar.';
            $result['data']                                                     =   FALSE;
        }
        else
        {
            $this->db->where('git_company =', 'T');
            $this->db->where('flag_drop', 0);
            $this->db->where('id_role', $param['id_role']);
            $this->db->from('fet_affiliates');

            $total_affiliates                                                   =   $this->db->count_all_results();

            if ($total_affiliates > 0)
            {
                $result['message']                                              =   'Existen afiliados que tienen asignado este rol, por tanto no se puede eliminar.';
                $result['data']                                                 =   FALSE;
            }
            else
            {
                $data                                                           =   array(
                    'id'                                                                =>  $param['id_role'],
                    'flag_drop'                                                         =>  1,
                    'user_update'                                                       =>  $this->session->userdata['id_user'],
                    'date_update'                                                       =>  date('Y-m-d H:i:s')
                                                                                    );

                $delete                                                         =   $this->_trabajandofet_model->update_data($data, 'id_role', 'git_roles');

                if ($delete)
                {
                    $result['message']                                          =   'Acción realizada con éxito!';
                    $result['data']                                             =   TRUE;
                }
                else
                {
                    $result['message']                                          =   'Problemas al eliminar el rol.';
                    $result['data']                                             =   FALSE;
                }
            }
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
    
        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('git_roles', 'id_role', $param['id_role']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('git_roles_history', 'id_role', $param['id_role']);

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
        $this->db->select('name_role');
        $this->db->where('git_company =', 'T');
        $this->db->where('flag_drop', 0);
        
        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('id_role !=', $this->session->userdata['id_role']);
            $this->db->where('id_role !=', 11);
        }

        if (!empty($search))
        {
            $this->db->like('name_role', $search);
        }

        $this->db->order_by('name_role', 'ASC');
        $query                                                                  =   $this->db->get('git_roles');

        $result                                                                 =   array();

        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay roles para exportar.';
        }

        return $result;
        exit();
    }
}