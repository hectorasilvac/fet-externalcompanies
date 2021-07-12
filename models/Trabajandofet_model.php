<?php
/**
* @author    Innovación y Tecnología
* @copyright 2021 Fábrica de Desarrollo
* @version   v 2.0
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Trabajandofet_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $fields, boolean $drop, string $table, string $order, string $dir 
    * @return    array
    **/
    public function select_data($fields, $drop, $table, $order, $dir)
    {
        $this->db->select($fields);

        if ($drop)
        {
            $this->db->where('flag_drop', 0);
        }

        $prefix                                                                 =   explode('_', $table);

        if ($prefix[0] == 'git')
        {
            $this->db->where('git_company != ', 'G');
        }

        $this->db->order_by($order, $dir);
        $query                                                                  =   $this->db->get($table);
        return $query->result_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $fields, boolean $drop, string $where_field, string | int $where_value, string $table
    * @return    array $query->row_array()
    **/
    public function select_single_data($fields, $drop, $where_field, $where_value, $table)
    {
        $this->db->select($fields);

        if ($drop)
        {
            $this->db->where('flag_drop', 0);
        }

        $prefix                                                                 =   explode('_', $table);

        if ($prefix[0] == 'git')
        {
            $this->db->where($table . '.git_company != ', 'G');
        }

        $this->db->where($where_field, $where_value);
        $this->db->limit(1);

        $query                                                                  =   $this->db->get($table);
        return $query->row_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params, string $table
    * @return    boolean | int
    **/
    public function insert_data($params, $table)
    {
        $result                                                                 =   $this->db->insert($table, $params);

        if ($result)
        {
            if ($this->db->insert_id() == 0)
            {
                return true;
            }
            else
            {
                return $this->db->insert_id();
            }
        }

        return false;
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     array $params, string $name_id, string $table
    * @return    boolean
    **/
    public function update_data($params, $name_id, $table)
    {
        $id                                                                     =   $params['id'];
        unset($params['id']); 
        $this->db->where($name_id, $id);
        return $this->db->update($table, $params);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $name_id, int $value_id, string $table
    * @return    boolean 
    **/
    public function udrop_data($name_id, $value_id, $table, $params)
    {
        $data                                                                   =   array();

        if (is_array($params))
        {
            $data                                                               =   $params;
        }
        else
        {
            $data                                                               =   array(
                'flag_drop'                                                             =>  1,
                'user_update'                                                           =>  $this->session->userdata['id_user'],
                'date_update'                                                           =>  date('Y-m-d H:i:s')
                                                                                    );
        }

        $this->db->where($name_id, $value_id);

        return $this->db->update($table, $data);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     int $role, varchar $submodule
    * @return    $array
    **/
    public function drop_data($data, $table)
    {
        return $this->db->delete($table, $data);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $text
    * @return    string
    **/
    public function accents($text)
    {
        $clean_txt                                                              =   mb_ereg_replace('[^A-Za-z0-9áéíóúÁÉÍÓÚ\ \.\,\;\:\-\/\ñ\Ñ\#]', '', $text);
        return trim($clean_txt);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $text
    * @return    string
    **/
    public function no_accents($text_accents)
    {
        $no_allowed                                                             =   array ( 'á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','À','Ã','Ì','Ò','Ù',
                                                                                            'Ã™','Ã ','Ã¨','Ã¬','Ã²','Ã¹','ç','Ç','Ã¢','ê','Ã®','Ã´','Ã','Ã‚',
                                                                                            'ÃŠ','ÃŽ','Ã”','Ã›','ü','Ã¶','Ã–','Ã¯','Ã¤','Ò','Ã','Ã„',
                                                                                            'Ã‹','Ñ','à','è','ì','ò','ù');

        $allowed                                                                =   array ( 'a','e','i','o','u','A','E','I','O','U','n','N','A','E','I','O',
                                                                                            'U','a','e','i','o','u','c','C','a','e','i','o','u','A','E','I',
                                                                                            'O','U','u','o','O','i','a','e','U','I','A','E','N','a','e','i',
                                                                                            'o','u');

        $text                                                                   =   str_replace($no_allowed, $allowed ,$text_accents);

        return trim($text);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $text
    * @return    string
    **/
    public function clean_text($text)
    {
        $search                                                                 =   array('ñ', 'Ñ');
        $replace                                                                =   array('n', 'N');
        $new_text                                                               =   str_replace($search, $replace, $text);
        $clean_txt                                                              =   mb_ereg_replace('[^A-Za-z0-9\ \.]', '', $new_text);
        return trim($clean_txt);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $text
    * @return    string
    **/
    public function serial_clean($text_accents)
    {
        $no_allowed                                                             =   array ( 'á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','À','Ã','Ì','Ò','Ù',
                                                                                            'Ã™','Ã ','Ã¨','Ã¬','Ã²','Ã¹','ç','Ç','Ã¢','ê','Ã®','Ã´','Ã','Ã‚',
                                                                                            'ÃŠ','ÃŽ','Ã”','Ã›','ü','Ã¶','Ã–','Ã¯','Ã¤','Ò','Ã','Ã„',
                                                                                            'Ã‹','Ñ','à','è','ì','ò','ù');

        $allowed                                                                =   array ( 'a','e','i','o','u','A','E','I','O','U','n','N','A','E','I','O',
                                                                                            'U','a','e','i','o','u','c','C','a','e','i','o','u','A','E','I',
                                                                                            'O','U','u','o','O','i','a','e','U','I','A','E','N','a','e','i',
                                                                                            'o','u');

        $text                                                                   =   str_replace($no_allowed, $allowed, $text_accents);
        $clean_txt                                                              =   mb_ereg_replace('[^A-Za-z0-9\ \-]', '', $text);

        return mb_strtoupper(trim($text), 'UTF-8');
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $param
    * @return    string
    **/
    public function uc_first($param)
    {
        if ($param != 'y' && $param != 'en' && $param != 'de' && $param != 'la' && $param != 'el' && $param != 'del')
        {
            return ucfirst($param);
        }
        else
        {
            return $param;
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $text
    * @return    string
    **/
    public function to_camel($text)
    {
        $text_lower                                                             =   mb_strtolower($text);
        $text_array                                                             =   explode(' ', $text_lower);
        $array_map                                                              =   array_map(array($this, 'uc_first'), $text_array);
        $result                                                                 =   implode(' ', $array_map);

        return trim($result);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function trace_insert_register($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                    CONCAT(DATE_FORMAT(A.date_insert, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_insert, "%h:%i:%s %p"))) AS date_insert,
                                                                                    (SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv) AS name_affiliate FROM fet_affiliates B LEFT JOIN fet_cv C ON B.id_cv = C.id_cv  WHERE B.id_affiliate = A.affiliate_insert) AS user_insert
                                                                                    FROM ' . $table . ' A 
                                                                                    WHERE A.' . $id_table . ' = ' . $id;

        $query                                                                  =   $this->db->query($sql);

        return $query->row_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function trace_register($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                    IFNULL(CONCAT(DATE_FORMAT(A.date_insert, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_insert, "%h:%i:%s %p"))), \'NO APLICA\') AS date_insert,
                                                                                    IFNULL(CONCAT(DATE_FORMAT(A.date_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_update, "%h:%i:%s %p"))), \'NO APLICA\') AS date_update,
                                                                                    IFNULL((SELECT CONCAT(B.name_user, " ", B.lastname_user) FROM git_users B WHERE B.id_user = A.user_insert), \'NO APLICA\') AS user_insert, 
                                                                                    IFNULL((SELECT CONCAT(C.name_user, " ", C.lastname_user) FROM git_users C WHERE C.id_user = A.user_update), \'NO APLICA\') AS user_update 
                                                                                    FROM ' . $table . ' A 
                                                                                    WHERE A.' . $id_table . ' = ' . $id;

        $prefix                                                                 =   explode('_', $table);

        $query                                                                  =   $this->db->query($sql);

        return $query->row_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function global_trace_register($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                    IFNULL(CONCAT(DATE_FORMAT(A.date_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_update, "%h:%i:%s %p"))), \'NO APLICA\') AS date_update,
                                                                                    IFNULL((SELECT CONCAT(C.name_user, " ", C.lastname_user) FROM git_users C WHERE C.id_user = A.user_update), \'NO APLICA\') AS user_update 
                                                                                    FROM ' . $table . ' A 
                                                                                    WHERE A.' . $id_table . ' = ' . $id . ' 
                                                                                    ORDER BY date_update DESC';

        $query                                                                  =   $this->db->query($sql);

        return $query->result_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function trace_register_external($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                    IFNULL(CONCAT(DATE_FORMAT(A.date_insert, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_insert, "%h:%i:%s %p"))), \'NO APLICA\') AS date_insert,
                                                                                    IFNULL(CONCAT(DATE_FORMAT(A.date_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_update, "%h:%i:%s %p"))), \'NO APLICA\') AS date_update,
                                                                                    IFNULL((SELECT CONCAT(C.name_user, " ", C.lastname_user) FROM git_users C WHERE C.id_user = A.user_update), \'NO APLICA\') AS user_update 
                                                                                    FROM ' . $table . ' A 
                                                                                    WHERE A.' . $id_table . ' = ' . $id;

        $prefix                                                                 =   explode('_', $table);

        if ($prefix[0] == 'git')
        {
            $sql                                                                .=  ' AND A.git_company != "G"';
        }

        $query                                                                  =   $this->db->query($sql);

        return $query->row_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function global_trace_register_external($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                    IFNULL(CONCAT(DATE_FORMAT(A.date_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_update, "%h:%i:%s %p"))), \'NO APLICA\') AS date_update,
                                                                                    IFNULL((SELECT CONCAT(C.name_user, " ", C.lastname_user) FROM git_users C WHERE C.id_user = A.user_update), \'NO APLICA\') AS user_update 
                                                                                    FROM ' . $table . ' A 
                                                                                    WHERE A.' . $id_table . ' = ' . $id . ' 
                                                                                    ORDER BY date_update DESC';

        $query                                                                  =   $this->db->query($sql);

        return $query->result_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function trace_register_shiftchange($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                     IF(CONCAT(DATE_FORMAT(A.date_worker_insert, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_worker_insert, "%h:%i:%s %p"))) IS NULL,
                                                                                     CONCAT(DATE_FORMAT(A.date_insert, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_insert, "%h:%i:%s %p"))),
                                                                                     CONCAT(DATE_FORMAT(A.date_worker_insert, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_worker_insert, "%h:%i:%s %p")))) AS date_insert,
                                                                                     IF(CONCAT(DATE_FORMAT(A.date_worker_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_worker_update, "%h:%i:%s %p"))) IS NULL,
                                                                                     CONCAT(DATE_FORMAT(A.date_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_update, "%h:%i:%s %p"))),
                                                                                     CONCAT(DATE_FORMAT(A.date_worker_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_worker_update, "%h:%i:%s %p")))) AS date_update,
                                                                                     IF((SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv) AS name_worker FROM fet_workers B LEFT JOIN fet_cv C ON C.id_cv = B.id_cv WHERE B.id_worker = A.worker_insert) IS NULL, 
                                                                                     UPPER((SELECT CONCAT(D.name_user, " ", D.lastname_user, "<br>USUARIO") FROM git_users D WHERE D.id_user = A.user_insert)), 
                                                                                     UPPER((SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv, "<br>TRABAJADOR") FROM fet_workers B LEFT JOIN fet_cv C ON C.id_cv = B.id_cv WHERE B.id_worker = A.worker_insert))) AS user_insert,
                                                                                     IF((SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv) AS name_affiliate FROM fet_workers B LEFT JOIN fet_cv C ON C.id_cv = B.id_cv WHERE B.id_worker = A.worker_update) IS NULL, 
                                                                                     UPPER((SELECT CONCAT(C.name_user, " ", C.lastname_user, "<br>USUARIO") FROM git_users C WHERE C.id_user = A.user_update)), 
                                                                                     UPPER((SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv, "<br>TRABAJADOR") FROM fet_workers B LEFT JOIN fet_cv C ON C.id_cv = B.id_cv WHERE B.id_worker = A.worker_update))) AS user_update
                                                                                     FROM ' . $table . ' A 
                                                                                     WHERE A.' . $id_table . ' = ' . $id;

        $prefix                                                                 =   explode('_', $table);

        $query                                                                  =   $this->db->query($sql);

        return $query->row_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $table, string $id_table, int $id
    * @return    array
    **/
    public function global_trace_register_sh($table, $id_table, $id)
    {
        $sql                                                                    =   'SELECT
                                                                                     IF(CONCAT(DATE_FORMAT(A.date_worker_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_worker_update, "%h:%i:%s %p"))) IS NULL,
                                                                                     CONCAT(DATE_FORMAT(A.date_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_update, "%h:%i:%s %p"))),
                                                                                     CONCAT(DATE_FORMAT(A.date_worker_update, "%d-%m-%Y"), " ", LOWER(DATE_FORMAT(A.date_worker_update, "%h:%i:%s %p")))) AS date_update,
                                                                                     IF((SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv) AS name_affiliate FROM fet_workers B LEFT JOIN fet_cv C ON C.id_cv = B.id_cv WHERE B.id_worker = A.worker_update) IS NULL, 
                                                                                     UPPER((SELECT CONCAT(C.name_user, " ", C.lastname_user, "<br>USUARIO") FROM git_users C WHERE C.id_user = A.user_update)), 
                                                                                     UPPER((SELECT CONCAT(C.name_cv, " ", C.first_lcv, " ", C.second_lcv, "<br>TRABAJADOR") FROM fet_workers B LEFT JOIN fet_cv C ON C.id_cv = B.id_cv WHERE B.id_affiliate = A.worker_update))) AS user_update 
                                                                                     FROM ' . $table . ' A 
                                                                                     WHERE A.' . $id_table . ' = ' . $id;

        $query                                                                  =   $this->db->query($sql);

        return $query->result_array();
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $text
    * @return    string
    **/
    public function user_name($text)
    {
        $clean_txt                                                              =   mb_ereg_replace('[^A-Za-z0-9\ \.\_\-\@]', '', $text);
        return trim($clean_txt);
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $address, string $subject, string $message, string $origin
    * @return    array
    **/
    public function send_mail($address, $subject, $content)
    {
        // $config                                                                 =   array(
        //                                                                                 'protocol'      =>  'sendmail',
        //                                                                                 'smtp_host'     =>  'smtp.gmail.com',
        //                                                                                 'smtp_port'     =>  587,
        //                                                                                 'smtp_user'     =>  'trabajandofet@gmail.com',
        //                                                                                 'smtp_pass'     =>  'YVsR)uTXFVmi',
        //                                                                                 'smtp_crypto'   =>  'ssl',
        //                                                                                 'mailtype'      =>  'html',
        //                                                                                 'charset'       =>  'utf-8',
        //                                                                                 'wordwrap'      =>  TRUE
        //                                                                             );

        $config                                                                 =   array(
                                                                                        'protocol'      =>  'sendmail',
                                                                                        'smtp_host'     =>  'mail.trabajandofet.co',
                                                                                        'smtp_port'     =>  587,
                                                                                        'smtp_user'     =>  'gestion@trabajandofet.co',
                                                                                        'smtp_pass'     =>  'F2T@2021/jjml',
                                                                                        'smtp_crypto'   =>  'ssl',
                                                                                        'mailtype'      =>  'html',
                                                                                        'charset'       =>  'utf-8',
                                                                                        'wordwrap'      =>  TRUE
                                                                                    );

        $this->load->library('email', $config);

        // $this->email->from('trabajandofet@gmail.com', $content['of']);
        $this->email->from('gestion@trabajandofet.co', $content['of']);
        $this->email->to($address);
        $this->email->subject($subject);

        $body                                                                   =   '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">'
                                                                                .   '<head>'
                                                                                .   '<meta charset="UTF-8">'
                                                                                .   '<meta http-equiv="X-UA-Compatible" content="IE=edge">'
                                                                                .   '<meta name="viewport" content="width=device-width, initial-scale=1">'
                                                                                .   '<style type="text/css">'
                                                                                .   'p{margin:10px 0;padding:0;}table{border-collapse:collapse;}h1,h2,h3,h4,h5,h6{display:block;margin:0;padding:0;}img,a img{border:0;height:auto;outline:none;text-decoration:none;}body,#bodyTable,#bodyCell{height:100%;margin:0;padding:0;width:100%;}.mcnPreviewText{display:none !important;}#outlook a{padding:0;}img{-ms-interpolation-mode:bicubic;}table{mso-table-lspace:0pt;mso-table-rspace:0pt;}.ReadMsgBody{width:100%;}.ExternalClass{width:100%;}p,a,li,td,blockquote{mso-line-height-rule:exactly;}a[href^=tel],a[href^=sms]{color:inherit;cursor:default;text-decoration:none;}p,a,li,td,body,table,blockquote{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{line-height:100%;}a[x-apple-data-detectors]{color:inherit !important;text-decoration:none !important;font-size:inherit !important;font-family:inherit !important;font-weight:inherit !important;line-height:inherit !important;}.templateContainer{max-width:600px !important;}a.mcnButton{display:block;}.mcnImage,.mcnRetinaImage{vertical-align:bottom;}.mcnTextContent{word-break:break-word;}.mcnTextContent img{height:auto !important;}.mcnDividerBlock{table-layout:fixed !important;}h1{color:#2b60b0;font-family:Helvetica;font-size:40px;font-style:normal;font-weight:bold;line-height:150%;letter-spacing:normal;text-align:center;}h2{color:#222222;font-family:Helvetica;font-size:34px;font-style:normal;font-weight:bold;line-height:150%;letter-spacing:normal;text-align:left;}h3{color:#444444;font-family:Helvetica;font-size:22px;font-style:normal;font-weight:bold;line-height:150%;letter-spacing:normal;text-align:left;}h4{color:#949494;font-family:Georgia;font-size:20px;font-style:italic;font-weight:normal;line-height:125%;letter-spacing:normal;text-align:left;}#templateHeader{background-color:#F7F7F7;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:20px;padding-bottom:20px;}.headerContainer{background-color:transparent;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:0;padding-bottom:0;}.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{color:#757575;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left;}.headerContainer .mcnTextContent a,.headerContainer .mcnTextContent p a{color:#007C89;font-weight:normal;text-decoration:underline;}#templateBody{background-color:#FFFFFF;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:20px;padding-bottom:20px;}.bodyContainer{background-color:transparent;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:0;padding-bottom:0;}.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{color:#757575;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left;}.bodyContainer .mcnTextContent a,.bodyContainer .mcnTextContent p a{color:#007C89;font-weight:normal;text-decoration:underline;}#templateFooter{background-color:#2b60b0;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:45px;padding-bottom:63px;}.footerContainer{background-color:transparent;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:0;padding-bottom:0;}.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{color:#FFFFFF;font-family:Helvetica;font-size:12px;line-height:150%;text-align:center;}.footerContainer .mcnTextContent a,.footerContainer .mcnTextContent p a{color:#FFFFFF;font-weight:normal;text-decoration:underline;}@media only screen and (min-width:768px){.templateContainer{width:600px !important;}}@media only screen and (max-width:480px){body,table,td,p,a,li,blockquote{-webkit-text-size-adjust:none !important;}}@media only screen and (max-width:480px){body{width:100% !important;min-width:100% !important;}}@media only screen and (max-width:480px){.mcnRetinaImage{max-width:100% !important;}}@media only screen and (max-width:480px){.mcnImage{width:100% !important;}}@media only screen and (max-width:480px){.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{max-width:100% !important;width:100% !important;}}@media only screen and (max-width:480px){.mcnBoxedTextContentContainer{min-width:100% !important;}}@media only screen and (max-width:480px){.mcnImageGroupContent{padding:9px !important;}}@media only screen and (max-width:480px){.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{padding-top:9px !important;}}@media only screen and (max-width:480px){.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{padding-top:18px !important;}}@media only screen and (max-width:480px){.mcnImageCardBottomImageContent{padding-bottom:9px !important;}}@media only screen and (max-width:480px){.mcnImageGroupBlockInner{padding-top:0 !important;padding-bottom:0 !important;}}@media only screen and (max-width:480px){.mcnImageGroupBlockOuter{padding-top:9px !important;padding-bottom:9px !important;}}@media only screen and (max-width:480px){.mcnTextContent,.mcnBoxedTextContentColumn{padding-right:18px !important;padding-left:18px !important;}}@media only screen and (max-width:480px){.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{padding-right:18px !important;padding-bottom:0 !important;padding-left:18px !important;}}@media only screen and (max-width:480px){.mcpreview-image-uploader{display:none !important;width:100% !important;}}@media only screen and (max-width:480px){h1{font-size:30px !important;line-height:125% !important;}}@media only screen and (max-width:480px){h2{font-size:26px !important;line-height:125% !important;}}@media only screen and (max-width:480px){h3{font-size:20px !important;line-height:150% !important;}}@media only screen and (max-width:480px){h4{font-size:18px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{font-size:14px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{font-size:16px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{font-size:16px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{font-size:14px !important;line-height:150% !important;}}'
                                                                                .   '</style>'
                                                                                .   '</head>'
                                                                                .   '<body>'
                                                                                .   '<center>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="bodyCell">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%">'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="templateHeader" data-template-container>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="headerContainer">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnImageBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" style="padding:9px" class="mcnImageBlockInner">'
                                                                                .   '<table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;"> <img align="center" alt="" src="' . RESOURCES . '/img/logo.png" width="250" style="max-width:250px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage"> </td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnTextBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">'
                                                                                .   '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">'
                                                                                .   '<h1>' . $content['title'] . '</h1>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="templateBody" data-template-container>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="bodyContainer">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnTextBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">'
                                                                                .   '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">'
                                                                                .   '<br>'
                                                                                .   $content['body']
                                                                                .   '<br>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>';

        if ($content['login'] == '1')
        {
            $body                                                               .=  '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnButtonBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="center" class="mcnButtonBlockInner">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #e0922f;">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="middle" class="mcnButtonContent" style="font-family: Helvetica; font-size: 18px; padding: 18px;"> <a class="mcnButton " title="Ingresar" href="' . $content['url'] . '" target="_blank" style="font-weight: bold;letter-spacing: -0.5px;line-height: 100%;text-align: center;text-decoration: none;color: #FFF;">Ingresar</a> </td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>';
        }

        $body                                                                   .=  '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="templateFooter" data-template-container>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="footerContainer">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnDividerBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td class="mcnDividerBlockInner" style="min-width:100%; padding:18px;">'
                                                                                .   '<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top: 2px solid #FFF;">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td> <span></span> </td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnTextBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">'
                                                                                .   '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;"> Copyright &copy; ' . date('Y') . ' - FET <br> Todos los Derechos Reservados <br> TRABAJANDOFET <br> PLATAFORMA DE APOYO A LOS PROCESOS MISIONALES<br><br><strong>Para mayor información comunicate con nosotros al correo </strong><br>gestion@trabajandofet.co<br></td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</center>'
                                                                                .   '</body>'
                                                                                .   '</html>';


        $this->email->message($body);

        $this->email->set_newline("\r\n");

        if (!$this->email->send())
        {
            return show_error($this->email->print_debugger());
        }
        else
        {
            return TRUE;
        }
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $address, string $subject, string $message, string $origin
    * @return    array
    **/
    public function send_mail_asambleas($address, $subject, $content, $color_hex, $logo_company)
    {
        $config                                                                 =   array(
                                                                                        'protocol'      =>  'sendmail',
                                                                                        'smtp_host'     =>  'mail.trabajandofet.co',
                                                                                        'smtp_port'     =>  587,
                                                                                        'smtp_user'     =>  'gestion@trabajandofet.co',
                                                                                        'smtp_pass'     =>  'YVsR)uTXFVmi',
                                                                                        'smtp_crypto'   =>  'ssl',
                                                                                        'mailtype'      =>  'html',
                                                                                        'charset'       =>  'utf-8',
                                                                                        'wordwrap'      =>  TRUE
                                                                                    );

        $this->load->library('email', $config);

        $this->email->from('gestion@trabajandofet.co', $content['of']);
        $this->email->to($address);
        $this->email->subject($subject);

        $body                                                                   =   '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">'
                                                                                .   '<head>'
                                                                                .   '<meta charset="UTF-8">'
                                                                                .   '<meta http-equiv="X-UA-Compatible" content="IE=edge">'
                                                                                .   '<meta name="viewport" content="width=device-width, initial-scale=1">'
                                                                                .   '<style type="text/css">'
                                                                                .   'p{margin:10px 0;padding:0;}table{border-collapse:collapse;}h1,h2,h3,h4,h5,h6{display:block;margin:0;padding:0;}img,a img{border:0;height:auto;outline:none;text-decoration:none;}body,#bodyTable,#bodyCell{height:100%;margin:0;padding:0;width:100%;}.mcnPreviewText{display:none !important;}#outlook a{padding:0;}img{-ms-interpolation-mode:bicubic;}table{mso-table-lspace:0pt;mso-table-rspace:0pt;}.ReadMsgBody{width:100%;}.ExternalClass{width:100%;}p,a,li,td,blockquote{mso-line-height-rule:exactly;}a[href^=tel],a[href^=sms]{color:inherit;cursor:default;text-decoration:none;}p,a,li,td,body,table,blockquote{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{line-height:100%;}a[x-apple-data-detectors]{color:inherit !important;text-decoration:none !important;font-size:inherit !important;font-family:inherit !important;font-weight:inherit !important;line-height:inherit !important;}.templateContainer{max-width:600px !important;}a.mcnButton{display:block;}.mcnImage,.mcnRetinaImage{vertical-align:bottom;}.mcnTextContent{word-break:break-word;}.mcnTextContent img{height:auto !important;}.mcnDividerBlock{table-layout:fixed !important;}h1{color:' . $color_hex . ';font-family:Helvetica;font-size:40px;font-style:normal;font-weight:bold;line-height:150%;letter-spacing:normal;text-align:center;}h2{color:#222222;font-family:Helvetica;font-size:34px;font-style:normal;font-weight:bold;line-height:150%;letter-spacing:normal;text-align:left;}h3{color:#444444;font-family:Helvetica;font-size:22px;font-style:normal;font-weight:bold;line-height:150%;letter-spacing:normal;text-align:left;}h4{color:#949494;font-family:Georgia;font-size:20px;font-style:italic;font-weight:normal;line-height:125%;letter-spacing:normal;text-align:left;}#templateHeader{background-color:#F7F7F7;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:20px;padding-bottom:20px;}.headerContainer{background-color:transparent;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:0;padding-bottom:0;}.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{color:#757575;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left;}.headerContainer .mcnTextContent a,.headerContainer .mcnTextContent p a{color:#007C89;font-weight:normal;text-decoration:underline;}#templateBody{background-color:#FFFFFF;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:20px;padding-bottom:20px;}.bodyContainer{background-color:transparent;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:0;padding-bottom:0;}.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{color:#757575;font-family:Helvetica;font-size:16px;line-height:150%;text-align:left;}.bodyContainer .mcnTextContent a,.bodyContainer .mcnTextContent p a{color:#007C89;font-weight:normal;text-decoration:underline;}#templateFooter{background-color:' . $color_hex . ';background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:45px;padding-bottom:63px;}.footerContainer{background-color:transparent;background-image:none;background-repeat:no-repeat;background-position:center;background-size:cover;border-top:0;border-bottom:0;padding-top:0;padding-bottom:0;}.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{color:#FFFFFF;font-family:Helvetica;font-size:12px;line-height:150%;text-align:center;}.footerContainer .mcnTextContent a,.footerContainer .mcnTextContent p a{color:#FFFFFF;font-weight:normal;text-decoration:underline;}@media only screen and (min-width:768px){.templateContainer{width:600px !important;}}@media only screen and (max-width:480px){body,table,td,p,a,li,blockquote{-webkit-text-size-adjust:none !important;}}@media only screen and (max-width:480px){body{width:100% !important;min-width:100% !important;}}@media only screen and (max-width:480px){.mcnRetinaImage{max-width:100% !important;}}@media only screen and (max-width:480px){.mcnImage{width:100% !important;}}@media only screen and (max-width:480px){.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{max-width:100% !important;width:100% !important;}}@media only screen and (max-width:480px){.mcnBoxedTextContentContainer{min-width:100% !important;}}@media only screen and (max-width:480px){.mcnImageGroupContent{padding:9px !important;}}@media only screen and (max-width:480px){.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{padding-top:9px !important;}}@media only screen and (max-width:480px){.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{padding-top:18px !important;}}@media only screen and (max-width:480px){.mcnImageCardBottomImageContent{padding-bottom:9px !important;}}@media only screen and (max-width:480px){.mcnImageGroupBlockInner{padding-top:0 !important;padding-bottom:0 !important;}}@media only screen and (max-width:480px){.mcnImageGroupBlockOuter{padding-top:9px !important;padding-bottom:9px !important;}}@media only screen and (max-width:480px){.mcnTextContent,.mcnBoxedTextContentColumn{padding-right:18px !important;padding-left:18px !important;}}@media only screen and (max-width:480px){.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{padding-right:18px !important;padding-bottom:0 !important;padding-left:18px !important;}}@media only screen and (max-width:480px){.mcpreview-image-uploader{display:none !important;width:100% !important;}}@media only screen and (max-width:480px){h1{font-size:30px !important;line-height:125% !important;}}@media only screen and (max-width:480px){h2{font-size:26px !important;line-height:125% !important;}}@media only screen and (max-width:480px){h3{font-size:20px !important;line-height:150% !important;}}@media only screen and (max-width:480px){h4{font-size:18px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{font-size:14px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{font-size:16px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{font-size:16px !important;line-height:150% !important;}}@media only screen and (max-width:480px){.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{font-size:14px !important;line-height:150% !important;}}'
                                                                                .   '</style>'
                                                                                .   '</head>'
                                                                                .   '<body>'
                                                                                .   '<center>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="bodyCell">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%">'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="templateHeader" data-template-container>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="headerContainer">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnImageBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" style="padding:9px" class="mcnImageBlockInner">'
                                                                                .   '<table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;"> <img align="center" alt="" src="' . $logo_company . '" width="250" style="max-width:250px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage"> </td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnTextBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">'
                                                                                .   '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">'
                                                                                .   '<h1>' . $content['title'] . '</h1>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="templateBody" data-template-container>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="bodyContainer">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnTextBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">'
                                                                                .   '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">'
                                                                                .   '<br>'
                                                                                .   $content['body']
                                                                                .   '<br>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>';

        if ($content['login'] == '1')
        {
            $body                                                               .=  '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnButtonBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="center" class="mcnButtonBlockInner">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #e0922f;">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="middle" class="mcnButtonContent" style="font-family: Helvetica; font-size: 18px; padding: 18px;"> <a class="mcnButton " title="Ingresar" href="' . $content['url'] . '" target="_blank" style="font-weight: bold;letter-spacing: -0.5px;line-height: 100%;text-align: center;text-decoration: none;color: #FFF;">Ingresar</a> </td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>';
        }

        $body                                                                   .=  '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '<tr>'
                                                                                .   '<td align="center" valign="top" id="templateFooter" data-template-container>'
                                                                                .   '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="footerContainer">'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnDividerBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td class="mcnDividerBlockInner" style="min-width:100%; padding:18px;">'
                                                                                .   '<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top: 2px solid #FFF;">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td> <span></span> </td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">'
                                                                                .   '<tbody class="mcnTextBlockOuter">'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">'
                                                                                .   '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">'
                                                                                .   '<tbody>'
                                                                                .   '<tr>'
                                                                                .   '<td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;"> Copyright &copy; ' . date('Y') . ' - FET <br> Todos los Derechos Reservados <br> TRABAJANDOFET <br> PLATAFORMA DE APOYO A LOS PROCESOS MISIONALES<br><br><strong>Para mayor información comunicate con nosotros al correo </strong><br>gestion@trabajandofet.co<br></td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</tbody>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</td>'
                                                                                .   '</tr>'
                                                                                .   '</table>'
                                                                                .   '</center>'
                                                                                .   '</body>'
                                                                                .   '</html>';

        $this->email->message($body);

        $this->email->set_newline("\r\n");

        if (!$this->email->send())
        {
            return show_error($this->email->print_debugger());
        }
        else
        {
            return TRUE;
        }
        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     int $role, varchar $submodule
    * @return    $array
    **/
    public function actions_by_role($role, $submodule)
    {
        $this->db->select('name_action');
        $this->db->where('id_role', $role);
        $this->db->where('name_submodule', $submodule);
        $this->db->where('git_company != ', 'G');

        $query                                                                  =   $this->db->get('git_permissions');

        if (count($query->result_array()) > 0)
        {
            $result                                                             =   array();

            foreach ($query->result_array() as $row)
            {
                array_push($result, $row['name_action']);
            }

            return $result;
        }
        else
        {
            return FALSE;
        }

        exit();
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     FILE $error_image, FILE $name_image, varchar $dir, FILE $size_image, FILE $tmp_name_image
    * @return    $array
    **/
    public function upload_image($error_image, $name_image, $dir, $size_image, $tmp_name_image, $w_image, $h_image, $resize, $photo, $name_image_end)
    {
        $name_image_old                                                         =   $name_image;        

        if ($error_image === UPLOAD_ERR_OK)
        {
            $upload_folder                                                      =   ($name_image <> '') ? $dir : '';
            $fragments                                                          =   explode('.', $name_image);
            $extension                                                          =   end($fragments);
            $extension                                                          =   mb_strtolower($extension);

            if ($extension != 'jpg' && $extension != 'png' && $extension != 'jpeg')
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'La extensión de la imagen ' . $name_image_old . ' debe ser jpg, png o jpeg.';
                return $result;
                exit();
            }

            if($size_image > 20000000)
            {
                if (!$resize) 
                {
                    $result['data']                                             =   FALSE;
                    $result['message']                                          =   'El tamaño de la imagen ' . $name_image_old . ' no debe exceder los 20 Mb.';
                    return $result;
                    exit();
                }
            }

            $today                                                              =   strtotime("".date("Y-m-d H:i:s")."");

            if ($name_image_end)
            {
                $name_image                                                     =   $name_image_end . '.' . $extension;
            }
            else
            {
                $name_image                                                     =   $today . '.' . $extension;
            }

            $store                                                              =   $upload_folder . '/' . $name_image;
            $image                                                              =   FALSE;

            $data                                                               =   getimagesize($tmp_name_image);

            $width                                                              =   $data[0];
            $height                                                             =   $data[1];

            if ($photo)
            {
                if($width > "1200" || $height > "1200")
                {
                    $result['data']                                                 =   FALSE;
                    $result['message']                                              =   'El tamaño de la imagen ' . $name_image_old . ' no debe exceder los 1200px de ancho x 1200px de alto.';
                    return $result;
                    exit();
                }
            }

            if ($resize) 
            {
                if($width < $w_image)
                {
                    if (move_uploaded_file($tmp_name_image, $store))
                    {
                        $result['data']                                         =   TRUE;
                        $result['name_image']                                   =   $name_image;
                        return $result;
                        exit();
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Error subiendo la imagen ' . $name_image_old . ', inténta de nuevo.';
                        return $result;
                        exit();
                    }
                }
                else
                {
                    switch ($extension)
                    {
                        case 'jpg':
                        case 'jpeg':
                            $temp_image                                         =   imagecreatefromjpeg($tmp_name_image);
                            break;
                        case 'png':
                            $temp_image                                         =   imagecreatefrompng($tmp_name_image);
                            break;
                    }


                    $x_ratio                                                    =   $w_image / $width;
                    $y_ratio                                                    =   $h_image / $height;

                    switch (true) 
                    {
                        case (($width <= $w_image) && ($height <= $h_image)):
                            $w_final                                            =   $width; 
                            $h_final                                            =   $height;
                            break;
                        case (($x_ratio * $height) < $w_image):
                            $w_final                                            =   $w_image; 
                            $h_final                                            =   ceil($x_ratio * $height);
                            break;
                        default:
                            $w_final                                            =   ceil($y_ratio * $width);
                            $h_final                                            =   $h_image;
                            break;
                    }

                    $canvas                                                     =   imagecreatetruecolor($w_final, $h_final);

                    imagecopyresampled($canvas, $temp_image, 0, 0, 0, 0, $w_final, $h_final, $width, $height);
                    imagedestroy($temp_image);

                    switch ($extension)
                    {
                        case 'jpg':
                        case 'jpeg':
                            $image                                              =   imagejpeg($canvas, $store);
                            break;
                        case 'png':
                            $image                                              =   imagepng($canvas, $store);
                            break;
                    }

                    if ($image)
                    {
                        $result['data']                                         =   TRUE;
                        $result['name_image']                                   =   $name_image;
                        return $result;
                        exit();
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Error subiendo la imagen ' . $name_image_old . ', inténta de nuevo.';
                        return $result;
                        exit();
                    }                    
                }
            }
            else
            {
                $w_scale                                                        =   round((($w_image * 100) / $width), 1, PHP_ROUND_HALF_DOWN);
                $h_scale                                                        =   round((($h_image * 100) / $height), 1, PHP_ROUND_HALF_DOWN);

                $w_check                                                        =   floor($width * ($h_scale / 100));
                $h_check                                                        =   floor($height * ($w_scale / 100));

                if (($width > 600) && ($height > 600))
                {
                    switch (TRUE)
                    {
                        case (($w_check <= $w_image) && ($h_check <= $h_image)):
                            $scale                                              =   max(array($w_scale, $h_scale));
                            $p_scale                                            =   $scale / 100;
                            $n_height                                           =   $height * $p_scale;
                            $n_width                                            =   $width * $p_scale;
                            break;

                        case ($w_check <= $w_image):
                            $p_scale                                            =   $h_scale / 100;
                            $n_height                                           =   $height * $p_scale;
                            $n_width                                            =   $width * $p_scale;
                            break;

                        case ($h_check <= $h_image):
                            $p_scale                                            =   $w_scale / 100;
                            $n_height                                           =   $height * $p_scale;
                            $n_width                                            =   $width * $p_scale;
                            break;

                        default:
                            $n_height                                           =   $h_image;
                            $n_width                                            =   $w_image;
                    }

                    switch ($extension)
                    {
                        case 'jpg':
                        case 'jpeg':
                            $temp_image                                         =   imagecreatefromjpeg($tmp_name_image);
                            $temp_image                                         =   imagescale($temp_image, $n_width, $n_height);
                            $image                                              =   imagejpeg($temp_image, $store);

                            break;

                        case 'png':
                            $temp_image                                         =   imagecreatefrompng($tmp_name_image);
                            $temp_image                                         =   imagescale($temp_image, $n_width, $n_height);
                            $image                                              =   imagepng($temp_image, $store);

                            break;
                    }

                    if ($image)
                    {
                        $result['data']                                         =   TRUE;
                        $result['name_image']                                   =   $name_image;
                        return $result;
                        exit();
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Error subiendo la imagen ' . $name_image_old . ', inténta de nuevo.';
                        return $result;
                        exit();
                    }
                }
                else
                {
                    if (move_uploaded_file($tmp_name_image, $store))
                    {
                        $result['data']                                         =   TRUE;
                        $result['name_image']                                   =   $name_image;
                        return $result;
                        exit();
                    }
                    else
                    {
                        $result['data']                                         =   FALSE;
                        $result['message']                                      =   'Error subiendo la imagen ' . $name_image_old . ', inténta de nuevo.';
                        return $result;
                        exit();
                    }
                }
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Error subiendo la imagen ' . $name_image_old . ', inténta de nuevo.';
            return $result; 
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     FILE $error_file, FILE $name_file, varchar $dir, FILE $size_file, FILE $tmp_name_file
    * @return    $array
    **/
    public function upload_file($error_file, $name_file, $dir, $size_file, $tmp_name_file)
    {
        if ($error_file === UPLOAD_ERR_OK)
        {
            $upload_folder                                                      =   ($name_file <> '') ? $dir : '';
            $fragments                                                          =   explode('.', $name_file);
            $extension                                                          =   end($fragments);
            $extension                                                          =   mb_strtolower($extension);

            if ($extension != 'pdf')
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'La extensión del archivo ' . $name_file . ' debe ser pdf.';
                return $result;
                exit();
            }

            if($size_file > 20000000)
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'El tamaño del archivo ' . $name_file . ' no debe exceder los 20 Mb.';
                return $result;
                exit();
            }

            $today                                                              =   strtotime("".date("Y-m-d H:i:s")."");
            $name_file_store                                                    =   $today . '.' . $extension;
            $store                                                              =   $upload_folder . '/' . $name_file_store;

            if (move_uploaded_file($tmp_name_file, $store))
            {
                $result['data']                                                 =   TRUE;
                $result['name_file']                                            =   $name_file_store;
                return $result;
                exit();
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Error subiendo el archivo ' . $name_file . ', inténta de nuevo.';
                return $result;
                exit();
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Error subiendo el archivo ' . $name_file . ', inténta de nuevo.';
            return $result;
            exit();
        }
    }


    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     FILE $error_file, FILE $name_file, varchar $dir, FILE $size_file, FILE $tmp_name_file, varchar $nickname, varchar $extensions
    * @return    $array
    **/
    public function upload_file_extensions($error_file, $name_file, $dir, $size_file, $tmp_name_file, $nickname, $extensions)
    {
        if ($error_file === UPLOAD_ERR_OK)
        {
            $upload_folder                                                      =   ($name_file <> '') ? $dir : '';
            $fragments                                                          =   explode('.', $name_file);
            $extension                                                          =   end($fragments);
            $extension                                                          =   mb_strtolower($extension);

            $permit_extension                                                   =   explode('|', $extensions);

            $cont                                                               =   0;

            foreach ($permit_extension as $value)
            {
                if ($extension != $value)
                {
                    $cont++;
                }
            }

            if (count($permit_extension) == $cont)
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'La extensión del archivo ' . $name_file . ' debe ser (' . $extensions . ').';

                return $result;
                exit();
            }

            if($size_file > 20000000)
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'El tamaño del archivo ' . $name_file . ' no debe exceder las 20 Mb.';

                return $result;
                exit();
            }

            if ($nickname != false)
            {
                $today                                                          =   $nickname . '_' . strtotime("".date("Y-m-d H:i:s")."");
            }
            else
            {
                $today                                                          =   strtotime("".date("Y-m-d H:i:s")."");
            }

            $name_file_store                                                    =   $today . '.' . $extension;
            $store                                                              =   $upload_folder . '/' . $name_file_store;

            if (move_uploaded_file($tmp_name_file, $store))
            {
                $result['data']                                                 =   TRUE;
                $result['name_file']                                            =   $name_file_store;
                return $result;
                exit();
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Error subiendo el archivo ' . $name_file . ', inténta de nuevo.';
                return $result;
                exit();
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Error subiendo el archivo ' . $name_file . ', inténta de nuevo.';
            return $result;
            exit();
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     FILE $error_file, FILE $name_file, varchar $dir, FILE $size_file, FILE $tmp_name_file
    * @return    $array
    **/
    public function upload_file_name($error_file, $name_file, $dir, $size_file, $tmp_name_file, $new_name_file)
    {
        if ($error_file === UPLOAD_ERR_OK)
        {
            $upload_folder                                                      =   ($name_file <> '') ? $dir : '';
            $fragments                                                          =   explode('.', $name_file);
            $extension                                                          =   end($fragments);
            $extension                                                          =   mb_strtolower($extension);

            if ($extension != 'pdf')
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'La extensión del archivo ' . $name_file . ' debe ser pdf.';
                return $result;
                exit();
            }

            if($size_file > 20000000)
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'El tamaño del archivo ' . $name_file . ' no debe exceder los 20 Mb.';
                return $result;
                exit();
            }

            $name_file_store                                                    =   $new_name_file . '.' . $extension;
            $store                                                              =   $upload_folder . '/' . $name_file_store;

            if (move_uploaded_file($tmp_name_file, $store))
            {
                $result['data']                                                 =   TRUE;
                $result['name_file']                                            =   $name_file_store;
                return $result;
                exit();
            }
            else
            {
                $result['data']                                                 =   FALSE;
                $result['message']                                              =   'Error subiendo el archivo ' . $name_file . ', inténta de nuevo.';
                return $result;
                exit();
            }
        }
        else
        {
            $result['data']                                                     =   FALSE;
            $result['message']                                                  =   'Error subiendo el archivo ' . $name_file . ', inténta de nuevo.';
            return $result;
            exit();
        }
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
        $this->db->select('git_modules.name_es_module, git_modules.name_module, git_submodules.name_es_submodule, git_submodules.url_submodule');
        $this->db->join('git_modules', 'git_modules.id_module = git_submodules.id_module');
        $this->db->where('git_submodules.name_submodule', $submodule);
        $this->db->where('git_submodules.git_company != ', 'G');

        $query                                                                  =   $this->db->get('git_submodules');

        if (count($query->result_array()) > 0)
        {
            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $email
    * @return    array | boolean
    **/
    public function is_valid_email($email)
    {
        $result                                                                 =   (false !== filter_var($email, FILTER_VALIDATE_EMAIL));

        if ($result)
        {
            list($user, $domain)                                                =   explode('@', $email);

            $result                                                             =   checkdnsrr($domain, 'MX');
        }

        return $result;
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $key, $data
    * @return    string
    **/
    public function encrypt($key, $data)
    {
        $key                                                                    =   'FseRbDU54tX17ueRbDU' . $key;
        $encryptionKey                                                          =   base64_decode($key);
        $iv                                                                     =   openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-gcm'));
        $encrypted                                                              =   openssl_encrypt($data, 'aes-256-gcm', $encryptionKey, 0, $iv, $tag);

        return $encrypted . ':' . base64_encode($iv) . ':' . base64_encode($tag);
    }

    /**
    * @author    Innovación y Tecnología
    * @copyright 2021 Fábrica de Desarrollo
    * @since     v2.0.1
    * @param     string $key, $data
    * @return    string
    **/
    public function decrypt($key, $data)
    {
        $key                                                                    =   'FseRbDU54tX17ueRbDU' . $key;
        $encryptionKey                                                          =   base64_decode($key);
        list($encryptedData, $iv, $tag)                                         =   explode(':', $data, 3);

        return openssl_decrypt($encryptedData, 'aes-256-gcm', $encryptionKey, 0, base64_decode($iv), base64_decode($tag));
    }
}