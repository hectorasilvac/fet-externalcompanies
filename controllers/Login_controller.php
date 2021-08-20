<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fabrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();

        if(isset($this->session->userdata['id_user']) || isset($this->session->userdata['id_aspirant']) || isset($this->session->userdata['id_contributor']))
        {
            header("Location: " . $this->session->userdata['initial_site']);
            exit();
        }

        $this->load->model('login_model', '_login_model');
    }

    /**
	* @author    Innovación y Tecnología
	* @copyright 2021 Fabrica de Desarrollo
	* @since     v2.0.1
	* @param
	* @return    boolean
	**/
    public function view()
    {
        $this->_view->assign('path_admin',                                      site_url('login/admin'));
        $this->_view->assign('path_forgot_user',                                site_url('login/forgotuser'));
        $this->_view->assign('path_forgot_admin',                               site_url('login/forgotadmin'));
        $this->_view->assign('path_forgot_aspirant',                            site_url('login/forgotaspirant'));

        $this->_view->assign('path_security_questions',                         site_url('login/security/questions'));
        $this->_view->assign('path_draw_security_questions',                    site_url('login/security/questions/inputs'));

        $this->_view->assign('path_aspirants',                                  site_url('login/aspirants'));
        $this->_view->assign('path_register_aspirants',                         site_url('login/register/aspirants'));
        $this->_view->assign('path_security_aspirants',                         site_url('login/security/aspirants'));

        $this->_view->assign('path_contributors',                               site_url('login/contributors'));
        $this->_view->assign('path_cities_contributors',                        site_url('login/cities'));
        $this->_view->assign('path_register_contributors',                      site_url('login/register/contributors'));
        $this->_view->assign('path_security_contributors',                      site_url('login/security/contributors'));
        $this->_view->assign('path_economicsector',                             site_url('login/economicsector'));

        $this->_view->assign('path_workers',                                    site_url('login/workers'));

        $this->_view->assign('path_dashboard',                                  site_url('dashboard'));
        $this->_view->assign('path_biomechanical',                              site_url('biomechanicalform'));
        $this->_view->assign('random',                                          random_int(1, 3));
        $this->_view->assign('random_register',                                 random_int(1, 3));

        $this->_view->display('login.tpl');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function security_questions()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $security_questions                                                 =   $this->_login_model->security_questions_select($params);

            echo json_encode($security_questions);
            exit();
        }
        else
        {
            echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            exit();
        }
    }

    public function security_questions_inputs()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            $html                                                               =   $this->_login_model->security_questions_inputs_draw($params);

            echo json_encode($html);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('login'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    
    **/
    public function login_admin()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        $params['mobile']                                                       =   $this->_login_model->verify_device();

        $verify                                                                 =   $this->_login_model->verify_admin($params);

        if($verify['data'])
        {
            $user                                                               =   $verify['data'];

            $user_flags                                                         =   $this->_login_model->user_flags($user['id_user']);

            if (count($user_flags) > 0)
            {
                foreach ($user_flags as $user_flag)
                {
                    $user['flags'][$user_flag['name_flag']]                     =   TRUE;
                }
            }

            $verify_date                                                        =   $this->_login_model->verify_date();

            if($verify_date['data'] == FALSE || $user['id_role'] == 1)
            {
                $user['mobile']                                                 =   $params['mobile'];

                $this->session->set_userdata($user);
                echo json_encode($verify);
                exit();
            }
            else
            {
                echo json_encode($verify_date);
                exit();
            }

        }
        else
        {
            echo json_encode($verify);
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function login_aspirants()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $params['mobile']                                                       =   $this->_login_model->verify_device();

        $verify                                                                 =   $this->_login_model->verify_aspirants($params);

        if($verify['data'])
        {
            $verify_date                                                        =   $this->_login_model->verify_date();

            if($verify_date['data'] == FALSE)
            {
                echo json_encode($verify);
                exit();
            }
            else
            {
                echo json_encode($verify_date);
                exit();
            }
        }
        else
        {
            echo json_encode($verify);
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function security_aspirants()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $verify                                                                 =   $this->_login_model->security_aspirants($params);

        if($verify['data'])
        {
            $verify['user']['mobile']                                           =   $this->_login_model->verify_device();

            $this->session->set_userdata($verify['user']);

            switch ($verify['user']['flag_cv']) 
            {
                case 0:
                    echo json_encode(['location' => site_url('cv')]);
                    break;
                case 1:
                    echo json_encode(['location' => site_url('dashboard')]);
                    break;
                case 2:
                    echo json_encode(['location' => site_url('cv')]);
                    break;
                case 3:
                    echo json_encode(['location' => site_url('dashboard')]);
                    break;
                case 4:
                    echo json_encode(['location' => site_url('dashboard')]);
                    break;
                case 5:
                    echo json_encode(['location' => site_url('biomechanicalform')]);
                    break;
                default:
                    echo json_encode(['location' => site_url('dashboard')]);
                    break;
            }

            exit();
        }
        else
        {
            echo json_encode($verify);
            exit();
        }
    }


    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function login_contributors()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $params['mobile']                                                       =   $this->_login_model->verify_device();

        $verify                                                                 =   $this->_login_model->verify_contributors($params);

        if($verify['data'])
        {
            $verify_date                                                        =   $this->_login_model->verify_date();

            if($verify_date['data'] == FALSE)
            {
                echo json_encode($verify);
                exit();
            }
            else
            {
                echo json_encode($verify_date);
                exit();
            }
        }
        else
        {
            echo json_encode($verify);
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function security_contributors()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $verify                                                                 =   $this->_login_model->security_contributors($params);

        if($verify['data'])
        {
            $verify['user']['mobile']                                           =   $this->_login_model->verify_device();

            $this->session->set_userdata($verify['user']);

            echo json_encode(['location' => site_url('dashboard')]);
            exit();
        }
        else
        {
            echo json_encode($verify);
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function login_workers()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        $params['mobile']                                                       =   $this->_login_model->verify_device();

        $verify                                                                 =   $this->_login_model->verify_workers($params);

        if($verify['data'])
        {
            $user                                                               =   $verify['data'];

            $verify_date                                                        =   $this->_login_model->verify_date();

            if($verify_date['data'] == FALSE || $user['id_role'] == 1)
            {
                $user['mobile']                                                 =   $params['mobile'];

                $this->session->set_userdata($user);

                switch ($user['flag_cv']) 
                {
                    case 0:
                        header("Location: " . site_url('cv'));
                        break;
                    case 1:
                        header("Location: " . site_url('cv'));
                        break;
                    case 2:
                        header("Location: " . site_url('cv'));
                        break;
                    case 3:
                        header("Location: " . site_url('cv'));
                        break;
                    case 4:
                        header("Location: " . site_url('cv'));
                        break;
                    case 5:
                        header("Location: " . site_url('biomechanicalform'));
                        break;
                    default:
                        header("Location: " . site_url('dashboard'));
                        break;
                }

                echo json_encode($verify);
                exit();
            }
            else
            {
                echo json_encode($verify_date);
                exit();
            }
        }
        else
        {
            echo json_encode($verify);
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function register_aspirants()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            $params['mobile']                                                   =   $this->_login_model->verify_device();

            $verify_exist_aspirants                                             =   $this->_login_model->verify_exist_aspirants($params);

            if ($verify_exist_aspirants['data'])
            {
                $register_aspirants                                             =   $this->_login_model->register_aspirants($params);

                echo json_encode($register_aspirants);
                exit();
            }
            else
            {
                echo json_encode($verify_exist_aspirants);
                exit();
            }
        }
        else
        {
            echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json array
    **/
    public function cities()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $cities                                                             =   $this->_login_model->cities_select($params);

            echo json_encode($cities);
            exit();
        }
        else
        {
            echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function register_contributors()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            $params['mobile']                                                   =   $this->_login_model->verify_device();

            $verify_exist_contributors                                          =   $this->_login_model->verify_exist_contributors($params);

            if ($verify_exist_contributors['data'])
            {
                $register_contributors                                          =   $this->_login_model->register_contributors($params);

                echo json_encode($register_contributors);
                exit();
            }
            else
            {
                echo json_encode($verify_exist_contributors);
                exit();
            }
        }
        else
        {
            echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function forgot_admin()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $verify                                                                 =   $this->_login_model->verify_email_admin($params);
        echo json_encode($verify);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function forgot_user()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $verify                                                                 =   $this->_login_model->verify_email_user($params);
        echo json_encode($verify);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return
    **/
    public function forgot_aspirant()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);
        $verify                                                                 =   $this->_login_model->verify_email_aspirant($params);
        echo json_encode($verify);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json array
    **/
    public function economic_sector()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $economic_sector                                                    =   $this->_login_model->economic_sector_select($params);

            echo json_encode($economic_sector);
            exit();
        }
        else
        {
            echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            exit();
        }
    }
}