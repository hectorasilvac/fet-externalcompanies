<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

include_once 'vendor/autoload.php';
use ipinfo\ipinfo\IPinfo;

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $role
    * @return    string
    **/
    public function submodules_by_role($role)
    {
        $html                                                                   =   '';

        $all_modules                                                            =   array();

        $this->db->select('git_modules.id_module, git_modules.name_module, git_modules.name_es_module, git_modules.icon_module, git_modules.url_module');
        $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
        $this->db->join('git_modules', 'git_modules.id_module = git_submodules.id_module');
        $this->db->where('git_permissions.id_role', $role);
        $this->db->where('git_permissions.git_company != ', 'G');
        $this->db->group_by('git_modules.id_module');
        $this->db->order_by('git_modules.sort_module ASC');

        $query                                                                  =   $this->db->get('git_permissions');

        $modules                                                                =   array();
        $modules                                                                =   $query->result_array();

        if (count($modules) > 0)
        {
            $this->db->select('git_modules.id_module, git_submodules.name_submodule, git_submodules.name_es_submodule, git_submodules.url_submodule');
            $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
            $this->db->join('git_modules', 'git_modules.id_module = git_submodules.id_module');
            $this->db->where('git_permissions.id_role', $role);
            $this->db->where('git_permissions.git_company != ', 'G');
            $this->db->group_by('git_permissions.id_submodule');
            $this->db->order_by('git_submodules.sort_submodule ASC');

            $query                                                              =   $this->db->get('git_permissions');

            $submodules                                                         =   array();
            $submodules                                                         =   $query->result_array();

            foreach ($modules as $module)
            {
                if ($module['url_module'] != '' && $module['url_module'] != '#')
                {
                    $html                                                       .=  '<a href="' . base_url($module['url_module']) . '" class="' . $module['name_module'] . ' br-menu-link">'
                                                                                .   '<div class="br-menu-item">'
                                                                                .   '<i class="menu-item-icon ' . $module['icon_module'] . '"></i>'
                                                                                .   '<span class="menu-item-label op-lg-0-force d-lg-none">' . $module['name_es_module'] . '</span>'
                                                                                .   '</div>'
                                                                                .   '</a>';

                    $all_modules[]                                              =   $module['name_module'];
                }
                else
                {
                    $html                                                       .=  '<a href="' . base_url($module['url_module']) . '" class="' . $module['name_module'] . ' br-menu-link">'
                                                                                .   '<div class="br-menu-item">'
                                                                                .   '<i class="menu-item-icon ' . $module['icon_module'] . '"></i>'
                                                                                .   '<span class="menu-item-label op-lg-0-force d-lg-none">' . $module['name_es_module'] . '</span>'
                                                                                .   '<i class="menu-item-arrow fas fa-angle-down op-lg-0-force d-lg-none"></i>'
                                                                                .   '</div>'
                                                                                .   '</a>'
                                                                                .   '<ul class="br-menu-sub nav flex-column">';

                    foreach ($submodules as $submodule)
                    {
                        if ($submodule['id_module'] == $module['id_module'])
                        {
                            $html                                               .=  '<li class="nav-item"><a href="' . base_url($submodule['url_submodule']) . '" class="' . $submodule['url_submodule'] . ' nav-link">' . $submodule['name_es_submodule'] . '</a></li>';
                        }

                        $all_modules[]                                          =   $submodule['name_submodule'];
                    }

                    $html                                                       .=  '</ul>';
                }
            }
        }

        if (isset($this->session->userdata['id_role']))
        {
            $this->session->set_userdata(array('modules' => $all_modules));
        }

        return $html;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $id_user
    * @return    array $query->result_array()
    **/
    public function user_flags($id_user)
    {
        $this->db->select('git_users_flags.id_flag, git_flags.name_flag');
        $this->db->join('git_flags', 'git_flags.id_flag = git_users_flags.id_flag');
        $this->db->where('git_flags.flag_drop', 0);
        $this->db->where('git_flags.git_company != ', 'G');
        $this->db->where('git_users_flags.id_user', $id_user);
        $this->db->group_by('git_users_flags.id_flag');

        $query                                                                  =   $this->db->get('git_users_flags');

        return $query->result_array();
        exit();
    }    

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $id_user
    * @return    boolean
    **/
    public function verify_device()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;
        $body_class = 'desktop';
 
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', mb_strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            $tablet_browser++;
            $body_class                                                         =   "mobile";
        }
 
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', mb_strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            $mobile_browser++;
            $body_class                                                         =   "mobile";
        }
 
        if ((strpos(mb_strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
        {
            $mobile_browser++;
            $body_class                                                         =   "mobile";
        }
 
        $mobile_ua                                                              =   mb_strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));

        $mobile_agents                                                          =   array(
                                                                                        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                                                                                        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                                                                                        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                                                                                        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                                                                                        'newt','noki','palm','pana','pant','phil','play','port','prox',
                                                                                        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                                                                                        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                                                                                        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                                                                                        'wapr','webc','winw','winw','xda ','xda-');
 
        if (in_array($mobile_ua,$mobile_agents))
        {
            $mobile_browser++;
        }

        if (strpos(mb_strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0)
        {
            $mobile_browser++;
            $stock_ua = mb_strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));

            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua))
            {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0)
        {
            $device                                                             =   1;
        }
        else if ($mobile_browser > 0)
        {
            $device                                                             =   1;
        }
        else
        {
            $device                                                             =   0;
        }

        return $device;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $id_user
    * @return    boolean
    **/
    public function verify_date()
    {
        $result                                                                 =   array();

        $this->db->select('now_date');
        $this->db->where('type_date', 'FECHA DE ACTUALIZACION');
        $this->db->where('now_date', date("Y-m-d"));
        $this->db->where('now() > hour_date');
        $this->db->where('now() < DATE_ADD(hour_date, INTERVAL 1 HOUR)');
        $this->db->where('flag_drop', 0);
        $this->db->where('git_company != ', 'G');

        $query                                                                  =   $this->db->get('git_dates');

        if (count($query->result_array()) > 0)
        {
            $result['data']                                                     =   $query->result_array();
            $result['message']                                                  =   'Hay actualizaciones pendientes.';
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'No hay actualizaciones pendientes.';
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
    public function security_questions_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_security_question AS id, name_security_question AS text');
        $this->db->where('flag_drop', 0);

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('name_security_question', $params['q']);
        }

        if (isset($params['id']) && count($params['id']) > 0)
        {
            $this->db->where_not_in('id_security_question', $params['id']);
        }

        $this->db->order_by('id_security_question', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_security_questions');

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
    public function security_questions_inputs_draw($params)
    {
        $html                                                                   =   '';

        if (isset($params['name_parameter'])) 
        {
            $this->db->select('git_parameters.value_parameter');
            $this->db->where('name_parameter', $params['name_parameter']);

            $query                                                              =   $this->db->get('git_parameters');
            $limit                                                              =   $query->row_array();

            if ($limit) 
            {
                $autofocus                                                      = "autofocus";

                $html .= '<div class="row mg-x-0">';

                for ($i = 0; $i < intval($limit['value_parameter']); $i++) 
                {
                    if ($i > 0)
                    {
                        $autofocus                                              = "";
                    }

                    $html .= '<div class="col-md-12">
                        <div class="form-group pos-relative">
                            <select name="name_security_question[]" class="form-control mg-x-auto select_security_question" required ' . $autofocus . '></select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group pos-relative">
                            <input type="text" name="value_security_question[]" placeholder="Respuesta" class="form-control mg-x-auto" required/>
                        </div>
                    </div>';
                }
            }

            $html .= '</div>';
        }

        return $html;
        exit();
    }


    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function verify_admin($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('user', 'Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');

        if($this->form_validation->run())
        {
            $params['type_user']                                                =   'ADMIN';

            $this->db->select('gu.id_user, CONCAT(gu.name_user,\' \',gu.lastname_user) as user, gu.user as alias, gu.id_role, gu.id_sgcdocumentarea, gr.name_role,gu.password_user', FALSE);
            $this->db->join('git_roles gr', 'gr.id_role = gu.id_role');
            $this->db->where('gu.user', $this->_trabajandofet_model->user_name($params['user']));
            $this->db->where('gu.flag_display', 1);
            $this->db->where('gu.flag_drop', 0);
            $this->db->where('gu.git_company != ', 'G');
            $query                                                              =   $this->db->get('git_users gu');

            if (count($query->result_array()) > 0)
            {
                $data                                                           =   $query->row_array();

                if (password_verify($params['password'], $data['password_user']))
                {
                    $this->db->select('git_permissions.name_submodule,git_submodules.url_submodule');
                    $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
                    $this->db->where('git_permissions.id_role', $data['id_role']);
                    $this->db->where('git_permissions.git_company != ', 'G');
                    $this->db->group_by('git_permissions.name_submodule,git_submodules.url_submodule');

                    $query2                                                     =   $this->db->get('git_permissions');

                    if (count($query2->result_array()) > 0)
                    {
                        $submodules                                             =   $query2->result_array();
                        unset($data['password_user']);
                        $result['data']                                         =   $data;
                        $result['message']                                      =   FALSE;

                        $update['id']                                           =    $data['id_user'];
                        $update['date_keepalive']                               =    date('Y-m-d H:i:s');

                        $this->_trabajandofet_model->update_data($update, 'id_user', 'git_users');
                        $this->success_login($params);
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Usuario y clave correctos, pero sin permisos para ingresar.';
                    }
                }
                else
                {
                    $this->fail_login($params);
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Usuario o clave incorrecta.';
                }
            }
            else
            {
                $this->fail_login($params);
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Usuario o clave incorrecta.';
            }
        }
        else
        {
            $this->fail_login($params);
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Todos los campos son requeridos.';
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
    public function verify_aspirants($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('user', 'Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');

        if($this->form_validation->run())
        {
            $params['type_user']                                                =   'ASPIRANTE';

            $this->db->select('fet_aspirants.id_aspirant, fet_aspirants.password_aspirant', FALSE);
            $this->db->where('fet_aspirants.user_aspirant', $this->_trabajandofet_model->user_name($params['user']));
            $this->db->where('fet_aspirants.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_aspirants');

            if (count($query->result_array()) > 0)
            {
                $data                                                           =   $query->row_array();

                if (password_verify($params['password'], $data['password_aspirant']))
                {
                    $this->db->select('fasq.id_aspirant_security_question, gsq.name_security_question', FALSE);
                    $this->db->join('git_security_questions gsq', 'gsq.id_security_question = fasq.id_security_question');
                    $this->db->where('fasq.id_aspirant', $data['id_aspirant']);
                    $this->db->where('fasq.flag_drop', 0);
                    $this->db->order_by('RAND()');
                    $this->db->limit(1);

                    $query                                                      =   $this->db->get('fet_aspirant_security_questions fasq');
                    
                    if ($query->row_array()) 
                    {
                        $this->success_login($params);
                        $result['data']                                         =   TRUE;
                        $result['question']                                     =   $query->row_array();
                    }
                    else
                    {
                        $this->fail_login($params);
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'No posee preguntas de seguridad, contacte con el administrador.';
                    }
                }
                else
                {
                    $this->fail_login($params);
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Ya te registraste? si ya lo hiciste, es probable que tu usuario o contraseña esten incorrectos.';
                }
            }
            else
            {
                $this->fail_login($params);
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Ya te registraste? si ya lo hiciste, es probable que tu usuario o contraseña esten incorrectos.';
            }
        }
        else
        {
            $this->fail_login($params);
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Todos los campos son requeridos.';
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
    public function security_aspirants($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('id_security_question', 'Id Pregunta', 'required');
        $this->form_validation->set_rules('value_security_question', 'Respuesta', 'required');

        if($this->form_validation->run())
        {
            $this->db->select('fasq.id_aspirant, fasq.value_security_question', FALSE);
            $this->db->where('fasq.id_aspirant_security_question', $params['id_security_question']);
            $this->db->where('fasq.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_aspirant_security_questions fasq');

            if ($query->row_array()) 
            {
                $validate                                                       =   $query->row_array();

                if ($validate['value_security_question'] == trim(mb_strtolower($params['value_security_question']))) 
                {
                    $this->db->select('fet_aspirants.id_aspirant');
                    $this->db->select('fet_aspirants.id_project, fet_aspirants.id_cv');
                    $this->db->select('fet_aspirants.id_role, git_roles.name_role');
                    $this->db->select('CONCAT(fet_aspirants.name_aspirant, \' \', fet_aspirants.first_last_name_aspirant, \' \', fet_aspirants.second_last_name_aspirant) as user');
                    $this->db->select('fet_aspirants.phone_aspirant AS phone_user, fet_aspirants.email_aspirant AS email_user, fet_aspirants.user_aspirant AS alias');
                    $this->db->join('git_roles', 'git_roles.id_role = fet_aspirants.id_role');
                    $this->db->where('fet_aspirants.id_aspirant', $validate['id_aspirant']);
                    $this->db->where('fet_aspirants.flag_drop', 0);

                    $query                                                      =   $this->db->get('fet_aspirants');
                    $aspirant                                                   =   $query->row_array();
                    if (isset($aspirant['id_aspirant']))
                    {
                        $user                                                   =   array();

                        $this->db->select('fet_affiliates.id_affiliate, fet_affiliates.id_aspirant');
                        $this->db->select('fet_affiliates.id_jobtitle, fet_affiliates.id_project, fet_affiliates.id_cv');
                        $this->db->select('fet_affiliates.id_role, git_roles.name_role');
                        $this->db->select('CONCAT(fet_cv.name_cv, \' \', fet_cv.first_lcv, \' \', fet_cv.second_lcv) as user');
                        $this->db->select('fet_cv.type_dcv AS type_doc_user, fet_cv.number_dcv AS document_user, fet_cv.email_ccv AS email_user');
                        $this->db->join('fet_cv', 'fet_cv.id_cv = fet_affiliates.id_cv', 'left');
                        $this->db->join('git_roles', 'git_roles.id_role = fet_affiliates.id_role');
                        $this->db->where('fet_affiliates.id_aspirant', $aspirant['id_aspirant']);
                        $this->db->where('fet_affiliates.flag_drop', 0);

                        $query                                                  =   $this->db->get('fet_affiliates');
                        $affiliate                                              =   $query->row_array();

                        if (isset($affiliate['id_affiliate'])) 
                        {
                            $this->db->select('fet_workers.id_worker, fet_workers.id_affiliate, fet_affiliates.id_aspirant');
                            $this->db->select('fet_workers.id_jobtitle, fet_workers.id_project, fet_workers.id_cv');
                            $this->db->select('fet_workers.id_role, git_roles.name_role');
                            $this->db->select('CONCAT(fet_cv.name_cv, \' \', fet_cv.first_lcv, \' \', fet_cv.second_lcv) as user');
                            $this->db->select('fet_cv.type_dcv AS type_doc_user, fet_cv.number_dcv AS document_user, fet_cv.email_ccv AS email_user');
                            $this->db->join('git_roles', 'git_roles.id_role = fet_workers.id_role');
                            $this->db->join('fet_affiliates', 'fet_affiliates.id_affiliate = fet_workers.id_affiliate');
                            $this->db->join('fet_cv', 'fet_cv.id_cv = fet_workers.id_cv', 'left');
                            $this->db->where('fet_workers.id_affiliate', $affiliate['id_affiliate']);
                            $this->db->where('fet_workers.flag_drop', 0);

                            $query                                              =   $this->db->get('fet_workers');
                            $worker                                             =   $query->row_array();

                            if (isset($worker['id_worker'])) 
                            {
                                $user                                           =   $worker;
                                $user['alias']                                  =   $aspirant['alias'];
                                $user['phone_user']                             =   $aspirant['phone_user'];
                            }
                            else
                            {
                                $user                                           =   $affiliate;
                                $user['alias']                                  =   $aspirant['alias'];
                                $user['phone_user']                             =   $aspirant['phone_user'];
                            }
                        }
                        else
                        {
                            $user                                               =   $aspirant;
                        }

                        $this->db->select('git_permissions.name_submodule, git_submodules.url_submodule');
                        $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
                        $this->db->where('git_permissions.id_role', $user['id_role']);
                        $this->db->where('git_permissions.git_company != ', 'G');
                        $this->db->group_by('git_permissions.name_submodule,git_submodules.url_submodule');

                        $query                                                  =   $this->db->get('git_permissions');

                        if (count($query->result_array()) > 0)
                        {
                            if (isset($user['id_cv']) && $user['id_cv'] != '')
                            {
                                $this->db->select('fet_cv.flag_close, fet_cv.flag_end');
                                $this->db->where('fet_cv.id_cv', $user['id_cv']);

                                $query                                          =   $this->db->get('fet_cv');
                                $flags                                          =   $query->row_array();

                                if ( boolval($flags['flag_end']) ) 
                                {
                                    if ( boolval($flags['flag_close']) )
                                    {
                                        $user['flag_cv']                        =   4;
                                    }
                                    else
                                    {
                                        $user['flag_cv']                        =   1;
                                    }                                
                                }
                                else
                                {
                                    if ( !boolval($flags['flag_close']) )
                                    {
                                        $user['flag_cv']                        =   2;
                                    }
                                }
                            }
                            else
                            {
                                $user['flag_cv']                                =   0;
                            }


                            $result['data']                                     =   TRUE;
                            $result['user']                                     =   $user;
                            $result['message']                                  =   FALSE;
                        }
                        else
                        {
                            $result['data']                                     =   FALSE;
                            $result['message']                                  =   'Usuario y clave correctos, pero sin permisos para ingresar.';
                        }                        
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Usuario no habilitado para acceder.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Tu respuesta no coincide con la respuesta correcta.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'No se pudo encontrar la pregunta de seguridad.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Complete todos los campos.';
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
    public function verify_contributors($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('user', 'Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');

        if($this->form_validation->run())
        {
            $params['type_user']                                                =   'CONTRIBUYENTE';

            $this->db->select('fet_contributors.id_contributor, fet_contributors.password_contributor', FALSE);
            $this->db->where('fet_contributors.user_contributor', $this->_trabajandofet_model->user_name($params['user']));
            $this->db->where('fet_contributors.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_contributors');

            if (count($query->result_array()) > 0)
            {
                $data                                                           =   $query->row_array();

                if (password_verify($params['password'], $data['password_contributor']))
                {
                    $this->db->select('fcsq.id_contributor_security_question, gsq.name_security_question', FALSE);
                    $this->db->join('git_security_questions gsq', 'gsq.id_security_question = fcsq.id_security_question');
                    $this->db->where('fcsq.id_contributor', $data['id_contributor']);
                    $this->db->where('fcsq.flag_drop', 0);
                    $this->db->order_by('RAND()');
                    $this->db->limit(1);

                    $query                                                      =   $this->db->get('fet_contributors_security_questions fcsq');
                    
                    if ($query->row_array()) 
                    {
                        $this->success_login($params);
                        $result['data']                                         =   TRUE;
                        $result['question']                                     =   $query->row_array();
                    }
                    else
                    {
                        $this->fail_login($params);
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'No posee preguntas de seguridad, contacte con el administrador.';
                    }
                }
                else
                {
                    $this->fail_login($params);
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Usuario o clave incorrecta.';
                }
            }
            else
            {
                $this->fail_login($params);
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Usuario o clave incorrecta.';
            }
        }
        else
        {
            $this->fail_login($params);
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Todos los campos son requeridos.';
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
    public function security_contributors($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('id_security_question', 'Id Pregunta', 'required');
        $this->form_validation->set_rules('value_security_question', 'Respuesta', 'required');

        if($this->form_validation->run())
        {
            $this->db->select('fcsq.id_contributor, fcsq.value_security_question', FALSE);
            $this->db->where('fcsq.id_contributor_security_question', $params['id_security_question']);
            $this->db->where('fcsq.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_contributors_security_questions fcsq');

            if ($query->row_array()) 
            {
                $validate                                                       =   $query->row_array();

                if ($validate['value_security_question'] == trim(mb_strtolower($params['value_security_question']))) 
                {
                    $this->db->select('fet_contributors.id_contributor');
                    $this->db->select('CONCAT(fet_contributors.name_contributor, \' \', fet_contributors.first_last_name_contributor, \' \', fet_contributors.second_last_name_contributor) as user');
                    $this->db->select('fet_contributors.user_contributor as alias, fet_contributors.id_role, git_roles.name_role');
                    $this->db->join('git_roles', 'git_roles.id_role = fet_contributors.id_role');
                    $this->db->where('fet_contributors.id_contributor', $validate['id_contributor']);
                    $this->db->where('fet_contributors.flag_drop', 0);

                    $query                                                      =   $this->db->get('fet_contributors');
                    $contributors                                               =   $query->row_array();

                    if (isset($contributors['id_contributor']))
                    {

                        $this->db->select('fet_contributors.id_contributor, fet_businessmans.id_businessman');
                        $this->db->select('CONCAT(fet_contributors.name_contributor, \' \', fet_contributors.first_last_name_contributor, \' \', fet_contributors.second_last_name_contributor) as user');
                        $this->db->select('fet_contributors.user_contributor as alias, fet_businessmans.id_role, git_roles.name_role');
                        $this->db->join('git_roles', 'git_roles.id_role = fet_contributors.id_role');
                        $this->db->where('fet_businessmans.id_contributor', $contributors['id_contributor']);
                        $this->db->where('fet_businessmans.flag_drop', 0);

                        $query                                                  =   $this->db->get('fet_businessmans');
                        $businessman                                            =   $query->row_array();

                        if ($businessman['id_businessman']) 
                        {
                            $user                                               =   $businessman;
                        }
                        else
                        {
                            $user                                               =   $contributors;
                        }

                        $this->db->select('git_permissions.name_submodule, git_submodules.url_submodule');
                        $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
                        $this->db->where('git_permissions.id_role', $user['id_role']);
                        $this->db->where('git_permissions.git_company != ', 'G');
                        $this->db->group_by('git_permissions.name_submodule,git_submodules.url_submodule');

                        $query                                                  =   $this->db->get('git_permissions');

                        if (count($query->result_array()) > 0)
                        {
                            $result['data']                                     =   TRUE;
                            $result['user']                                     =   $user;
                            $result['message']                                  =   FALSE;
                        }
                        else
                        {
                            $result['data']                                     =   FALSE;
                            $result['message']                                  =   'Usuario y clave correctos, pero sin permisos para ingresar.';
                        }
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Usuario no habilitado para acceder.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Tu respuesta no coincide con la respuesta correcta.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'No se pudo encontrar la pregunta de seguridad.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Complete todos los campos.';
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
    public function verify_workers($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('user', 'Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');

        if($this->form_validation->run())
        {
            $params['type_user']                                                =   'USER';

            $this->db->select('fa.id_affiliate, fa.id_cv, fa.id_role, fa.id_project as project, fa.id_jobtitle, fc.number_dcv, CONCAT(fc.name_cv, " ", fc.first_lcv, " ", fc.second_lcv) AS name_affiliate, fas.password_aspirant, gp.id_company as company, gj.name_jobtitle, gr.name_role');
            $this->db->join('git_projects gp', 'gp.id_project = fa.id_project');
            $this->db->join('git_jobtitles gj', 'gj.id_jobtitle = fa.id_jobtitle');
            $this->db->join('git_roles gr', 'gr.id_role = fa.id_role');
            $this->db->join('fet_cv fc', 'fc.id_cv = fa.id_cv', 'left');
            $this->db->join('fet_aspirants fas', 'fas.id_aspirant = fa.id_aspirant');
            $this->db->where('fas.user_aspirant', $this->_trabajandofet_model->user_name($params['user']));
            $this->db->where('fa.flag_display', 1);
            $this->db->where('fa.flag_drop', 0);
            $this->db->where('fa.flag_initialform', 1);

            $query                                                              =   $this->db->get('fet_affiliates fa');

            if (count($query->result_array()) > 0)
            {
                $affiliate                                                      =   $query->row_array();

                $this->db->select('git_permissions.name_submodule,git_submodules.url_submodule');
                $this->db->join('git_submodules', 'git_submodules.id_submodule = git_permissions.id_submodule');
                $this->db->where('git_permissions.id_role', $affiliate['id_role']);
                $this->db->where('git_permissions.git_company != ', 'G');
                $this->db->group_by('git_permissions.name_submodule, git_submodules.url_submodule');

                $query                                                          =   $this->db->get('git_permissions');

                if (count($query->result_array()) > 0)
                {
                    if (password_verify($params['password'], $data['password_aspirant']))
                    {
                        unset($affiliate['password_aspirant']);

                        $submodules                                             =   $query->result_array();

                        if (isset($affiliate['id_cv']) && $affiliate['id_cv'] != '')
                        {
                            $this->db->select('fet_cv.flag_close, fet_cv.flag_end');
                            $this->db->where('fet_cv.id_cv', $affiliate['id_cv']);

                            $query                                              =   $this->db->get('fet_cv');
                            $flags                                              =   $query->row_array();

                            if ( boolval($flags['flag_end']) ) 
                            {
                                if ( boolval($flags['flag_close']) )
                                {
                                    $affiliate['flag_cv']                       =   5;
                                }
                                else
                                {
                                    $affiliate['flag_cv']                       =   1;
                                }                                
                            }
                            else
                            {
                                if ( !boolval($flags['flag_close']) )
                                {
                                    $affiliate['flag_cv']                       =   2;
                                }
                            }
                        }
                        else
                        {
                            $affiliate['flag_cv']                               =   0;
                        }

                        $result['data']                                         =   $affiliate;
                        $result['message']                                      =   FALSE;

                        $this->success_login($params);
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Usuario o clave incorrecta.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Usuario y clave correcta pero no cuentas con permisos para ingresar.';
                }
            }
            else
            {
                $this->fail_login($params);
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Usuario y clave incorrecta o no cuentas con los permisos necesarios para acceder.';
            }
        }
        else
        {
            $this->fail_login($params);
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Todos los campos son requeridos.';
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
    public function verify_exist_aspirants($params)
    {
        $result                                                                 =   array();

        $this->db->select('email_aspirant, user_aspirant, phone_aspirant');
        $this->db->where('flag_drop', 0);

        $this->db->group_start();
        $this->db->where('email_aspirant', trim($params['email_aspirant']));
        $this->db->or_where('user_aspirant', trim($params['user_aspirant']));
        $this->db->or_where('phone_aspirant', trim($params['phone_aspirant']));
        $this->db->group_end();

        $query                                                                  =   $this->db->get('fet_aspirants');

        if (count($query->result_array()) > 0)
        {
            $message                                                            =   ' alguno de estos datos';

            foreach ($query->row_array() as $key => $value)
            {
                switch ($key)
                {
                    case 'email_aspirant':
                        if ($value == trim($params['email_aspirant']))
                        {
                            $message                                            =   ' este correo electrónico.';
                        }
                        break;

                    case 'user_aspirant':
                        if ($value == trim($params['user_aspirant']))
                        {
                            $message                                            =   ' este nombre de usuario.';
                        }
                        break;

                    case 'phone_aspirant':
                        if ($value == trim($params['phone_aspirant']))
                        {
                            $message                                            =   ' este número de celular.';
                        }
                        break;
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe un aspirante con' . $message;
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function register_aspirants($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('name_aspirant', 'Nombres', 'required');
        $this->form_validation->set_rules('first_last_name_aspirant', 'Primer apellido', 'required');
        $this->form_validation->set_rules('second_last_name_aspirant', 'Segundo apellido', 'required');
        $this->form_validation->set_rules('email_aspirant', 'Correo electrónico', 'required');
        $this->form_validation->set_rules('phone_aspirant', 'Teléfono', 'required');
        $this->form_validation->set_rules('user_aspirant', 'Usuario', 'required');
        $this->form_validation->set_rules('password_aspirant', 'Contraseña', 'required');

        $this->form_validation->set_rules('name_security_question[]', 'Preguntas', 'required');
        $this->form_validation->set_rules('value_security_question[]', 'Respuestas', 'required');

        if($this->form_validation->run())
        {
            $this->db->trans_start();

                $data                                                           =   array();

                $data['id_role']                                                =   12; // ROL DE ASPIRANTE
                $data['name_aspirant']                                          =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['name_aspirant'])));
                $data['first_last_name_aspirant']                               =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['first_last_name_aspirant'])));
                $data['second_last_name_aspirant']                              =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['second_last_name_aspirant'])));
                $data['phone_aspirant']                                         =   trim($params['phone_aspirant']);
                $data['email_aspirant']                                         =   $this->_trabajandofet_model->user_name($params['email_aspirant']);
                $data['user_aspirant']                                          =   $this->_trabajandofet_model->user_name($params['user_aspirant']);
                $data['password_aspirant']                                      =   password_hash($params['password_aspirant'], PASSWORD_DEFAULT);

                $answer                                                         =   $this->_trabajandofet_model->insert_data($data, 'fet_aspirants');

                $data2                                                          =   array();

                for ($i = 0; $i < count($params['name_security_question']); $i++) 
                { 
                    $data2[]                                                    =   array(
                                                                                        'id_aspirant'               => $answer, 
                                                                                        'id_security_question'      => $params['name_security_question'][$i],
                                                                                        'value_security_question'   => mb_strtolower(trim($params['value_security_question'][$i]))
                                                                                    );
                }

                $this->db->insert_batch('fet_aspirant_security_questions', $data2);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
            {
                $data_history                                                   =   $data;
                $data_history['id_aspirant']                                    =   $answer;

                $this->_trabajandofet_model->insert_data($data_history, 'fet_aspirants_history');

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Fabuloso! Te has registrado satisfactoriamente.';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas para registrarse.';
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
    public function verify_exist_contributors($params)
    {
        $result                                                                 =   array();

        $this->db->select('email_contributor, user_contributor, phone_contributor');
        $this->db->where('flag_drop', 0);

        $this->db->group_start();
        $this->db->where('email_contributor', trim($params['email_contributor']));
        $this->db->or_where('user_contributor', trim($params['user_contributor']));
        $this->db->or_where('phone_contributor', trim($params['phone_contributor']));
        $this->db->group_end();

        $query                                                                  =   $this->db->get('fet_contributors');

        if (count($query->result_array()) > 0)
        {
            $message                                                            =   ' alguno de estos datos';

            foreach ($query->row_array() as $key => $value)
            {
                switch ($key)
                {
                    case 'email_contributor':
                        if ( $value == trim($params['email_contributor']) )
                        {
                            $message                                            =   ' este correo electrónico.';
                        }
                        break;

                    case 'user_contributor':
                        if ($value == trim($params['user_contributor']))
                        {
                            $message                                            =   ' este nombre de usuario.';
                        }
                        break;

                    case 'phone_contributor':
                        if ($value == trim($params['phone_contributor']))
                        {
                            $message                                            =   ' este número de celular.';
                        }
                        break;
                }
            }

            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Ya existe un empresario con' . $message;
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
    public function cities_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_city AS id, CONCAT(git_cities.name_city, ", ", git_departments.name_department, ", ", git_countries.name_country, "") AS text');
        $this->db->where('git_cities.flag_drop', 0);
        $this->db->join('git_departments', 'git_departments.id_department = git_cities.id_department', 'left');
        $this->db->join('git_countries', 'git_countries.id_country = git_departments.id_country', 'left');

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->like('git_cities.name_city', $params['q']);
        }

        $this->db->order_by('id_city', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_cities');

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
    public function register_contributors($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('name_contributor', 'Nombres', 'required');
        $this->form_validation->set_rules('first_last_name_contributor', 'Primer apellido', 'required');
        $this->form_validation->set_rules('second_last_name_contributor', 'Segundo apellido', 'required');
        $this->form_validation->set_rules('email_contributor', 'Correo electrónico', 'required');
        $this->form_validation->set_rules('phone_contributor', 'Teléfono', 'required');
        $this->form_validation->set_rules('user_contributor', 'Usuario', 'required');
        $this->form_validation->set_rules('password_contributor', 'Contraseña', 'required');
        $this->form_validation->set_rules('name_business_contributor', 'Razón social', 'required');
        $this->form_validation->set_rules('nit_contributor', 'NIT', 'required');
        $this->form_validation->set_rules('economic_sector', 'Sector económico', 'required');

        $this->form_validation->set_rules('name_security_question[]', 'Preguntas', 'required');
        $this->form_validation->set_rules('value_security_question[]', 'Respuestas', 'required');

        if($this->form_validation->run())
        {
            $this->db->trans_start();

            $data                                                               =   array();

            $data['id_role']                                                    =   15; // ROL DE CONTRIBUYENTE
            $data['name_contributor']                                           =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['name_contributor'])));
            $data['first_last_name_contributor']                                =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['first_last_name_contributor'])));
            $data['second_last_name_contributor']                               =   ucwords(mb_strtolower($this->_trabajandofet_model->accents($params['second_last_name_contributor'])));
            $data['phone_contributor']                                          =   trim($params['phone_contributor']);
            $data['email_contributor']                                          =   $this->_trabajandofet_model->user_name($params['email_contributor']);
            $data['user_contributor']                                           =   $this->_trabajandofet_model->user_name($params['user_contributor']);
            $data['password_contributor']                                       =   password_hash($params['password_contributor'], PASSWORD_DEFAULT);

            $answer                                                             =   $this->_trabajandofet_model->insert_data($data, 'fet_contributors');

            if ($answer)
            {   
                $data2                                                          =   array();

                $data2['id_company_group']                                      =   2; // COMPANY GROUP - FET
                $data2['git_company']                                           =   'A';
                $data2['id_contributor']                                        =   $answer;
                $data2['id_city']                                               =   $params['id_city'];

                $this->db->select('git_departments.id_department, git_countries.id_country');
                $this->db->join('git_departments', 'git_departments.id_department = git_cities.id_department', 'left');
                $this->db->join('git_countries', 'git_countries.id_country = git_departments.id_country', 'left');
                $this->db->where('git_cities.id_city', $params['id_city']);

                $query                                                          =   $this->db->get('git_cities');

                $cities_array                                                   =   $query->result_array();

                $data2['id_department']                                         =   $cities_array[0]['id_department'];
                $data2['id_country']                                            =   $cities_array[0]['id_country'];
                $data2['code_company']                                          =   mb_strtoupper($this->_trabajandofet_model->accents($params['code_company']));
                $data2['social_reason_company']                                 =   mb_strtoupper($this->_trabajandofet_model->accents($params['name_business_contributor']));
                $data2['name_company']                                          =   $data2['social_reason_company'];
                $data2['nit_company']                                           =   trim($params['nit_contributor']);
                $data2['checkdigit_company']                                    =   trim($params['checkdigit_company']);
                $data2['economic_sector']                                       =   mb_strtoupper($this->_trabajandofet_model->accents($params['economic_sector']));
                $data2['legal_rc']                                              =   mb_strtoupper($data['name_contributor'] . ' ' . $data['first_last_name_contributor'] . ' ' . $data['second_last_name_contributor']);
                $data2['phone_number_company']                                  =   $data['phone_contributor'];
                $data2['flag_internal']                                         =   0; // EMPRESA EXTERNA AL GRUPO FET

                $answer2                                                        =   $this->_trabajandofet_model->insert_data($data2, 'git_companies');

                if ($answer2)
                {
                    $data_history                                               =   $data2;
                    $data_history['id_company']                                 =   $answer2;
                    $data_history['user_update']                                =   2; //Luis Carlos Muñoz Diaz

                    unset($data_history['git_company']);
                    unset($data_history['user_insert']);

                    $this->_trabajandofet_model->insert_data($data_history, 'git_companies_history');
                }

                $data3                                                          =   array();

                for ($i = 0; $i < count($params['name_security_question']); $i++) 
                { 
                    $data3[]                                                    =   array(
                                                                                        'id_contributor'            => $answer, 
                                                                                        'id_security_question'      => $params['name_security_question'][$i],
                                                                                        'value_security_question'   => mb_strtolower(trim($params['value_security_question'][$i]))
                                                                                    );
                }
            }

            $this->db->insert_batch('fet_contributors_security_questions', $data3);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE)
            {
                $data_history                                                   =   $data;
                $data_history['id_contributor']                                 =   $answer;

                $this->_trabajandofet_model->insert_data($data_history, 'fet_contributors_history');

                $body                                                           =   '<p style="text-align: justify;">Hola, se ha registrado <i>' . $data['name_contributor'] . ' ' . $data['first_last_name_contributor'] . ' ' . $data['second_last_name_contributor'] . '</i> como nuevo contribuyente el día <i>' . date('Y-m-d')  . '</i> a las <i>' . date('h:i a') . '</i> con el correo <b>' . $data['email_contributor'] . '</b>, el usuario <i>' . $data['user_contributor'] . '</i>.';

                $content                                                        =   array(
                                                                                        'of'                    =>  'TRABAJANDOFET',
                                                                                        'title'                 =>  'NUEVO CONTRIBUYENTE REGISTRADO',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '0'
                                                                                    );

                $this->_trabajandofet_model->send_mail('innovacion@fet.co', '✋ [TRABAJANDOFET - NUEVO CONTRIBUYENTE] ' . $data['name_contributor'], $content);


                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Fabuloso! Te has registrado satisfactoriamente.';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Problemas para registrarse.';
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
    public function economic_sector_select($params)
    {
        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('git_parameters.value_parameter');
        $this->db->where('name_parameter', 'ECONOMIC_SECTOR');
        
        $query                                                                  =   $this->db->get('git_parameters');

        $options                                                                =   $query->row_array();
        $options                                                                =   explode(",", $options['value_parameter']);

        if (isset($params['q']) && $params['q'] != '')
        {
            $search                                                             =   preg_quote($params['q'], '~');
            $options                                                            =   preg_grep('~' . strtoupper($search) . '~', $options);
        }

        if (count($options) > 0)
        {
            $result['items']                                                    =   $options;
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
    public function verify_email_admin($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('email_forgot', 'Correo Electrónico', 'required');
        $this->form_validation->set_rules('user_forgot', 'Usuario', 'required');

        if($this->form_validation->run())
        {
            $this->db->select('id_user');
            $this->db->where('user', $params['user_forgot']);
            $this->db->where('email_user', $params['email_forgot']);
            $this->db->where('flag_drop', 0);
            $this->db->where('flag_display', 1);
            $this->db->where('git_company != ', 'G');

            $query                                                              =   $this->db->get('git_users');
 
            if (count($query->result_array()) > 0)
            {
                $data                                                           =   $query->row_array();
                $tmp_password                                                   =   substr(md5(microtime()), 1, 8);
                $data_update                                                    =   array(
                                                                                        'password_user'    =>  password_hash($tmp_password, PASSWORD_DEFAULT),
                                                                                        'id'               =>  $data['id_user']
                                                                                    );

                $update                                                         =   $this->_trabajandofet_model->update_data($data_update, 'id_user', 'git_users');

                if ($update)
                {
                    $body                                                       =   '<p style="text-align: justify;">Hemos recibido una solicitud de cambio de contraseña para tu usuario <b>' 
                                                                                .   $params['user_forgot'] . '</b>.</p>'
                                                                                .   '<p style="text-align: justify;">A continuación te entregamos la nueva contraseña temporal de acceso a la plataforma, '
                                                                                .   'recuerda cambiarla en la menor brevedad posible.</p>'
                                                                                .   '<p>Nueva contraseña:</p>'
                                                                                .   '<p style="text-align: center;"><b>' . $tmp_password . '</b></p>';

                    $content                                                    =   array(
                                                                                        'of'                    =>  'Plataforma',
                                                                                        'title'                 =>  'Recuperación de Contraseña',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                    $send                                                       =   $this->_trabajandofet_model->send_mail($params['email_forgot'], $content['title'], $content);

                    if ($send)
                    {
                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Se ha enviado un correo electrónico de recuperación a ' . $params['email_forgot'];
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al enviar el correo electrónico.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Error en actualización de datos.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Usuario o correo electrónico incorrecto.';
            }

            return $result;
            exit();
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function verify_email_user($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('email_forgot', 'Correo Electrónico', 'required');
        $this->form_validation->set_rules('user_forgot', 'Número de Documento', 'required');

        if($this->form_validation->run())
        {
            $this->db->select('fas.id_aspirant');
            $this->db->join('fet_cv fc', 'fc.id_cv = fa.id_cv', 'left');
            $this->db->join('fet_aspirants fas', 'fas.id_aspirant = fa.id_aspirant', 'left');
            $this->db->where('fc.number_dcv', $params['user_forgot']);
            $this->db->where('fas.email_aspirant', $params['email_forgot']);
            $this->db->where('fa.flag_drop', 0);
            $query                                                              =   $this->db->get('fet_affiliates fa');
 
            if (count($query->result_array()) > 0)
            {
                $data                                                           =   $query->row_array();
                $tmp_password                                                   =   substr(md5(microtime()), 1, 8);
                $data_update                                                    =   array(
                                                                                        'password_aspirant'    =>  password_hash($tmp_password, PASSWORD_DEFAULT),
                                                                                        'id'                    =>  $data['id_aspirant']
                                                                                    );

                $update                                                         =   $this->_trabajandofet_model->update_data($data_update, 'id_aspirant', 'fet_aspirants');

                if ($update)
                {
                    $body                                                       =   '<p style="text-align: justify;">Hemos recibido una solicitud de cambio de contraseña para tu usuario ' 
                                                                                .   $params['user_forgot']
                                                                                .   ' a continuación te escribimos la nueva contraseña temporal de acceso a la plataforma, '
                                                                                .   'recuerda cambiarla en la menor brevedad posible.</p>'
                                                                                .   '<p>Nueva contraseña:</p>'
                                                                                .   '<p style="text-align: center;"><b>' . $tmp_password . '</b></p>';

                    $content                                                    =   array(
                                                                                        'of'                    =>  'Plataforma',
                                                                                        'title'                 =>  'Recuperación de Contraseña',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                    $send                                                       =   $this->_trabajandofet_model->send_mail($params['email_forgot'], $content['title'], $content);

                    if ($send)
                    {
                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Se ha enviado un correo electrónico de recuperación a ' . $params['email_forgot'];
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al enviar el correo electrónico.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Error en actualización de datos.';
                }

            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Identificación o correo electrónico incorrecto.';
            }

            return $result;
            exit();
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function verify_email_aspirant($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('email_forgot', 'Correo Electrónico', 'required');

        if($this->form_validation->run())
        {
            $this->db->select('id_aspirant, user_aspirant');
            $this->db->where('email_aspirant', $params['email_forgot']);
            $this->db->where('flag_drop', 0);

            $query                                                              =   $this->db->get('fet_aspirants');
 
            if (count($query->result_array()) > 0)
            {
                $data                                                           =   $query->row_array();

                $tmp_password                                                   =   substr(md5(microtime()), 1, 8);

                $data_update                                                    =   array(
                                                                                        'password_aspirant'     =>  password_hash($tmp_password, PASSWORD_DEFAULT),
                                                                                        'id'                    =>  $data['id_aspirant']
                                                                                    );

                $update                                                         =   $this->_trabajandofet_model->update_data($data_update, 'id_aspirant', 'fet_aspirants');

                if ($update)
                {
                    $body                                                       =   '<p style="text-align: justify;">Hemos recibido una solicitud de cambio de contraseña para tu usuario <i>' 
                                                                                .   $data['user_aspirant']
                                                                                .   '</i> a continuación te escribimos la nueva contraseña temporal de acceso a la plataforma, '
                                                                                .   'recuerda cambiarla en la menor brevedad posible.</p>'
                                                                                .   '<p>Usuario:</p>'
                                                                                .   '<p style="text-align: center;"><b>' . $data['user_aspirant'] . '</b></p>'
                                                                                .   '<p>Nueva contraseña:</p>'
                                                                                .   '<p style="text-align: center;"><b>' . $tmp_password . '</b></p>';

                    $content                                                    =   array(
                                                                                        'of'                    =>  'Plataforma',
                                                                                        'title'                 =>  'Recuperación de Contraseña',
                                                                                        'body'                  =>  $body,
                                                                                        'login'                 =>  '1',
                                                                                        'url'                   =>  'https://www.trabajandofet.co'
                                                                                    );

                    $send                                                       =   $this->_trabajandofet_model->send_mail($params['email_forgot'], $content['title'], $content);

                    if ($send)
                    {
                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Se ha enviado un correo electrónico de recuperación a ' . $params['email_forgot'];
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al enviar el correo electrónico.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Error en actualización de datos.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Identificación o correo electrónico incorrecto.';
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
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    boolean 
    **/
    public function success_login($params)
    {
        $ip                                                                     =   "";

        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip                                                                 =   $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip                                                                 =   $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip                                                                 =   $_SERVER['REMOTE_ADDR'];
        }

        $data_browser                                                           =   get_browser(NULL, TRUE);
        $browser                                                                =   $data_browser['browser'];
        $device                                                                 =   ($params['mobile'] == "0") ? 'Desktop' : 'Mobile';
        $access_token                                                           =   'e0be6f878377f4';
        $client                                                                 =   new IPinfo($access_token);
        $details                                                                =   $client->getDetails($ip);

        $city                                                                   =   (isset($details->city)) ? ((isset($details->country)) ? '(' . $details->country . ') ' : '') . $details->city : '';
        
        $data                                                                   =   array(
                                                                                        'git_company'                   => 'T',
                                                                                        'type_session_history'          => ucfirst( mb_strtolower($browser) ) .', '. $device,
                                                                                        'location_sh'                   => ucfirst( mb_strtolower($city) ) .' ( '. $ip .' )',
                                                                                        'date_session_history'          => date('Y-m-d h:i:s'),
                                                                                        'information_sh'                => $params['type_user'] . ' Ingresó ('. $params['user'] . ')'
                                                                                    );

        return $this->_trabajandofet_model->insert_data($data, 'git_session_history');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    boolean
    **/
    public function fail_login($params)
    {
        $ip                                                                     =   "";

        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip                                                                 =   $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip                                                                 =   $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip                                                                 =   $_SERVER['REMOTE_ADDR'];
        }

        $data_browser                                                           =   get_browser(NULL, TRUE);
        $browser                                                                =   $data_browser['browser'];
        $device                                                                 =   ($params['mobile'] == "0") ? 'Desktop' : 'Mobile';
        $access_token                                                           =   '0e1909ff12efe6';
        $client                                                                 =   new IPinfo($access_token);
        $details                                                                =   $client->getDetails($ip);

        $city                                                                   =   (isset($details->city)) ? ((isset($details->country)) ? '(' . $details->country . ') ' : '') . $details->city : '';

        $data                                                                   =   array(
                                                                                        'git_company'                   => 'T',
                                                                                        'type_session_history'          => ucfirst( mb_strtolower($browser) ) .', '. $device,
                                                                                        'location_sh'                   => ucfirst( mb_strtolower($city) ) .' ( '. $ip .' )',
                                                                                        'date_session_history'          => date('Y-m-d h:i:s'),
                                                                                        'information_sh'                => $params['type_user'] . ' (' . $params['user'] . (isset($params['password']) ? ' || ' . $params['password'] . ')' : '')
                                                                                    );

        return $this->_trabajandofet_model->insert_data($data, 'git_session_history');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $id_affiliate
    * @return    boolean
    **/
    public function update_keep_alive_user($id_aspirant)
    {
        $params                                                                 =   array(
                                                                                        'date_keepalive'    =>  date('Y-m-d H:i:s'),
                                                                                        'id'                =>  $id_aspirant
                                                                                    );

        return $this->_trabajandofet_model->update_data($params, 'id_aspirant', 'fet_aspirants');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     int $id_user
    * @return    boolean
    **/
    public function update_keep_alive_admin($id_user)
    {
        $params                                                                 =   array(
                                                                                        'date_keepalive'    =>  date('Y-m-d H:i:s'),
                                                                                        'id'                =>  $id_user
                                                                                    );

        return $this->_trabajandofet_model->update_data($params, 'id_user', 'git_users');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param
    * @return    array $user
    **/
    public function get_user()
    {
        if (isset($this->session->userdata['id_aspirant'])) 
        {
            $user                                                               =   $this->_trabajandofet_model->select_single_data('user_aspirant AS user', TRUE, 'id_aspirant', $this->session->userdata['id_aspirant'], 'fet_aspirants');
        }
        elseif (isset($this->session->userdata['id_user'])) 
        {
            $user                                                               =   $this->_trabajandofet_model->select_single_data('user', TRUE, 'id_user', $this->session->userdata['id_user'], 'git_users');
        }
        elseif (isset($this->session->userdata['id_contributor'])) 
        {
            $user                                                               =   $this->_trabajandofet_model->select_single_data('user_contributor AS user', TRUE, 'id_contributor', $this->session->userdata['id_contributor'], 'fet_contributors');
        }
        else
        {
            $user                                                               =   array();
        }

        return $user;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    array $result
    **/
    public function edit($params)
    {
        $result                                                                 =   array();

        $this->form_validation->set_rules('user', 'Usuario', array('required', 'min_length[5]'));
        $this->form_validation->set_rules('password_user', 'Contraseña', 'min_length[8]');

        if($this->form_validation->run())
        {
            if (isset($this->session->userdata['id_aspirant'])) 
            {
                $this->db->select('id_aspirant');
                $this->db->from('fet_aspirants');
                $this->db->where('flag_drop', 0);
                $this->db->where('user_aspirant', trim($params['user']));
                $this->db->where('id_aspirant !=', $this->session->userdata['id_aspirant']);

                $query                                                          =   $this->db->count_all_results();

                if ($query > 1)
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Ya existe este usuario.';

                    return $result;
                    exit();
                }
                else
                {
                    $data                                                       =   array();

                    if (isset($params['password_user']) && $params['password_user'] != '')
                    {
                        $data['password_aspirant']                              =   password_hash($params['password_user'], PASSWORD_DEFAULT);
                    }

                    $data['id']                                                 =   $this->session->userdata['id_aspirant'];
                    $data['user_aspirant']                                      =   $this->_trabajandofet_model->user_name($params['user']);
                    $data['user_update']                                        =   $this->session->userdata['id_aspirant'];
                    $data['date_update']                                        =   date('Y-m-d H:i:s');

                    $update                                                     =   $this->_trabajandofet_model->update_data($data, 'id_aspirant', 'fet_aspirants');

                    if ($update)
                    {
                        $this->session->set_userdata('alias', $params['user']);

                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Acción realizada con éxito!';
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al editar el usuario.';
                    }
                }
            }
            elseif (isset($this->session->userdata['id_user'])) 
            {
                $this->db->select('id_user');
                $this->db->from('git_users');
                $this->db->where('git_company != ', 'G');
                $this->db->where('flag_drop', 0);
                $this->db->where('user', trim($params['user']));
                $this->db->where('id_user !=', $this->session->userdata['id_user']);

                $query                                                          =   $this->db->count_all_results();

                if ($query > 1)
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Ya existe este usuario.';

                    return $result;
                    exit();
                }
                else
                {
                    if (isset($params['password_user']) && $params['password_user'] != '')
                    {
                        $params['password_user']                                =   password_hash($params['password_user'], PASSWORD_DEFAULT);
                    }
                    else
                    {
                        unset($params['password_user']);
                    }

                    $params['id']                                               =   $this->session->userdata['id_user'];
                    $params['user']                                             =   $this->_trabajandofet_model->user_name($params['user']);
                    $params['user_update']                                      =   $this->session->userdata['id_user'];
                    $params['date_update']                                      =   date('Y-m-d H:i:s');

                    $update                                                     =   $this->_trabajandofet_model->update_data($params, 'id_user', 'git_users');

                    if ($update)
                    {
                        $this->session->set_userdata('alias', $params['user']);

                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Acción realizada con éxito!';
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al editar el usuario.';
                    }
                }
            }
            elseif (isset($this->session->userdata['id_contributor'])) 
            {
                $this->db->select('id_contributor');
                $this->db->from('fet_contributors');
                $this->db->where('flag_drop', 0);
                $this->db->where('user_contributor', trim($params['user']));
                $this->db->where('id_contributor !=', $this->session->userdata['id_contributor']);

                $query                                                          =   $this->db->count_all_results();

                if ($query > 1)
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Ya existe este usuario.';

                    return $result;
                    exit();
                }
                else
                {
                    $data                                                       =   array();

                    if (isset($params['password_user']) && $params['password_user'] != '')
                    {
                        $data['password_contributor']                           =   password_hash($params['password_user'], PASSWORD_DEFAULT);
                    }

                    $data['id']                                                 =   $this->session->userdata['id_contributor'];
                    $data['user_contributor']                                   =   $this->_trabajandofet_model->user_name($params['user']);
                    $data['user_update']                                        =   $this->session->userdata['id_contributor'];
                    $data['date_update']                                        =   date('Y-m-d H:i:s');

                    $update                                                     =   $this->_trabajandofet_model->update_data($data, 'id_contributor', 'fet_contributors');

                    if ($update)
                    {
                        $this->session->set_userdata('alias', $params['user']);

                        $result['data']                                         =   TRUE;
                        $result['message']                                      =   'Acción realizada con éxito!';
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Problemas al editar el usuario.';
                    }
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'No hay usuarios en sesión.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Campo requerido y con longitud definida.';
        }

        return $result;
        exit();
    }
}