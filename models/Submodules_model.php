<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/
defined('BASEPATH') OR exit('No direct script access allowed');

class Submodules_model extends CI_Model
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

        $this->db->select('git_submodules.id_submodule');
        $this->db->from('git_submodules');
        $this->db->where('git_submodules.git_company =', 'T');
        $this->db->where('git_submodules.id_submodule != 53');

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('git_submodules.id_submodule');
            $this->db->from('git_submodules');
            $this->db->join('git_modules', 'git_modules.id_module = git_submodules.id_module');
            $this->db->where('git_submodules.git_company =', 'T');
            $this->db->where('git_submodules.id_submodule != 53');
            $this->db->group_start();
            $this->db->like('git_submodules.name_submodule', $search);
            $this->db->or_like('git_submodules.name_es_submodule', $search);
            $this->db->or_like('git_submodules.url_submodule', $search);
            $this->db->or_like('git_modules.name_es_module', $search);
            $this->db->group_end();

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
        $this->db->select('git_submodules.id_submodule, git_submodules.id_module, git_submodules.name_submodule, git_submodules.name_es_submodule, git_submodules.url_submodule, git_modules.name_es_module');
        $this->db->join('git_modules', 'git_modules.id_module = git_submodules.id_module');
        $this->db->where('git_submodules.git_company =', 'T');
        $this->db->where('git_submodules.id_submodule != 53');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('git_submodules.name_submodule', $search);
            $this->db->or_like('git_submodules.name_es_submodule', $search);
            $this->db->or_like('git_submodules.url_submodule', $search);
            $this->db->or_like('git_modules.name_es_module', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('git_submodules');

        $submodules                                                             =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($submodules as $key => $action)
            {
                $count++;
                $submodules[$key]['number']                                     =   $count;
            }
        }

        return $submodules;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function count_rows_actions($search)
    {
        $result                                                                 =   array();

        $this->db->where('git_company =', 'T');

        $query                                                                  =   $this->db->get('git_actions');
        $result['total']                                                        =   $query->num_rows();

        if (!empty($search))
        {
            $this->db->select('id_submodule');
            $this->db->where('git_company =', 'T');
            $this->db->group_start();
            $this->db->like('name_action', $search);
            $this->db->or_like('name_es_action', $search);
            $this->db->or_like('id_action', $search);
            $this->db->group_end();
            $this->db->from('git_actions');

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
    public function all_rows_actions($limit, $start, $search, $col, $dir, $id_submodule)
    {
        $this->db->select('CONCAT(git_actions.name_es_action, " (", git_actions.name_action, ")") AS name');
        $this->db->select('git_actions.name_action AS action');
        $this->db->select('git_actions.id_action AS id');
        $this->db->select('IF((SELECT COUNT(*) FROM git_actions_submodule WHERE `git_actions_submodule`.`id_submodule` = ' . $id_submodule . ' AND `git_actions_submodule`.`id_action` = `git_actions`.`id_action`) > 0,"1","0") AS state_action');
        $this->db->where('git_actions.git_company =', 'T');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('git_actions.name_action', $search);
            $this->db->or_like('git_actions.name_es_action', $search);
            $this->db->or_like('git_actions.id_action', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('git_actions');

        $actions                                                                =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($actions as $key => $action)
            {
                $count++;
                $actions[$key]['number']                                        =   $count;
            }
        }

        return $actions;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function sort_submodule($params)
    {
        $this->db->select('(MAX(git_submodules.sort_submodule) + 1) AS sort_submodule');
        $this->db->where('id_module =', $params['id_module']);

        $query                                                                  =   $this->db->get('git_submodules');

        $result                                                                 =   $query->row_array();

        if ($result['sort_submodule'] != NULL)
        {
            return $result['sort_submodule'];
        }
        else
        {
            return "1";
        }
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
        $this->form_validation->set_rules('name_submodule', 'Nombre', 'required');
        $this->form_validation->set_rules('name_es_submodule', 'Significado', 'required');
        $this->form_validation->set_rules('id_module', 'Modulo', 'required');
        $this->form_validation->set_rules('url_submodule', 'URL', 'required');

        if($this->form_validation->run())
        {
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['name_submodule']                                           =   mb_strtoupper($this->_trabajandofet_model->clean_text($this->_trabajandofet_model->no_accents($params['name_submodule'])));
            $params['name_es_submodule']                                        =   $this->_trabajandofet_model->to_camel($params['name_es_submodule']);

            $url_submodule                                                      =   explode('/', $params['url_submodule']);

            for ($i = 0; $i < count($url_submodule); $i++)
            {
                $url_submodule[$i]                                              =   mb_strtolower($this->_trabajandofet_model->accents($url_submodule[$i]));
            }

            $params['url_submodule']                                            =   implode('/', $url_submodule);

            $submodule['id_module']                                             =   $params['id_module'];
            $submodule['git_company']                                           =   'T';
            $submodule['name_submodule']                                        =   $params['name_submodule'];
            $submodule['name_es_submodule']                                     =   $params['name_es_submodule'];
            $submodule['url_submodule']                                         =   $params['url_submodule'];
            $submodule['sort_submodule']                                        =   $params['sort_submodule'];
            $submodule['user_insert']                                           =   $params['user_insert'];

            $this->db->trans_start();

            $new_sub                                                            =   $this->_trabajandofet_model->insert_data($submodule, 'git_submodules');

            if ($new_sub)
            {
                $action_submodule                                               =   array(
                                                                                    'id_action'         =>  16,
                                                                                    'git_company'       =>  'T',
                                                                                    'name_action'       =>  'VIEW',
                                                                                    'id_submodule'      =>  $new_sub,
                                                                                    'name_submodule'    =>  $submodule['name_submodule'],
                                                                                    'user_insert'       =>  $params['user_insert']
                                                                                );

                $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');
            }

            $this->db->trans_complete();

            $result                                                             =   array();

            if ($this->db->trans_status() === TRUE)
            {
                $data_history                                                   =   $submodule;
                $data_history['id_submodule']                                   =   $new_sub;
                $data_history['user_update']                                    =   $params['user_insert'];
                unset($data_history['git_company']);
                unset($data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'git_submodules_history');

                $result['data']                                                 =   TRUE;
                $result['id_submodule']                                         =   $new_sub;
                $result['name_submodule']                                       =   $submodule['name_submodule'];
                $result['message']                                              =   'Acción realizada con éxito! <br/>Ahora puede seleccionar <br/>las acciones del submodulo.';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al guardar el submodulo.';
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
    public function update_state_action($params)
    {
        $action                                                                 =   explode(",", $params['action']);
        $submodule                                                              =   explode(",", $params['submodule']);

        $this->db->select('COUNT(*) as actions');
        $this->db->from('git_actions_submodule');
        $this->db->where('git_company =', 'T');
        $this->db->where('git_actions_submodule.id_submodule =', $submodule[0]);
        $this->db->where('git_actions_submodule.id_action =', $action[0]);

        $query                                                                  =   $this->db->get();

        $actions                                                                =   $query->row_array();

        if (isset($actions['actions']) && intval($actions['actions']) > 0)
        {
            $drop_data                                                          =   array(
                                                                                        'id_submodule'          =>  $submodule[0],
                                                                                        'id_action'             =>  $action[0]
                                                                                    );

            $drop                                                               =   $this->_trabajandofet_model->drop_data($drop_data, 'git_actions_submodule');

            if ($drop)
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al eliminar la acción.';
            }
        }
        else
        {
            $params['user_insert']                                              =   $this->session->userdata['id_user'];

            $action_submodule                                                   =   array(
                                                                                        'id_action'         =>  $action[0],
                                                                                        'git_company'       =>  'T',
                                                                                        'name_action'       =>  $action[1],
                                                                                        'id_submodule'      =>  $submodule[0],
                                                                                        'name_submodule'    =>  $submodule[1],
                                                                                        'user_insert'       =>  $params['user_insert']
                                                                                    );

            $insert                                                             =   $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');

            if ($insert)
            {
                $this->db->select('COUNT(*) as actions');
                $this->db->from('git_actions_submodule');
                $this->db->where('git_company =', 'T');
                $this->db->where('git_actions_submodule.id_submodule =', $submodule[0]);
                $this->db->where('git_actions_submodule.id_action =', 16);

                $query                                                          =   $this->db->get();

                $actions                                                        =   $query->row_array();

                if (isset($actions['actions']) && intval($actions['actions']) == 0)
                {
                    $action_submodule                                           =   array(
                                                                                    'git_company'       =>  'T',
                                                                                    'id_action'         =>  16,
                                                                                    'name_action'       =>  'VIEW',
                                                                                    'id_submodule'      =>  $submodule[0],
                                                                                    'name_submodule'    =>  $submodule[1],
                                                                                    'user_insert'       =>  $params['user_insert']
                                                                                );

                    $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');
                }

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al agregar la acción.';
            }
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
    public function update_actions_all($params)
    {
        $submodule                                                              =   explode(",", $params['submodule']);
        $params['user_insert']                                                  =   $this->session->userdata['id_user'];

        $this->db->select('git_actions.id_action, git_actions.name_action');
        $this->db->from('git_actions');
        $this->db->where('git_company =', 'T');

        $query                                                                  =   $this->db->get();

        $actions                                                                =   $query->result_array();

        if ($params['flag'] == "1")
        {
            $this->db->trans_start();

            $drop_data                                                          =   array(
                                                                                        'id_submodule'          =>  $submodule[0]
                                                                                    );

            $drop                                                               =   $this->_trabajandofet_model->drop_data($drop_data, 'git_actions_submodule');

            if ($drop)
            {
                $action_submodule                                               =   array(
                                                                                    'id_action'         =>  16,
                                                                                    'git_company'       =>  'T',
                                                                                    'name_action'       =>  'VIEW',
                                                                                    'id_submodule'      =>  $submodule[0],
                                                                                    'name_submodule'    =>  $submodule[1],
                                                                                    'user_insert'       =>  $params['user_insert']
                                                                                );

                $action_add                                                     =   $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');

                if ($action_add)
                {
                    foreach ($actions as $action)
                    {
                        $action                                                 =   array(
                                                                                        'id_action'             =>  $action['id_action'],
                                                                                        'git_company'           =>  'T',
                                                                                        'name_action'           =>  $action['name_action'],
                                                                                        'id_submodule'          =>  $submodule[0],
                                                                                        'name_submodule'        =>  $submodule[1],
                                                                                        'user_insert'           =>  $params['user_insert']
                                                                                    );

                        $this->_trabajandofet_model->insert_data($action, 'git_actions_submodule');
                    }

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === TRUE)
                    {
                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Acción realizada con éxito!';
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al guardar las acciones.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Problemas al agregar la acción VER(VIEW).';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al eliminar las acciones existentes.';
            }
        }
        else
        {
            $drop_data                                                          =   array(
                                                                                        'id_submodule'          =>  $submodule[0]
                                                                                    );

            $drop                                                               =   $this->_trabajandofet_model->drop_data($drop_data, 'git_actions_submodule');

            if ($drop)
            {
                $action_submodule                                               =   array(
                                                                                    'id_action'         =>  16,
                                                                                    'git_company'       =>  'T',
                                                                                    'name_action'       =>  'VIEW',
                                                                                    'id_submodule'      =>  $submodule[0],
                                                                                    'name_submodule'    =>  $submodule[1],
                                                                                    'user_insert'       =>  $params['user_insert']
                                                                                );

                $action_add                                                     =   $this->_trabajandofet_model->insert_data($action_submodule, 'git_actions_submodule');

                if ($action_add)
                {
                    $result['data']                                             =   TRUE;
                    $result['message']                                          =   'Acción realizada con éxito!';
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Se han eliminado las acciones existentes, </br>pero no se ha agregado la acción VER(VIEW).';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al eliminar las acciones existentes.';
            }
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
    public function module_in_submodule($params)
    {
        $this->db->select('id_module');
        $this->db->where('git_company =', 'T');
        $this->db->where('id_submodule =', $params['id_submodule']);

        $query                                                                  =   $this->db->get('git_submodules');

        $result                                                                 =   $query->row_array();

        return $result['id_module'];
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
        if ($params['value'] != '' || $params['value'] != null)
        {
            $data                                                               =   array();

            switch ($params['name'])
            {
                case 'name_submodule':
                    $data['name_submodule']                                     =   mb_strtoupper($this->_trabajandofet_model->clean_text($this->_trabajandofet_model->no_accents($params['value'])));
                    break;

                case 'name_es_submodule':
                    $data['name_es_submodule']                                  =   ucfirst($this->_trabajandofet_model->accents($params['value']));
                    break;

                case 'url_submodule':
                    $url_submodule                                              =   explode('/', $params['value']);

                    for ($i = 0; $i < count($url_submodule); $i++)
                    {
                        $url_submodule[$i]                                      =   mb_strtolower($this->_trabajandofet_model->accents($url_submodule[$i]));
                    }

                    $data['url_submodule']                                      =   implode('/', $url_submodule);
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

            $this->_trabajandofet_model->update_data($data, 'id_submodule', 'git_submodules');

            $data_history                                                       =   $data;
            $data_history['id_submodule']                                       =   $data_history['id'];
            unset($data_history['id']);
            unset($data_history['git_company']);

            $data_history['id_module']                                          =   $this->module_in_submodule($data_history);

            $this->_trabajandofet_model->insert_data($data_history, 'git_submodules_history');

            $result                                                             =   array();

            if ($params['name'] == 'name_submodule')
            {
                $this->_trabajandofet_model->update_data($data, 'id_submodule', 'git_actions_submodule');
                unset($data['user_update']);
                unset($data['date_update']);
                $this->_trabajandofet_model->update_data($data, 'id_submodule', 'git_permissions');
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al editar el submodulo.';
            }
            else
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
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
    public function modules_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_module AS id, name_es_module AS text');
        $this->db->where('git_company =', 'T');
        $this->db->where('url_module = "#"');

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->group_start();
            $this->db->like('name_es_module', $params['q']);
            $this->db->or_like('name_module', $params['q']);
            $this->db->group_end();
        }

        $this->db->from('git_modules');
        $this->db->order_by('name_es_module', 'asc');
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
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function exist_submodule($params)
    {
        if ($params)
        {
            $result                                                             =   array();

            if (isset($params['name']) && $params['name'] == 'id_module')
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   FALSE;
            }
            else
            {
                if (isset($params['pk']))
                {
                    $this->db->select('id_submodule');
                    $this->db->where('git_company =', 'T');
                    $this->db->group_start();
                    $this->db->where($params['name'], trim($params['value']));
                    $this->db->where('id_submodule !=', $params['pk']);
                    $this->db->group_end();
                }
                else
                {
                    $this->db->select('id_submodule');
                    $this->db->where('git_company =', 'T');
                    $this->db->group_start();
                    $this->db->where('name_submodule', trim($params['name_submodule']));
                    $this->db->or_where('name_es_submodule', trim($params['name_es_submodule']));
                    $this->db->or_where('url_submodule', trim($params['url_submodule']));
                    $this->db->group_end();
                }

                $query                                                          =   $this->db->get('git_submodules');

                if (count($query->result_array()) > 0)
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Ya existe un submodulo con estos valores.';
                }
                else
                {
                    $result['data']                                             =   TRUE;
                    $result['message']                                          =   FALSE;
                }
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ruta indefinida.';
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

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('git_submodules', 'id_submodule', $param['id_submodule']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('git_submodules_history', 'id_submodule', $param['id_submodule']);

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
        $this->db->select('name_submodule, name_es_submodule, url_submodule, name_es_module');
        $this->db->where('git_submodules.id_submodule != 53');
        $this->db->where('git_submodules.git_company =', 'T');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_submodule', $search);
            $this->db->or_like('name_es_submodule', $search);
            $this->db->or_like('url_submodule', $search);
            $this->db->or_like('name_es_module', $search);
            $this->db->group_end();
        }

        $this->db->join('git_modules', 'git_modules.id_module = git_submodules.id_module');
        $this->db->order_by('name_submodule', 'ASC');

        $query                                                                  =   $this->db->get('git_submodules');

        $result                                                                 =   array();
        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay submódulos para exportar.';
        }

        return $result;
        exit();
    }
}