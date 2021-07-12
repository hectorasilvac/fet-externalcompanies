<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Externalcompanies_model extends CI_Model
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

        if ( ! is_null($parent_table))
        { 
            $this->db->where($parent_table['id'], $params['parentId']);
        }

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like("{$table['text']}", $params['q']);
        }

        $this->db->order_by("{$table['text']}", 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get("{$table['name']}");

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
}