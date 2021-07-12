<?php 
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Model extends CI_Model
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
    * @param     int $id
    * @return    html
    **/
    public function show_notifications()
    {
        $result                                                                 =   array();
        $html                                                                   =   '';

        if (isset($this->session->userdata['id_user'])) 
        {
            $this->db->query('SET lc_time_names = "es_ES"');

            $this->db->select('git_sgcdocumentsareas.color_sgcdocumentarea, git_sgcdocumentsareas.name_sgcdocumentarea');
            $this->db->select('git_sgcdocuments.id_sgcdocument, git_sgcdocuments.code_sgcdocument, git_sgcdocuments.icon_sgcdocument');
            $this->db->select('DATE_FORMAT(git_sgcdocuments_timeline.date_sgcdocument_timeline, \'%M %d, %Y\') AS date_sgcdocument');
            $this->db->select('owner.name_user AS name_owner');
            $this->db->where('git_sgcdocuments_timeline.id_user', $this->session->userdata['id_user']);
            $this->db->join('git_sgcdocuments', 'git_sgcdocuments.id_sgcdocument = git_sgcdocuments_timeline.id_sgcdocument', 'left');
            $this->db->join('git_users owner', 'owner.id_user = git_sgcdocuments_timeline.id_user', 'left');
            $this->db->join('git_sgcdocumentsareas', 'git_sgcdocumentsareas.id_sgcdocumentarea = owner.id_sgcdocumentarea', 'left');
            $this->db->order_by('git_sgcdocuments_timeline.id_sgcdocument_timeline', 'DESC');
            $this->db->group_by('git_sgcdocuments.id_sgcdocument');

            $query                                                              =   $this->db->get('git_sgcdocuments_timeline');

            if (count($query->result_array()) > 0) 
            {
                foreach ($query->result_array() as $key => $value) 
                {
                    $html .= '<a href="' . site_url('sgcdocuments/details/') . $value['id_sgcdocument'] . '" class="media-list-link read">
                        <div class="media pd-x-20 pd-y-15">
                            <div class="wd-40 ht-40 rounded-circle text-white text-center pd-x-10 pd-y-10" title="' . $value['name_sgcdocumentarea'] . '" style="background-color: ' . $value['color_sgcdocumentarea'] . ';">
                                <i class="' . $value['icon_sgcdocument'] . ' align-middle"></i>
                            </div>
                            <div class="media-body">
                                <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">' . $value['name_owner'] . '</strong> te ha asignado el documento <i>' . $value['code_sgcdocument'] . '</i> para revisión.</p>
                                <span class="tx-12">' . $value['date_sgcdocument'] . '</span>
                            </div>
                        </div>
                    </a>';
                }

                $result['data']                                                 =   TRUE;
                $result['html']                                                 =   $html;
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['html']                                                 =   $html;
            }
        }

        return $result;
        exit();
    }

    public function news()
    {
        $result                                                                 =   array();
        $html                                                                   =   '';

        $this->db->query('SET lc_time_names = "es_ES"');

        $this->db->select('git_news.id_new, git_news.title_new, git_news.description_new, git_news.color_new');
        $this->db->select('DATE_FORMAT(git_news.date_new, \'%M %d, %Y\') AS date_new');
        $this->db->select('git_users.user_insert, git_users.gender_user, git_users.name_user, git_roles.name_role');

        $this->db->join('git_users', 'git_users.id_user = git_news.user_insert', 'left');
        $this->db->join('git_roles', 'git_roles.id_role = git_users.id_role', 'left');
        $this->db->order_by('git_news.id_new', 'DESC');

        $query                                                                  =   $this->db->get('git_news');

        if (count($query->result_array()) > 0) 
        {
            $html .= '<div class="row">
                <div class="col-lg-12">
                    <div class="media-list bg-white rounded shadow-base">';

            foreach ($query->result_array() as $value) 
            {
                $html .= '<div class="media pd-20 pd-xs-30">';

                    if ($value['gender_user'] == 'MASCULINO') 
                    {
                        $html .= '<img src="' . RESOURCES . 'img/profiles/men/1.png" alt="Foto de perfil" class="wd-40 rounded-circle">';
                    }

                    if ($value['gender_user'] == 'FEMENINO') 
                    {
                        $html .= '<img src="' . RESOURCES . 'img/profiles/woman/5.png" alt="Foto de perfil" class="wd-40 rounded-circle">';
                    }

                    $html .= '<div class="media-body mg-l-20">
                        <div class="d-flex justify-content-between mg-b-10">
                            <div>
                                <h6 class="mg-b-2 tx-inverse tx-14">' . $value['title_new'] . '</h6>
                                <span class="tx-12 tx-gray-500">' . ucwords(mb_strtolower($value['name_user'])) . '</span>
                            </div>
                            <span class="tx-12">' . $value['date_new'] . '</span>
                        </div>
                        <p class="mg-b-20">' . $value['description_new'] . '</p>
                        <div class="media-footer">
                            <div class="d-none">
                                <a href="#"><i class="fa fa-heart"></i></a>
                                <a href="#" class="mg-l-10"><i class="fa fa-comment"></i></a>
                                <a href="#" class="mg-l-10"><i class="fa fa-retweet"></i></a>
                                <a href="#" class="mg-l-10"><i class="fa fa-ellipsis-h"></i></a>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            $html .= '</div>
                </div>
            </div>';

            $result['data']                                                     =   TRUE;
            $result['html']                                                     =   $html;
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['html']                                                     =   $html;
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
    public function session_data()
    {
        $result                                                                 = array();

        $this->db->select('fet_aspirants.id_aspirant, fet_aspirants.id_cv, CONCAT(fet_aspirants.name_aspirant, \' \', fet_aspirants.first_last_name_aspirant, \' \', fet_aspirants.second_last_name_aspirant) as user, fet_aspirants.user_aspirant as alias');
        $this->db->select('fet_aspirants.name_aspirant, fet_aspirants.first_last_name_aspirant, fet_aspirants.second_last_name_aspirant, fet_aspirants.phone_aspirant');
        $this->db->select('fet_aspirants.email_aspirant AS email, fet_aspirants.id_role, git_roles.name_role');
        $this->db->select('fet_cv.type_dcv, fet_cv.number_dcv');
        $this->db->where('fet_aspirants.flag_drop', 0);

        if (isset($this->session->userdata['id_worker'])) 
        {
            $this->db->select('fet_workers.id_worker, fet_affiliates.id_affiliate');
            $this->db->join('fet_affiliates', 'fet_affiliates.id_affiliate = fet_workers.id_affiliate', 'left');
            $this->db->join('fet_aspirants', 'fet_aspirants.id_aspirant = fet_affiliates.id_aspirant', 'left');
            $this->db->join('git_roles', 'git_roles.id_role = fet_workers.id_role', 'left');
            $this->db->join('fet_cv', 'fet_cv.id_cv = fet_workers.id_cv', 'left');
            $this->db->where('fet_workers.id_worker', $this->session->userdata['id_worker']);

            $query                                                              =   $this->db->get('fet_workers');
        }
        elseif (isset($this->session->userdata['id_affiliate'])) 
        {
            $this->db->select('fet_affiliates.id_affiliate');
            $this->db->join('fet_aspirants', 'fet_aspirants.id_aspirant = fet_affiliates.id_aspirant', 'left');
            $this->db->join('git_roles', 'git_roles.id_role = fet_affiliates.id_role', 'left');
            $this->db->join('fet_cv', 'fet_cv.id_cv = fet_affiliates.id_cv', 'left');
            $this->db->where('fet_affiliates.id_affiliate', $this->session->userdata['id_affiliate']);

            $query                                                              =   $this->db->get('fet_affiliates');
        }
        else
        {
            $this->db->join('git_roles', 'git_roles.id_role = fet_aspirants.id_role', 'left');
            $this->db->join('fet_cv', 'fet_cv.id_cv = fet_aspirants.id_cv', 'left');
            $this->db->where('fet_aspirants.id_aspirant', $this->session->userdata['id_aspirant']);

            $query                                                              =   $this->db->get('fet_aspirants');
        }

        if ($query->row_array())
        {
            $result                                                             =   $query->row_array();
            $result['name_role']                                                =   ucfirst(mb_strtolower($result['name_role']));
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
    public function session_photo($id)
    {
        if ($id) 
        {
            $this->db->select('fet_cv.photo_cv, fet_cv.gender_cv');
            $this->db->where('fet_cv.id_cv', $id);
            $this->db->where('fet_cv.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_cv');
            $photo                                                              =   $query->row_array();
            $folder                                                             =   array('woman', 'men');
            $key                                                                =   array_rand($folder); 

            if (isset($photo['photo_cv']))
            {
                $file                                                           =   'application/files/cv/profile/' . $photo['photo_cv'];

                if (file_exists($file))
                {
                    $fp                                                         =   fopen($file, 'rb');

                    header("Content-Type: image/jpeg");
                    header("Content-Length: " . filesize($file));

                    fpassthru($fp);
                    exit();
                }
                else
                {
                    if ($photo['gender_cv'] == 'FEMENINO')
                    {
                        $file                                                   =   'resources/img/profiles/woman/' . random_int(1, 5) . '.png';

                        if (file_exists($file)) 
                        {
                            $fp                                                 =   fopen($file, 'rb');

                            header("Content-Type: image/jpeg");
                            header("Content-Length: " . filesize($file));

                            fpassthru($fp);
                            exit();
                        }
                    }
                    elseif ($photo['gender_cv'] == 'MASCULINO')
                    {
                        $file                                                   =   'resources/img/profiles/men/' . random_int(1, 5) . '.png';

                        if (file_exists($file)) 
                        {
                            $fp                                                 =   fopen($file, 'rb');

                            header("Content-Type: image/jpeg");
                            header("Content-Length: " . filesize($file));

                            fpassthru($fp);
                            exit();
                        }
                    }
                    else
                    {
                        $file                                                   =   'resources/img/profile-user.jpg';

                        if (file_exists($file)) 
                        {
                            $fp                                                 =   fopen($file, 'rb');

                            header("Content-Type: image/jpeg");
                            header("Content-Length: " . filesize($file));

                            fpassthru($fp);
                            exit();
                        }
                    }
                }
            }
            else
            {
                $file                                                           =   'resources/img/profile-user.jpg';

                if (file_exists($file)) 
                {
                    $fp                                                         =   fopen($file, 'rb');

                    header("Content-Type: image/jpeg");
                    header("Content-Length: " . filesize($file));

                    fpassthru($fp);
                    exit();
                }
            }
        }
        else
        {
            $file                                                               =   'resources/img/profile-user.jpg';

            if (file_exists($file)) 
            {
                $fp                                                             =   fopen($file, 'rb');

                header("Content-Type: image/jpeg");
                header("Content-Length: " . filesize($file));

                fpassthru($fp);
                exit();
            }
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fabrica de Desarrollo
    * @since     v2.0.1
    * @param     
    * @return    $result
    **/
    public function profile_update($params)
    {
        if(isset($_FILES['photo_cv']['name']) && $_FILES['photo_cv']['name'] != '')
        {
            $this->db->select('fet_aspirants.id_cv, fet_cv.photo_cv');
            $this->db->join('fet_cv', 'fet_cv.id_cv = fet_aspirants.id_cv');
            $this->db->where('fet_aspirants.id_aspirant', $this->session->userdata['id_aspirant']);
            $this->db->where('fet_aspirants.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_aspirants');
            $photo                                                              =   $query->row_array();

            if (isset($photo['id_cv']) && $photo['photo_cv'] != '' && $photo['photo_cv'] != null)
            {
                $dir_photo                                                      =   'application/files/cv/profile';

                if (!file_exists($dir_photo))
                {
                    mkdir($dir_photo, 0755, true);
                }

                $error_image                                                    =   $_FILES['photo_cv']['error'];
                $name_image                                                     =   $_FILES['photo_cv']['name'];
                
                $size_image                                                     =   $_FILES['photo_cv']['size'];
                $tmp_name_image                                                 =   $_FILES['photo_cv']['tmp_name'];

                $image                                                          =   $this->_trabajandofet_model->upload_image($error_image, $name_image, $dir_photo, $size_image, $tmp_name_image, 354, 472, false, true, false);

                if ($image['data'])
                {
                    $file_old                                                   =   'application/files/cv/profile/' . $photo['photo_cv'];

                    if (file_exists($file_old))
                    {
                        @unlink($file_old);
                    }

                    $data                                                       =   array();

                    $data['id']                                                 =   $photo['id_cv'];
                    $data['photo_cv']                                           =   $image['name_image'];

                    $data['aspirant_update']                                    =   $this->session->userdata['id_aspirant'];
                    $data['date_update']                                        =   date('Y-m-d H:i:s');

                    $answer                                                     =   $this->_trabajandofet_model->update_data($data, 'id_cv', 'fet_cv');

                    if (!$answer) 
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Fallo al actualizar la foto de perfil.';

                        return $result;
                        exit();
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'Fallo al subir la foto de perfil.';

                    return $result;
                    exit();
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Por favor actualice su hoja de vida para subir una foto de perfi.';

                return $result;
                exit();                
            }
        }

        $data                                                                   =   array();

        if (isset($params['user_aspirant']) && $params['user_aspirant'] != '') 
        {
            $data['user_aspirant']                                              =   $this->_trabajandofet_model->user_name($params['user_aspirant']);
        }

        if (isset($params['email_aspirant']) && $params['email_aspirant'] != '') 
        {
            $data['email_aspirant']                                             =   $params['email_aspirant'];
        }

        if (isset($params['phone_aspirant']) && $params['phone_aspirant'] != '') 
        {
            $data['phone_aspirant']                                             =   $params['phone_aspirant'];
        }

        if (isset($params['password_aspirant']) && $params['password_aspirant'] != '') 
        {
            $data['password_aspirant']                                          =   password_hash($params['password_aspirant'], PASSWORD_DEFAULT);
        }

        $this->db->trans_start();

            $data['id']                                                         =   $this->session->userdata['id_aspirant'];

            //$data['user_update']                                                =   $this->session->userdata['id_aspirant'];
            $data['date_update']                                                =   date('Y-m-d H:i:s');

            $this->_trabajandofet_model->update_data($data, 'id_aspirant', 'fet_aspirants');

            $this->db->select('fet_aspirants.id_cv');
            $this->db->where('fet_aspirants.id_aspirant', $this->session->userdata['id_aspirant']);
            $this->db->where('fet_aspirants.flag_drop', 0);

            $query                                                              =   $this->db->get('fet_aspirants');
            $cv                                                                 =   $query->row_array();

            if (isset($cv['id_cv']))
            {
                $data2                                                          =   array();

                if (isset($params['email_aspirant']) && $params['email_aspirant'] != '') 
                {
                    $data2['email_ccv']                                         =   $params['email_aspirant'];
                }

                if (isset($params['phone_aspirant']) && $params['phone_aspirant'] != '') 
                {
                    $data2['phone_ccv']                                         =   $params['phone_aspirant'];
                }

                $data2['id']                                                    =   $cv['id_cv'];

                $data2['aspirant_update']                                       =   $this->session->userdata['id_aspirant'];
                $data2['date_update']                                           =   date('Y-m-d H:i:s');

                $this->_trabajandofet_model->update_data($data2, 'id_cv', 'fet_cv');
            }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
        {
            unset($data['id']);
            $data_history                                                       =   $data;
            $data_history['id_aspirant']                                        =   $this->session->userdata['id_aspirant'];

            $this->_trabajandofet_model->insert_data($data_history, 'fet_aspirants_history');

            $result['data']                                                     =   TRUE;
            $result['message']                                                  =   'Datos actualizados!';
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Datos actualizados!';
        }

        return $result;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @return    array $result
    **/
    public function supports_cv($params)
    {
        $result                                                                 =   array();
        $html                                                                   =   '';

        $this->db->select('fet_cv_supports.id_cv_support, fet_checklist_items.name_checklist_item');
        $this->db->where('fet_cv_supports.id_cv', $params['id_cv']);
        $this->db->join('fet_checklist_items', 'fet_checklist_items.id_checklist_item = fet_cv_supports.id_checklist_item', 'left');

        $query                                                                  =   $this->db->get('fet_cv_supports');
        $supports                                                               =   $query->result_array();

        if (count($supports) > 0) 
        {
            $header_mobile                                                      = '';
            $header                                                             = '';
            $body                                                               = '';

            foreach ($supports as $key => $value) 
            {
                if ($key > 0) 
                {
                    $header_mobile .= '<a class="nav-link" href="' . site_url('dashboard/supportsfiles/?id_cv_support=') . $value['id_cv_support'] . '">' . $value['name_checklist_item'] . '</a>';
                    $header .= '<a class="nav-link" id="v-pills-' . $value['id_cv_support'] . '-tab" data-id="' . $value['id_cv_support'] . '" data-toggle="pill" href="#v-pills-' . $value['id_cv_support'] . '" role="tab" aria-controls="v-pills-' . $value['id_cv_support'] . '" aria-selected="true">' . $value['name_checklist_item'] . '</a>';
                    $body .= '<div class="tab-pane fade" id="v-pills-' . $value['id_cv_support'] . '" role="tabpanel" aria-labelledby="v-pills-' . $value['id_cv_support'] . '-tab"></div>';
                }
                else
                {
                    $header_mobile .= '<a class="nav-link" href="' . site_url('dashboard/supportsfiles/?id_cv_support=') . $value['id_cv_support'] . '">' . $value['name_checklist_item'] . '</a>';
                    $header .= '<a class="nav-link active" id="v-pills-' . $value['id_cv_support'] . '-tab" data-id="' . $value['id_cv_support'] . '" data-toggle="pill" href="#v-pills-' . $value['id_cv_support'] . '" role="tab" aria-controls="v-pills-' . $value['id_cv_support'] . '" aria-selected="true">' . $value['name_checklist_item'] . '</a>';
                    $body .= '<div class="tab-pane fade active show" id="v-pills-' . $value['id_cv_support'] . '" role="tabpanel" aria-labelledby="v-pills-' . $value['id_cv_support'] . '-tab">
                        <iframe id="support-pdf' . $value['id_cv_support'] . '" class="iframe-pdf" src="' . site_url('dashboard/supportsfiles/?id_cv_support=') . $value['id_cv_support'] . '" width="100%" height="800" frameborder="0"></iframe>
                    </div>';
                }
            }

            if ($this->session->userdata['mobile']) 
            {
                $html = '<div class="row">
                    <div class="col-lg-12">
                        <div class="card pd-20 shadow-base bd-0">
                            <div class="nav flex-column nav-pills d-block" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                ' . $header_mobile . '
                            </div>
                        </div>
                    </div>
                </div>';
            }
            else
            {
                $html = '<div class="row">
                    <div class="col-lg-4">
                        <div class="card pd-20 shadow-base bd-0">
                            <div class="nav flex-column nav-pills d-block" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                ' . $header . '
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="media-list bg-white rounded shadow-base">
                            <div class="media pd-20 pd-xs-30">
                                <div class="media-body">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        ' . $body . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            $result['data']                                                     =   TRUE;
            $result['html']                                                     =   $html;
            $result['message']                                                  =   'Soportes cargados con exito!';
        }
        else
        {

            $html = '<div class="row">
                <div class="col-lg-12 mg-t-30 mg-lg-t-0">
                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 tx-center">
                        <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">No hay soportes disponibles, deseas actualizar tu hoja de vida?</h6>
                        <a href="' . site_url('cv') . '" class="btn btn-info">Actualizar Hoja de Vida</a>
                    </div>
                </div>
            </div>';

            $result['data']                                                     =   FALSE;
            $result['html']                                                     =   $html;
            $result['message']                                                  =   'No se encontraron soportes soportes.';
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
    public function supports_files($params)
    {
        $result                                                                 =   array();

        $this->db->select('fet_cv_supports.name_cv_support, fet_checklist_items.name_checklist_item');
        $this->db->where('fet_cv_supports.id_cv_support', $params['id_cv_support']);
        $this->db->join('fet_checklist_items', 'fet_checklist_items.id_checklist_item = fet_cv_supports.id_checklist_item', 'left');

        $query                                                                  =   $this->db->get('fet_cv_supports');
        $document                                                               =   $query->row_array();

        if ($document) 
        {
            $file                                                               =   'application/files/cv/cv_supports/' . $document['name_cv_support'];

            if (file_exists($file)) 
            {
                if ($this->session->userdata['mobile']) 
                {
                    $data                                                       =   file_get_contents($file);
                    force_download($document['name_cv_support'], $data);
                }
                else
                {
                    header("Content-Type: application/pdf");
                    readfile($file);
                }

                $result['data']                                                 =   TRUE;
                $result['message']                                              =   'Soportes cargados con exito!';
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'No existe el soporte.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Fallo al cargar los soportes.';
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
    public function request_card($params)
    {
        $result                                                                 =   array();
        
        $this->db->select('id_cv, id_project, id_jobtitle');
        $this->db->where('flag_drop', 0);
        $this->db->where('id_worker', $params['id_worker']);

        $query                                                                  =   $this->db->get('fet_workers');
        $worker                                                                 =   $query->row_array();

        if (isset($worker['id_cv']) && $worker['id_cv'] != '') 
        {
            $this->db->select('git_companies.code_company, git_projects.name_project');
            $this->db->where('git_projects.flag_drop', 0);
            $this->db->where('git_companies.flag_drop', 0);
            $this->db->where('git_projects.id_project', $worker['id_project']);
            $this->db->join('git_companies', 'git_companies.id_company = git_projects.id_company', 'left');

            $query                                                              =   $this->db->get('git_projects');
            $company                                                            =   $query->row_array();

            if (!is_null($company))
            {

                $this->db->select('git_jobtitles.name_jobtitle');
                $this->db->where('git_jobtitles.flag_drop', 0);
                $this->db->where('git_jobtitles.id_jobtitle', $worker['id_jobtitle']);

                $query                                                          =   $this->db->get('git_jobtitles');
                $jobtitle                                                       =   $query->row_array();

                if (!is_null($jobtitle))
                {
                    $this->db->select('id_carnet, count_carnet, flag_delivery');
                    $this->db->where('flag_drop', 0);
                    $this->db->where('id_worker', $params['id_worker']);
                    $this->db->like('name_company', $company['code_company']);

                    $query                                                      =   $this->db->get('fet_carnets');
                    $carnet                                                     =   $query->row_array();

                    if (is_null($carnet))
                    {
                        $data                                                   =   array();

                        $data['id_worker']                                      =   $params['id_worker'];
                        $data['name_company']                                   =   $company['code_company'];
                        $data['name_project']                                   =   $company['name_project'];
                        $data['name_jobtitle']                                  =   $jobtitle['name_jobtitle'];
                        $data['count_carnet']                                   =   1;
                        $data['user_insert']                                    =   8;

                        $answer                                                 =   $this->_trabajandofet_model->insert_data($data, 'fet_carnets');
                    }
                    else
                    {
                        if (boolval($carnet['flag_delivery'])) 
                        {
                            $data                                               =   array();

                            $data['id']                                         =   $carnet['id_carnet'];

                            $data['flag_talent']                                =   0;
                            $data['flag_design']                                =   0;
                            $data['flag_purchases']                             =   0;                            
                            $data['flag_delivery']                              =   0;

                            $data['count_carnet']                               =   intval($carnet['count_carnet']) + 1;
                            $data['user_update']                                =   8;
                            $data['date_update']                                =   date('Y-m-d H:i:s');

                            $answer                                             =   $this->_trabajandofet_model->update_data($data, 'id_carnet', 'fet_carnets');
                        }
                        else
                        {
                            $result['data']                                     =   FALSE;
                            $result['message']                                  =   'Ya tiene un carnet en proceso.';

                            return $result;
                            exit();
                        }
                    }

                    if ($answer) 
                    {
                        $this->db->where('flag_delivery', 0);
                        $this->db->where('flag_drop', 0);

                        $query                                                  =   $this->db->get('fet_carnets');
                        $n_carnets                                              =   $query->num_rows();

                        if($n_carnets >= 40)
                        {
                            $result['data']                                     =   TRUE;
                            $result['message']                                  =   'Su carnet sera solicitado, pero ten en cuenta que no será obligatoria su generación y entrega esto a causa del limite de carnets semanales definido en el procedimiento de carnets.';
                        }
                        else
                        {
                            $result['data']                                     =   TRUE;
                            $result['message']                                  =   'Solicitud realizada correctamente!';
                        }
                    }
                    else 
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'No se puede crear la solicitud.';
                    }
                }
                else
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'No tienes un cargo asociado, por favor actualiza tu hoja de vida.';
                }
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'No tienes una empresa asociada, por favor actualiza tu hoja de vida.';
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Por favor actualice su hoja de vida.';
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
    public function companies_select($params)
    {
        $result                                                                 =   array();

        $page                                                                   =   $params['page'];
        $range                                                                  =   10;

        $start                                                                  =   ($page - 1) * $range;
        $limit                                                                  =   $start + $range;

        $this->db->select('id_company AS id, CONCAT(code_company, " (", nit_company, "-", checkdigit_company, ")") AS text');
        $this->db->where('git_company != ', 'G');
        $this->db->where('flag_association_active', 1);
        $this->db->where('flag_internal', 1);
        $this->db->where('flag_drop', 0);

        if (isset($params['q']) && $params['q'] != '')
        {
            $this->db->group_start();
            $this->db->like('code_company', $params['q']);
            $this->db->or_like('name_company', $params['q']);
            $this->db->or_like('nit_company', $params['q']);
            $this->db->group_end();
        }

        $this->db->order_by('code_company', 'asc');
        $this->db->limit($limit, $start);

        $query                                                                  =   $this->db->get('git_companies');

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
    public function projects_select($params)
    {
        $result                                                                 =   array(
            'items'                                                                     =>  array()
                                                                                    );
        if (isset($params['id']) && $params['id'] != '')
        {
            $page                                                                   =   $params['page'];
            $range                                                                  =   10;

            $start                                                                  =   ($page - 1) * $range;
            $limit                                                                  =   $start + $range;

            $this->db->select('id_project AS id, CONCAT(name_project, " - ", neighborhood_project) AS text');
            $this->db->where('git_company != ', 'G');
            $this->db->where('id_company', $params['id']);
            $this->db->where('flag_drop', 0);

            if (isset($params['q']) && $params['q'] != '')
            {
                $this->db->group_start();
                $this->db->like('name_project', $params['q']);
                $this->db->or_like('neighborhood_project', $params['q']);
                $this->db->group_end();
            }

            $this->db->order_by('name_project', 'asc');
            $this->db->limit($limit, $start);

            $query                                                                  =   $this->db->get('git_projects');

            $result['total_count']                                                  =   $query->num_rows();

            if ($result['total_count'] > 0)
            {
                $result['items']                                                    =   $query->result_array();
            }
        }

        return $result;
        exit();
    }
}