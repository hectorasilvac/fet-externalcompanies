<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     $role, $submodule
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

        $this->db->select('id_module');
        $this->db->where('id_module !=', 13);                                   //TRABAJANDOFET
        $this->db->where('git_company =', 'T');
        $this->db->from('git_modules');
        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('id_module');
            $this->db->where('id_module !=', 13);
            $this->db->where('git_company =', 'T');
            $this->db->group_start();
            $this->db->like('name_module', $search);
            $this->db->or_like('name_es_module', $search);
            $this->db->or_like('url_module', $search);
            $this->db->or_like('icon_module', $search);
            $this->db->group_end();
            $this->db->from('git_modules');
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
        $this->db->select('id_module, name_module, name_es_module, url_module, icon_module');
        $this->db->where('id_module !=', 13);                                   //TRABAJANDOFET
        $this->db->where('git_company =', 'T');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_module', $search);
            $this->db->or_like('name_es_module', $search);
            $this->db->or_like('url_module', $search);
            $this->db->or_like('icon_module', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('git_modules');

        $modules                                                                =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($modules as $key => $action)
            {
                $count++;
                $modules[$key]['number']                                        =   $count;
            }
        }

        return $modules;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function exist_module($params)
    {
        $result                                                                 =   array();

        if (isset($params['pk']))
        {
            $this->db->select($params['name']);
            $this->db->where($params['name'], trim($params['value']));
            $this->db->where('id_module !=', $params['pk']);
        }
        else
        {
            $this->db->select('name_module, name_es_module, url_module');
            $this->db->where('name_module', trim($params['name_module']));
            $this->db->or_where('name_es_module', trim($params['name_es_module']));

            if (trim($params['url_module'] != '#'))
            {
                $this->db->or_where('url_module', trim($params['url_module']));
            }
        }

        $this->db->where('git_company =', 'T');

        $query                                                                  =   $this->db->get('git_modules');

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
                switch ($key)
                {
                    case 'name_module':
                        if ( $value == mb_strtoupper(trim($params['name_module'])) )
                        {
                            $message                                            =   ' este nombre.';
                        }
                        break;

                    case 'name_es_module':
                        if ( $value == $this->_trabajandofet_model->to_camel(mb_strtolower($params['name_es_module'])) )
                        {
                            $message                                            =   ' este significado.';
                        }
                        break;

                    case 'url_module':
                        if ( $value == mb_strtolower(trim($params['url_module'])) )
                        {
                            $message                                            =   ' esta URL.';
                        }
                        break;
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe un módulo con '. $message;
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

        $this->form_validation->set_rules('name_module', 'Nombre', 'required');
        $this->form_validation->set_rules('name_es_module', 'Significado', 'required');
        $this->form_validation->set_rules('url_module', ' URL', 'required');
        $this->form_validation->set_rules('icon_module', 'Icono', 'required');

        if($this->form_validation->run())
        {
            $params['git_company']                                              =   'T';
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['name_module']                                              =   mb_strtoupper($this->_trabajandofet_model->clean_text($this->_trabajandofet_model->no_accents($params['name_module'])));
            $params['name_es_module']                                           =   $this->_trabajandofet_model->to_camel(mb_strtolower($this->_trabajandofet_model->accents($params['name_es_module'])));
            $params['icon_module']                                              =   mb_strtolower($params['icon_module']);

            $url_module                                                         =   explode('/', $params['url_module']);

            for ($i = 0; $i < count($url_module); $i++)
            {
                $url_module[$i]                                                 =   mb_strtolower($this->_trabajandofet_model->accents($url_module[$i]));
            }

            $params['url_module']                                               =   implode('/', $url_module);

            $this->db->select('sort_module');
            $this->db->order_by('sort_module', 'desc');
            $this->db->limit(1);

            $query                                                              =   $this->db->get('git_modules');

            $sort                                                               =   $query->row_array();

            if (isset($sort))
            {
                $params['sort_module']                                          =   $sort['sort_module'] + 1;
            }

            $this->db->trans_start();

            $answer                                                             =   $this->_trabajandofet_model->insert_data($params, 'git_modules');

            if ($params['url_module'] != '' && $params['url_module'] != '#' && $answer)
            {
                $submodule['id_module']                                         =   $answer;
                $submodule['git_company']                                       =   'T';
                $submodule['name_submodule']                                    =   $params['name_module'];
                $submodule['name_es_submodule']                                 =   $params['name_es_module'];
                $submodule['url_submodule']                                     =   $params['url_module'];
                $submodule['sort_submodule']                                    =   1;
                $submodule['user_insert']                                       =   $params['user_insert'];

                $submodule_insert                                               =   $this->_trabajandofet_model->insert_data($submodule, 'git_submodules');

                if ($submodule_insert)
                {
                    $action_submodule['id_action']                              =   1;
                    $action_submodule['git_company']                            =   'T';
                    $action_submodule['name_action']                            =   'VIEW';
                    $action_submodule['id_submodule']                           =   $submodule_insert;
                    $action_submodule['name_submodule']                         =   $submodule['name_submodule'];
                    $action_submodule['user_insert']                            =   $params['user_insert'];

                    $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
            {
                $data_history                                                   =   $params;
                $data_history['id_module']                                      =   $answer;
                $data_history['user_update']                                    =   $params['user_insert'];
                unset($data_history['git_company']);
                unset($data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'git_modules_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al guardar el módulo.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Todos los campos son requeridos.';
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

        if ($params['value'] != '' || $params['value'] != null)
        {
            $data                                                               =   array();

            switch ($params['name'])
            {
                case 'name_module':
                    $data['name_module']                                        =   mb_strtoupper($this->_trabajandofet_model->clean_text($this->_trabajandofet_model->no_accents($params['value'])));
                    break;

                case 'name_es_module':
                    $data['name_es_module']                                     =   $this->_trabajandofet_model->to_camel(mb_strtolower($this->_trabajandofet_model->accents($params['value'])));
                    break;

                case 'icon_module':
                    $data['icon_module']                                        =   mb_strtolower($params['value']);
                    break;

                case 'url_module':
                    $url_module                                                 =   explode('/', $params['value']);

                    for ($i = 0; $i < count($url_module); $i++)
                    {
                        $url_module[$i]                                         =   mb_strtolower($this->_trabajandofet_model->accents($url_module[$i]));
                    }

                    $data['url_module']                                         =   implode('/', $url_module);

                    break;

                default:
                    $data[$params['name']]                                      =   $params['value'];
                    break;
            }

            $data['id']                                                         =   $params['pk'];
            $data['git_company']                                                =   'T';
            $data['user_update']                                                =   $this->session->userdata['id_user'];
            $data['date_update']                                                =   date('Y-m-d H:i:s');

            $this->db->trans_start();

            $this->_trabajandofet_model->update_data($data, 'id_module', 'git_modules');

            $data_history                                                       =   $data;
            $data_history['id_module']                                          =   $data_history['id'];
            unset($data_history['id']);
            unset($data_history['git_company']);

            $this->_trabajandofet_model->insert_data($data_history, 'git_modules_history');

            if (isset($params['flag']))
            {
                $this->db->select('git_permissions.id_role, git_permissions.id_submodule');
                $this->db->join('git_submodules','git_submodules.id_submodule = git_permissions.id_submodule');
                $this->db->where('git_submodules.id_module', $data['id']);
                $this->db->where('git_permissions.git_company =', 'T');
                $this->db->group_by('git_permissions.id_role, git_permissions.id_submodule');

                $query                                                          =   $this->db->get('git_permissions');

                $permissions                                                    =   $query->result_array();

                if (count($permissions) > 0)
                {
                    $roles                                                      =   array();

                    foreach ($permissions as $row)
                    {
                        $drop_data                                              =   array(
                            'id_role'                                                   =>  $row['id_role'],
                            'id_submodule'                                              =>  $row['id_submodule']
                                                                                    );

                        $this->_trabajandofet_model->drop_data($drop_data, 'git_permissions');

                        if (!in_array($row['id_role'], $roles))
                        {
                            $roles[]                                            =   $row['id_role'];
                        }
                    }
                }

                $this->db->select('id_submodule');
                $this->db->where('id_module', $data['id']);
                $this->db->where('git_company =', 'T');

                $query_sub                                                      =   $this->db->get('git_submodules');

                $submodules                                                     =   $query_sub->result_array();

                if (count($submodules) > 0)
                {
                    foreach ($submodules as $row)
                    {
                        $drop_as                                                =   array(
                            'id_submodule'                                              =>  $row['id_submodule']
                                                                                    );

                        $this->_trabajandofet_model->drop_data($drop_as, 'git_actions_submodule');

                        $drop_sub                                               =   array(
                            'id_submodule'                                              =>  $row['id_submodule']
                                                                                    );

                        $this->_trabajandofet_model->drop_data($drop_sub, 'git_submodules');
                    }
                }

                if ($params['flag'] == 1)
                {
                    $module                                                     =   $this->_trabajandofet_model->select_single_data('name_module, name_es_module, url_module', FALSE, 'id_module', $data['id'], 'git_modules');

                    if (isset($module))
                    {
                        $submodule['id_module']                                 =   $data['id'];
                        $submodule['git_company']                               =   'T';
                        $submodule['name_submodule']                            =   $module['name_module'];
                        $submodule['name_es_submodule']                         =   $module['name_es_module'];
                        $submodule['url_submodule']                             =   $module['url_module'];
                        $submodule['sort_submodule']                            =   1;
                        $submodule['user_insert']                               =   $data['user_update'];

                        $new_sub                                                =   $this->_trabajandofet_model->insert_data($submodule, 'git_submodules');

                        if ($new_sub)
                        {
                            $action_submodule                                   =   array(
                                    'id_action'                                         =>  1,
                                    'git_company'                                       =>  'T',
                                    'name_action'                                       =>  'VIEW',
                                    'id_submodule'                                      =>  $new_sub,
                                    'name_submodule'                                    =>  $submodule['name_submodule'],
                                    'user_insert'                                       =>  $data['user_update']
                                                                                    );

                            $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');

                            if (count($permissions) > 0)
                            {
                                for ($i = 0; $i < count($roles); $i++)
                                {
                                    $insert                                     =   array(
                                        'id_role'                                       =>  $roles[$i],
                                        'id_submodule'                                  =>  $new_sub,
                                        'git_company'                                   =>  'T',
                                        'name_submodule'                                =>  $submodule['name_submodule'],
                                        'id_action'                                     =>  1,
                                        'name_action'                                   =>  'VIEW',
                                        'user_insert'                                   =>  $data['user_update']
                                                                                    );

                                    $this->_trabajandofet_model->insert_data($insert, 'git_permissions');
                                }
                            }
                        }
                    }
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;            
                $result['message']                                              =   'Problemas al editar el módulo.';
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
    public function users_permissions($param)
    {
        $this->db->select('git_users.id_user');
        $this->db->from('git_permissions');
        $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
        $this->db->join('git_users', 'git_users.id_role = git_permissions.id_role');
        $this->db->where('git_submodules.id_module',$param['module']);
        $this->db->where('git_users.flag_drop',0);
        $this->db->group_by('git_users.id_user');

        $total                                                                  =   $this->db->count_all_results();

        $result['data']                                                         =   $total;
        $result['message']                                                      =   FALSE;

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

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('git_modules', 'id_module', $param['id_module']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('git_modules_history', 'id_module', $param['id_module']);

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
        $result                                                                 =   array();

        $this->db->select('name_module, name_es_module, url_module, icon_module');
        $this->db->where('id_module !=', 13);                                   //TRABAJANDOFET
        $this->db->where('git_company =', 'T');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_module', $search);
            $this->db->or_like('name_es_module', $search);
            $this->db->or_like('url_module', $search);
            $this->db->or_like('icon_module', $search);
            $this->db->group_end();
        }

        $this->db->order_by('name_module', 'ASC');
        $query                                                                  =   $this->db->get('git_modules');
        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay módulos para exportar.';
        }

        return $result;
        exit();
    }
}