<?php 
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
}