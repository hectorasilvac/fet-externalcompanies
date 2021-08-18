<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
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

        $this->db->where('git_company != ', 'G');
        $this->db->where('flag_drop', 0);
        $this->db->where('id_user !=', $this->session->userdata['id_user']);

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('id_role !=', 11);
        }

        $this->db->from('git_users');

        $result['total']                                                        =   $this->db->count_all_results();

        if (!empty($search))
        {
            $this->db->select('git_users.id_user');
            $this->db->from('git_users');
            $this->db->join('git_roles', 'git_roles.id_role = git_users.id_role');
            $this->db->join('fet_aspirants', 'fet_aspirants.id_aspirant = git_users.id_aspirant', 'left');
            $this->db->where('git_users.git_company != ', 'G');
            $this->db->where('git_users.flag_drop', 0);
            $this->db->where('git_users.id_user !=', $this->session->userdata['id_user']);

            if($this->session->userdata['id_role'] != "11")
            {
                $this->db->where('git_users.id_role !=', 11);
            }

            $this->db->group_start();
            $this->db->like('CONCAT(git_users.name_user, " ", git_users.lastname_user)', $search);
            $this->db->or_like('git_roles.name_role', $search);
            $this->db->or_like('git_users.user', $search);
            $this->db->or_like('git_users.email_user', $search);
            $this->db->or_like('DATE_FORMAT(git_users.date_keepalive, \'%d-%m-%Y\')', $search);
            $this->db->or_like('CONCAT(fet_aspirants.name_aspirant, " ", fet_aspirants.first_last_name_aspirant, " ", fet_aspirants.second_last_name_aspirant)', $search);
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
    public function all_rows($limit, $start, $search, $col, $dir) // Eliminar -> Pendiente
    {
        $this->db->select('gu.id_user, gu.id_role, gu.name_user, gu.lastname_user, fr.name_role, gu.user, gu.email_user,  DATE_FORMAT(gu.date_keepalive, \'%d-%m-%Y %h:%i %p\') AS date_keepalive, gu.flag_display, gu.id_aspirant, CONCAT(fa.name_aspirant, " ", fa.first_last_name_aspirant, " ", fa.second_last_name_aspirant) AS name_aspirant');
        $this->db->from('git_users gu');
        $this->db->join('git_roles fr', 'fr.id_role = gu.id_role');
        $this->db->join('fet_aspirants fa', 'fa.id_aspirant = gu.id_aspirant', 'left');
        $this->db->where('gu.git_company != ', 'G');
        $this->db->where('gu.flag_drop', 0);
        $this->db->where('gu.id_user !=', $this->session->userdata['id_user']);

        if($this->session->userdata['id_role'] != "11")
        {
            $this->db->where('gu.id_role !=', 11);
        }

        if (!empty($search))
        {
            $this->db->group_start();
            $this->db->like('CONCAT(gu.name_user, " ", gu.lastname_user)', $search);
            $this->db->or_like('fr.name_role', $search);
            $this->db->or_like('gu.user', $search);
            $this->db->or_like('gu.email_user', $search);
            $this->db->or_like('DATE_FORMAT(gu.date_keepalive, \'%d-%m-%Y\')', $search);
            $this->db->or_like('CONCAT(fa.name_aspirant, " ", fa.first_last_name_aspirant, " ", fa.second_last_name_aspirant)', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($col, $dir);

        $query                                                                  =   $this->db->get();

        $users                                                                  =   $query->result_array();

        if ($this->session->userdata['mobile'] == 0)
        {
            $count                                                              =   $start;
            foreach ($users as $key => $action)
            {
                $count++;
                $users[$key]['number']                                          =   $count;
            }
        }

        return $users;
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
    public function exist_extension($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        if (isset($params['pk']))
        {
            $this->db->select($params['name']);
            $this->db->where('git_company != ', 'G');
            $this->db->where('flag_drop', 0);
            $this->db->where($params['name'], trim($params['value']));
            $this->db->where('id_user !=', $params['pk']);
        }
        else
        {
            $this->db->select('email_user, user, id_aspirant');
            $this->db->where('git_company != ', 'G');
            $this->db->where('flag_drop', 0);
            $this->db->group_start();
            $this->db->where('email_user', trim($params['email_user']));
            $this->db->or_where('user', trim($params['user']));
            $this->db->or_where('id_aspirant', $params['id_aspirant']);
            $this->db->group_end();
        }

        $query                                                                  =   $this->db->get('git_users');

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
                    case 'email_user':
                        if ( $value == trim($params['email_user']) )
                        {
                            $message                                            =   ' este correo electrónico.';
                        }
                        break;

                    case 'user':
                        if ($value == trim($params['user']))
                        {
                            $message                                            =   ' este nombre de usuario.';
                        }
                        break;

                    case 'id_aspirant':
                        if ($value == $params['id_aspirant'])
                        {
                            $message                                            =   ' este aspirante asignado.';
                        }
                        break;
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe un usuario con ' . $message;
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
    public function add($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('name_user', 'Nombres', 'required');
        $this->form_validation->set_rules('lastname_user', 'Apellidos', 'required');
        $this->form_validation->set_rules('user', 'Usuario', 'required');
        $this->form_validation->set_rules('password_user', 'Contraseña', array('required', 'min_length[8]'));
        $this->form_validation->set_rules('email_user', 'Correo', 'required');
        $this->form_validation->set_rules('id_role', 'Rol', 'required');

        if($this->form_validation->run())
        {
            $data                                                               =   array();
            $data['id_role']                                                    =   $params['id_role'];
            $data['git_company']                                                =   'T';
            $data['name_user']                                                  =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['name_user'])));
            $data['lastname_user']                                              =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['lastname_user'])));
            $data['email_user']                                                 =   $this->_trabajandofet_model->user_name($params['email_user']);
            $data['user']                                                       =   $this->_trabajandofet_model->user_name($params['user']);
            $password_user_text                                                 =   $params['password_user'];
            $data['password_user']                                              =   password_hash($params['password_user'], PASSWORD_DEFAULT);
            $data['user_insert']                                                =   $this->session->userdata['id_user'];

            if (isset($params['id_aspirant']) && $params['id_aspirant'] != '')
            {
                $data['id_aspirant']                                            =   $params['id_aspirant'];
            }
            else
            {
                unset($params['id_aspirant']);
            }

            $this->db->trans_start();

            $answer                                                             =   $this->_trabajandofet_model->insert_data($data, 'git_users');

            if ($answer && isset($params['flags']))
            {
                foreach ($params['flags'] as $key => $value)
                {
                    $flag['id_user']                                            =   $answer;
                    $flag['id_flag']                                            =   $value;
                    $flag['git_company']                                        =   'T';
                    $flag['user_insert']                                        =   $data['user_insert'];

                    $this->_trabajandofet_model->insert_data($flag, 'git_users_flags');
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
            {
                $data_history                                                   =   $data;
                $data_history['id_user']                                        =   $answer;
                $data_history['user_update']                                    =   $data['user_insert'];
                unset($data_history['git_company']);
                unset($data_history['user_insert']);

                $this->_trabajandofet_model->insert_data($data_history, 'git_users_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';

                $body                                                           =   '<p style="text-align: justify;">Hola ' . $data['name_user'] . ' ' . $data['lastname_user'] . ' la plataforma'
                                                                                .   ' de Apoyo a los Procesos Misionales TRABAJANDOFET te da la bienvenida,'
                                                                                .   ' a continuación te presentamos tu usuario y clave para la ejecución de tus'
                                                                                .   ' actividades, cualquier requerimiento que necesites sobre la misma, favor'
                                                                                .   ' comunícate con nosotros por medio de nuestro correo corporativo <i>gestion@trabajandofet.co.</i></p>'
                                                                                .   '<p><b>Usuario:</b></p>'
                                                                                .   '<p style="text-align: center;">' . $data['user'] . '</p>'
                                                                                .   '<p><b>Contraseña:</b></p>'
                                                                                .   '<p style="text-align: center;">' . $password_user_text . '</p>';

                $content                                                        =   array(
                                                                                        'of'                    =>  'Plataforma',
                                                                                        'title'                 =>  'Nuevo Usuario en Plataforma',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                $send                                                           =   $this->_trabajandofet_model->send_mail($data['email_user'], $content['title'], $content);

                if ($send)
                {
                    $result['data']                                             =   TRUE;
                    $result['message']                                          =   'Se ha enviado un correo electrónico con el nuevo usuario a ' . $data['email_user'];
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Problemas al enviar el correo electrónico.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al guardar el usuario.';
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
    public function edit($params) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        if ($params['value'] != '' || $params['value'] != null)
        {
            $data                                                               =   array();

            switch ($params['name'])
            {
                case 'name_user':
                    $data['name_user']                                          =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['value'])));
                    break;

                case 'lastname_user':
                    $data['lastname_user']                                      =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['value'])));
                    break;

                case 'email_user':
                    $data['email_user']                                         =   $this->_trabajandofet_model->user_name($params['value']);
                    break;

                case 'user':
                    $data['user']                                               =   $this->_trabajandofet_model->user_name($params['value']);
                    break;

                case 'password_user':
                    $data['password_user']                                      =   password_hash($params['value'], PASSWORD_DEFAULT);
                    break;

                default:
                    $data[$params['name']]                                      =   $params['value'];
                    break;
            }

            $data['id']                                                         =   $params['pk'];
            $data['user_update']                                                =   $this->session->userdata['id_user'];
            $data['date_update']                                                =   date('Y-m-d H:i:s');

            $answer                                                             =   $this->_trabajandofet_model->update_data($data, 'id_user', 'git_users');

            if ($answer)
            {
                $data_history                                                   =   $data;
                $data_history['id_user']                                        =   $data_history['id'];
                unset($data_history['id']);

                $data_history['id_role']                                        =   $this->role_in_user($data_history);

                $this->_trabajandofet_model->insert_data($data_history, 'git_users_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Acción realizada con éxito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas al editar el usuario.';
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
    public function udrop($param) // Eliminar -> Pendiente
    {
        $data                                                                   =   array(
            'id'                                                                        =>  $param['id_user'],
            'flag_drop'                                                                 =>  1,
            'user_update'                                                               =>  $this->session->userdata['id_user'],
            'date_update'                                                               =>  date('Y-m-d H:i:s')
                                                                                    );

        $result                                                                 =   array();

        $answer                                                                 =   $this->_trabajandofet_model->update_data($data, 'id_user', 'git_users');

        if ($answer)
        {
            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Acción realizada con éxito!';
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Problemas al eliminar el usuario.';
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
    public function trace_register($param) // Eliminar -> Pendiente
    {
        $result                                                                 =   array();

        $result['data']                                                         =   $this->_trabajandofet_model->trace_register('git_users', 'id_user', $param['id_user']);
        $result['data_global']                                                  =   $this->_trabajandofet_model->global_trace_register('git_users_history', 'id_user', $param['id_user']);

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