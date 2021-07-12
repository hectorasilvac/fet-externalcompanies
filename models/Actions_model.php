<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/
defined('BASEPATH') OR exit('No direct script access allowed');

class Actions_model extends CI_Model
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
        $this->db->from('git_actions');

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('id_action');
            $this->db->where('git_company =', 'T');
            $this->db->group_start();
            $this->db->like('name_action', $search);
            $this->db->or_like('name_es_action', $search);
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
    public function all_rows($limit, $start, $search, $col, $dir)
    {
        $this->db->select('id_action, name_action, name_es_action');

        $this->db->where('git_company =', 'T');

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('name_action', $search);
            $this->db->or_like('name_es_action', $search);
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
    * @param     array $params
    * @return    array $result
    **/
    public function exist_action($params)
    {
        $result                                                                 =   array();

        $this->db->select('name_action, name_es_action');
        $this->db->where('git_company =', 'T');
        $this->db->where('name_action', trim($params['name_action']));
        $this->db->or_where('name_es_action', trim($params['name_es_action']));

        $query                                                                  =   $this->db->get('git_actions');

        if (count($query->result_array()) > 0)
        {
            $message                                                            =   ' alguno de estos datos';

            foreach ($query->row_array() as $key => $value)
            {
                switch ($key)
                {
                    case 'name_action':
                        if ($value == mb_strtoupper(trim($params['name_action'])))
                        {
                            $message                                            =   ' este nombre.';
                        }
                        break;

                    case 'name_es_action':
                        if ( $value == ucfirst(mb_strtolower(trim($params['name_es_action']))) )
                        {
                            $message                                            =   ' este significado.';
                        }
                        break;
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe una acción con '.$message;
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

        $this->form_validation->set_rules('name_action', 'Nombre', 'required');
        $this->form_validation->set_rules('name_es_action', 'Significado', 'required');

        if($this->form_validation->run())
        {
            $params['git_company']                                              =   'T';
            $params['user_insert']                                              =   $this->session->userdata['id_user'];
            $params['name_action']                                              =   mb_strtoupper($this->_trabajandofet_model->clean_text($this->_trabajandofet_model->no_accents($params['name_action'])));
            $params['name_es_action']                                           =   ucfirst($this->_trabajandofet_model->accents($params['name_es_action']));

            $answer                                                             =   $this->_trabajandofet_model->insert_data($params, 'git_actions');

            if ($answer)
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al guardar la acción.';
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
    *@author    Innovación y Tecnología
    *@copyright 2021 Fábrica de Desarrollo
    *@since     v2.0.1
    *@param     array $param
    *@return    array $result
    **/
    public function trace_register($param)
    {
        $result                                                                 =   array();
    
        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('git_actions', 'id_action', $param['id_action']);
        $result['data_global']                                                  =   array();

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
        $this->db->select('name_action, name_es_action');

        if (!empty($search))
        {
            $this->db->like('name_action', $search);
            $this->db->or_like('name_es_action', $search);
        }

        $this->db->where('git_company =', 'T');
        $this->db->order_by('name_action', 'ASC');

        $query                                                                  =   $this->db->get('git_actions');

        $result                                                                 =   array();
        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay acciones para exportar.';
        }

        return $result;
        exit();
    }
}