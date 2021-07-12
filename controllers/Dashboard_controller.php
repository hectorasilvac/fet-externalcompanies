<?php
/**
*@author 	Innovación y Tecnología
*@copyright 2021 Fabrica de Desarrollo
*@version 	v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_controller extends CI_Controller 
{
	private $actions;

    public function __construct()
    {
        parent::__construct();

        if(isset($this->session->userdata['id_user']) || isset($this->session->userdata['id_aspirant']) || isset($this->session->userdata['id_contributor']))
        { 
            $this->load->model('dashboard_model', '_dashboard_model');
            $this->load->model('login_model', '_login_model');
            $this->actions                                                      =   $this->_dashboard_model->actions_by_role($this->session->userdata['id_role'], 'DASHBOARD');
            $this->breadcrumb                                                   =   $this->_dashboard_model->get_breadcrumb('DASHBOARD');

            if($this->session->userdata['id_role'] != "11")
            {
                if(in_array('DASHBOARD', $this->session->userdata['modules']) == FALSE)
                {
                    header("Location: " . $this->session->userdata['initial_site']);
                    exit();
                }
            }            
        }
        else
        {
            header("Location: " . site_url('login'));
            exit();

        }
    }

	/**
	*@author 	Innovación y Tecnología
	*@copyright 2021 Fabrica de Desarrollo
	*@since 	v2.0.1
	*@param
	*@return
	**/
	public function view()
	{
        if(in_array('VIEW', $this->actions))
        {
            if ($this->breadcrumb != FALSE)
            {
                $this->_view->assign('module_layout',                           $this->breadcrumb['name_es_module']);
                $this->_view->assign('submodule_layout',                        $this->breadcrumb['name_es_submodule']);
                $this->_view->assign('module_active',                           '"' . $this->breadcrumb['name_module']);
                $this->_view->assign('submodule_active',                        '"' . $this->breadcrumb['url_submodule']);
            }
            else
            {
                $this->_view->assign('module_layout',                           '');
                $this->_view->assign('submodule_layout',                        '');
                $this->_view->assign('module_active',                           'noreplace');
                $this->_view->assign('submodule_active',                        'noreplace');
            }

            $this->_view->assign('session_photo',                               site_url('dashboard/sessionphoto'));
            $this->_view->assign('profile_update',                              site_url('dashboard/profileupdate'));
            $this->_view->assign('path_supports_cv',                            site_url('dashboard/supportscv'));
            $this->_view->assign('path_supports_files',                         site_url('dashboard/supportsfiles'));
            $this->_view->assign('path_news',                                   site_url('dashboard/news'));
            $this->_view->assign('path_request_card',                           site_url('dashboard/requestcard'));
            $this->_view->assign('path_companies',                              site_url('dashboard/companies'));
            $this->_view->assign('path_projects',                               site_url('dashboard/projects'));
            $this->_view->assign('path_company_project',                        site_url('dashboard/companyproject'));
            $this->_view->assign('user_company',                                isset($this->session->userdata['user_company'])? $this->session->userdata['user_company']: '');
            $this->_view->assign('user_project',                                isset($this->session->userdata['user_project'])? $this->session->userdata['user_project']: '');
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . $this->session->userdata['initial_site']);
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'No cuentas con los permisos necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }

        if (isset($this->session->userdata['id_user'])) 
        {
            $this->_view->display('admin/dashboard.tpl');
        }
        elseif (isset($this->session->userdata['id_aspirant'])) 
        {
            $this->_view->assign('sessiondata',                                 $this->_dashboard_model->session_data());

            $this->_view->display('admin/dashboard_aspirant.tpl');
        }
        elseif (isset($this->session->userdata['id_contributor'])) 
        {
            $this->_view->display('admin/dashboard_contributor.tpl');
        }
        else
        {
            header("Location: " . $this->session->userdata['initial_site']);
        }
	}

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param
    *@return
    **/
    public function notifications()
    {
        $notifications                                                          =   $this->_dashboard_model->show_notifications();

        echo json_encode($notifications);
        exit();        
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param
    *@return
    **/
    public function logout()
    {
        $this->session->sess_destroy();
        header("Location: " . site_url('login'));
        exit();
    }

    public function news()
    {
        $news                                                               =   $this->_dashboard_model->news();

        echo json_encode($news);
        exit();
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param
    *@return
    **/
    public function session_photo($id = null)
    {
        $photo                                                                  =   $this->_dashboard_model->session_photo($id);
        exit();
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param
    *@return
    **/
    public function profile_update()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            $profile                                                            =   $this->_dashboard_model->profile_update($params);

            echo json_encode($profile);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2021 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param
    *@return
    **/
    public function request_card()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            $card                                                               =   $this->_dashboard_model->request_card($params);

            echo json_encode($card);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2020 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param     array $param
    *@return    json array
    **/
    public function supports_cv()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $supports                                                           =   $this->_dashboard_model->supports_cv($params);

            echo json_encode($supports);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }

    /**
    *@author    Innovación y Tecnología
    *@copyright 2020 Fabrica de Desarrollo
    *@since     v2.0.1
    *@param     array $param
    *@return    json array
    **/
    public function supports_files()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $files                                                              =   $this->_dashboard_model->supports_files($params);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
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
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json $companies
    **/
    public function companies()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $companies                                                          =   $this->_dashboard_model->companies_select($params);

            echo json_encode($companies);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
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
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json $projects
    **/
    public function projects()
    {
        $params                                                                 =   $this->security->xss_clean($_GET);

        if ($params)
        {
            $projects                                                           =   $this->_dashboard_model->projects_select($params);

            echo json_encode($projects);
            exit();
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
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
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params
    * @return    json $projects
    **/
    public function company_project()
    {
        $params                                                                 =   $this->security->xss_clean($_POST);

        if ($params)
        {
            if (isset($params['id_company']) && $params['id_company'] != '')
            {
                $this->session->set_userdata(array('user_company' => $params['id_company'], 'user_company_text' => $params['text_company']));
            }

            if (isset($params['id_project']) && $params['id_project'] != '')
            {
                $this->session->set_userdata(array('user_project' => $params['id_project'], 'user_project_text' => $params['text_project']));
            }

            echo json_encode(array('data'=> TRUE, 'message' => 'Selección realizada!'));
        }
        else
        {
            if ($this->input->method(TRUE) == 'GET')
            {
                header("Location: " . site_url('dashboard'));
            }
            else
            {
                echo json_encode(array('data'=> FALSE, 'message' => 'Los campos enviados no corresponden a los necesarios para ejecutar esta solicitud.'));
            }

            exit();
        }
    }
}
