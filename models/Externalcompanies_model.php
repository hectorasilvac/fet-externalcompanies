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

        /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function exist_company($params)
    {
        $result                                                                 =   array();
        $nit_is_set                                                             =   strlen($params['nit_cv_ec']) > 0 && is_numeric($params['nit_cv_ec']);
        $email_is_set                                                           =   strlen($params['email_cv_ec']) > 0;
        $phone_is_set                                                           =   strlen($params['phone_cv_ec']) > 0 && is_numeric($params['phone_cv_ec']);

        $select_query                                                           =   '';
        $select_query                                                           .=   'name_cv_ec, ';
        $select_query                                                           .=   'type_cv_ec, '; 

        $nit_is_set   ?   $select_query .=  'nit_cv_ec, '     : NULL;
        $email_is_set ?   $select_query .=  'email_cv_ec, '   : NULL;
        $phone_is_set ?   $select_query .=  'phone_cv_ec, '   : NULL;

        $select_query                                                           =  rtrim(trim($select_query),',');
        $this->db->select($select_query);
        $this->db->where('flag_drop', 0);

        $this->db->group_start();
        $nit_is_set   ?   $this->db->where('nit_cv_ec', $params['nit_cv_ec'])         :   NULL;
        $email_is_set ?   $this->db->or_where('email_cv_ec', $params['email_cv_ec'])  :   NULL;
        $phone_is_set ?   $this->db->or_where('phone_cv_ec', $params['phone_cv_ec'])  :   NULL;
        $this->db->group_end();

        $query                                                                  =   $this->db->get('fet_cv_ec');

        if (count($query->result_array()) > 0)
        {
            $message                                                            =   '';
            foreach ($query->row_array() as $key => $value)
            {
                $value == trim($params['name_cv_ec'])   ?   $message = 'este nombre'    :   NULL;
                $value == trim($params['nit_cv_ec'])    ?   $message = 'este NIT'       :   NULL;
                $value == trim($params['email_cv_ec'])  ?   $message = 'este correo'    :   NULL;
                $value == trim($params['phone_cv_ec'])  ?   $message = 'este teléfono'  :   NULL;
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe un empresa con ' . $message;
        }
        else
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   FALSE;
        }

        return $result;
        exit();
    }
}