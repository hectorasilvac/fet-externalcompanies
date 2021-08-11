<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Biomechanicalform_model extends CI_Model
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
    * @param     array $params
    * @return    array $result
    **/
    public function participate()
    {
        $result                                                                 =   array();

        if (isset($this->session->userdata['id_worker'])) 
        {
            $this->db->select('B.id_biomechanical');
            $this->db->where('B.worker_insert', $this->session->userdata['id_worker']);

            $query                                                              =   $this->db->get('fet_biomechanical_form B');

            if ($query->row_array()) 
            {
                $result['data']                                                 =   false;
                $result['message']                                              =   'Si deseas realizar nuevamente esta encuesta, por favor contacta con nuestra área de Seguridad y Salud en el Trabajo.';
            }
            else
            {
                $result['data']                                                 =   true;
                $result['message']                                              =   false;
            }
        }
        else
        {
            $result['data']                                                     =   false;
            $result['message']                                                  =   'Solo los trabajadores pueden participar en la encuesta.';
        }

        return json_encode($result);
    }


    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function worker_name()
    {
        if (isset($this->session->userdata['id_worker'])) 
        {
            $this->db->select('fet_cv.name_cv');
            $this->db->join('fet_cv', 'fet_cv.id_cv = fet_workers.id_cv');
            $this->db->where('fet_workers.id_worker', $this->session->userdata['id_worker']);

            $query                                                              =   $this->db->get('fet_workers');

            if ($query->row_array()) 
            {
                $result                                                         =   $query->row_array();
                return ucwords(mb_strtolower($result['name_cv'], 'UTF-8'));
            }
            else
            {
                return false;
            }
        }
        else
        {
            $name_user                                                          =   explode(' ', $this->_trabajandofet_model->to_camel($this->session->userdata['user']));
            return $name_user[0];
        }
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
        $result                                                                 =   array();

        $this->form_validation->set_rules('axa_3', 'Área de Trabajo', 'required');

        if($this->form_validation->run())
        {

            if (isset($params['axa_8'])) 
            {
                $axa_8                                                          =   '';

                foreach ($params['axa_8'] as $key => $axa) 
                {
                    $axa_8                                                      .=  $axa . ',';
                }

                $params['axa_8']                                                =   $axa_8;
            }

            if (isset($params['axa_32'])) 
            {
                $axa_32                                                         =   '';

                foreach ($params['axa_32'] as $key => $axa) 
                {
                    $axa_32                                                     .=  $axa . ',';
                }

                $params['axa_32']                                               =   $axa_32;
            }

            if (isset($params['axa_38'])) 
            {
                $axa_38                                                         =   '';

                foreach ($params['axa_38'] as $key => $axa) 
                {
                    $axa_38                                                     .=  $axa . ',';
                }

                $params['axa_38']                                               =   $axa_38;
            }

            $answer                                                             =   $this->_trabajandofet_model->insert_data($params, 'fet_biomechanical_form');

            if ($answer)
            {
                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Gracias por tu participación.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Por favor completa todos los campos.';
        }

        return $result;
        exit();
    }
}