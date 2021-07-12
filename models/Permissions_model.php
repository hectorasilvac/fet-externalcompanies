<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
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
    * @copyright 2021 Fabrica de Desarrollo
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function count_rows($search)
    {
        $result                                                                 =   array();

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('id_role !=', $this->session->userdata['id_role']);
            $this->db->where('id_role !=', 11);
        }

        $this->db->where('git_company =', 'T');
        $this->db->from('git_permissions');

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('git_permissions.id_role');

            if($this->session->userdata['id_role'] != "11")
            {
                $this->db->where('git_permissions.id_role !=', $this->session->userdata['id_role']);
                $this->db->where('git_permissions.id_role !=', 11);
            }

            $this->db->group_start();
            $this->db->like('git_roles.name_role', $search);
            $this->db->or_like('submodules.name_es_submodule', $search);
            $this->db->or_like('actions.name_es_action', $search);
            $this->db->group_end();
            $this->db->from('git_permissions');
            $this->db->where('git_permissions.git_company =', 'T');
            $this->db->join('git_roles', 'git_roles.id_role = git_permissions.id_role');
            $this->db->join('git_submodules submodules', 'submodules.id_submodule = git_permissions.id_submodule');
            $this->db->join('git_actions actions', 'actions.id_action = git_permissions.id_action');

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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $limit, int $start, string $search, int $col, string $dir
    * @return    array $query->result_array()
    **/
    public function all_rows($limit, $start, $search, $col, $dir)
    {
        $this->db->select('roles.name_role, submodules.name_es_submodule AS name_submodule, actions.name_es_action AS name_action, permissions.id_role, permissions.id_submodule, permissions.id_action');

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('permissions.id_role !=', $this->session->userdata['id_role']);
            $this->db->where('permissions.id_role !=', 11);
        }

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('roles.name_role', $search);
            $this->db->or_like('submodules.name_es_submodule', $search);
            $this->db->or_like('actions.name_es_action', $search);
            $this->db->group_end();
        }

        $this->db->from('git_permissions permissions');
        $this->db->where('permissions.git_company =', 'T');
        $this->db->join('git_roles roles', 'roles.id_role = permissions.id_role');
        $this->db->join('git_submodules submodules', 'submodules.id_submodule = permissions.id_submodule');
        $this->db->join('git_actions actions', 'actions.id_action = permissions.id_action');
        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);
        $this->db->order_by('name_role', $dir);
        $this->db->order_by('name_submodule', $dir);
        $this->db->order_by('name_action', $dir);

        $query                                                                  =   $this->db->get();

        $permissions                                                            =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($permissions as $key => $action)
            {
                $count++;
                $permissions[$key]['number']                                    =   $count;
            }
        }

        return $permissions;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search, array $params
    * @return    array $result
    **/
    public function count_rows_actions($search, $params)
    {
        $result                                                                 =   array();

        $this->db->select('id_action');
        $this->db->where('id_submodule', $params['id_submodule']);
        $this->db->where('git_company =', 'T');
        $this->db->from('git_actions_submodule');

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('id_action');
            $this->db->where('id_submodule', $params['id_submodule']);
            $this->db->where('git_company =', 'T');
            $this->db->group_start();
            $this->db->like('name_action', $search);
            $this->db->or_like('name_es_action', $search);
            $this->db->or_like('id_action', $search);
            $this->db->group_end();
            $this->db->from('git_actions_submodule');

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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $limit, int $start, string $search, int $col, string $dir
    * @return    array $query->result_array()
    **/
    public function all_rows_actions($limit, $start, $search, $col, $dir, $params)
    {
        $submodule = explode('-',  $params['id_submodule']);

        $this->db->select('CONCAT(git_actions.name_es_action, " (", git_actions_submodule.name_action, ")") AS name, CONCAT(git_actions_submodule.id_action, "-", git_actions_submodule.name_action, "-", git_actions.name_es_action) AS id, git_actions_submodule.id_action');
        $this->db->join('git_actions', 'git_actions.id_action = git_actions_submodule.id_action');
        $this->db->where('git_actions_submodule.id_submodule', $submodule[0]);
        $this->db->where('git_actions_submodule.git_company =', 'T');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_action', $search);
            $this->db->or_like('name_es_action', $search);
            $this->db->or_like('id_action', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('git_actions_submodule');

        return $query->result_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function add($params)
    {
        $params['user_insert']                                                  =   $this->session->userdata['id_user'];

        $result                                                                 =   array();

        $this->form_validation->set_rules('id_role', 'Rol', 'required');
        $this->form_validation->set_rules('id_submodule', 'Submodulo', 'required');

        if($this->form_validation->run())
        {
            $result                                                             =   array();
            $result['message']                                                  =   '';
            $result['data']                                                     =   FALSE;

            $submodule                                                          =   explode('-',  $params['id_submodule']);

            if ($params['select_actions_all'] == "1")
            {
                $this->db->trans_start();

                $data                                                           =   array(
                        'id_role'                                                       =>  $params['id_role'],
                        'id_submodule'                                                  =>  $submodule[0]
                                                                                    );

                $this->_trabajandofet_model->drop_data($data, 'git_permissions');

                $this->db->select('id_action, name_action');
                $this->db->where('id_submodule = ', $submodule[0]);
                $this->db->where('git_company =', 'T');

                $query                                                          =   $this->db->get('git_actions_submodule');

                $actions                                                        =   $query->result_array();

                foreach ($actions as $action)
                {
                    $permission                                                 =   array(
                        'git_company'                                                   =>  'T',
                        'id_role'                                                       =>  $params['id_role'],
                        'id_submodule'                                                  =>  $submodule[0],
                        'name_submodule'                                                =>  $submodule[1],
                        'id_action'                                                     =>  $action['id_action'],
                        'name_action'                                                   =>  $action['name_action'],
                        'user_insert'                                                   =>  $params['user_insert']
                                                                                    );

                    $this->_trabajandofet_model->insert_data($permission, 'git_permissions');
                }

                $this->db->trans_complete();

                $result                                                         =   array();

                if ($this->db->trans_status() === TRUE)
                {
                    $result['data']                                             =   TRUE;
                    $result['id_role']                                          =   $params['id_role'];
                    $result['message']                                          =   'Has guardado los permisos del submodulo sobre el rol correctamente!';
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Problemas al guardar las acciones.';
                }
            }
            else
            {
                $this->db->trans_start();

                $data                                                           =   array(
                        'id_role'                                                       =>  $params['id_role'],
                        'id_submodule'                                                  =>  $submodule[0]
                                                                                    );

                $this->_trabajandofet_model->drop_data($data, 'git_permissions');

                $flag_view = FALSE;

                for ($i=0; $i < count($params); $i++)
                {
                    if (isset($params['checkbox' . $i]))
                    {
                        $checkbox                                               =   explode("-", $params['checkbox' . $i]);

                        if ($checkbox[1] == "VIEW")
                        {
                            $flag_view = TRUE;
                        }

                        $permission                                             =   array(
                            'git_company'                                               =>  'T',
                            'id_role'                                                   =>  $params['id_role'],
                            'id_submodule'                                              =>  $submodule[0],
                            'name_submodule'                                            =>  $submodule[1],
                            'id_action'                                                 =>  $checkbox[0],
                            'name_action'                                               =>  $checkbox[1],
                            'user_insert'                                               =>  $params['user_insert']
                                                                                    );

                        $this->_trabajandofet_model->insert_data($permission, 'git_permissions');
                    }
                }

                if ($flag_view == FALSE)
                {
                    $permission                                                 =   array(
                        'git_company'                                                   =>  'T',
                        'id_role'                                                       =>  $params['id_role'],
                        'id_submodule'                                                  =>  $submodule[0],
                        'name_submodule'                                                =>  $submodule[1],
                        'id_action'                                                     =>  1,
                        'name_action'                                                   =>  'VIEW',
                        'user_insert'                                                   =>  $params['user_insert']
                                                                                    );

                    $this->_trabajandofet_model->insert_data($permission, 'git_permissions');
                }

                $this->db->trans_complete();

                $result                                                         =   array();

                if ($this->db->trans_status() === TRUE)
                {
                    $result['data']                                             =   TRUE;
                    $result['id_role']                                          =   $params['id_role'];
                    $result['message']                                          =   'Has guardado los permisos del submodulo sobre el rol correctamente!';
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Problemas al guardar las acciones.';
                }
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function roles_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_role AS id, name_role AS text');

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('name_role', $params['q']);
        }

        if (isset($params['id']) && $params['id'] != '')
        {
            $this->db->where('id_role !=', $params['id']);
        }

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('id_role !=', $this->session->userdata['id_role']);
            $this->db->where('id_role !=', 11);
        }

        $this->db->where('flag_drop', 0);
        $this->db->from('git_roles');
        $this->db->where('git_company =', 'T');
        $this->db->order_by('name_role', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get();

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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function submodules_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('CONCAT(id_submodule, \'-\', name_submodule, \'-\', name_es_submodule) as id, name_es_submodule as text');

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->group_start();
            $this->db->like('name_es_submodule', $params['q']);
            $this->db->or_like('name_submodule', $params['q']);
            $this->db->group_end();
        }

        if (isset($params['ids']) && count($params['ids']) > 0)
        {
            $id                                                                 =   array();

            foreach ($params['ids'] as $key => $ids)
            {
                $submodule                                                      =   explode('-',  $ids);
                array_push($id, $submodule[0]);
            }

            $this->db->where_not_in('id_submodule', $id);
        }

        $this->db->from('git_submodules');
        $this->db->where('git_company =', 'T');
        $this->db->order_by('name_es_submodule', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get();

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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function actions_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('CONCAT(id_action, \'-\', name_action, \'-\', name_es_action) AS id, name_es_action AS text');

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->group_start();
            $this->db->like('name_es_action', $params['q']);
            $this->db->or_like('name_action', $params['q']);
            $this->db->group_end();
        }

        if (isset($params['ids']) && count($params['ids']) > 0)
        {
            $id                                                                 =   array();

            foreach ($params['ids'] as $key => $ids)
            {
                $submodule                                                      =   explode('-',  $ids);
                array_push($id, $submodule[0]);
            }

            $this->db->where_not_in('id_action', $id);
        }

        $this->db->from('git_actions');
        $this->db->where('git_company =', 'T');
        $this->db->order_by('name_es_action', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get();

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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function drop($params)
    {
        if ($params['id_action'] == '1')
        {
            $this->db->select('COUNT(*) AS count');
            $this->db->where('id_role = ', $params['id_role']);
            $this->db->where('id_submodule = ', $params['id_submodule']);
            $this->db->where('id_action != ', $params['id_action']);
            $this->db->where('git_company = ', 'T');

            $query                                                              =   $this->db->get('git_permissions');

            $count                                                              =   $query->row_array();

            if (intval($count['count']) != 0)
            {
                $permissions                                                    =   '';

                if (intval($count['count']) > 1)
                {
                    $permissions                                                =   'los otros (' . $count['count'] . ') permisos.';
                }
                else
                {
                    $permissions                                                =   'el permiso faltante.';
                }

                $result['data']                                                 =   FALSE;
                $salto                                                          =   ($this->session->userdata['mobile'] == 1) ? '': '<br/>';
                $result['message']                                              =   'No es posible eliminar esta acción sin eliminar ' . $salto . 'antes ' . $permissions;

                return $result;
                exit();
            }
        }

        $data                                                                   =   array(
                'id_role'                                                               =>  $params['id_role'],
                'id_submodule'                                                          =>  $params['id_submodule'],
                'id_action'                                                             =>  $params['id_action']
                                                                                    );

        $drop                                                                   =   $this->_trabajandofet_model->drop_data($data, 'git_permissions');

        if ($drop)
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Acción realizada con éxito!';
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'La acción no se realizó.';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function trace_register($params)
    {
        $ids                                                                    =   explode('-',  $params['ids']);

        $params['id_role']                                                      =   $ids[0];
        $params['id_submodule']                                                 =   $ids[1];
        $params['id_action']                                                    =   $ids[2];

        $result                                                                 =   array();

        $sql                                                                    =   'SELECT
                                                                                    DATE_FORMAT(A.date_insert, \'%d-%m-%Y %h:%i %p\') AS date_insert,
                                                                                    (SELECT CONCAT(B.name_user, \' \', B.lastname_user) FROM git_users B WHERE B.id_user = A.user_insert) AS user_insert
                                                                                    FROM git_permissions A
                                                                                    WHERE A.git_company = "T" 
                                                                                    AND A.id_role = ' . $params['id_role'] . '
                                                                                    AND A.id_submodule = ' . $params['id_submodule'] . '
                                                                                    AND A.id_action = ' . $params['id_action'];

        $query                                                                  =   $this->db->query($sql);

        $result['data']                                                         =   $query->row_array();

        if (count($result['data']) > 0)
        {
            $result['data_global']                                              =   FALSE;
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['message']                                                  =   'No hay histórico de este registro.';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function export_xlsx($search)
    {
        $this->db->select('name_role, name_submodule, name_action');

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('git_permissions.id_role !=', $this->session->userdata['id_role']);
            $this->db->where('git_permissions.id_role !=', 11);
        }

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_role', $search);
            $this->db->or_like('name_submodule', $search);
            $this->db->or_like('name_action', $search);
            $this->db->group_end();
        }

        $this->db->from('git_permissions');
        $this->db->where('git_permissions.git_company = ', 'T');
        $this->db->join('git_roles', 'git_roles.id_role = git_permissions.id_role');
        $this->db->order_by('name_action', 'ASC');
        $query                                                                  =   $this->db->get();
        $result                                                                 =   array();
        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay permisos para exportar.';
        }

        return $result;
        exit();
    }
}