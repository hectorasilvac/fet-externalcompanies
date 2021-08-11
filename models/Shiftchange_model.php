<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Shiftchange_model extends CI_Model
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
    public function count_rows($search, $state, $coordinator)
    {
        $result                                                                 =   array();

        $this->db->select('id_shiftchange');
        $this->db->from('fet_shiftchange');
        $this->db->where('flag_drop', 0);

        if (isset($this->session->userdata['id_worker']))
        {
            $this->db->group_start();
            $this->db->where('id_worker_applicant', $this->session->userdata['id_worker']);
            $this->db->or_where('id_worker_replacement', $this->session->userdata['id_worker']);
            $this->db->group_end();
        }
        else if (isset($this->session->userdata['id_user']))
        {
            if (!isset($this->session->userdata['flags']['flag_view_shiftchange']))
            {
                $this->db->where('id_coordinator', $this->session->userdata['id_user']);
                $this->db->where('vob_shiftchange', $state);
            }
            else
            {
                $this->db->where('vob_shiftchange', $state);

                if (!empty($coordinator))
                {
                    $this->db->where('id_coordinator', $coordinator);
                }
            }
        }

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('fsc.id_shiftchange');
            $this->db->join('fet_workers fw1', 'fsc.id_worker_applicant = fw1.id_worker', 'left');
            $this->db->join('fet_cv fc1', 'fw1.id_cv = fc1.id_cv', 'left');
            $this->db->join('fet_workers fw2', 'fsc.id_worker_replacement = fw2.id_worker', 'left');
            $this->db->join('fet_cv fc2', 'fw2.id_cv = fc2.id_cv', 'left');
            $this->db->join('git_users gu1', 'fsc.id_coordinator = gu1.id_aspirant', 'left');
            $this->db->where('fsc.flag_drop', 0);
            $this->db->from('fet_shiftchange fsc');
            $this->db->group_start();

            if (isset($this->session->userdata['id_worker']))
            {
                $this->db->like('CONCAT(fc1.name_cv," ", fc1.first_lcv, " ", fc1.second_lcv)', $search);
                $this->db->or_like('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv)', $search);
                $this->db->group_start();
                $this->db->where('fsc.id_worker_applicant', $this->session->userdata['id_worker']);
                $this->db->or_where('fsc.id_worker_replacement', $this->session->userdata['id_worker']);
                $this->db->group_end();
            }
            else if (isset($this->session->userdata['id_user']))
            {
                $this->db->like('CONCAT(fc1.name_cv," ", fc1.first_lcv, " ", fc1.second_lcv)', $search);
                $this->db->or_like('fc1.number_dcv', $search);
                $this->db->or_like('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv)', $search);
                $this->db->or_like('fc2.number_dcv', $search);
                $this->db->or_like('gu1.name_user', $search);
                $this->db->or_like('gu1.lastname_user', $search);
                $this->db->where('fsc.vob_shiftchange', $state);

                if (!isset($this->session->userdata['flags']['flag_view_shiftchange']))
                {
                    $this->db->where('fsc.id_coordinator', $this->session->userdata['id_user']);
                }
                else
                {
                    if (!empty($coordinator))
                    {
                        $this->db->where('fsc.id_coordinator', $coordinator);
                    }
                }
            }
            
            $this->db->or_like('fsc.date_shiftchange', $search);
            $this->db->or_like('fsc.date_return_shiftchange', $search);
            $this->db->or_like('fsc.type_shiftchange', $search);
            $this->db->or_like('fsc.type_return_shiftchange', $search);
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $limit, int $start, string $search, int $col, string $dir
    * @return    array $query->result_array()
    **/
    public function all_rows($limit, $start, $search, $col, $dir, $state, $coordinator)
    {
        $this->db->select('fsc.id_shiftchange, fsc.date_shiftchange, fsc.date_return_shiftchange, fsc.vob_shiftchange');
        $this->db->select('id_coordinator, CONCAT(gu1.name_user, " ", gu1.lastname_user) AS coordinator');
        $this->db->select('IF(fsc.type_shiftchange = "C", "COMPLETO", IF(fsc.type_shiftchange = "N", "NOCHE", "N/A")) AS type_shiftchange');
        $this->db->select('IF(fsc.type_return_shiftchange = "C", "COMPLETO", IF(fsc.type_return_shiftchange = "N", "NOCHE", "N/A")) AS type_return_shiftchange');
        $this->db->join('fet_workers fw1', 'fsc.id_worker_applicant = fw1.id_worker', 'left');
        $this->db->join('fet_cv fc1', 'fw1.id_cv = fc1.id_cv', 'left');
        $this->db->join('fet_workers fw2', 'fsc.id_worker_replacement = fw2.id_worker', 'left');
        $this->db->join('fet_cv fc2', 'fw2.id_cv = fc2.id_cv', 'left');
        $this->db->join('git_users gu1', 'fsc.id_coordinator = gu1.id_user', 'left');

        if (isset($this->session->userdata['id_worker']))
        {
            $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv) AS name_worker_applicant');
            $this->db->select('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv) AS name_worker_replacement');
            $this->db->group_start();
            $this->db->where('fsc.id_worker_applicant', $this->session->userdata['id_worker']);
            $this->db->or_where('fsc.id_worker_replacement', $this->session->userdata['id_worker']);
            $this->db->group_end();
        }
        else if (isset($this->session->userdata['id_user']))
        {
            $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv, " (", fc1.number_dcv, ")") AS name_worker_applicant');
            $this->db->select('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv, " (", fc2.number_dcv, ")") AS name_worker_replacement');

            if (!isset($this->session->userdata['flags']['flag_view_shiftchange']))
            {
                $this->db->where('fsc.id_coordinator', $this->session->userdata['id_user']);
                $this->db->where('fsc.vob_shiftchange', $state);
            }
            else
            {
                $this->db->where('fsc.vob_shiftchange', $state);

                if (!empty($coordinator))
                {
                    $this->db->where('fsc.id_coordinator', $coordinator);
                }
            }
        }

        $this->db->where('fsc.flag_drop', 0);

        if (!empty($search))
        {
            $this->db->group_start();

            if (isset($this->session->userdata['id_worker']))
            {
                $this->db->like('CONCAT(fc1.name_cv," ", fc1.first_lcv, " ", fc1.second_lcv)', $search);
                $this->db->or_like('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv)', $search);
            }
            else if (isset($this->session->userdata['id_user']))
            {
                $this->db->like('CONCAT(fc1.name_cv," ", fc1.first_lcv, " ", fc1.second_lcv)', $search);
                $this->db->or_like('fc1.number_dcv', $search);
                $this->db->or_like('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv)', $search);
                $this->db->or_like('fc2.number_dcv', $search);
                $this->db->or_like('gu1.name_user', $search);
                $this->db->or_like('gu1.lastname_user', $search);
            }

            $this->db->or_like('fsc.date_shiftchange', $search);
            $this->db->or_like('fsc.date_return_shiftchange', $search);
            $this->db->or_like('fsc.type_shiftchange', $search);
            $this->db->or_like('fsc.type_return_shiftchange', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get('fet_shiftchange fsc');

        $workers                                                                =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($workers as $key => $action)
            {
                $count++;
                $workers[$key]['number']                                        =   $count;
            }
        }

        return $workers;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function workers_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        if (isset($this->session->userdata['id_worker']))
        {
            $this->db->select('fet_workers.id_worker AS id, CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv) AS text');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->where('fet_workers.id_worker != ', $this->session->userdata['id_worker']);
            $this->db->where('fet_workers.id_project', $this->session->userdata['id_project']);
            $this->db->where('fet_workers.flag_drop', 0);
        }
        else if (isset($this->session->userdata['id_user']))
        {
            $this->db->select('fet_workers.id_worker AS id, CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv) AS text');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');

            if (isset($this->session->userdata['flags']['flag_view_shiftchange']))
            {
                $this->db->where('fet_workers.id_project', 5);
            }
            
            $this->db->where('fet_workers.flag_drop', 0);
        }

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->group_start();
            $this->db->like('CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv)', $params['q']);
            $this->db->group_end();
        }

        $this->db->order_by('fet_cv.name_cv', 'ASC');
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function coordinators_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_user AS id, CONCAT(name_user, " ", lastname_user) AS text');
        $this->db->where('id_role', 24);
        $this->db->where('git_company != ', 'G');
        $this->db->where('flag_drop', 0);
        $this->db->where('flag_display', 1);

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->group_start();
            $this->db->like('name_user', $params['q']);
            $this->db->or_like('lastname_user', $params['q']);
            $this->db->group_end();
        }

        $this->db->order_by('name_user', 'ASC');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_users');

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
    public function exist_shiftchange($params)
    {
        $result                                                                 =   array();

        $this->db->select('value_parameter');
        $this->db->where('name_parameter', 'CAMBIOS DE TURNO');
        $this->db->where('flag_drop', 0);
        $this->db->where('git_company != ', 'G');

        $query_parameter                                                        =   $this->db->get('git_parameters');

        $parameter                                                              =   $query_parameter->row_array();

        if (!isset($parameter['value_parameter']))
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Se debe definir el parámetro (cambios de turno) para poder agregar registros.';
        }
        else
        {
            $this->db->select('id_shiftchange');
            $this->db->where('id_worker_applicant', $params['id_worker_applicant']);
            $this->db->where('MONTH(date_worker_insert)', date('n'));
            $this->db->where('vob_shiftchange', 1);
            $this->db->where('flag_drop', 0);

            $query                                                              =   $this->db->get('fet_shiftchange');

            if (count($query->result_array()) >= intval($parameter['value_parameter']))
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Lo sentimos, ya completaste los turnos aprobados, no puedes cambiar más turnos por este mes.';
            }
            else
            {
                $result                                                         =   array();

                $this->db->select('id_shiftchange');
                $this->db->where('flag_drop', 0);
                $this->db->where('vob_shiftchange != 2');
                $this->db->group_start();
                $this->db->where('id_worker_applicant', $params['id_worker_applicant']);
                $this->db->where('date_shiftchange', $params['date_shiftchange']);
                $this->db->where('id_worker_replacement', $params['id_worker_replacement']);
                $this->db->where('date_return_shiftchange', $params['date_return_shiftchange']);
                $this->db->group_end();

                $query                                                          =   $this->db->get('fet_shiftchange');

                if (count($query->result_array()) > 0)
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Ya existe un registro creado anteriormente con las fechas indicadas.';
                }
                else
                {
                    $result                                                     =   array();

                    $this->db->select('id_shiftchange');
                    $this->db->where('id_worker_applicant', $params['id_worker_applicant']);
                    $this->db->where('date_shiftchange', $params['date_shiftchange']);
                    $this->db->where('flag_drop', 0);
                    $this->db->where('vob_shiftchange != 2');

                    $query                                                      =   $this->db->get('fet_shiftchange');

                    if (count($query->result_array()) > 0)
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Ya existe un registro creado anteriormente con la fecha de cambio indicada.';
                    }
                    else
                    {
                        $result                                                 =   array();

                        $this->db->select('id_shiftchange');
                        $this->db->where('id_worker_applicant', $params['id_worker_applicant']);
                        $this->db->where('date_return_shiftchange', $params['date_return_shiftchange']);
                        $this->db->where('flag_drop', 0);
                        $this->db->where('vob_shiftchange != 2');

                        $query                                                  =   $this->db->get('fet_shiftchange');

                        if (count($query->result_array()) > 0)
                        {
                            $result['data']                                     =   FALSE;
                            $result['message']                                  =   'Ya existe un registro creado anteriormente con la fecha de reemplazo indicada.';
                        }
                        else
                        {
                            $result                                             =   array();

                            $this->db->select('id_shiftchange');
                            $this->db->where('id_worker_replacement', $params['id_worker_replacement']);
                            $this->db->where('date_shiftchange', $params['date_shiftchange']);
                            $this->db->where('flag_drop', 0);
                            $this->db->where('vob_shiftchange != 2');

                            $query                                              =   $this->db->get('fet_shiftchange');

                            if (count($query->result_array()) > 0)
                            {
                                $result['data']                                 =   FALSE;
                                $result['message']                              =   'El remplazo ya tiene un turno programado en la fecha de cambio indicada.';
                            }
                            else
                            {
                                $result                                         =   array();

                                $this->db->select('id_shiftchange');
                                $this->db->where('id_worker_replacement', $params['id_worker_replacement']);
                                $this->db->where('date_return_shiftchange', $params['date_return_shiftchange']);
                                $this->db->where('flag_drop', 0);
                                $this->db->where('vob_shiftchange != 2');

                                $query                                          =   $this->db->get('fet_shiftchange');

                                if (count($query->result_array()) > 0)
                                {
                                    $result['data']                             =   FALSE;
                                    $result['message']                          =   'El remplazo ya tiene un turno programado en la fecha de reemplazo indicada.';
                                }
                                else
                                {
                                    $result['data']                             =   TRUE;
                                    $result['message']                          =   FALSE;
                                }
                            }
                        }
                    }
                }
            }
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
    public function email_worker_flag($param)
    {
        $this->db->select('fet_cv.email_ccv');
        $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv', 'left');
        $this->db->where('fet_workers.id_worker', $param);

        $query                                                                  =   $this->db->get('fet_workers');

        $worker                                                                 =   $query->result_array();

        $result                                                                 =   FALSE;

        if (count($worker) > 0)
        {
            if ($worker[0]['email_ccv'] == 'gestion@trabajandofet.co')
            {
                $result                                                         =   TRUE;
            }
            else
            {
                $result                                                         =   FALSE;
            }
        }
        else
        {
            $result                                                             =   FALSE;
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
    public function worker_select($params)
    {
        if (isset($params['id_worker']))
        {
            $this->db->select('CONCAT(fet_cv.name_cv, " ", fet_cv.first_lcv, " ", fet_cv.second_lcv) AS name_worker, fet_cv.email_ccv');
            $this->db->join('fet_cv', 'fet_workers.id_cv = fet_cv.id_cv');
            $this->db->where('fet_workers.flag_drop', 0);
            $this->db->where('fet_workers.id_worker', $params['id_worker']);

            $query                                                              =   $this->db->get('fet_workers');

            $result                                                             =   $query->row_array();

            return $result;
            exit();
        }
        else
        {
            return FALSE;
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function user_select($params)
    {
        if (isset($params['id_user']))
        {
            $this->db->select('CONCAT(name_user, " ", lastname_user) AS name_user, email_user');
            $this->db->where('flag_drop', 0);
            $this->db->where('id_user', $params['id_user']);

            $query                                                              =   $this->db->get('git_users');

            $result                                                             =   $query->row_array();

            return $result;
            exit();
        }
        else
        {
            return FALSE;
            exit();
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

        $this->form_validation->set_rules('id_worker_applicant', 'Afiliado Solicitante', 'required');
        $this->form_validation->set_rules('id_worker_replacement', 'Afiliado de Reemplazo', 'required');
        $this->form_validation->set_rules('flag_signature_applicant', 'Firma del Solicitante', 'required');
        $this->form_validation->set_rules('date_shiftchange', 'Fecha de Cambio', 'required');
        $this->form_validation->set_rules('type_shiftchange', 'Tipo de Turno de Cambio', 'required');
        $this->form_validation->set_rules('date_return_shiftchange', 'Fecha de Reposición', 'required');
        $this->form_validation->set_rules('type_return_shiftchange', 'Tipo de Turno de Reposición', 'required');
        $this->form_validation->set_rules('id_coordinator', 'Coordinador de Enlace', 'required');

        if($this->form_validation->run())
        {
            $cv_applicant                                                       =   $this->_trabajandofet_model->select_single_data('id_cv', TRUE, 'id_worker', $params['id_worker_applicant'], 'fet_workers');

            $cv_replacement                                                     =   $this->_trabajandofet_model->select_single_data('id_cv', TRUE, 'id_worker', $params['id_worker_replacement'], 'fet_workers');

            $params['date_shiftchange']                                         =   date('Y-m-d', strtotime($params['date_shiftchange']));
            $params['date_return_shiftchange']                                  =   date('Y-m-d', strtotime($params['date_return_shiftchange']));

            if (isset($cv_applicant) && isset($cv_replacement))
            {
                $this->db->trans_start();

                $data['id']                                                     =   $cv_applicant['id_cv'];
                $data['email_ccv']                                              =   mb_strtolower($params['email_worker_applicant']);
                $data['date_update']                                            =   date('Y-m-d H:i:s');

                $this->_trabajandofet_model->update_data($data, 'id_cv', 'fet_cv');

                $data2['id']                                                    =   $cv_replacement['id_cv'];
                $data2['email_ccv']                                             =   mb_strtolower($params['email_worker_replacement']);
                $data2['date_update']                                           =   date('Y-m-d H:i:s');

                $this->_trabajandofet_model->update_data($data2, 'id_cv', 'fet_cv');

                $email_worker_replacement                                       =   $params['email_worker_replacement'];

                unset($params['email_worker_applicant']);
                unset($params['email_worker_replacement']);

                if (isset($this->session->userdata['id_user']))
                {
                    $params['user_insert']                                      =   $this->session->userdata['id_user'];
                    $params['date_insert']                                      =   date('Y-m-d H:i:s');
                }

                if (isset($this->session->userdata['id_worker']))
                {
                    $params['worker_insert']                                    =   $this->session->userdata['id_worker'];
                    $params['date_worker_insert']                               =   date('Y-m-d H:i:s');
                }

                $answer                                                         =   $this->_trabajandofet_model->insert_data($params, 'fet_shiftchange');

                if ($answer)
                {
                    $data_history                                               =   $params;
                    $data_history['id_shiftchange']                             =   $answer;

                    if (isset($this->session->userdata['id_user']))
                    {
                        $data_history['user_update']                            =   $data_history['user_insert'];
                        $data_history['date_update']                            =   date('Y-m-d H:i:s');

                        unset($data_history['user_insert']);
                        unset($data_history['date_insert']);
                    }

                    if (isset($this->session->userdata['id_worker']))
                    {
                        $data_history['worker_update']                          =   $data_history['worker_insert'];
                        $data_history['date_worker_update']                     =   date('Y-m-d H:i:s');

                        unset($data_history['worker_insert']);
                        unset($data_history['date_worker_insert']);
                    }

                    $this->_trabajandofet_model->insert_data($data_history, 'fet_shiftchange_history');

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === TRUE)
                    {
                        $data_email['id_worker']                                =   $params['id_worker_replacement'];

                        $worker_replacement                                     =   $this->worker_select($data_email);

                        $data_email['id_worker']                                =   $params['id_worker_applicant'];

                        $worker_applicant                                       =   $this->worker_select($data_email);

                        $data_email['id_user']                                  =   $params['id_coordinator'];

                        $body                                                   =   '<p style="text-align: justify;">Hola ' . $this->_trabajandofet_model->to_camel($worker_replacement['name_worker']) . ', la plataforma'
                                                                                .   ' de Apoyo a los Procesos Misionales TRABAJANDOFET necesita una confirmación por tu parte.</p>'
                                                                                .   '<p style="text-align: justify;">A continuación te aparece un boton de ingreso para que valides un cambio de turno solicitado por '
                                                                                .   $this->_trabajandofet_model->to_camel($worker_applicant['name_worker']) . ' el día de hoy, si realizaste este cambio'
                                                                                .   ' en mutuo acuerdo favor ingresar y aprobar el cambio de turno por tu parte.</p>';

                        $content                                                =   array(
                                                                                        'of'                    =>  'Trabajandofet',
                                                                                        'title'                 =>  'Aceptar Cambio de Turno',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  APPLICATION . 'shiftchanged/ebd082b0f8' . $answer
                                                                                    );

                        $send                                                   =   $this->_trabajandofet_model->send_mail($email_worker_replacement, '☛ Notificación sobre Cambio de Turno', $content);

                        if ($send)
                        {
                            $result['data']                                     =   TRUE;
                            $result['message']                                  =   'Ha sido creado tu cambio de turno y se ha enviado una notificación al correo de tu reemplazo ' . $email_worker_replacement . '.';
                        }
                        else
                        {
                            $result['data']                                     =   FALSE;
                            $result['message']                                  =   'Ha sido creado tu cambio de turno!';

                            $body                                               =   '<p style="text-align: justify;">Hola, la persona ' . $this->_trabajandofet_model->to_camel($worker_applicant['name_worker']) . ' a solicitado'
                                                                                .   ' un cambio de turno por la plataforma y no ha podido ser enviada la notificación al correo del reemplazo <b>' . $email_worker_replacement . '</b>'
                                                                                .   ' de la persona ' . $this->_trabajandofet_model->to_camel($worker_replacement['name_worker']) . ' el día de hoy.</p>'
                                                                                .   '<p style="text-align: justify;">La URL a ser dada al trabajador de reemplazo para su aprobación es ' . APPLICATION . 'shiftchanged/ebd082b0f8' . $answer . '</p>';

                            $content                                            =   array(
                                                                                        'of'                    =>  'Trabajandofet',
                                                                                        'title'                 =>  'Error al Enviar la Notificación',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '0'
                                                                                    );

                            $this->_trabajandofet_model->send_mail('sandra.amaya@grupoaw.co', '✋ No fue enviada la Notificación', $content);
                        }
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'No fue posible guardar el histórico del control.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Problemas al guardar tu cambio de turno.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al actualizar los datos ingresados.';
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

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $param
    * @return    array $result
    **/
    public function detail($param)
    {
        $result                                                                 =   array();

        $this->db->select('fsc.id_shiftchange, fsc.date_shiftchange, fsc.date_return_shiftchange, IF(fsc.flag_signature_applicant IS NULL, fsc.flag_signature_applicant, fc1.signature_cv) AS signature_applicant, IF(fsc.flag_signature_replacement IS NULL, fsc.flag_signature_replacement, fc2.signature_cv) AS signature_replacement');
        $this->db->select('IF(fsc.type_shiftchange = "C", "COMPLETO", IF(fsc.type_shiftchange = "N", "NOCHE", "N/A")) AS type_shiftchange');
        $this->db->select('IF(fsc.type_return_shiftchange = "C", "COMPLETO", IF(fsc.type_return_shiftchange = "N", "NOCHE", "N/A")) AS type_return_shiftchange');

        if (isset($this->session->userdata['id_worker']))
        {
            $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv) AS name_worker_applicant');
            $this->db->select('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv) AS name_worker_replacement');
        }
        else if (isset($this->session->userdata['id_user']))
        {
            $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv, " (", fc1.number_dcv, ")") AS name_worker_applicant');
            $this->db->select('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv, " (", fc2.number_dcv, ")") AS name_worker_replacement');
        }

        $this->db->join('fet_workers fw1', 'fsc.id_worker_applicant = fw1.id_worker');
        $this->db->join('fet_cv fc1', 'fw1.id_cv = fc1.id_cv');
        $this->db->join('fet_workers fw2', 'fsc.id_worker_replacement = fw2.id_worker');
        $this->db->join('fet_cv fc2', 'fw2.id_cv = fc2.id_cv');
        $this->db->where('fsc.flag_drop', 0);
        $this->db->where('fsc.id_shiftchange', $param['id_shiftchange']);

        $query                                                                  =   $this->db->get('fet_shiftchange fsc');

        if (count($query->result_array()) > 0)
        {
            $result['data']                                                     =   $query->row_array();
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay información para visualizar en el cambio de turno.';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     
    * @return    $result
    **/
    public function file_signature($file)
    {
        if ($file != '')
        {
            $signature                                                          =   'application/files/cv/signature/' . $file;

            if (file_exists($signature)) 
            {
                $fp                                                             =   fopen($signature, 'rb');

                header("Content-Type: image/jpeg");
                header("Content-Length: " . filesize($signature));

                fpassthru($fp);
                exit();
            }
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function count_worker($params)
    {
        if (isset($params['id_worker_applicant']) && isset($params['id_worker_replacement']))
        {
            $this->db->select('COUNT(id_shiftchange) AS count_shiftchange');
            $this->db->where('flag_drop', 0);
            $this->db->where('vob_shiftchange', '1');
            $this->db->where('MONTH(date_worker_insert)', date('n'));
            $this->db->where('id_worker_applicant', $params['id_worker_applicant']);

            $query1                                                             =   $this->db->get('fet_shiftchange');

            $result1                                                            =   $query1->row_array();

            $this->db->select('COUNT(id_shiftchange) AS count_shiftchange');
            $this->db->where('flag_drop', 0);
            $this->db->where('vob_shiftchange', '1');
            $this->db->where('MONTH(date_worker_insert)', date('n'));
            $this->db->where('id_worker_replacement', $params['id_worker_replacement']);

            $query2                                                             =   $this->db->get('fet_shiftchange');

            $result2                                                            =   $query2->row_array();

            $result['count_applicant']                                          =   $result1['count_shiftchange'];
            $result['count_replacement']                                        =   $result2['count_shiftchange'];

            return $result;
            exit();
        }
        else
        {
            return FALSE;
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $param
    * @return    array $result
    **/
    public function mail_replacement($params)
    {
        $params['id']                                                           =   $params['id_shiftchange'];

        unset($params['id_shiftchange']);

        $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv) AS replacement, fc1.email_ccv AS email_replacement, CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv) AS applicant');
        $this->db->join('fet_workers fw1', 'fsc.id_worker_replacement = fw1.id_worker');
        $this->db->join('fet_cv fc1', 'fw1.id_cv = fc1.id_cv');
        $this->db->join('fet_workers fw2', 'fsc.id_worker_applicant = fw2.id_worker');
        $this->db->join('fet_cv fc2', 'fw2.id_cv = fc2.id_cv');
        $this->db->where('fsc.flag_drop', 0);
        $this->db->where('fsc.vob_shiftchange', 0);
        $this->db->where('fsc.flag_signature_replacement IS NULL');
        $this->db->where('fsc.id_shiftchange', $params['id']);

        $query                                                                  =   $this->db->get('fet_shiftchange fsc');

        $shiftchange                                                            =   $query->row_array();

        if (is_null($shiftchange))
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'El afiliado de reemplazo ya ha aprobado con su firma el cambio de turno, no es necesario enviar la notificación.';

            return $result;
            exit();
        }

        $replacement                                                            =   $shiftchange['replacement'];
        $email_replacement                                                      =   $shiftchange['email_replacement'];
        $applicant                                                              =   $shiftchange['applicant'];

        $body                                                                   =   '<p style="text-align: justify;">Hola ' . $this->_trabajandofet_model->to_camel($replacement) . ', la plataforma'
                                                                                .   ' de Apoyo a los Procesos Misionales TRABAJANDOFET necesita una confirmación por tu parte.</p>'
                                                                                .   '<p style="text-align: justify;">A continuación te aparece un boton de ingreso para que valides un cambio de turno solicitado por '
                                                                                .   $this->_trabajandofet_model->to_camel($applicant) . ' el día de hoy, si realizaste este cambio'
                                                                                .   ' en mutuo acuerdo favor ingresar y aprobar el cambio de turno por tu parte.</p>';

        $content                                                                =   array(
                                                                                        'of'                    =>  'Trabajandofet',
                                                                                        'title'                 =>  'Aceptar Cambio de Turno',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  APPLICATION . 'shiftchanged/ebd082b0f8' . $params['id']
                                                                                    );

        $send                                                                   =   $this->_trabajandofet_model->send_mail($email_replacement, '☛ Notificación sobre Cambio de Turno', $content);

        if ($send)
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Se ha enviado nuevamente una notificación al correo del reemplazo ' . $email_replacement . '.';
        }
        else
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'No fue posible enviar la notificación al correo del reemplazo ' . $email_replacement . '.';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $param
    * @return    array $result
    **/
    public function assign($params)
    {
        $params['id']                                                           =   $params['id_shiftchange'];

        $this->db->select('flag_signature_replacement, id_worker_replacement, id_worker_applicant, id_coordinator');
        $this->db->where('flag_drop', 0);
        $this->db->where('id_shiftchange', $params['id']);

        $query                                                                  =   $this->db->get('fet_shiftchange');

        $shiftchange                                                            =   $query->row_array();

        $count_worker                                                           =   $this->count_worker($shiftchange);

        if (($count_worker['count_applicant'] < 5) && ($count_worker['count_replacement'] < 5))
        {
            if ((count($shiftchange) > 0) && isset($shiftchange['flag_signature_replacement']))
            {
                if (isset($this->session->userdata['id_worker']))
                {
                    $params['worker_update']                                    =   $this->session->userdata['id_worker'];
                    $params['date_worker_update']                               =   date('Y-m-d H:i:s');
                }
                else if (isset($this->session->userdata['id_user']))
                {
                    $params['user_update']                                      =   $this->session->userdata['id_user'];
                    $params['date_update']                                      =   date('Y-m-d H:i:s');
                }

                unset($params['id_shiftchange']);

                $assign                                                         =   $this->_trabajandofet_model->update_data($params, 'id_shiftchange', 'fet_shiftchange');

                $result                                                         =   array();
                
                if ($assign)
                {
                    $data_history                                               =   $params;
                    $data_history['id_shiftchange']                             =   $data_history['id'];

                    unset($data_history['id']);

                    $this->_trabajandofet_model->insert_data($data_history, 'fet_shiftchange_history');

                    $data_email['id_worker']                                    =   $shiftchange['id_worker_replacement'];

                    $worker_replacement                                         =   $this->worker_select($data_email);

                    $data_email['id_worker']                                    =   $shiftchange['id_worker_applicant'];

                    $worker_applicant                                           =   $this->worker_select($data_email);

                    $data_email['id_user']                                      =   $shiftchange['id_coordinator'];

                    $coordinator                                                =   $this->user_select($data_email);

                    $body                                                       =   '<p style="text-align: justify;">Hola ' . $this->_trabajandofet_model->to_camel($worker_applicant['name_worker']);

                    $flag_mail1                                                 =   '';

                    if ($params['vob_shiftchange'] == '1')
                    {
                        $body                                                   .=  ' tu solicitud de cambio de turno ha sido <b>APROBADA</b> por Talento Humano.</p>';

                        $flag_mail1                                             =   '👍 Notificación Cambio de Turno';
                    }
                    else if ($params['vob_shiftchange'] == '2')
                    {
                        $body                                                   .=  ' tu solicitud de cambio de turno ha sido <b>RECHAZADA</b> por Talento Humano.</p>';

                        $flag_mail1                                             =   '👎 Notificación sobre Cambio de Turno';
                    }
                    else
                    {
                        $body                                                   .=  '.</p>';
                    }

                    $content                                                    =   array(
                                                                                        'of'                    =>  'Trabajandofet',
                                                                                        'title'                 =>  'Cambio de Turno',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '0',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                    $mail1                                                      =   $this->_trabajandofet_model->send_mail($worker_applicant['email_ccv'], $flag_mail1, $content);

                    if ($mail1)
                    {
                        $body                                                   =   '<p style="text-align: justify;">Hola ' . $this->_trabajandofet_model->to_camel($worker_replacement['name_worker'])
                                                                                .   ' la solicitud hecha por ' . $this->_trabajandofet_model->to_camel($worker_applicant['name_worker']);
                        
                        $flag_mail2                                             =   '';

                        if ($params['vob_shiftchange'] == '1')
                        {
                            $body                                               .=  ' sobre el cambio de turno ha sido <b>APROBADA</b> por Talento Humano.</p>';

                            $flag_mail2                                         =   '👍 Notificación Cambio de Turno';
                        }
                        else if ($params['vob_shiftchange'] == '2')
                        {
                            $body                                               .=  ' sobre el cambio de turno ha sido <b>RECHAZADA</b> por Talento Humano.</p>';

                            $flag_mail2                                         =   '👎 Notificación sobre Cambio de Turno';
                        }
                        else
                        {
                            $body                                               .=  '.</p>';
                        }

                        $content                                                =   array(
                                                                                        'of'                    =>  'Trabajandofet',
                                                                                        'title'                 =>  'Cambio de Turno',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '0',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                        $mail2                                                  =   $this->_trabajandofet_model->send_mail($worker_replacement['email_ccv'], $flag_mail2, $content);

                        if ($mail2)
                        {
                            $body                                               =   '<p style="text-align: justify;">Hola Coordinadora ' . $this->_trabajandofet_model->to_camel($coordinator['name_user']) . ', la persona '
                                                                                .   $this->_trabajandofet_model->to_camel($worker_applicant['name_worker']) . ' a solicitado un cambio de turno con la persona '
                                                                                .   $this->_trabajandofet_model->to_camel($worker_replacement['name_worker']);

                            $flag_mail3                                         =   '';

                            if ($params['vob_shiftchange'] == '1')
                            {
                                $body                                           .=  ' y esta ha sido <b>APROBADA</b> por Talento Humano.</p>';

                                $flag_mail3                                     =   '👍 Notificación Cambio de Turno';
                            }
                            else if ($params['vob_shiftchange'] == '2')
                            {
                                $body                                           .=  ' y esta ha sido <b>RECHAZADA</b> por Talento Humano.</p>';

                                $flag_mail3                                     =   '👎 Notificación sobre Cambio de Turno';
                            }
                            else
                            {
                                $body                                           .=  '.</p>';
                            }

                            $content                                            =   array(
                                                                                        'of'                    =>  'Trabajandofet',
                                                                                        'title'                 =>  'Cambio de Turno',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                            $mail3                                              =   $this->_trabajandofet_model->send_mail($coordinator['email_user'], $flag_mail3, $content);

                            if ($mail3)
                            {
                                $result['data']                                 =   TRUE;
                                $result['message']                              =   'Se ha informado por correo electrónico al afiliado solicitante, al afiliado del reemplazo y a la coordinadora de enlace.';
                            }
                            else
                            {
                                $result['data']                                 =   TRUE;
                                $result['message']                              =   'Se ha informado por correo electrónico al afiliado solicitante y al afiliado del reemplazo pero no a la coordinadora de enlace.';
                            }
                        }
                        else
                        {
                            $result['data']                                     =   TRUE;
                            $result['message']                                  =   'Se ha aprobado el cambio y se ha notificado por correo electrónico al afiliado solicitante pero no al afiliado del reemplazo y a la coordinadora de enlace.';
                        }
                    }
                    else
                    {
                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Se ha aprobado el cambio pero no fue posible enviar la notificación de correo al afiliado solicitante, al afiliado del reemplazo y a la coordinadora de enlace.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;

                    if ($params['vob_shiftchange'] == '1')
                    {
                        $result['message']                                      =   'No es posible <b>aprobar</b> la solicitud, por favor comunicate con nosotros para revisar la razón (gestion@trabajandofet.co).';
                    }
                    else if ($params['vob_shiftchange'] == '2')
                    {
                        $result['message']                                      =   'No es posible <b>rechazar</b> la solicitud, por favor comunicate con nosotros para revisar la razón (gestion@trabajandofet.co).';
                    }
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;

                if ($params['vob_shiftchange'] == '1')
                {
                    $result['message']                                          =   'No es posible <b>aprobar</b> la solicitud dado que el afiliado de reemplazo no ha aprobado el cambio.';
                }
                else if ($params['vob_shiftchange'] == '2')
                {
                    $result['message']                                          =   'No es posible <b>rechazar</b> la solicitud dado que el afiliado de reemplazo no ha aprobado el cambio.';
                }
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;

            if ($count_worker['count_applicant'] >= 5)
            {
                $result['message']                                              =   'No es posible aprobar el cambio de turno dado que el solicitante tiene ' . $count_worker['count_applicant'] . ' turnos aprobados en el mes.';
            }
            else if ($count_worker['count_replacement'] >= 5)
            {
                $result['message']                                              =   'No es posible aprobar el cambio de turno dado que el reemplazo tiene ' . $count_worker['count_replacement'] . ' turnos aprobados en el mes.';
            }
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
    public function change_coordinator($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('id', 'Identificador turno', 'required');
        $this->form_validation->set_rules('id_coordinator', 'Coordinador de Enlace', 'required');

        if($this->form_validation->run())
        {
            if (isset($this->session->userdata['id_worker']))
            {
                $params['worker_update']                                        =   $this->session->userdata['id_worker'];
                $params['date_worker_update']                                   =   date('Y-m-d H:i:s');
            }
            else if (isset($this->session->userdata['id_user']))
            {
                $params['user_update']                                          =   $this->session->userdata['id_user'];
                $params['date_update']                                          =   date('Y-m-d H:i:s');
            }

            $answer                                                             =   $this->_trabajandofet_model->update_data($params, 'id_shiftchange', 'fet_shiftchange');

            if ($answer)
            {
                $params['id_shiftchange']                                       =   $params['id'];

                unset($params['id']);

                $this->_trabajandofet_model->insert_data($params, 'fet_shiftchange_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Tenemos problemas al cambiar el coordinador del cambio de turno.';
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

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $param
    * @return    array $result
    **/
    public function udrop($param)
    {
        if (isset($param['id_shiftchange']))
        {
            $this->db->select('vob_shiftchange');
            $this->db->where('flag_drop', 0);
            $this->db->where('id_shiftchange', $param['id_shiftchange']);

            $query                                                              =   $this->db->get('fet_shiftchange');

            $shiftchange_udrop                                                  =   $query->row_array();

            if (isset($shiftchange_udrop['vob_shiftchange']) && ($shiftchange_udrop['vob_shiftchange'] == '0' || $shiftchange_udrop['vob_shiftchange'] == '1'))
            {
                $data['id']                                                     =   $param['id_shiftchange'];
                $data['flag_drop']                                              =   1;

                if (isset($this->session->userdata['id_user']))
                {
                    $data['user_update']                                        =   $this->session->userdata['id_user'];
                    $data['date_update']                                        =   date('Y-m-d H:i:s');
                }

                if (isset($this->session->userdata['id_worker']))
                {
                    $data['worker_update']                                      =   $this->session->userdata['id_worker'];
                    $data['date_worker_update']                                 =   date('Y-m-d H:i:s');
                }

                $result                                                         =   array();

                $answer                                                         =   $this->_trabajandofet_model->update_data($data, 'id_shiftchange', 'fet_shiftchange');

                if ($answer)
                {
                    $data_history                                               =   $data;
                    $data_history['id_shiftchange']                             =   $data['id'];

                    unset($data_history['id']);

                    $this->_trabajandofet_model->insert_data($data_history, 'fet_shiftchange_history');

                    $result['data']                                             =   TRUE;
                    $result['message']                                          =   'Acción realizada con éxito!';
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Tenemos problemas al eliminar el cambio de turno.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;

                if ($shiftchange_udrop['vob_shiftchange'] == '1')
                {
                    $result['message']                                          =   'El cambio de turno ya se encuentra aprobado, no es posible eliminarlo.';
                }
                else if ($shiftchange_udrop['vob_shiftchange'] == '2')
                {
                    $result['message']                                          =   'El cambio de turno ya se encuentra rechazado, no es posible eliminarlo.';
                }
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No es posible reconocer el cambio de turno.';
        }

        return $result;
        exit();
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param     array $param
    *@return    array $result
    **/
    public function trace_register($param)
    {
        $result                                                                 =   array();

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register_shiftchange('fet_shiftchange', 'id_shiftchange', $param['id_shiftchange']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register_sh('fet_shiftchange_history', 'id_shiftchange', $param['id_shiftchange']);

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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     string $search
    * @return    array $result
    **/
    public function export_xlsx($search)
    {
        if (isset($this->session->userdata['id_worker']))
        {
            $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv) AS name_worker_applicant');
            $this->db->select('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv) AS name_worker_replacement');
        }
        else if (isset($this->session->userdata['id_user']))
        {
            $this->db->select('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ", fc1.second_lcv, " (", fc1.number_dcv, ")") AS name_worker_applicant');
            $this->db->select('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv, " (", fc2.number_dcv, ")") AS name_worker_replacement');
        }

        $this->db->select('fsc.date_shiftchange');
        $this->db->select('IF(fsc.type_shiftchange = "C", "COMPLETO", IF(fsc.type_shiftchange = "N", "NOCHE", "N/A")) AS type_shiftchange');
        $this->db->select('fsc.date_return_shiftchange');
        $this->db->select('IF(fsc.type_return_shiftchange = "C", "COMPLETO", IF(fsc.type_return_shiftchange = "N", "NOCHE", "N/A")) AS type_return_shiftchange');
        $this->db->select('UPPER(CONCAT(gu1.name_user, " ", gu1.lastname_user)) AS coordinador');
        $this->db->select('IF(fsc.vob_shiftchange = 0, "INDEFINIDO", IF(fsc.vob_shiftchange = 1, "APROBADO", IF(fsc.vob_shiftchange = 2, "RECHAZADO", "N/A"))) AS state');
        $this->db->join('fet_workers fw1', 'fsc.id_worker_applicant = fw1.id_worker');
        $this->db->join('fet_cv fc1', 'fw1.id_cv = fc1.id_cv');
        $this->db->join('fet_workers fw2', 'fsc.id_worker_replacement = fw2.id_worker');
        $this->db->join('fet_cv fc2', 'fw2.id_cv = fc2.id_cv');
        $this->db->join('git_users gu1', 'fsc.id_coordinator = gu1.id_user');
        $this->db->where('fsc.flag_drop', 0);

        if (!empty($search))
        {
            $this->db->group_start();

            if (isset($this->session->userdata['id_worker']))
            {
                $this->db->like('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ",fc1.second_lcv)', $search);
                $this->db->or_like('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv)', $search);
                $this->db->where('fsc.id_worker_applicant', $this->session->userdata['id_worker']);
            }
            else if (isset($this->session->userdata['id_user']))
            {
                $this->db->like('CONCAT(fc1.name_cv, " ", fc1.first_lcv, " ",fc1.second_lcv)', $search);
                $this->db->or_like('fc1.number_dcv', $search);
                $this->db->or_like('CONCAT(fc2.name_cv, " ", fc2.first_lcv, " ", fc2.second_lcv)', $search);
                $this->db->or_like('fc2.number_dcv', $search);

                if (!isset($this->session->userdata['flags']['flag_view_shiftchange']))
                {
                    $this->db->where('fsc.id_coordinator', $this->session->userdata['id_user']);
                }
            }

            $this->db->or_like('fsc.date_shiftchange', $search);
            $this->db->or_like('fsc.date_return_shiftchange', $search);
            $this->db->or_like('fsc.type_shiftchange', $search);
            $this->db->or_like('fsc.type_return_shiftchange', $search);
            $this->db->group_end();
        }

        $this->db->order_by('fsc.date_shiftchange', 'DESC');

        $query                                                                  =   $this->db->get('fet_shiftchange fsc');

        $result                                                                 =   array();

        $result['data']                                                         =   $query->result_array();

        if (count($result['data']) > 0)
        {
            $result['message']                                                  =   FALSE;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay afiliados para exportar.';
        }

        return $result;
        exit();
    }
}