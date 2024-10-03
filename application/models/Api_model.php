<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Api_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    function connectDb($common_user_id)
    {
        $hostname = '';
        $username = '';
        $password = '';
        $database = '';

        $where_clause = array(
            'device_users.id'     => $common_user_id,
            'device_users.status' => 1,
            'device_users.block'  => 0
        );
        $company_result = $this->device_users($where_clause);

        if (count($company_result) > 0) {
            foreach ($company_result as $row) {
                $hostname = $row->db_hostname;
                $username = $row->db_user;
                $password = $row->db_password;
                $database = $row->db_name;
            }
        }

        if ($hostname != '' AND  $username != '' AND  $password != '' AND $database != ''){
            $db['atom_portal'] = array(
                'dsn'	=> '',
                'hostname' => $hostname,
                'username' => $username,
                'password' => $password,
                'database' => $database,
                'dbdriver' => 'mysqli',
                'dbprefix' => '',
                'pconnect' => FALSE,
                'db_debug' => (ENVIRONMENT !== 'production'),
                'cache_on' => FALSE,
                'cachedir' => '',
                'char_set' => 'utf8',
                'dbcollat' => 'utf8_general_ci',
                'swap_pre' => '',
                'encrypt' => FALSE,
                'compress' => FALSE,
                'stricton' => FALSE,
                'failover' => array(),
                'save_queries' => TRUE
            );
            $atom_db = $this->load->database($db['atom_portal'],TRUE);
            return $atom_db;
        }else{
            return null;
        }
    }
    function connectCo($company_id)
    {
        $hostname = '';
        $username = '';
        $password = '';
        $database = '';

        $where_clause = array(
            'id'      => $company_id,
            'status'  => 1,
            'deleted' => 0
        );
        $company_result = $this->fetch_record('company',$where_clause);
        // echo "<pre>";
        // print_r($company_result);exit;
        if (count((array)$company_result) > 0) {
            $hostname = $company_result->db_hostname;
            $hostname = 'localhost';
            // $username = $company_result->db_user;
            $username = 'root';
            // $password = $company_result->db_password;
            $password = '';
            $database = 'ai_21_02';
            // $database = $company_result->db_name;
        }

        if ($hostname != '' AND  $username != '' AND  $password != '' AND $database != ''){
            $db['atom_portal'] = array(
                'dsn'	=> '',
                'hostname' => $hostname,
                'username' => $username,
                'password' => $password,
                'database' => $database,
                'dbdriver' => 'mysqli',
                'dbprefix' => '',
                'pconnect' => FALSE,
                'db_debug' => (ENVIRONMENT !== 'production'),
                'cache_on' => FALSE,
                'cachedir' => '',
                'char_set' => 'utf8',
                'dbcollat' => 'utf8_general_ci',
                'swap_pre' => '',
                'encrypt' => FALSE,
                'compress' => FALSE,
                'stricton' => FALSE,
                'failover' => array(),
                'save_queries' => TRUE
            );
            $atom_db = $this->load->database($db['atom_portal'],TRUE);
            return $atom_db;
        }else{
            return null;
        }
    }
    function encrypt_password($password) {
        $salt = substr(md5(uniqid(rand(), true)), 0, 8);
        $hash = $salt . md5($salt . $password);
        return $hash;
    }
    function decrypt_password($password, $hash) {
        $salt = substr($hash, 0, 8);

        if ($hash == $salt . md5($salt . $password)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function insert($table, $data,$atomdb=null) {
        if ($atomdb==null){
            $this->db->insert($table, $data);
            $insert_id = $this->db->insert_id();
        }else{
            $atomdb->insert($table, $data);
            $insert_id = $atomdb->insert_id();
        }
        return $insert_id;
    }
    public function update($table, $where_clause, $data,$atomdb=null) {
        if ($atomdb==null){
            $this->db->trans_start();
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            $this->db->update($table, $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                return false;
            }else{
                return true;
            }
        }else{
            $atomdb->trans_start();
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            $atomdb->update($table, $data);
            $atomdb->trans_complete();
            if ($atomdb->trans_status() === FALSE)
            {
                return false;
            }else{
                return true;
            }
        }
    }
    public function fetch_question_formate($table,$where_clause,$atomdb=null)
    {
         
         if ($atomdb==null){
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            $query = $this->db->get($table);
            return $query->result();
        }else{
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            $query = $atomdb->get($table);
            return $query->result();
        }     
    }
    public function fetch_domain_url($table,$atomdb=null)
    {
         
         if ($atomdb==null){
            $query = $this->db->get($table);
            return $query->result();
        }else{
          
            $query = $atomdb->get($table);
            return $query->result();
        }     
    }
    public function fetch_record($table,$where_clause,$atomdb=null){
        if ($atomdb==null){
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            $query = $this->db->get($table);
        }else{
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            $query = $atomdb->get($table);
        }
        return $query->row();
    }
    public function fetch_results($table,$where_clause,$atomdb=null){
        if ($atomdb==null){
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            $query = $this->db->get($table);
            return $query->result();
        }else{
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            $query = $atomdb->get($table);
            return $query->result();
        }
    }
    public function record_count($table,$where_clause,$atomdb=null){
        if ($atomdb==null){
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            return $this->db->count_all_results($table);
        }else{
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            return $atomdb->count_all_results($table);
        }
    }
    public function update_attempts($table,$user_id,$assessment_id,$atomdb=null){
        if ($atomdb==null){
            $this->db->trans_start();
            $this->db->set('attempts', 'attempts+1', FALSE);
            $this->db->set('addeddate', date('Y-m-d H:i:s'));
            $this->db->where('user_id', $user_id);
            $this->db->where('assessment_id', $assessment_id);
            $this->db->update($table);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                return false;
            }else{
                return true;
            }
        }else{
            $atomdb->trans_start();
            $atomdb->set('attempts', 'attempts+1', FALSE);
            $atomdb->set('addeddate', date('Y-m-d H:i:s'));
            $atomdb->where('user_id', $user_id);
            $atomdb->where('assessment_id', $assessment_id);
            $atomdb->update($table);
            $atomdb->trans_complete();
            if ($atomdb->trans_status() === FALSE)
            {
                return false;
            }else{
                return true;
            }
        }
    }

    public function device_users($where_clause,$atomdb=null){
        if ($atomdb==null){
            $this->db->select('company.*,count(device_users.id) as registered_users');    
            $this->db->from('company');
            $this->db->join('device_users', 'company.id = device_users.company_id AND (`device_users`.`status` = 1 AND `device_users`.`block` =0)','left');
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            return $this->db->get()->result();
        }else{
            $atomdb->select('company.*,count(device_users.id) as registered_users');    
            $atomdb->from('company');
            $atomdb->join('device_users', 'company.id = device_users.company_id AND (`device_users`.`status` = 1 AND `device_users`.`block` =0)','left');
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            return $atomdb->get()->result();
        }
    }
    public function fetch_device_user_details($where_clause,$atomdb=null) {
        if ($atomdb==null){
            $this->db->select('*');    
            $this->db->from('device_users');
            foreach ($where_clause as $key=>$value){
                $this->db->where($key, $value);
            }
            return $this->db->get()->result();
        }else{
            $atomdb->select('*');    
            $atomdb->from('device_users');
            foreach ($where_clause as $key=>$value){
                $atomdb->where($key, $value);
            }
            return $atomdb->get()->result();
        }
    }
    public function fetch_workshop_ongoing($company_id,$user_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        // $system_date = date('Y-m-d');
        // $system_time = date('H:i:s');

        //PRE AND POST DATE TIME CANNOT BE SAME
        //0000-00-00 & 1970-01-01- EMPTY DATE
        //AND (all_questions_fired!='1' OR all_feedbacks_fired!='1')

        $query = "SELECT * FROM (
        (SELECT w.*,DATE_FORMAT(w.pre_start_date,'%d.%m.%Y') as workshop_date,'PRE' as workshop_session,
        IFNULL(wru.otp_verified,'N') as is_registered,count(wu.id) as restricted_users,
        IFNULL(wru.all_questions_fired,0) as all_questions_fired,IFNULL(wru.all_feedbacks_fired,0) as all_feedbacks_fired,
        IFNULL(wru.session_preclose,0) as session_preclose
        FROM workshop as w
        LEFT JOIN workshop_registered_users as wru ON (w.id = wru.workshop_id AND 
        (wru.user_id ='".$user_id."' AND wru.workshop_session='PRE'))
        LEFT JOIN workshop_users as wu ON (w.id = wu.workshop_id)
        WHERE w.status='1' AND w.company_id='".$company_id."'
        AND ((w.pre_start_date IS NOT NULL) AND (w.pre_start_date != '0000-00-00') AND (w.pre_start_date != '1970-01-01'))
        AND ((w.pre_end_date IS NOT NULL) AND (w.pre_end_date != '0000-00-00') AND (w.pre_end_date != '1970-01-01'))
        AND ((w.pre_start_time IS NOT NULL) AND (w.pre_start_time != ''))
        AND ((w.pre_end_time IS NOT NULL) AND (w.pre_end_time != ''))
        AND('".$system_date."' BETWEEN CONCAT(w.pre_start_date,' ',STR_TO_DATE(w.pre_start_time, '%l:%i %p')) AND 
        CONCAT(w.pre_end_date,' ',STR_TO_DATE(w.pre_end_time, '%l:%i %p')))
        GROUP BY w.id,workshop_session
        ORDER BY w.start_date DESC)
        UNION ALL
        (SELECT w.*,DATE_FORMAT(w.post_start_date,'%d.%m.%Y') as workshop_date,'POST' as workshop_session ,
        IFNULL(wru.otp_verified,'N') as is_registered,count(wu.id) as restricted_users,
        IFNULL(wru.all_questions_fired,0) as all_questions_fired,IFNULL(wru.all_feedbacks_fired,0) as all_feedbacks_fired,
        IFNULL(wru.session_preclose,0) as session_preclose
        FROM workshop as w
        LEFT JOIN workshop_registered_users as wru ON (w.id = wru.workshop_id AND 
        (wru.user_id ='".$user_id."' AND wru.workshop_session='POST'))
        LEFT JOIN workshop_users as wu ON (w.id = wu.workshop_id)
        WHERE w.status='1' AND w.company_id='".$company_id."'
        AND ((w.post_start_date IS NOT NULL) AND (w.post_start_date != '0000-00-00') AND (w.post_start_date != '1970-01-01'))
        AND ((w.post_end_date IS NOT NULL) AND (w.post_end_date != '0000-00-00') AND (w.post_end_date != '1970-01-01'))
        AND ((w.post_start_time IS NOT NULL) AND (w.post_start_time != ''))
        AND ((w.post_end_time IS NOT NULL) AND (w.post_end_time != ''))
        AND('".$system_date."' BETWEEN CONCAT(w.post_start_date,' ',STR_TO_DATE(w.post_start_time, '%l:%i %p')) AND 
        CONCAT(w.post_end_date,' ',STR_TO_DATE(w.post_end_time, '%l:%i %p')))
        GROUP BY w.id,workshop_session
        ORDER BY w.start_date DESC)
        ) as wshop 
        ORDER BY wshop.start_date DESC,wshop.id,wshop.workshop_session";
        //WHERE (wshop.all_questions_fired=0 OR wshop.all_feedbacks_fired=0)

        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_workshop_completed($company_id,$user_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        // $system_date = date('Y-m-d');
        // $system_time = date('H:i:s');

        //PRE AND POST DATE TIME CANNOT BE SAME
        //0000-00-00 & 1970-01-01- EMPTY DATE
        // AND ('".$system_date."' NOT BETWEEN CONCAT(w.pre_start_date,' ',STR_TO_DATE(w.pre_start_time, '%l:%i %p')) AND 
        // CONCAT(w.pre_end_date,' ',STR_TO_DATE(w.pre_end_time, '%l:%i %p'))) 
        
        //AND (wru.all_questions_fired=1 AND wru.all_feedbacks_fired=1)
        //AND (wru.all_questions_fired=1 AND wru.all_feedbacks_fired=1)

        $query = "SELECT * FROM (
        (SELECT w.*,DATE_FORMAT(w.pre_start_date,'%d.%m.%Y') as workshop_date,'PRE' as workshop_session,
        IFNULL(wru.otp_verified,'N') as is_registered,count(wu.id) as restricted_users,
        IFNULL(wru.all_questions_fired,0) as all_questions_fired,IFNULL(wru.all_feedbacks_fired,0) as all_feedbacks_fired,
        IFNULL(wru.session_preclose,0) as session_preclose
        FROM workshop as w
        LEFT JOIN workshop_registered_users as wru ON (w.id = wru.workshop_id AND  
        (wru.user_id ='".$user_id."' AND wru.workshop_session='PRE' ))
        LEFT JOIN workshop_users as wu ON (w.id = wu.workshop_id)
        WHERE w.status='1' AND w.company_id='".$company_id."'
        AND ((w.pre_start_date IS NOT NULL) AND (w.pre_start_date != '0000-00-00') AND (w.pre_start_date != '1970-01-01'))
        AND ((w.pre_end_date IS NOT NULL) AND (w.pre_end_date != '0000-00-00') AND (w.pre_end_date != '1970-01-01'))
        AND ((w.pre_start_time IS NOT NULL) AND (w.pre_start_time != ''))
        AND ((w.pre_end_time IS NOT NULL) AND (w.pre_end_time != ''))
        AND ((CONCAT(w.pre_start_date,' ',STR_TO_DATE(w.pre_start_time, '%l:%i %p'))<='".$system_date."') 
        OR (CONCAT(w.pre_end_date,' ',STR_TO_DATE(w.pre_end_time, '%l:%i %p'))<='".$system_date."'))
        AND ('".$system_date."' NOT BETWEEN CONCAT(w.pre_start_date,' ',STR_TO_DATE(w.pre_start_time, '%l:%i %p')) AND 
        CONCAT(w.pre_end_date,' ',STR_TO_DATE(w.pre_end_time, '%l:%i %p'))) 
        GROUP BY w.id,workshop_session
        ORDER BY w.start_date DESC)
        UNION ALL
        (SELECT w.*,DATE_FORMAT(w.post_start_date,'%d.%m.%Y') as workshop_date,'POST' as workshop_session,
        IFNULL(wru.otp_verified,'N') as is_registered,count(wu.id) as restricted_users,
        IFNULL(wru.all_questions_fired,0) as all_questions_fired,IFNULL(wru.all_feedbacks_fired,0) as all_feedbacks_fired,
        IFNULL(wru.session_preclose,0) as session_preclose
        FROM workshop as w
        LEFT JOIN workshop_registered_users as wru ON (w.id = wru.workshop_id AND  
        (wru.user_id ='".$user_id."' AND  wru.workshop_session='POST' )) 
        LEFT JOIN workshop_users as wu ON (w.id = wu.workshop_id)
        WHERE w.status='1' AND w.company_id='".$company_id."'
        AND ((w.post_start_date IS NOT NULL) AND (w.post_start_date != '0000-00-00') AND (w.post_start_date != '1970-01-01'))
        AND ((w.post_end_date IS NOT NULL) AND (w.post_end_date != '0000-00-00') AND (w.post_end_date != '1970-01-01'))
        AND ((w.post_start_time IS NOT NULL) AND (w.post_start_time != ''))
        AND ((w.post_end_time IS NOT NULL) AND (w.post_end_time != ''))
        AND ((CONCAT(w.post_start_date,' ',STR_TO_DATE(w.post_start_time, '%l:%i %p'))<='".$system_date."') 
        OR (CONCAT(w.post_end_date,' ',STR_TO_DATE(w.post_end_time, '%l:%i %p'))<='".$system_date."'))
        AND ('".$system_date."' NOT BETWEEN CONCAT(w.post_start_date,' ',STR_TO_DATE(w.post_start_time, '%l:%i %p')) AND 
        CONCAT(w.post_end_date,' ',STR_TO_DATE(w.post_end_time, '%l:%i %p'))) 
        GROUP BY w.id,workshop_session
        ORDER BY w.start_date DESC)
        ) as wshop ORDER BY wshop.start_date DESC,wshop.id,wshop.workshop_session";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }

    public function fetch_workshop_registered_users_details() {
        $query = "SELECT * FROM workshop_registered_users";
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function fetch_workshop_details($company_id,$workshop_id,$atomdb=null) {
        $query = "SELECT hide_on_website,workshop_name,company_id,workshop_type,region,creation_date,timer,powered_by,
        short_description,long_description,workshop_url,workshop_image,questionset_type,post_question_type,start_date,end_date,
        pre_start_date,pre_start_time,pre_end_date,pre_end_time,IFNULL(pre_time_status,0) as pre_time_status,
        post_start_date,post_start_time,post_end_date,post_end_time,IFNULL(post_time_status,0) as post_time_status,
        point_multiplier,rupee_earn,feedbackform_id,otp,target,payback_option,play_only_once,IFNULL(play_all_feedback,0) as play_all_feedback,heading,message,
        remarks,remarks,IFNULL(fset_pre_trigger,0) as fset_pre_trigger,IFNULL(fset_post_trigger,0) as fset_post_trigger,
        DATE_FORMAT(start_date,'%d.%m.%Y') as workshop_date,questions_order
        FROM workshop 
        WHERE status='1' AND id='".$workshop_id."' AND company_id='".$company_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_pre_questions_count($company_id,$workshop_id,$user_id,$atomdb=null) {
        $fieldex_query      = "SHOW COLUMNS FROM workshop_questionset_pre LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $query = "SELECT sum(total) AS total_questions FROM(
            SELECT DISTINCT workshop_questionset_pre.questionset_id,
            IF(workshop_questionset_pre.questions_limit!='',workshop_questionset_pre.questions_limit,count(workshop_questions.question_id)) AS total 
            FROM workshop_questionset_pre 
            LEFT JOIN workshop_questions ON workshop_questionset_pre.questionset_id = workshop_questions.questionset_id 
            AND  workshop_questions.company_id= '".$company_id."' AND workshop_questions.workshop_id = '".$workshop_id."'
            WHERE workshop_questionset_pre.status='1' AND workshop_questionset_pre.active='1' AND workshop_questionset_pre.workshop_id = '".$workshop_id."'
            GROUP BY workshop_questionset_pre.questionset_id) AS main";
        }else{
            $query = "SELECT company_id,workshop_id,count(*) as total_questions 
            FROM workshop_questions
            WHERE company_id='".$company_id."' AND workshop_id = '".$workshop_id."' AND
            questionset_id IN (SELECT questionset_id FROM workshop_questionset_pre WHERE status='1' AND active='1' AND workshop_id = '".$workshop_id."')
            GROUP BY company_id,workshop_id";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_post_questions_count($company_id,$workshop_id,$user_id,$atomdb=null) {
        $fieldex_query      = "SHOW COLUMNS FROM workshop_questionset_post LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $query = "SELECT sum(total) AS total_questions FROM(
            SELECT DISTINCT workshop_questionset_post.questionset_id,
            IF(workshop_questionset_post.questions_limit!='',workshop_questionset_post.questions_limit,count(workshop_questions.question_id)) AS total 
            FROM workshop_questionset_post 
            LEFT JOIN workshop_questions ON workshop_questionset_post.questionset_id = workshop_questions.questionset_id 
            AND  workshop_questions.company_id= '".$company_id."' AND workshop_questions.workshop_id = '".$workshop_id."'
            WHERE workshop_questionset_post.status='1' AND workshop_questionset_post.active='1' AND workshop_questionset_post.workshop_id = '".$workshop_id."'
            GROUP BY workshop_questionset_post.questionset_id) AS main";
        }else{
            $query = "SELECT company_id,workshop_id,count(*) as total_questions 
            FROM workshop_questions
            WHERE company_id='".$company_id."' AND workshop_id = '".$workshop_id."' AND
            questionset_id IN (SELECT questionset_id FROM workshop_questionset_post WHERE status='1' AND active='1' AND workshop_id = '".$workshop_id."')
            GROUP BY company_id,workshop_id";
        }
        
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_pre_questions_count_all_days($company_id,$workshop_id,$user_id,$atomdb=null) {
        $query = "SELECT company_id,workshop_id,count(*) as total_questions 
                FROM workshop_questions
                WHERE company_id='".$company_id."' AND workshop_id = '".$workshop_id."' AND
                questionset_id IN (SELECT questionset_id FROM workshop_questionset_pre WHERE active='1' AND workshop_id = '".$workshop_id."')
                GROUP BY company_id,workshop_id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_post_questions_count_all_days($company_id,$workshop_id,$user_id,$atomdb=null) {
        $query = "SELECT company_id,workshop_id,count(*) as total_questions 
                FROM workshop_questions
                WHERE company_id='".$company_id."' AND workshop_id = '".$workshop_id."' AND
                questionset_id IN (SELECT questionset_id FROM workshop_questionset_post WHERE active='1' AND workshop_id = '".$workshop_id."')
                GROUP BY company_id,workshop_id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_pre_questions($company_id,$workshop_id,$user_id,$questions_order,$atomdb=null) {
        $fieldex_query      = "SHOW COLUMNS FROM workshop_questionset_pre LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $limit_query = "SELECT DISTINCT wqs.questionset_id,wqs.questions_limit,wqs.questions_order FROM workshop_questionset_pre AS wqs
            INNER JOIN question_set AS qs ON wqs.questionset_id = qs.id
            INNER JOIN workshop_questionset_trainer AS qst ON wqs.questionset_id = qst.questionset_id AND  wqs.workshop_id = qst.workshop_id
            WHERE wqs.status='1' AND wqs.active='1' AND wqs.workshop_id='".$workshop_id."'";
            $limit_result = $atomdb->query($limit_query);
            $questionset_result = $limit_result->result();
            $lquery = "";
            if (count((array)$questionset_result)>0){
                foreach ($questionset_result as $lrow) {
                    $questionset_id     = $lrow->questionset_id;
                    $questionset_limit  = $lrow->questions_limit;
                    $question_set_order = $lrow->questions_order;
                    $lquery .= " (SELECT
                    q.question_id,q.company_id,q.workshop_id,q.questionset_id,q.trainer_id,q.topic_id,q.subtopic_id,q.question_title,
                    q.option_a,q.option_b,q.option_c,q.option_d,q.correct_answer,q.tip,q.hint_image,q.youtube_link,c.company_name,
                    qt.description as topic,qst.description as sub_topic,q.sorting
                    FROM workshop_questions as q
                    INNER JOIN company as c ON (q.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
                    INNER JOIN question_topic as qt ON (q.topic_id = qt.id AND qt.`status`='1' AND qt.company_id='".$company_id."')
                    INNER JOIN question_subtopic as qst ON (q.subtopic_id = qst.id AND qst.`status`='1')
                    WHERE q.company_id='".$company_id."' AND q.workshop_id='".$workshop_id."' AND q.questionset_id='".$questionset_id ."' 
                    AND q.question_id NOT IN( SELECT question_id FROM atom_results as ar WHERE ar.company_id='".$company_id."' AND 
                    ar.user_id='".$user_id."' AND ar.workshop_id='".$workshop_id."' AND ar.questionset_id='".$questionset_id ."' AND ar.workshop_session='PRE')";
                    if ($question_set_order==''){
                        if ($questionset_limit!=''){
                            $lquery .= " ORDER BY ar.questionset_id ASC,RAND() LIMIT 0,".$questionset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY ar.questionset_id ASC,RAND() ) UNION ALL ";
                        }
                    }
                    if ($question_set_order==1){
                        if ($questionset_limit!=''){
                            $lquery .= " ORDER BY RAND() LIMIT 0,".$questionset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY RAND() ) UNION ALL ";
                        }
                    }
                    if ($question_set_order==2){
                        if ($questionset_limit!=''){
                            $lquery .= " ORDER BY q.sorting ASC,q.question_id ASC LIMIT 0,".$questionset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY q.sorting ASC,q.question_id ASC ) UNION ALL ";
                        }
                    }
                }
                if ($lquery!='') {
                    $lquery = substr($lquery,0,strlen($lquery)-10);
                }
            }
            $query = "SELECT 
                qm.question_id as id,qm.company_id,wqset.questionset_id,wqset.trainer_id,qm.topic_id,qm.subtopic_id,qm.question_title,
                qm.option_a,qm.option_b,qm.option_c,qm.option_d,qm.correct_answer,qm.tip,qm.hint_image,qm.youtube_link,
                qm.company_name,qm.topic,qm.sub_topic,wqset.title,wqset.powered_by,wqset.reward,wqset.weight,wqset.timer,wqset.hide_answer,
                wqset.questions_order FROM 
                (". $lquery.") as qm
                INNER JOIN 
                (SELECT
                wqs.workshop_id,wqs.questionset_id,qst.trainer_id,qst.topic_id,qst.subtopic_id,
                qs.title,qs.powered_by,qs.reward,qs.weight,qs.timer,wqs.hide_answer,wqs.questions_order
                FROM
                workshop_questionset_pre AS wqs
                INNER JOIN question_set AS qs ON wqs.questionset_id = qs.id
                INNER JOIN workshop_questionset_trainer AS qst ON wqs.questionset_id = qst.questionset_id AND  wqs.workshop_id = qst.workshop_id
                WHERE wqs.status='1' AND wqs.active='1' AND wqs.workshop_id='".$workshop_id."') as wqset
                ON (qm.questionset_id = wqset.questionset_id AND qm.topic_id = wqset.topic_id AND qm.subtopic_id = wqset.subtopic_id)";
        }else{
            $query = "SELECT 
                        qm.question_id as id,qm.company_id,wqset.questionset_id,wqset.trainer_id,qm.topic_id,qm.subtopic_id,qm.question_title,
                        qm.option_a,qm.option_b,qm.option_c,qm.option_d,qm.correct_answer,qm.tip,qm.hint_image,qm.youtube_link,
                        qm.company_name,qm.topic,qm.sub_topic,wqset.title,wqset.powered_by,wqset.reward,wqset.weight,wqset.timer,wqset.hide_answer,
                        wqset.questions_order FROM 
                        (SELECT
                        q.question_id,q.company_id,q.workshop_id,q.questionset_id,q.trainer_id,q.topic_id,q.subtopic_id,q.question_title,
                        q.option_a,q.option_b,q.option_c,q.option_d,q.correct_answer,q.tip,q.hint_image,q.youtube_link,c.company_name,
                        qt.description as topic,qst.description as sub_topic,q.sorting
                        FROM workshop_questions as q
                        INNER JOIN company as c ON (q.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
                        INNER JOIN question_topic as qt ON (q.topic_id = qt.id AND qt.`status`='1' AND qt.company_id='".$company_id."')
                        INNER JOIN question_subtopic as qst ON 
                        (q.subtopic_id = qst.id AND qst.`status`='1')
                        WHERE q.company_id='".$company_id."' AND q.workshop_id='".$workshop_id."'
                        AND q.question_id NOT IN( SELECT question_id FROM atom_results as ar WHERE ar.company_id='".$company_id."' AND 
                        ar.user_id='".$user_id."' AND ar.workshop_id='".$workshop_id."' AND ar.workshop_session='PRE')
                        ) as qm
                        INNER JOIN 
                        (SELECT
                        wqs.workshop_id,wqs.questionset_id,qst.trainer_id,qst.topic_id,qst.subtopic_id,
                        qs.title,qs.powered_by,qs.reward,qs.weight,qs.timer,wqs.hide_answer,wqs.questions_order
                        FROM
                        workshop_questionset_pre AS wqs
                        INNER JOIN question_set AS qs ON wqs.questionset_id = qs.id
                        INNER JOIN workshop_questionset_trainer AS qst ON wqs.questionset_id = qst.questionset_id AND  wqs.workshop_id = qst.workshop_id
                        WHERE wqs.status='1' AND wqs.active='1' AND wqs.workshop_id='".$workshop_id."') as wqset
                        ON (qm.questionset_id = wqset.questionset_id AND qm.topic_id = wqset.topic_id AND qm.subtopic_id = wqset.subtopic_id) ";
        }
        if ($questions_order==''){
            $query .= " ORDER BY wqset.questionset_id ASC,IF(wqset.questions_order=2,qm.sorting, RAND()) LIMIT 0,1";
        }
        if ($questions_order==1){
            $query .= " ORDER BY RAND() LIMIT 0,1";
        }
        if ($questions_order==2){
            $query .= " ORDER BY qm.sorting ASC,qm.question_id ASC LIMIT 0,1";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_post_questions($company_id,$workshop_id,$user_id,$questions_order,$atomdb=null) {
        $fieldex_query      = "SHOW COLUMNS FROM workshop_questionset_post LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){

            $limit_query = "SELECT DISTINCT wqs.questionset_id,wqs.questions_limit,wqs.questions_order FROM workshop_questionset_post AS wqs
            INNER JOIN question_set AS qs ON wqs.questionset_id = qs.id
            INNER JOIN workshop_questionset_trainer AS qst ON wqs.questionset_id = qst.questionset_id AND  wqs.workshop_id = qst.workshop_id
            WHERE wqs.status='1' AND wqs.active='1' AND wqs.workshop_id='".$workshop_id."'";
            $limit_result = $atomdb->query($limit_query);
            $questionset_result = $limit_result->result();
            $lquery = "";
            if (count((array)$questionset_result)>0){
                foreach ($questionset_result as $lrow) {
                    $questionset_id     = $lrow->questionset_id;
                    $questionset_limit  = $lrow->questions_limit;
                    $question_set_order = $lrow->questions_order;
                    $lquery .= " (SELECT
                    q.question_id,q.company_id,q.workshop_id,q.questionset_id,q.trainer_id,q.topic_id,q.subtopic_id,q.question_title,
                    q.option_a,q.option_b,q.option_c,q.option_d,q.correct_answer,q.tip,q.hint_image,q.youtube_link,c.company_name,
                    qt.description as topic,qst.description as sub_topic,q.sorting
                    FROM workshop_questions as q
                    INNER JOIN company as c ON (q.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
                    INNER JOIN question_topic as qt ON (q.topic_id = qt.id AND qt.`status`='1' AND qt.company_id='".$company_id."')
                    INNER JOIN question_subtopic as qst ON (q.subtopic_id = qst.id AND qst.`status`='1')
                    WHERE q.company_id='".$company_id."' AND q.workshop_id='".$workshop_id."' AND q.questionset_id='".$questionset_id ."' 
                    AND q.question_id NOT IN( SELECT question_id FROM atom_results as ar WHERE ar.company_id='".$company_id."' AND 
                    ar.user_id='".$user_id."' AND ar.workshop_id='".$workshop_id."' AND ar.questionset_id='".$questionset_id ."' AND ar.workshop_session='POST')";
                    if ($question_set_order==''){
                        if ($questionset_limit!=''){
                            $lquery .= " ORDER BY ar.questionset_id ASC,RAND() LIMIT 0,".$questionset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY ar.questionset_id ASC,RAND() ) UNION ALL ";
                        }
                    }
                    if ($question_set_order==1){
                        if ($questionset_limit!=''){
                            $lquery .= " ORDER BY RAND() LIMIT 0,".$questionset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY RAND() ) UNION ALL ";
                        }
                    }
                    if ($question_set_order==2){
                        if ($questionset_limit!=''){
                            $lquery .= " ORDER BY q.sorting ASC,q.question_id ASC LIMIT 0,".$questionset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY q.sorting ASC,q.question_id ASC ) UNION ALL ";
                        }
                    }
                }
                if ($lquery!='') {
                    $lquery = substr($lquery,0,strlen($lquery)-10);
                }
            }
            $query = "SELECT 
                qm.question_id as id,qm.company_id,wqset.questionset_id,wqset.trainer_id,qm.topic_id,qm.subtopic_id,qm.question_title,
                qm.option_a,qm.option_b,qm.option_c,qm.option_d,qm.correct_answer,qm.tip,qm.hint_image,qm.youtube_link,
                qm.company_name,qm.topic,qm.sub_topic,wqset.title,wqset.powered_by,wqset.reward,wqset.weight,wqset.timer,wqset.hide_answer,
                wqset.questions_order FROM 
                (". $lquery.") as qm
                INNER JOIN 
                (SELECT
                wqs.workshop_id,wqs.questionset_id,qst.trainer_id,qst.topic_id,qst.subtopic_id,
                qs.title,qs.powered_by,qs.reward,qs.weight,qs.timer,wqs.hide_answer,wqs.questions_order
                FROM
                workshop_questionset_post AS wqs
                INNER JOIN question_set AS qs ON wqs.questionset_id = qs.id
                INNER JOIN workshop_questionset_trainer AS qst ON wqs.questionset_id = qst.questionset_id AND  wqs.workshop_id = qst.workshop_id
                WHERE wqs.status='1' AND wqs.active='1' AND wqs.workshop_id='".$workshop_id."') as wqset
                ON (qm.questionset_id = wqset.questionset_id AND qm.topic_id = wqset.topic_id AND qm.subtopic_id = wqset.subtopic_id)";
        }else{
            $query = "SELECT 
                    qm.question_id as id,qm.company_id,wqset.questionset_id,wqset.trainer_id,qm.topic_id,qm.subtopic_id,qm.question_title,
                    qm.option_a,qm.option_b,qm.option_c,qm.option_d,qm.correct_answer,qm.tip,qm.hint_image,qm.youtube_link,
                    qm.company_name,qm.topic,qm.sub_topic,wqset.title,wqset.powered_by,wqset.reward,wqset.weight,wqset.timer,wqset.hide_answer,
                    wqset.questions_order	FROM 
                    (SELECT
                    q.question_id,q.company_id,q.workshop_id,q.questionset_id,q.trainer_id,q.topic_id,q.subtopic_id,q.question_title,
                    q.option_a,q.option_b,q.option_c,q.option_d,q.correct_answer,q.tip,q.hint_image,q.youtube_link,c.company_name,
                    qt.description as topic,qst.description as sub_topic,q.sorting
                    FROM workshop_questions as q
                    INNER JOIN company as c ON (q.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
                    INNER JOIN question_topic as qt ON (q.topic_id = qt.id AND qt.`status`='1' AND qt.company_id='".$company_id."')
                    INNER JOIN question_subtopic as qst ON 
                    (q.subtopic_id = qst.id AND qst.`status`='1')
                    WHERE q.company_id='".$company_id."' AND q.workshop_id='".$workshop_id."'
                    AND q.question_id NOT IN( SELECT question_id FROM atom_results as ar WHERE ar.company_id='".$company_id."' AND 
                    ar.user_id='".$user_id."' AND ar.workshop_id='".$workshop_id."' AND ar.workshop_session='POST')
                    ) as qm
                    INNER JOIN 
                    (SELECT
                    wqs.workshop_id,wqs.questionset_id,qst.trainer_id,qst.topic_id,qst.subtopic_id,
                    qs.title,qs.powered_by,qs.reward,qs.weight,qs.timer,wqs.hide_answer,wqs.questions_order
                    FROM
                    workshop_questionset_post AS wqs
                    INNER JOIN question_set AS qs ON wqs.questionset_id = qs.id
                    INNER JOIN workshop_questionset_trainer AS qst ON wqs.questionset_id = qst.questionset_id AND  wqs.workshop_id = qst.workshop_id
                    WHERE wqs.status='1' AND wqs.active='1' AND wqs.workshop_id='".$workshop_id."') as wqset
                    ON (qm.questionset_id = wqset.questionset_id AND qm.topic_id = wqset.topic_id AND qm.subtopic_id = wqset.subtopic_id)";
        }
        
        if ($questions_order==''){
            $query .= " ORDER BY wqset.questionset_id ASC,IF(wqset.questions_order=2,qm.sorting, RAND()) LIMIT 0,1";
        }
        if ($questions_order==1){
            $query .= " ORDER BY RAND() LIMIT 0,1";
        }
        if ($questions_order==2){
            $query .= " ORDER BY qm.sorting ASC,qm.question_id ASC LIMIT 0,1";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_pre_feedback_questions_count($company_id,$workshop_id,$user_id,$atomdb=null){
        $fieldex_query      = "SHOW COLUMNS FROM workshop_feedbackset_pre LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $query = "SELECT sum(total) AS total_questions  FROM
            (SELECT DISTINCT workshop_feedbackset_pre.feedbackset_id, 
            IF (workshop_feedbackset_pre.questions_limit != '', workshop_feedbackset_pre.questions_limit, count(workshop_feedback_questions.question_id)) AS total 
            FROM workshop_feedbackset_pre
            LEFT JOIN workshop_feedback_questions ON workshop_feedbackset_pre.feedbackset_id = workshop_feedback_questions.feedbackset_id
            AND workshop_feedback_questions.company_id = '".$company_id."'
            AND workshop_feedback_questions.workshop_id = '".$workshop_id."' 
            WHERE workshop_feedbackset_pre.STATUS = '1' AND workshop_feedbackset_pre.active = '1' AND workshop_feedbackset_pre.workshop_id = '".$workshop_id."' 
            GROUP BY workshop_feedbackset_pre.feedbackset_id) AS main";
        }else{
            $query = "SELECT company_id,workshop_id,count(*) as total_questions 
            FROM workshop_feedback_questions
            WHERE company_id='".$company_id."' AND workshop_id = '".$workshop_id."' AND
            feedbackset_id IN (SELECT feedbackset_id FROM workshop_feedbackset_pre WHERE status='1' AND active='1' AND workshop_id = '".$workshop_id."')
            GROUP BY company_id,workshop_id";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_post_feedback_questions_count($company_id,$workshop_id,$user_id,$atomdb=null){
        $fieldex_query      = "SHOW COLUMNS FROM workshop_feedbackset_post LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $query = "SELECT sum(total) AS total_questions  FROM
            (SELECT DISTINCT workshop_feedbackset_post.feedbackset_id, 
            IF (workshop_feedbackset_post.questions_limit != '', workshop_feedbackset_post.questions_limit, count(workshop_feedback_questions.question_id)) AS total 
            FROM workshop_feedbackset_post
            LEFT JOIN workshop_feedback_questions ON workshop_feedbackset_post.feedbackset_id = workshop_feedback_questions.feedbackset_id
            AND workshop_feedback_questions.company_id = '".$company_id."'
            AND workshop_feedback_questions.workshop_id = '".$workshop_id."' 
            WHERE workshop_feedbackset_post.STATUS = '1' AND workshop_feedbackset_post.active = '1' AND workshop_feedbackset_post.workshop_id = '".$workshop_id."' 
            GROUP BY workshop_feedbackset_post.feedbackset_id) AS main";
        }else{
            $query = "SELECT company_id,workshop_id,count(*) as total_questions 
            FROM workshop_feedback_questions
            WHERE company_id='".$company_id."' AND workshop_id = '".$workshop_id."' AND
            feedbackset_id IN (SELECT feedbackset_id FROM workshop_feedbackset_post WHERE status='1' AND active='1' AND workshop_id = '".$workshop_id."')
            GROUP BY company_id,workshop_id";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_pre_feedback_questions($company_id,$workshop_id,$user_id,$questions_order,$atomdb=null){
        $fieldex_query      = "SHOW COLUMNS FROM workshop_feedbackset_pre LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $limit_query = "SELECT DISTINCT wfs.feedbackset_id,wfs.questions_limit,wfs.questions_order FROM workshop_feedbackset_pre AS wfs
                INNER JOIN feedback AS f ON wfs.feedbackset_id = f.id
                INNER JOIN feedbackset_type AS fs ON wfs.feedbackset_id = fs.feedbackset_id AND wfs.active='1'
                WHERE wfs.status='1' AND wfs.workshop_id='".$workshop_id."'";
            $limit_result = $atomdb->query($limit_query);
            $feedbackset_result = $limit_result->result();
            $lquery = "";
            if (count((array)$feedbackset_result)>0){
                foreach ($feedbackset_result as $lrow) {
                    $feedbackset_id    = $lrow->feedbackset_id;
                    $feedbackset_limit = $lrow->questions_limit;
                    $feedbackset_order = $lrow->questions_order;

                    $lquery .= " (SELECT
                    fq.question_id,fq.company_id,c.company_name,fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_title,
                    IFNULL(fq.option_a,'') as option_a,fq.weight_a,IFNULL(fq.option_b,'') as option_b,fq.weight_b,
                    IFNULL(fq.option_c,'') as option_c,fq.weight_c,IFNULL(fq.option_d,'') as option_d,fq.weight_d,
                    IFNULL(fq.option_e,'') as option_e,fq.weight_e,IFNULL(fq.option_f,'') as option_f,fq.weight_f,
                    fq.multiple_allow,ft.description as type,fst.description as sub_type,fq.tip,fq.hint_image,fq.sorting,fq.question_type,fq.min_length,fq.max_length,fq.question_timer,fq.text_weightage
                    FROM workshop_feedback_questions as fq
                    INNER JOIN company as c ON (fq.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
                    INNER JOIN feedback_type as ft ON (fq.type_id= ft.id AND ft.`status`='1' AND ft.company_id='".$company_id."')
                    INNER JOIN feedback_subtype as fst ON 
                    (fq.subtype_id = fst.id AND fst.`status`='1')
                    WHERE fq.company_id='".$company_id."' AND fq.workshop_id='".$workshop_id."' AND fq.feedbackset_id='".$feedbackset_id ."'
                    AND CONCAT(fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_id) NOT IN (SELECT CONCAT(feedbackset_id,feedback_type_id,feedback_subtype_id,feedback_id) as question_id FROM atom_feedback as af WHERE af.company_id='".$company_id."' AND 
                    af.user_id='".$user_id."' AND af.workshop_id='".$workshop_id."' AND af.feedbackset_id='".$feedbackset_id ."' AND af.workshop_session='PRE' AND af.feedbackset_id IN 
                    (select tmpwqs.feedbackset_id from workshop_feedbackset_pre as tmpwqs WHERE tmpwqs.status='1' AND tmpwqs.active='1' AND tmpwqs.workshop_id='".$workshop_id."')
                    GROUP BY af.feedbackset_id";

                    if ($feedbackset_order==''){
                        if ($feedbackset_limit!=''){
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id,RAND() ) LIMIT 0,".$feedbackset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id,RAND() )) UNION ALL ";
                        }
                    }
                    if ($feedbackset_order==1){
                        if ($feedbackset_limit!=''){
                            $lquery .= " ORDER BY RAND() ) LIMIT 0,".$feedbackset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY RAND() )) UNION ALL ";
                        }
                    }
                    if ($feedbackset_order==2){
                        if ($feedbackset_limit!=''){
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id ) LIMIT 0,".$feedbackset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id )) UNION ALL ";
                        }
                    }

                }
                if ($lquery!='') {
                    $lquery = substr($lquery,0,strlen($lquery)-10);
                }
            }
            $query = "SELECT 
                fm.question_id as id,fm.company_id,fm.company_name,fm.feedbackset_id,fm.type_id,fm.subtype_id,fm.question_title,
                fm.option_a,fm.weight_a,fm.option_b,fm.weight_b,fm.option_c,fm.weight_c,fm.option_d,
                fm.weight_d,fm.option_e,fm.weight_e,fm.option_f,fm.weight_f,fm.multiple_allow,
                fqset.workshop_id,fqset.feedbackset_id,fqset.feedback_type_id,fqset.feedback_subtype_id,
                fqset.feedback_set_title,fqset.powered_by,fm.type,fm.sub_type,fqset.timer,fqset.questions_order,fm.tip,fm.hint_image,fm.sorting,fm.question_type,fm.min_length,fm.max_length,fm.question_timer,fm.text_weightage FROM 
                (". $lquery.") as fm
                INNER JOIN 
                (SELECT
                wfs.workshop_id,wfs.feedbackset_id,fs.feedback_type_id,fs.feedback_subtype_id,
                f.title as feedback_set_title,f.powered_by,f.timer,wfs.questions_order
                FROM
                workshop_feedbackset_pre AS wfs
                INNER JOIN feedback AS f ON wfs.feedbackset_id = f.id
                INNER JOIN feedbackset_type AS fs ON wfs.feedbackset_id = fs.feedbackset_id AND wfs.active='1'
                WHERE wfs.status='1' AND wfs.workshop_id='".$workshop_id."') as fqset
                ON (fm.feedbackset_id=fqset.feedbackset_id AND fm.type_id = fqset.feedback_type_id AND fm.subtype_id = fqset.feedback_subtype_id)
                ";
        }else{
            $query = "SELECT 
            fm.question_id as id,fm.company_id,fm.company_name,fm.feedbackset_id,fm.type_id,fm.subtype_id,fm.question_title,
            fm.option_a,fm.weight_a,fm.option_b,fm.weight_b,fm.option_c,fm.weight_c,fm.option_d,
            fm.weight_d,fm.option_e,fm.weight_e,fm.option_f,fm.weight_f,fm.multiple_allow,
            fqset.workshop_id,fqset.feedbackset_id,fqset.feedback_type_id,fqset.feedback_subtype_id,
            fqset.feedback_set_title,fqset.powered_by,fm.type,fm.sub_type,fqset.timer,fqset.questions_order,fm.tip,fm.hint_image,fm.sorting,fm.question_type,fm.min_length,fm.max_length,fm.question_timer,fm.text_weightage FROM 
            (SELECT
            fq.question_id,fq.company_id,c.company_name,fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_title,
            IFNULL(fq.option_a,'') as option_a,fq.weight_a,IFNULL(fq.option_b,'') as option_b,fq.weight_b,
            IFNULL(fq.option_c,'') as option_c,fq.weight_c,IFNULL(fq.option_d,'') as option_d,fq.weight_d,
            IFNULL(fq.option_e,'') as option_e,fq.weight_e,IFNULL(fq.option_f,'') as option_f,fq.weight_f,
            fq.multiple_allow,ft.description as type,fst.description as sub_type,fq.tip,fq.hint_image,fq.sorting,fq.question_type,fq.min_length,fq.max_length,fq.question_timer,fq.text_weightage
            FROM workshop_feedback_questions as fq
            INNER JOIN company as c ON (fq.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
            INNER JOIN feedback_type as ft ON (fq.type_id= ft.id AND ft.`status`='1' AND ft.company_id='".$company_id."')
            INNER JOIN feedback_subtype as fst ON 
            (fq.subtype_id = fst.id AND fst.`status`='1')
            WHERE fq.company_id='".$company_id."' AND fq.workshop_id='".$workshop_id."'
            AND CONCAT(fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_id) NOT IN (SELECT CONCAT(feedbackset_id,feedback_type_id,feedback_subtype_id,feedback_id) as question_id FROM atom_feedback as af WHERE af.company_id='".$company_id."' AND 
            af.user_id='".$user_id."' AND af.workshop_id='".$workshop_id."' AND af.workshop_session='PRE' AND af.feedbackset_id IN 
            (select tmpwqs.feedbackset_id from workshop_feedbackset_pre as tmpwqs WHERE tmpwqs.status='1' AND tmpwqs.active='1' AND tmpwqs.workshop_id='".$workshop_id."')
            GROUP BY af.feedbackset_id
            ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id
            )) as fm
            INNER JOIN 
            (SELECT
            wfs.workshop_id,wfs.feedbackset_id,fs.feedback_type_id,fs.feedback_subtype_id,
            f.title as feedback_set_title,f.powered_by,f.timer,wfs.questions_order
            FROM
            workshop_feedbackset_pre AS wfs
            INNER JOIN feedback AS f ON wfs.feedbackset_id = f.id
            INNER JOIN feedbackset_type AS fs ON wfs.feedbackset_id = fs.feedbackset_id AND wfs.active='1'
            WHERE wfs.status='1' AND wfs.workshop_id='".$workshop_id."') as fqset
            ON (fm.feedbackset_id=fqset.feedbackset_id AND fm.type_id = fqset.feedback_type_id AND fm.subtype_id = fqset.feedback_subtype_id) ";
        }
        
        if ($questions_order==''){
            $query .= " ORDER BY fqset.feedbackset_id ASC,IF(fqset.questions_order=2,fm.sorting, RAND()) LIMIT 0,1";
        }
        if ($questions_order==1){
            $query .= " ORDER BY RAND() LIMIT 0,1";
        }
        if ($questions_order==2){
            $query .= " ORDER BY fm.sorting ASC,fm.question_id ASC LIMIT 0,1";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_post_feedback_questions($company_id,$workshop_id,$user_id,$questions_order,$atomdb=null){
        $fieldex_query      = "SHOW COLUMNS FROM workshop_feedbackset_post LIKE 'questions_limit'";
        $fieldex_result     = $atomdb->query($fieldex_query);
        $field_exist_result = $fieldex_result->result();
        if (count((array)$field_exist_result)>0){
            $limit_query = "SELECT DISTINCT wfs.feedbackset_id,wfs.questions_limit,wfs.questions_order FROM workshop_feedbackset_post AS wfs
                INNER JOIN feedback AS f ON wfs.feedbackset_id = f.id
                INNER JOIN feedbackset_type AS fs ON wfs.feedbackset_id = fs.feedbackset_id AND wfs.active='1'
                WHERE wfs.status='1' AND wfs.workshop_id='".$workshop_id."'";
            $limit_result = $atomdb->query($limit_query);
            $feedbackset_result = $limit_result->result();
            $lquery = "";
            if (count((array)$feedbackset_result)>0){
                foreach ($feedbackset_result as $lrow) {
                    $feedbackset_id    = $lrow->feedbackset_id;
                    $feedbackset_limit = $lrow->questions_limit;
                    $feedbackset_order = $lrow->questions_order;

                    $lquery .= " (SELECT
                    fq.question_id,fq.company_id,c.company_name,fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_title,
                    IFNULL(fq.option_a,'') as option_a,fq.weight_a,IFNULL(fq.option_b,'') as option_b,fq.weight_b,
                    IFNULL(fq.option_c,'') as option_c,fq.weight_c,IFNULL(fq.option_d,'') as option_d,fq.weight_d,
                    IFNULL(fq.option_e,'') as option_e,fq.weight_e,IFNULL(fq.option_f,'') as option_f,fq.weight_f,
                    fq.multiple_allow,ft.description as type,fst.description as sub_type,fq.tip,fq.hint_image,fq.sorting,fq.question_type,fq.min_length,fq.max_length,fq.question_timer,fq.text_weightage
                    FROM workshop_feedback_questions as fq
                    INNER JOIN company as c ON (fq.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
                    INNER JOIN feedback_type as ft ON (fq.type_id= ft.id AND ft.`status`='1' AND ft.company_id='".$company_id."')
                    INNER JOIN feedback_subtype as fst ON 
                    (fq.subtype_id = fst.id AND fst.`status`='1')
                    WHERE fq.company_id='".$company_id."' AND fq.workshop_id='".$workshop_id."' AND fq.feedbackset_id='".$feedbackset_id ."'
                    AND CONCAT(fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_id) NOT IN (SELECT CONCAT(feedbackset_id,feedback_type_id,feedback_subtype_id,feedback_id) as question_id FROM atom_feedback as af WHERE af.company_id='".$company_id."' AND 
                    af.user_id='".$user_id."' AND af.workshop_id='".$workshop_id."' AND af.feedbackset_id='".$feedbackset_id ."' AND af.workshop_session='POST' AND af.feedbackset_id IN 
                    (select tmpwqs.feedbackset_id from workshop_feedbackset_post as tmpwqs WHERE tmpwqs.status='1' AND tmpwqs.active='1' AND tmpwqs.workshop_id='".$workshop_id."')
                    GROUP BY af.feedbackset_id";

                    if ($feedbackset_order==''){
                        if ($feedbackset_limit!=''){
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id,RAND() ) LIMIT 0,".$feedbackset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id,RAND() )) UNION ALL ";
                        }
                    }
                    if ($feedbackset_order==1){
                        if ($feedbackset_limit!=''){
                            $lquery .= " ORDER BY RAND() ) LIMIT 0,".$feedbackset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY RAND() )) UNION ALL ";
                        }
                    }
                    if ($feedbackset_order==2){
                        if ($feedbackset_limit!=''){
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id ) LIMIT 0,".$feedbackset_limit.") UNION ALL ";
                        }else{
                            $lquery .= " ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id )) UNION ALL ";
                        }
                    }
                }
                if ($lquery!='') {
                    $lquery = substr($lquery,0,strlen($lquery)-10);
                }
            }
            $query = "SELECT 
                fm.question_id as id,fm.company_id,fm.company_name,fm.feedbackset_id,fm.type_id,fm.subtype_id,fm.question_title,
                fm.option_a,fm.weight_a,fm.option_b,fm.weight_b,fm.option_c,fm.weight_c,fm.option_d,
                fm.weight_d,fm.option_e,fm.weight_e,fm.option_f,fm.weight_f,fm.multiple_allow,
                fqset.workshop_id,fqset.feedbackset_id,fqset.feedback_type_id,fqset.feedback_subtype_id,
                fqset.feedback_set_title,fqset.powered_by,fm.type,fm.sub_type,fqset.timer,fqset.questions_order,fm.tip,fm.hint_image,fm.sorting,fm.question_type,fm.min_length,fm.max_length,fm.question_timer,fm.text_weightage FROM 
                (". $lquery.") as fm
                INNER JOIN 
                (SELECT
                wfs.workshop_id,wfs.feedbackset_id,fs.feedback_type_id,fs.feedback_subtype_id,
                f.title as feedback_set_title,f.powered_by,f.timer,wfs.questions_order
                FROM
                workshop_feedbackset_post AS wfs
                INNER JOIN feedback AS f ON wfs.feedbackset_id = f.id
                INNER JOIN feedbackset_type AS fs ON wfs.feedbackset_id = fs.feedbackset_id AND wfs.active='1'
                WHERE wfs.status='1' AND wfs.workshop_id='".$workshop_id."') as fqset
                ON (fm.feedbackset_id=fqset.feedbackset_id AND fm.type_id = fqset.feedback_type_id AND fm.subtype_id = fqset.feedback_subtype_id)";
        }else{
            $query = "SELECT 
            fm.question_id as id,fm.company_id,fm.company_name,fm.feedbackset_id,fm.type_id,fm.subtype_id,fm.question_title,
            fm.option_a,fm.weight_a,fm.option_b,fm.weight_b,fm.option_c,fm.weight_c,fm.option_d,
            fm.weight_d,fm.option_e,fm.weight_e,fm.option_f,fm.weight_f,fm.multiple_allow,
            fqset.workshop_id,fqset.feedbackset_id,fqset.feedback_type_id,fqset.feedback_subtype_id,
            fqset.feedback_set_title,fqset.powered_by,fm.type,fm.sub_type,fqset.timer,fqset.questions_order,fm.tip,fm.hint_image,fm.sorting,fm.question_type,fm.min_length,fm.max_length,fm.question_timer,fm.text_weightage FROM 
            (SELECT
            fq.question_id,fq.company_id,c.company_name,fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_title,
            IFNULL(fq.option_a,'') as option_a,fq.weight_a,IFNULL(fq.option_b,'') as option_b,fq.weight_b,
            IFNULL(fq.option_c,'') as option_c,fq.weight_c,IFNULL(fq.option_d,'') as option_d,fq.weight_d,
            IFNULL(fq.option_e,'') as option_e,fq.weight_e,IFNULL(fq.option_f,'') as option_f,fq.weight_f,
            fq.multiple_allow,ft.description as type,fst.description as sub_type,fq.tip,fq.hint_image,fq.sorting,fq.question_type,fq.min_length,fq.max_length,fq.question_timer,fq.text_weightage
            FROM workshop_feedback_questions as fq
            INNER JOIN company as c ON (fq.company_id= c.id AND c.`status`='1' AND c.id='".$company_id."')
            INNER JOIN feedback_type as ft ON (fq.type_id= ft.id AND ft.`status`='1' AND ft.company_id='".$company_id."')
            INNER JOIN feedback_subtype as fst ON 
            (fq.subtype_id = fst.id AND fst.`status`='1')
            WHERE fq.company_id='".$company_id."' AND fq.workshop_id='".$workshop_id."'
            AND CONCAT(fq.feedbackset_id,fq.type_id,fq.subtype_id,fq.question_id) NOT IN (SELECT CONCAT(feedbackset_id,feedback_type_id,feedback_subtype_id,feedback_id) as question_id FROM atom_feedback as af WHERE af.company_id='".$company_id."' AND 
            af.user_id='".$user_id."' AND af.workshop_id='".$workshop_id."' AND af.workshop_session='POST' AND af.feedbackset_id IN 
            (select tmpwqs.feedbackset_id from workshop_feedbackset_post as tmpwqs WHERE tmpwqs.status='1' AND tmpwqs.active='1' AND tmpwqs.workshop_id='".$workshop_id."')
            GROUP BY af.feedbackset_id
            ORDER BY fq.feedbackset_id,fq.type_id,fq.subtype_id
            )) as fm
            INNER JOIN 
            (SELECT
            wfs.workshop_id,wfs.feedbackset_id,fs.feedback_type_id,fs.feedback_subtype_id,
            f.title as feedback_set_title,f.powered_by,f.timer,wfs.questions_order
            FROM
            workshop_feedbackset_post AS wfs
            INNER JOIN feedback AS f ON wfs.feedbackset_id = f.id
            INNER JOIN feedbackset_type AS fs ON wfs.feedbackset_id = fs.feedbackset_id AND  wfs.active='1'
            WHERE wfs.status='1' AND wfs.workshop_id='".$workshop_id."') as fqset
            ON (fm.feedbackset_id=fqset.feedbackset_id AND fm.type_id = fqset.feedback_type_id AND fm.subtype_id = fqset.feedback_subtype_id) ";
        }
        
        if ($questions_order==''){            
            $query .= " ORDER BY fqset.feedbackset_id ASC,IF(fqset.questions_order=2,fm.sorting, RAND()) LIMIT 0,1";
        }
        if ($questions_order==1){
            $query .= " ORDER BY RAND() LIMIT 0,1";
        }
        if ($questions_order==2){
            $query .= " ORDER BY fm.sorting ASC,fm.question_id ASC LIMIT 0,1";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_atom_results_count($company_id,$workshop_id,$user_id,$workshop_session,$atomdb=null){
        if ($workshop_session=='PRE'){
            $query = "SELECT count(id) as total FROM atom_results
            WHERE  company_id='".$company_id."' AND user_id='".$user_id."' 
            AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'
            AND questionset_id IN (SELECT questionset_id FROM workshop_questionset_pre WHERE `status`='1' and active='1' and workshop_id='".$workshop_id."')";
        }
        if ($workshop_session=='POST'){
            $query = "SELECT count(id) as total FROM atom_results
            WHERE  company_id='".$company_id."' AND user_id='".$user_id."' 
            AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'
            AND questionset_id IN (SELECT questionset_id FROM workshop_questionset_post WHERE `status`='1' and active='1' and workshop_id='".$workshop_id."')";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_atom_feedback_count($company_id,$workshop_id,$user_id,$workshop_session,$atomdb=null){
        if ($workshop_session=='PRE'){
            $query = "SELECT count(id) as total FROM atom_feedback
                WHERE  company_id='".$company_id."' AND user_id='".$user_id."' 
                AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'
                AND feedbackset_id IN (SELECT feedbackset_id FROM workshop_feedbackset_pre WHERE `status`='1' AND active='1' and workshop_id='".$workshop_id."')";
        }
        if ($workshop_session=='POST'){
            $query = "SELECT count(id) as total FROM atom_feedback
                WHERE  company_id='".$company_id."' AND user_id='".$user_id."' 
                AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'
                AND feedbackset_id IN (SELECT feedbackset_id FROM workshop_feedbackset_post WHERE `status`='1' AND active='1' and workshop_id='".$workshop_id."')";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_atom_feedback_count_full($company_id,$workshop_id,$user_id,$workshop_session,$atomdb=null){
        if ($workshop_session=='PRE'){
            $query = "SELECT count(id) as total FROM atom_feedback
                WHERE  company_id='".$company_id."' AND user_id='".$user_id."' 
                AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'";
        }
        if ($workshop_session=='POST'){
            $query = "SELECT count(id) as total FROM atom_feedback
                WHERE  company_id='".$company_id."' AND user_id='".$user_id."' 
                AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->row();
        }else{
            $result = $atomdb->query($query);
            return $result->row();
        }
    }
    public function fetch_completed_score($company_id,$user_id,$workshop_id,$workshop_session,$atomdb=null){
        $query = "SELECT IFNULL(SUM(is_correct),0) as correct ,IFNULL(SUM(is_wrong),0) as wrong,IFNULL(SUM(is_timeout),0) as time_out 
        FROM atom_results WHERE company_id='".$company_id."' AND user_id='".$user_id."' 
        AND workshop_id='".$workshop_id."' AND workshop_session='".$workshop_session."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_information_form($company_id,$form_id,$atomdb=null){
        $query = 'SELECT ffh.id as form_header_id,ffh.form_name,ffh.short_description,
        ffh.`status`,ffd.id as form_detail_id,ffd.field_name,ffd.field_display_name,ffd.field_type,
        ffd.default_value,ffd.is_required 
        FROM feedback_form_header as ffh
        INNER JOIN feedback_form_details as ffd ON ffh.id = ffd.header_id AND ffd.`status`="1"
        WHERE ffh.`status` ="1" AND ffh.id ="'.$form_id.'" AND ffh.company_id="'.$company_id.'"';
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_cmplt_wksh_prepost_session($company_id,$user_id,$atomdb=null){
        $system_date = date('Y-m-d H:i:s');
        $query = "select DISTINCT w.id,w.workshop_name,w.pre_start_date,w.pre_start_time,w.pre_end_date,w.pre_end_time, 
                w.post_start_date,w.post_start_time,w.post_end_date,w.post_end_time
                FROM workshop as w 
                INNER JOIN workshop_registered_users as wru ON (w.id = wru.workshop_id AND  
                (wru.user_id ='".$user_id."' OR (wru.all_questions_fired='1' AND all_feedbacks_fired='1') )) 
                WHERE w.status='1' AND w.company_id='".$company_id."'
                AND ((w.pre_start_date IS NOT NULL) AND (w.pre_start_date != '0000-00-00') AND (w.pre_start_date != '1970-01-01'))
                AND ((w.pre_end_date IS NOT NULL) AND (w.pre_end_date != '0000-00-00') AND (w.pre_end_date != '1970-01-01'))
                AND ((w.pre_start_time IS NOT NULL) AND (w.pre_start_time != ''))
                AND ((w.pre_end_time IS NOT NULL) AND (w.pre_end_time != ''))
                AND ((w.post_start_date IS NOT NULL) AND (w.post_start_date != '0000-00-00') AND (w.post_start_date != '1970-01-01'))
                AND ((w.post_end_date IS NOT NULL) AND (w.post_end_date != '0000-00-00') AND (w.post_end_date != '1970-01-01'))
                AND ((w.post_start_time IS NOT NULL) AND (w.post_start_time != ''))
                AND ((w.post_end_time IS NOT NULL) AND (w.post_end_time != ''))

                AND ((CONCAT(w.pre_start_date,' ',STR_TO_DATE(w.pre_start_time, '%l:%i %p'))<='".$system_date."') 
                OR (CONCAT(w.pre_end_date,' ',STR_TO_DATE(w.pre_end_time, '%l:%i %p'))<='".$system_date."'))
                AND ('".$system_date."' NOT BETWEEN CONCAT(w.pre_start_date,' ',STR_TO_DATE(w.pre_start_time, '%l:%i %p')) AND 
                CONCAT(w.pre_end_date,' ',STR_TO_DATE(w.pre_end_time, '%l:%i %p'))) 
                
                AND ((CONCAT(w.post_start_date,' ',STR_TO_DATE(w.post_start_time, '%l:%i %p'))<='".$system_date."') 
                OR (CONCAT(w.post_end_date,' ',STR_TO_DATE(w.post_end_time, '%l:%i %p'))<='".$system_date."'))
                AND ('".$system_date."' NOT BETWEEN CONCAT(w.post_start_date,' ',STR_TO_DATE(w.post_start_time, '%l:%i %p')) AND 
                CONCAT(w.post_end_date,' ',STR_TO_DATE(w.post_end_time, '%l:%i %p'))) 
                ORDER BY CONCAT(w.post_end_date,' ',STR_TO_DATE(w.post_end_time, '%l:%i %p')) DESC, 
                CONCAT(w.post_start_date,' ',STR_TO_DATE(w.post_start_time, '%l:%i %p')) DESC";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_play_result_exists($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "select workshop_session,sum(is_correct),sum(is_wrong),sum(is_timeout) from atom_results 
                WHERE company_id='".$company_id."' and user_id = '".$user_id."' and workshop_id = '".$workshop_id."'
                GROUP BY company_id,user_id,workshop_id,workshop_session";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }      
    }
    public function fetch_wksh_user_pre_post_accuracy($company_id,$user_id,$workshop_id,$is_tester,$atomdb=null){
        if ($is_tester=='Y'){
            $query = "select 
                result.user_id,
                result.fullname,
                result.workshop_id,
                result.workshop_name,
                result.pre_total_questions,
                result.pre_correct,
                result.post_total_questions,
                result.post_correct,
                result.pre_total_seconds_taken,
                result.post_total_seconds_taken,
                IFNULL(ROUND(AVG(result.pre_average),2),0) as pre_average,
                IFNULL(ROUND(AVG(result.post_average),2),0) as post_average,
                IFNULL(ROUND(AVG(result.post_average) - AVG(result.pre_average),2),0) as ce,
                result.sortby,
                @dcp as previous,
                CONVERT((SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ),UNSIGNED INTEGER) AS rank,
                @dcp := CONCAT(post_average,post_total_seconds_taken) as current
                FROM (
                SELECT prpo.user_id,prpo.fullname,
                prpo.workshop_id,
                w.workshop_name,
                prpo.topic_id,
                qt.description as topic_name,
                prpo.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(prpo.pre_correct) as pre_correct,
                sum(prpo.post_correct) as post_correct, 
                sum(prpo.pre_total_questions) as pre_total_questions,
                sum(prpo.post_total_questions) as post_total_questions,
                sum(prpo.pre_total_seconds) as pre_total_seconds,
                FORMAT(sum(prpo.pre_total_seconds_taken)/sum(prpo.pre_total_questions),2) as pre_total_seconds_taken,
                sum(prpo.post_total_seconds) as post_total_seconds,
                ROUND(sum(prpo.post_total_seconds_taken)/sum(prpo.post_total_questions),2) as post_total_seconds_taken,
                ROUND(((sum(prpo.pre_correct)*100)/sum(prpo.pre_total_questions)),2) as pre_average,
                ROUND(((sum(prpo.post_correct)*100)/sum(prpo.post_total_questions)),2) as post_average,
                ROUND((((sum(prpo.post_correct)*100)/sum(prpo.post_total_questions)) - ((sum(prpo.pre_correct)*100)/sum(prpo.pre_total_questions))),2) as ce,
                CAST(CONCAT(sum(prpo.post_correct),sum(prpo.post_total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,prwq.total_questions as pre_total_questions,0 as post_total_questions  FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,atom_results.trainer_id,
                atom_results.workshop_id,atom_results.workshop_session,atom_results.questionset_id,
                atom_results.topic_id,atom_results.subtopic_id,
                sum(atom_results.is_correct) as pre_correct,0 as post_correct,
                sum(atom_results.timer) as pre_total_seconds,sum(atom_results.seconds) as pre_total_seconds_taken,0 as post_total_seconds,0 as post_total_seconds_taken
                FROM atom_results 
                INNER JOIN device_users ON atom_results.company_id = device_users.company_id 
                AND atom_results.user_id = device_users.user_id
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'PRE'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_pre as wpr ON 
                wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.status='1' and wpr.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as prwq
                ON ar.company_id = prwq.company_id AND ar.workshop_id = prwq.workshop_id 
                AND ar.questionset_id= prwq.questionset_id AND ar.trainer_id = prwq.trainer_id
                AND ar.topic_id = prwq.topic_id
                AND ar.subtopic_id = prwq.subtopic_id
                UNION ALL
                SELECT ar.*,0 as pre_total_questions,powq.total_questions as post_total_questions  FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,atom_results.trainer_id,
                atom_results.workshop_id,atom_results.workshop_session,atom_results.questionset_id,
                atom_results.topic_id,atom_results.subtopic_id,
                0 as pre_correct,sum(atom_results.is_correct) as post_correct,
                0 as pre_total_seconds, 0 as pre_total_seconds_taken,sum(atom_results.timer) as post_total_seconds,sum(atom_results.seconds) as post_total_seconds_taken
                FROM atom_results 
                INNER JOIN device_users ON atom_results.company_id = device_users.company_id 
                AND atom_results.user_id = device_users.user_id
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'POST'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_post as wpo ON 
                wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.status='1' AND wpo.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as powq
                ON ar.company_id = powq.company_id AND ar.workshop_id = powq.workshop_id 
                AND ar.questionset_id= powq.questionset_id AND ar.trainer_id = powq.trainer_id
                AND ar.topic_id = powq.topic_id
                AND ar.subtopic_id = powq.subtopic_id
                ) as prpo
                INNER JOIN workshop as w ON prpo.workshop_id = w.id AND prpo.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON prpo.topic_id = qt.id AND prpo.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON prpo.subtopic_id = qst.id
                GROUP BY prpo.user_id,prpo.workshop_id
                ORDER BY post_average DESC,post_total_seconds_taken ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                GROUP BY result.user_id,result.workshop_id
                HAVING user_id='".$user_id."'
                ORDER BY rank ASC,fullname ASC";
        }else{
            $query = "select 
                result.user_id,
                result.fullname,
                result.workshop_id,
                result.workshop_name,
                result.pre_total_questions,
                result.pre_correct,
                result.post_total_questions,
                result.post_correct,
                result.pre_total_seconds_taken,
                result.post_total_seconds_taken,
                IFNULL(ROUND(AVG(result.pre_average),2),0) as pre_average,
                IFNULL(ROUND(AVG(result.post_average),2),0) as post_average,
                IFNULL(ROUND(AVG(result.post_average) - AVG(result.pre_average),2),0) as ce,
                result.sortby,
                @dcp as previous,
                CONVERT((SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ),UNSIGNED INTEGER) AS rank,
                @dcp := CONCAT(post_average,post_total_seconds_taken) as current
                FROM (
                SELECT prpo.user_id,prpo.fullname,
                prpo.workshop_id,
                w.workshop_name,
                prpo.topic_id,
                qt.description as topic_name,
                prpo.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(prpo.pre_correct) as pre_correct,
                sum(prpo.post_correct) as post_correct, 
                sum(prpo.pre_total_questions) as pre_total_questions,
                sum(prpo.post_total_questions) as post_total_questions,
                sum(prpo.pre_total_seconds) as pre_total_seconds,
                FORMAT(sum(prpo.pre_total_seconds_taken)/sum(prpo.pre_total_questions),2) as pre_total_seconds_taken,
                sum(prpo.post_total_seconds) as post_total_seconds,
                ROUND(sum(prpo.post_total_seconds_taken)/sum(prpo.post_total_questions),2) as post_total_seconds_taken,
                ROUND(((sum(prpo.pre_correct)*100)/sum(prpo.pre_total_questions)),2) as pre_average,
                ROUND(((sum(prpo.post_correct)*100)/sum(prpo.post_total_questions)),2) as post_average,
                ROUND((((sum(prpo.post_correct)*100)/sum(prpo.post_total_questions)) - ((sum(prpo.pre_correct)*100)/sum(prpo.pre_total_questions))),2) as ce,
                CAST(CONCAT(sum(prpo.post_correct),sum(prpo.post_total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,prwq.total_questions as pre_total_questions,0 as post_total_questions  FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,atom_results.trainer_id,
                atom_results.workshop_id,atom_results.workshop_session,atom_results.questionset_id,
                atom_results.topic_id,atom_results.subtopic_id,
                sum(atom_results.is_correct) as pre_correct,0 as post_correct,
                sum(atom_results.timer) as pre_total_seconds,sum(atom_results.seconds) as pre_total_seconds_taken,0 as post_total_seconds,0 as post_total_seconds_taken
                FROM atom_results 
                INNER JOIN device_users ON atom_results.company_id = device_users.company_id 
                AND atom_results.user_id = device_users.user_id  AND device_users.istester!='1'
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'PRE'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_pre as wpr ON 
                wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.status='1' and wpr.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as prwq
                ON ar.company_id = prwq.company_id AND ar.workshop_id = prwq.workshop_id 
                AND ar.questionset_id= prwq.questionset_id AND ar.trainer_id = prwq.trainer_id
                AND ar.topic_id = prwq.topic_id
                AND ar.subtopic_id = prwq.subtopic_id
                UNION ALL
                SELECT ar.*,0 as pre_total_questions,powq.total_questions as post_total_questions  FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,atom_results.trainer_id,
                atom_results.workshop_id,atom_results.workshop_session,atom_results.questionset_id,
                atom_results.topic_id,atom_results.subtopic_id,
                0 as pre_correct,sum(atom_results.is_correct) as post_correct,
                0 as pre_total_seconds, 0 as pre_total_seconds_taken,sum(atom_results.timer) as post_total_seconds,sum(atom_results.seconds) as post_total_seconds_taken
                FROM atom_results 
                INNER JOIN device_users ON atom_results.company_id = device_users.company_id 
                AND atom_results.user_id = device_users.user_id  AND device_users.istester!='1'
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'POST'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_post as wpo ON 
                wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.status='1' AND wpo.active='1'
                WHERE wq.company_id='".$company_id."' AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as powq
                ON ar.company_id = powq.company_id AND ar.workshop_id = powq.workshop_id 
                AND ar.questionset_id= powq.questionset_id AND ar.trainer_id = powq.trainer_id
                AND ar.topic_id = powq.topic_id
                AND ar.subtopic_id = powq.subtopic_id
                ) as prpo
                INNER JOIN workshop as w ON prpo.workshop_id = w.id AND prpo.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON prpo.topic_id = qt.id AND prpo.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON prpo.subtopic_id = qst.id
                GROUP BY prpo.user_id,prpo.workshop_id
                ORDER BY post_average DESC,post_total_seconds_taken ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                GROUP BY result.user_id,result.workshop_id
                HAVING user_id='".$user_id."'
                ORDER BY rank ASC,fullname ASC";
        }
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }                
    }
    function fetch_wksh_user_best_pre_topic($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT 
            result.workshop_id,
            result.workshop_name,
            result.topic_id,
            result.topic_name,
            result.correct,
            result.total_questions,
            result.average,
            result.total_seconds_taken,
            (SELECT @cnt :=  @cnt + 1 ) AS rank  FROM
            (SELECT wkavg.user_id,
            wkavg.workshop_id,
            w.workshop_name,
            wkavg.topic_id,
            qt.description as topic_name,
            sum(wkavg.correct) as correct,
            sum(wkavg.total_questions) as total_questions,
            CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_questions)),2) AS DECIMAL(11,2)) as average,
            sum(wkavg.total_seconds_taken) as total_seconds_taken
            FROM(
            SELECT ar.*,wses.total_questions FROM 
            (SELECT company_id,user_id,trainer_id,workshop_id,workshop_session,questionset_id,topic_id,
            sum(is_correct) as correct,
            sum(timer) as total_seconds,sum(seconds) as total_seconds_taken
            FROM atom_results 
            WHERE company_id='".$company_id."' AND 
            user_id='".$user_id."' AND
            workshop_id='".$workshop_id."' AND
            workshop_session = 'PRE'
            GROUP BY company_id,user_id,trainer_id,workshop_id,topic_id) as ar
            INNER JOIN
            (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,count(wq.question_id) as total_questions 
            FROM workshop_questions as wq
            INNER JOIN workshop_questionset_pre as wpr ON 
            wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.status='1' and wpr.active='1'
            WHERE wq.company_id='".$company_id."'  AND 
            wq.workshop_id='".$workshop_id."'
            GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id) as wses
            ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
            AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
            AND ar.topic_id = wses.topic_id) as wkavg
            INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
            INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
            GROUP BY wkavg.user_id,wkavg.workshop_id,wkavg.topic_id
            ORDER BY average DESC,total_seconds_taken ASC) as result
            CROSS JOIN (SELECT @cnt := 0) AS rank_counter
            ORDER BY rank ASC";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    function fetch_wksh_user_best_post_topic($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT 
            result.workshop_id,
            result.workshop_name,
            result.topic_id,
            result.topic_name,
            result.correct,
            result.total_questions,
            result.average,
            result.total_seconds_taken,
            (SELECT @cnt :=  @cnt + 1 ) AS rank  FROM
            (SELECT wkavg.user_id,
            wkavg.workshop_id,
            w.workshop_name,
            wkavg.topic_id,
            qt.description as topic_name,
            sum(wkavg.correct) as correct,
            sum(wkavg.total_questions) as total_questions,
            CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_questions)),2) AS DECIMAL(11,2)) as average,
            sum(wkavg.total_seconds_taken) as total_seconds_taken
            FROM(
            SELECT ar.*,wses.total_questions FROM 
            (SELECT company_id,user_id,trainer_id,workshop_id,workshop_session,questionset_id,topic_id,
            sum(is_correct) as correct,
            sum(timer) as total_seconds,sum(seconds) as total_seconds_taken
            FROM atom_results 
            WHERE company_id='".$company_id."' AND 
            user_id='".$user_id."' AND
            workshop_id='".$workshop_id."' AND
            workshop_session = 'POST'
            GROUP BY company_id,user_id,trainer_id,workshop_id,topic_id) as ar
            INNER JOIN
            (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,count(wq.question_id) as total_questions 
            FROM workshop_questions as wq
            INNER JOIN workshop_questionset_post as wpo ON 
            wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.status='1' AND wpo.active='1'
            WHERE wq.company_id='".$company_id."'  AND 
            wq.workshop_id='".$workshop_id."'
            GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id) as wses
            ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
            AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
            AND ar.topic_id = wses.topic_id) as wkavg
            INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
            INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
            GROUP BY wkavg.user_id,wkavg.workshop_id,wkavg.topic_id
            ORDER BY average DESC,total_seconds_taken ASC) as result
            CROSS JOIN (SELECT @cnt := 0) AS rank_counter
            ORDER BY rank ASC";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    function fetch_wksh_user_pre_rank($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT * FROM (
                SELECT 
                result.user_id,
                result.fullname,
                result.workshop_id,
                result.workshop_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                CONVERT((SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ),UNSIGNED INTEGER) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.fullname,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_questions)),2) AS DECIMAL(11,2)) as average,
                FORMAT(sum(wkavg.total_seconds_taken)/sum(wkavg.total_questions),2) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,
                atom_results.trainer_id,atom_results.workshop_id,atom_results.workshop_session,
                atom_results.questionset_id,atom_results.topic_id,atom_results.subtopic_id,
                sum(atom_results.is_correct) as correct,
                sum(atom_results.timer) as total_seconds,sum(atom_results.seconds) as total_seconds_taken,
                count(atom_results.question_id) as total_played
                FROM atom_results 
                INNER JOIN device_users ON atom_results.user_id = device_users.user_id
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'PRE'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_pre as wpr ON 
                wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.active ='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id
                ORDER BY average DESC,total_seconds_taken ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,fullname ASC) as rank
                WHERE rank.user_id='".$user_id."'";

            if ($atomdb==null){
                $result = $this->db->query($query);
                return $result->result();
            }else{
                $result = $atomdb->query($query);
                return $result->result();
            }
    }
    function fetch_wksh_user_pre_rank_live($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT * FROM (
                SELECT 
                result.user_id,
                result.fullname,
                result.workshop_id,
                result.workshop_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                CONVERT((SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ),UNSIGNED INTEGER) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.fullname,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_played)),2) AS DECIMAL(11,2)) as average,
                FORMAT(sum(wkavg.total_seconds_taken)/sum(wkavg.total_played),2) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,
                atom_results.trainer_id,atom_results.workshop_id,atom_results.workshop_session,
                atom_results.questionset_id,atom_results.topic_id,atom_results.subtopic_id,
                sum(atom_results.is_correct) as correct,
                sum(atom_results.timer) as total_seconds,sum(atom_results.seconds) as total_seconds_taken,
                count(atom_results.question_id) as total_played
                FROM atom_results 
                INNER JOIN device_users ON atom_results.user_id = device_users.user_id
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'PRE'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_pre as wpr ON 
                wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id
                ORDER BY average DESC,total_seconds_taken ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,fullname ASC) as rank
                WHERE rank.user_id='".$user_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    function fetch_wksh_user_post_rank($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT * FROM (
                SELECT 
                result.user_id,
                result.fullname,
                result.workshop_id,
                result.workshop_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                CONVERT((SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ),UNSIGNED INTEGER) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.fullname,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_questions)),2) AS DECIMAL(11,2)) as average,
                FORMAT(sum(wkavg.total_seconds_taken)/sum(wkavg.total_questions),2) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,
                atom_results.trainer_id,atom_results.workshop_id,atom_results.workshop_session,
                atom_results.questionset_id,atom_results.topic_id,atom_results.subtopic_id,
                sum(atom_results.is_correct) as correct,
                sum(atom_results.timer) as total_seconds,sum(atom_results.seconds) as total_seconds_taken,
                count(atom_results.question_id) as total_played
                FROM atom_results 
                INNER JOIN device_users ON atom_results.user_id = device_users.user_id
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'POST'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_post as wpo ON 
                wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id
                ORDER BY average DESC,total_seconds_taken ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,fullname ASC) as rank
                WHERE rank.user_id='".$user_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    function fetch_wksh_user_post_rank_live($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT * FROM (
                SELECT 
                result.user_id,
                result.fullname,
                result.workshop_id,
                result.workshop_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                CONVERT((SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ),UNSIGNED INTEGER) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.fullname,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_played)),2) AS DECIMAL(11,2)) as average,
                FORMAT(sum(wkavg.total_seconds_taken)/sum(wkavg.total_played),2) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT atom_results.company_id,atom_results.user_id,CONCAT(device_users.firstname,' ',device_users.lastname) as fullname,
                atom_results.trainer_id,atom_results.workshop_id,atom_results.workshop_session,
                atom_results.questionset_id,atom_results.topic_id,atom_results.subtopic_id,
                sum(atom_results.is_correct) as correct,
                sum(atom_results.timer) as total_seconds,sum(atom_results.seconds) as total_seconds_taken,
                count(atom_results.question_id) as total_played
                FROM atom_results 
                INNER JOIN device_users ON atom_results.user_id = device_users.user_id
                WHERE atom_results.company_id='".$company_id."' AND 
                atom_results.workshop_id='".$workshop_id."' AND
                atom_results.workshop_session = 'POST'
                GROUP BY atom_results.company_id,atom_results.user_id,atom_results.trainer_id,atom_results.workshop_id,atom_results.topic_id,atom_results.subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_post as wpo ON 
                wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id
                ORDER BY average DESC,total_seconds_taken ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,fullname ASC) as rank
                WHERE rank.user_id='".$user_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_wksh_user_topic_subtopic_pre_accuracy($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT 
                result.workshop_id,
                result.workshop_name,
                result.topic_id,
                result.topic_name,
                result.subtopic_id,
                result.subtopic_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                (SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_questions)),2) AS DECIMAL(11,2)) as average,
                sum(wkavg.total_seconds_taken) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT company_id,user_id,trainer_id,workshop_id,workshop_session,questionset_id,topic_id,subtopic_id,
                sum(is_correct) as correct,
                sum(timer) as total_seconds,sum(seconds) as total_seconds_taken,
                count(question_id) as total_played 
                FROM atom_results 
                WHERE company_id='".$company_id."' AND 
                user_id='".$user_id."' AND
                workshop_id='".$workshop_id."' AND
                workshop_session = 'PRE'
                GROUP BY company_id,user_id,trainer_id,workshop_id,topic_id,subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_pre as wpr ON 
                wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id,wkavg.topic_id,wkavg.subtopic_id
                ORDER BY average DESC,total_seconds_taken ASC,subtopic_name ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,result.subtopic_name ASC";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_wksh_user_topic_subtopic_pre_accuracy_live($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT 
                result.workshop_id,
                result.workshop_name,
                result.topic_id,
                result.topic_name,
                result.subtopic_id,
                result.subtopic_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                (SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,    
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_played)),2) AS DECIMAL(11,2)) as average,
                sum(wkavg.total_seconds_taken) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT company_id,user_id,trainer_id,workshop_id,workshop_session,questionset_id,topic_id,subtopic_id,
                sum(is_correct) as correct,
                sum(timer) as total_seconds,sum(seconds) as total_seconds_taken,
                count(question_id) as total_played 
                FROM atom_results 
                WHERE company_id='".$company_id."' AND 
                user_id='".$user_id."' AND
                workshop_id='".$workshop_id."' AND
                workshop_session = 'PRE'
                GROUP BY company_id,user_id,trainer_id,workshop_id,topic_id,subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_pre as wpr ON 
                wq.workshop_id = wpr.workshop_id AND wq.questionset_id = wpr.questionset_id AND wpr.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id,wkavg.topic_id,wkavg.subtopic_id
                ORDER BY average DESC,total_seconds_taken ASC,subtopic_name ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,result.subtopic_name ASC";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_wksh_user_topic_subtopic_post_accuracy($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT 
                result.workshop_id,
                result.workshop_name,
                result.topic_id,
                result.topic_name,
                result.subtopic_id,
                result.subtopic_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                (SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_questions)),2) AS DECIMAL(11,2)) as average,
                sum(wkavg.total_seconds_taken) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT company_id,user_id,trainer_id,workshop_id,workshop_session,questionset_id,topic_id,subtopic_id,
                sum(is_correct) as correct,
                sum(timer) as total_seconds,sum(seconds) as total_seconds_taken,
                count(question_id) as total_played
                FROM atom_results 
                WHERE company_id='".$company_id."' AND 
                user_id='".$user_id."' AND
                workshop_id='".$workshop_id."' AND
                workshop_session = 'POST'
                GROUP BY company_id,user_id,trainer_id,workshop_id,topic_id,subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_post as wpo ON 
                wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id,wkavg.topic_id,wkavg.subtopic_id
                ORDER BY average DESC,total_seconds_taken ASC,subtopic_name ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,result.subtopic_name ASC";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_wksh_user_topic_subtopic_post_accuracy_live($company_id,$user_id,$workshop_id,$atomdb=null){
        $query = "SELECT 
                result.workshop_id,
                result.workshop_name,
                result.topic_id,
                result.topic_name,
                result.subtopic_id,
                result.subtopic_name,
                result.correct,
                result.total_questions,
                result.total_played,
                result.average,
                result.total_seconds_taken,
                result.sortby,
                @dcp as previous,
                (SELECT @cnt :=  CASE WHEN sortby=previous THEN @cnt  ELSE @cnt+1 END ) AS rank,
                @dcp := CONCAT(average,total_seconds_taken) as current
                FROM
                (SELECT wkavg.user_id,
                wkavg.workshop_id,
                w.workshop_name,
                wkavg.topic_id,
                qt.description as topic_name,
                wkavg.subtopic_id,
                if(qst.description='No sub-Topic','',qst.description) as subtopic_name,
                sum(wkavg.correct) as correct,
                sum(wkavg.total_questions) as total_questions,
                sum(wkavg.total_played) as total_played,
                CAST(FORMAT(((sum(wkavg.correct)*100)/sum(wkavg.total_played)),2) AS DECIMAL(11,2)) as average,
                sum(wkavg.total_seconds_taken) as total_seconds_taken,
                CAST(CONCAT(sum(wkavg.correct),sum(wkavg.total_seconds_taken)) as CHAR(50)) as sortby
                FROM(
                SELECT ar.*,wses.total_questions FROM 
                (SELECT company_id,user_id,trainer_id,workshop_id,workshop_session,questionset_id,topic_id,subtopic_id,
                sum(is_correct) as correct,
                sum(timer) as total_seconds,sum(seconds) as total_seconds_taken,
                count(question_id) as total_played
                FROM atom_results 
                WHERE company_id='".$company_id."' AND 
                user_id='".$user_id."' AND
                workshop_id='".$workshop_id."' AND
                workshop_session = 'POST'
                GROUP BY company_id,user_id,trainer_id,workshop_id,topic_id,subtopic_id) as ar
                INNER JOIN
                (SELECT wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id,count(wq.question_id) as total_questions 
                FROM workshop_questions as wq
                INNER JOIN workshop_questionset_post as wpo ON 
                wq.workshop_id = wpo.workshop_id AND wq.questionset_id = wpo.questionset_id AND wpo.active='1'
                WHERE wq.company_id='".$company_id."'  AND 
                wq.workshop_id='".$workshop_id."'
                GROUP BY wq.company_id,wq.workshop_id,wq.questionset_id,wq.trainer_id,wq.topic_id,wq.subtopic_id) as wses
                ON ar.company_id = wses.company_id AND ar.workshop_id = wses.workshop_id 
                AND ar.questionset_id= wses.questionset_id AND ar.trainer_id = wses.trainer_id
                AND ar.topic_id = wses.topic_id AND ar.subtopic_id = wses.subtopic_id) as wkavg
                INNER JOIN workshop as w ON wkavg.workshop_id = w.id AND wkavg.company_id = w.company_id AND w.company_id='".$company_id."'
                INNER JOIN question_topic as qt ON wkavg.topic_id = qt.id AND wkavg.company_id = qt.company_id AND qt.company_id='".$company_id."'
                INNER JOIN question_subtopic as qst ON wkavg.subtopic_id = qst.id
                GROUP BY wkavg.user_id,wkavg.workshop_id,wkavg.topic_id,wkavg.subtopic_id
                ORDER BY average DESC,total_seconds_taken ASC,subtopic_name ASC) as result
                CROSS JOIN (SELECT @cnt := 0,@dcp:=0) AS rank_counter
                ORDER BY rank ASC,result.subtopic_name";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_feedback() {
        $query = "SELECT * FROM feedback WHERE status='1' ORDER BY start_date desc";
        $result = $this->db->query($query);
        return $result->result_array();
    }
    public function fetch_common_advertisement($company_id,$atomdb=null){
        $system_date = date('Y-m-d');
        $query = "SELECT * FROM advertisement WHERE status='1' AND 
        company_id=$company_id AND ('".$system_date."' BETWEEN start_date AND end_date)  ORDER BY sorting";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_workshop_advertisement($workshop_id,$atomdb=null) {
        $system_date = date('Y-m-d');
        $query = "SELECT * FROM workshop_banner WHERE status='1' AND workshop_id='".$workshop_id."' 
        ORDER BY sorting desc";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_instructions($atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT instruction from assessment_practice_qus WHERE instruction!='' AND status='1' LIMIT 0,1 ";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_video_assessment($user_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT final.* FROM (SELECT am.*,DATE_FORMAT(am.start_dttm,'%d-%m-%Y') as start_date,DATE_FORMAT(am.end_dttm,'%d-%m-%Y') as end_date,ast.description as assessment_type_name,
        aa.otp_verified,aa.is_completed,IFNULL(aa.attempts,0) as assessment_attempts  
        FROM `assessment_mst` as am left join assessment_type as ast ON am.assessment_type = ast.id
        left join assessment_attempts as aa ON am.id = aa.assessment_id and aa.user_id='".$user_id."'
        WHERE  am.status='1' AND ('".$system_date."' BETWEEN am.start_dttm AND am.end_dttm) AND
        (am.id NOT IN (select aui.assessment_id from assessment_allow_users as aui order by aui.assessment_id) OR
        am.id IN (select au.assessment_id from assessment_allow_users as au where au.user_id='".$user_id."' order by au.assessment_id))
        ORDER BY am.start_dttm desc) AS final
        WHERE (isnull(final.is_completed) OR final.is_completed=0 OR final.is_completed='')";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_video_assessment_history($user_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT am.*,DATE_FORMAT(am.start_dttm,'%d-%m-%Y') as start_date,DATE_FORMAT(am.end_dttm,'%d-%m-%Y') as end_date,ast.description as assessment_type_name,
        IFNULL(aa.otp_verified,0) as otp_verified,IFNULL(aa.is_completed,0) as is_completed,IFNULL(aa.attempts,0) as assessment_attempts  
        FROM `assessment_mst` as am left join assessment_type as ast ON am.assessment_type = ast.id
        left join assessment_attempts as aa ON am.id = aa.assessment_id and aa.user_id='".$user_id."'
        WHERE  am.status='1' AND 
        (((am.start_dttm IS NOT NULL) AND (am.start_dttm != '0000-00-00 00:00:00') AND (am.start_dttm != '1970-01-01 00:00:00'))
        AND ((am.end_dttm IS NOT NULL) AND (am.end_dttm != '0000-00-00 00:00:00') AND (am.end_dttm != '1970-01-01 00:00:00'))
        AND ((am.start_dttm<='".$system_date."') OR (am.end_dttm<='".$system_date."'))
        AND ('".$system_date."' NOT BETWEEN am.start_dttm AND am.end_dttm)) OR (aa.is_completed =1)
        AND
        (am.id NOT IN (select aui.assessment_id from assessment_allow_users as aui order by aui.assessment_id) OR
        am.id IN (select au.assessment_id from assessment_allow_users as au where au.user_id='".$user_id."' order by au.assessment_id))
        ORDER BY am.start_dttm desc
        ";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_video_assessment_ftp($user_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT final.* FROM (SELECT am.*,DATE_FORMAT(am.start_dttm,'%d-%m-%Y') as start_date,DATE_FORMAT(am.end_dttm,'%d-%m-%Y') as end_date,ast.description as assessment_type_name,
        aa.otp_verified,aa.is_completed,aa.ftpto_vimeo_uploaded,IFNULL(aa.attempts,0) as assessment_attempts  
        FROM `assessment_mst` as am left join assessment_type as ast ON am.assessment_type = ast.id
        left join assessment_attempts as aa ON am.id = aa.assessment_id and aa.user_id='".$user_id."'
        WHERE  am.status='1' AND ('".$system_date."' BETWEEN am.start_dttm AND am.end_dttm) AND
        (am.id NOT IN (select aui.assessment_id from assessment_allow_users as aui order by aui.assessment_id) OR
        am.id IN (select au.assessment_id from assessment_allow_users as au where au.user_id='".$user_id."' order by au.assessment_id))
        ORDER BY am.start_dttm desc) AS final
        WHERE (isnull(final.is_completed) OR final.is_completed=0 OR final.is_completed='') OR (isnull(final.ftpto_vimeo_uploaded) OR final.ftpto_vimeo_uploaded=0 OR final.ftpto_vimeo_uploaded='')";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_video_assessment_history_ftp($user_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        // DCP 16.08.2021 
        // $query = "SELECT am.*,DATE_FORMAT(am.start_dttm,'%d-%m-%Y') as start_date,DATE_FORMAT(am.end_dttm,'%d-%m-%Y') as end_date,ast.description as assessment_type_name,
        // IFNULL(aa.otp_verified,0) as otp_verified,IFNULL(aa.is_completed,0) as is_completed,IFNULL(aa.ftpto_vimeo_uploaded,0) as ftpto_vimeo_uploaded,IFNULL(aa.attempts,0) as assessment_attempts  
        // FROM `assessment_mst` as am left join assessment_type as ast ON am.assessment_type = ast.id
        // left join assessment_attempts as aa ON am.id = aa.assessment_id and aa.user_id='".$user_id."'
        // WHERE  am.status='1' AND 
        // (((am.start_dttm IS NOT NULL) AND (am.start_dttm != '0000-00-00 00:00:00') AND (am.start_dttm != '1970-01-01 00:00:00'))
        // AND ((am.end_dttm IS NOT NULL) AND (am.end_dttm != '0000-00-00 00:00:00') AND (am.end_dttm != '1970-01-01 00:00:00'))
        // AND ((am.start_dttm<='".$system_date."') OR (am.end_dttm<='".$system_date."'))
        // AND ('".$system_date."' NOT BETWEEN am.start_dttm AND am.end_dttm)) OR (aa.is_completed=1 AND aa.ftpto_vimeo_uploaded=1)
        // AND
        // (am.id NOT IN (select aui.assessment_id from assessment_allow_users as aui order by aui.assessment_id) OR
        // am.id IN (select au.assessment_id from assessment_allow_users as au where au.user_id='".$user_id."' order by au.assessment_id))
        // ORDER BY am.start_dttm desc
        // ";
        $query = "SELECT * FROM (SELECT am.*,DATE_FORMAT(am.start_dttm,'%d-%m-%Y') as start_date,DATE_FORMAT(am.end_dttm,'%d-%m-%Y') as end_date,ast.description as assessment_type_name,
        IFNULL(aa.otp_verified,0) as otp_verified,IFNULL(aa.is_completed,0) as is_completed,IFNULL(aa.ftpto_vimeo_uploaded,0) as ftpto_vimeo_uploaded,IFNULL(aa.attempts,0) as assessment_attempts  
        FROM `assessment_mst` as am left join assessment_type as ast ON am.assessment_type = ast.id
        left join assessment_attempts as aa ON am.id = aa.assessment_id and aa.user_id='".$user_id."'
        WHERE  am.status='1' AND 
        (((am.start_dttm IS NOT NULL) AND (am.start_dttm != '0000-00-00 00:00:00') AND (am.start_dttm != '1970-01-01 00:00:00'))
        AND ((am.end_dttm IS NOT NULL) AND (am.end_dttm != '0000-00-00 00:00:00') AND (am.end_dttm != '1970-01-01 00:00:00'))
        AND ((am.start_dttm<='".$system_date."') OR (am.end_dttm<='".$system_date."'))
        AND ('".$system_date."' NOT BETWEEN am.start_dttm AND am.end_dttm)) OR (aa.is_completed=1 AND aa.ftpto_vimeo_uploaded=1)
        ORDER BY am.start_dttm desc) as main																												 
        WHERE main.id IN (SELECT au.assessment_id FROM assessment_allow_users as au WHERE au.user_id='".$user_id."' order by au.assessment_id)";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_attempts($user_id,$assessment_id,$atomdb=null){
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT * FROM assessment_attempts WHERE user_id='".$user_id."' AND assessment_id='".$assessment_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_question($assessment_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT ast.id as trans_id,aq.id,aq.company_id,aq.assessment_type,aq.weightage,aq.timer,aq.read_timer,aq.response_timer,aq.question,aq.instruction,aq.poweredby
        FROM assessment_trans AS ast
        INNER JOIN assessment_question as aq ON ast.question_id = aq.id AND aq.`status`='1'
        WHERE ast.assessment_id ='".$assessment_id."'
        ORDER BY ast.id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_question_new($company_id,$user_id,$assessment_id,$atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT ast.id as trans_id,aq.id,aq.company_id,aq.assessment_type,aq.weightage,aq.timer,aq.read_timer,aq.response_timer,aq.question,aq.instruction,aq.poweredby,aq.question_format as formate,aq.question_path,c.domin_url
        FROM assessment_trans AS ast
        INNER JOIN assessment_question as aq ON ast.question_id = aq.id AND aq.`status`='1'
        LEFT JOIN company as c on c.id='".$company_id."'
        WHERE ast.assessment_id ='".$assessment_id."'
        AND aq.id NOT IN (select question_id as id from assessment_results WHERE company_id='".$company_id."' AND user_id = '".$user_id."' AND assessment_id ='".$assessment_id."')
        ORDER BY ast.id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    
    public function fetch_assessment_demo_question($atomdb=null) {
        $system_date = date('Y-m-d H:i:s');
        $query = "SELECT * FROM assessment_practice_qus WHERE status='1' AND instruction='' ORDER BY id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    //01-08-2022 -- Krishna -- Assessment total time - show hours with minutes and seconds 
    public function fetch_assessment_total_time($assessment_id,$atomdb=null) {
        $query = "SELECT count(*) as total_question,sum(aq.read_timer) as read_timer,SEC_TO_TIME(sum(aq.read_timer)) as total_read_time,sum(aq.response_timer) as response_timer,SEC_TO_TIME(sum(aq.response_timer)) as total_response_time,
        IF(sum(aq.read_timer+aq.response_timer) > 3599, TIME_FORMAT(SEC_TO_TIME(sum(aq.read_timer+aq.response_timer)),'%h:%i:%s'), TIME_FORMAT(SEC_TO_TIME(sum(aq.read_timer+aq.response_timer)),'%i:%s')) as total_time
        -- TIME_FORMAT(SEC_TO_TIME(sum(aq.read_timer+aq.response_timer)),'%i:%s') as total_time
        FROM assessment_trans AS ast
        INNER JOIN assessment_question as aq ON ast.question_id = aq.id AND aq.`status`='1'
        WHERE ast.assessment_id ='".$assessment_id."'
        ORDER BY ast.id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_total_uploaded_videos_count($company_id,$user_id,$assessment_id,$atomdb=null) {
        $query = "SELECT DISTINCT company_id,assessment_id,user_id,trans_id,question_id FROM assessment_results 
        WHERE company_id ='".$company_id."' AND user_id ='".$user_id."' AND assessment_id ='".$assessment_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_total_uploaded_videos_count_ftp($company_id,$user_id,$assessment_id,$atomdb=null) {
        $query = "SELECT DISTINCT company_id,assessment_id,user_id,trans_id,question_id FROM assessment_results 
        WHERE company_id ='".$company_id."' AND user_id ='".$user_id."' AND assessment_id ='".$assessment_id."' AND ftp_status='1'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_results($crondb=null) {
        $query = "SELECT * FROM assessment_results WHERE session_id!='' AND token_id!='' AND archive_id!='' AND tokbox_status=0 ORDER BY id DESC LIMIT 1";
        if ($crondb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $crondb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_video_result($company_id,$assessment_id,$trans_id,$user_id,$question_id,$atomdb=null) {
        $query = "SELECT * FROM assessment_results WHERE company_id='". $company_id ."' AND assessment_id='".$assessment_id."' AND user_id='". $user_id."' AND trans_id='".$trans_id."' AND question_id='".$question_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function delete_self_assesment_video_result($company_id,$user_id,$assessment_id,$atomdb=null) {
        $query = "DELETE FROM assessment_results WHERE company_id ='".$company_id."' AND user_id ='".$user_id."' AND assessment_id ='".$assessment_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return true;
    }
    public function delete_self_assesment_video_single_result($company_id,$user_id,$assessment_id,$trans_id,$question_id,$atomdb=null) {
        $query = "DELETE FROM assessment_results WHERE company_id ='".$company_id."' AND user_id ='".$user_id."' AND assessment_id ='".$assessment_id."' AND trans_id ='".$trans_id."' AND question_id ='".$question_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return true;
    }
    public function update_internship($user_id,$workshop_id,$atomdb=null){
        $query = "update internship_applied_users as iu left JOIN
        (select a.user_id,b.total_question,a.played_que,a.correct,a.wrong,format(a.correct*100/b.total_question,2) as result FROM
        (SELECT ar.workshop_id,ar.user_id,count(DISTINCT ar.id) as played_que,sum(ar.is_correct) as correct,sum(ar.is_wrong) as wrong,
        FORMAT(sum(ar.seconds)/COUNT(ar.id),2) as avg_resp_time from atom_results ar
        LEFT JOIN workshop w ON w.id=ar.workshop_id where ar.user_id=$user_id
        ) as a left join(select workshop_id,count(id) as total_question from workshop_questions where workshop_id=$workshop_id ) as b ON b.workshop_id=a.workshop_id
            ) as r on r.user_id = iu.user_id set status=2,iu.workshop_id=$workshop_id,iu.quiz_total_qus=r.total_question,iu.quiz_played_qus=r.played_que,iu.quiz_correct=r.correct,
            iu.quiz_wrong=r.wrong,iu.quize_score=r.result where iu.user_id=$user_id and iu.status=1";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return true;
    }
    public function fetch_company_for_vimeo($cronjob_id="",$upload_google_drive=0) {
        $query = "SELECT * FROM company WHERE status=1 ";
		if($cronjob_id !=''){
			$query .= " AND cronjob_id=".$cronjob_id;
        }
		if($upload_google_drive){
			$query .= " AND upload_google_drive=1";
        }
        $result = $this->db->query($query);
        return $result->result();
    }
    public function fetch_assessment_results_ftpurl($company_id,$atomdb=null) {
        // $query = "SELECT * FROM assessment_results WHERE company_id='".$company_id."' AND ((ftp_url IS NOT NULL) AND ftp_url!='')  AND ftp_status='0'";
        $query = "SELECT a.* FROM assessment_results as a left join assessment_mst as b on a.assessment_id = b.id 
		WHERE b.company_id='".$company_id."' AND a.ftp_status='0' and (b.stop_cronjob_vimgdrive is null OR b.stop_cronjob_vimgdrive=0)";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function delete_single_assessment_results($assessment_result_id,$atomdb=null) {
        $query = "DELETE FROM assessment_results WHERE id ='".$assessment_result_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return true;
    }
    public function fetch_assessment_video_result_ftp($company_id,$assessment_id,$trans_id,$user_id,$question_id,$atomdb=null) {
        // $query = "SELECT * FROM assessment_results WHERE company_id='". $company_id ."' AND assessment_id='".$assessment_id."' AND user_id='". $user_id."' AND trans_id='".$trans_id."' AND question_id='".$question_id."' AND ((ftp_url IS NOT NULL) AND ftp_url!='')  AND ftp_status='0'";
        $query = "SELECT * FROM assessment_results WHERE company_id='". $company_id ."' AND assessment_id='".$assessment_id."' AND user_id='". $user_id."' AND trans_id='".$trans_id."' AND question_id='".$question_id."' AND ftp_status='0'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_completed_assessment($company_id,$atomdb=null) {
        $query = "select aa.user_id,CONCAT(du.firstname,' ',du.lastname) as user_name,du.email,aa.assessment_id,am.assessment,aa.is_completed,aa.ftpto_vimeo_uploaded from assessment_attempts as aa 
        left join assessment_mst as am on aa.assessment_id = am.id and am.company_id='".$company_id."'
        left join device_users as du on aa.user_id = du.user_id and du.company_id='".$company_id."'
        where aa.is_completed='1' and aa.ftpto_vimeo_uploaded ='1' and (ISNULL(am.stop_cronjob_vimgdrive) OR am.stop_cronjob_vimgdrive=0) order by aa.assessment_id,aa.user_id";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_user_assessment_result_for_gdrive($company_id,$user_id,$assessment_id,$atomdb=null) {
        // $query = "select a.company_id,a.user_id,a.assessment_id,a.trans_id,a.question_id,a.video_url,CONCAT('Q',CONVERT((SELECT @cnt :=  @cnt+1),UNSIGNED INTEGER)) AS question_series,IFNULL(agdf.gdrive_file_id,'') as gdrive_file_id
        // from (select ar.company_id,ar.user_id,ar.assessment_id,ar.trans_id,ar.question_id,ar.video_url from assessment_results as ar
        // where (ISNULL(ar.session_id) OR ar.session_id='') and (ISNULL(ar.token_id) OR ar.token_id='') and  (ISNULL(ar.archive_id) OR ar.archive_id='') and trans_id>0 and question_id >0 and ar.company_id = '".$company_id."' and ar.user_id='".$user_id."' and ar.assessment_id='".$assessment_id."' order by trans_id) as a
        // CROSS JOIN (SELECT @cnt := 0) AS qcounter
        // left join assessment_gdrive_files as agdf on a.company_id=agdf.company_id and a.user_id=agdf.user_id and a.assessment_id=agdf.assessment_id and a.trans_id=agdf.trans_id and a.question_id=agdf.question_id and a.video_url=agdf.video_url";
        $query = "select a.company_id,a.user_id,a.assessment_id,a.trans_id,a.question_id,a.video_url,CONCAT('Q',CONVERT((SELECT @cnt :=  @cnt+1),UNSIGNED INTEGER)) AS question_series,IFNULL(agdf.gdrive_file_id,'') as gdrive_file_id
        from (select ar.company_id,ar.user_id,ar.assessment_id,ar.trans_id,ar.question_id,ar.video_url from assessment_results as ar
        where trans_id>0 and question_id >0 and ar.company_id = '".$company_id."' and ar.user_id='".$user_id."' and ar.assessment_id='".$assessment_id."' order by trans_id) as a
        CROSS JOIN (SELECT @cnt := 0) AS qcounter
        left join assessment_gdrive_files as agdf on a.company_id=agdf.company_id and a.user_id=agdf.user_id and a.assessment_id=agdf.assessment_id and a.trans_id=agdf.trans_id and a.question_id=agdf.question_id and a.video_url=agdf.video_url";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_video_count($company_id,$assessment_id,$atomdb=null){
        $query = "select count(*) as total from assessment_results where company_id = '".$company_id."' AND assessment_id = '".$assessment_id."' AND trans_id > 0 AND question_id > 0 AND vimeo_uri!='' AND ftp_status=1";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_aitask_completed($company_id,$assessment_id,$atomdb=null){
        $query = "select count(*) as total from ai_schedule where task_status='1' AND xls_generated='1' AND xls_filename!='' AND xls_imported='1' AND company_id='".$company_id."' AND assessment_id='".$assessment_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_manualrating_completed($assessment_id,$user_id,$atomdb=null){
        $query = "select count(DISTINCT question_id) as total from assessment_results_trans where assessment_id='".$assessment_id."' AND user_id='".$user_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_question_count($assessment_id,$atomdb=null){
        $query = "select count(question_id) as total from assessment_trans where assessment_id='".$assessment_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_task_schedule($assessment_id,$atomdb=null){
        $query = "select * from ai_cronjob where assessment_id='".$assessment_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_show_report_status($company_id,$assessment_id,$atomdb=null){
        $query = "select * from ai_cronreports where company_id='".$company_id."' AND assessment_id='".$assessment_id."' AND show_pwa='1'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function fetch_assessment_aixls_imported($company_id,$assessment_id,$user_id,$atomdb=null){
        $query = "select count(*) as total from ai_schedule where task_status='1' AND xls_generated='1' AND xls_filename!='' AND xls_imported='1' AND company_id='".$company_id."' AND assessment_id='".$assessment_id."' AND user_id='".$user_id."'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
	public function fetch_drreddy_users(){
        $query = "select * from device_users where company_id=67 and emp_id is null order by id";
        $result = $this->db->query($query);
        return $result->result();
    }

    //BILLING MODULE QUERIES
    public function fetch_total_users_statistics($atomdb=null){
        $query = "SELECT count(*) as total, sum(status) as active, COUNT(CASE WHEN status=0 THEN 1 END) as inactive
                 FROM (SELECT * FROM 
                    (SELECT main1.* FROM (SELECT status,email FROM `device_users` WHERE istester=0 UNION ALL SELECT status,email FROM company_users) as main1 ORDER BY main1.status DESC)
                 as main GROUP BY main.email) as a";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }

    public function fetch_last_added_users($atomdb=null, $addeddate){
        $query = "SELECT COUNT(DISTINCT email) as `latest_users` FROM 
                    (SELECT status,email,addeddate FROM `device_users` WHERE istester=0 AND status=1
                        UNION ALL
                     SELECT status,email,addeddate FROM company_users WHERE status=1) 
                as main WHERE addeddate LIKE '$addeddate%'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }

    public function fetch_users_with_attempts($atomdb=null, $addeddate)
    {
        $query = "SELECT COUNT(DISTINCT(user_id)) as `user_attempted` from assessment_attempts WHERE addeddate LIKE '$addeddate%'";
        if ($atomdb==null){
            $result = $this->db->query($query);
            return $result->result();
        }else{
            $result = $atomdb->query($query);
            return $result->result();
        }
    }
    public function get_users_value($Table, $Column, $Clause ,$atomdb=null)
    {
        $result = "SELECT " . $Column . " FROM " . $Table . " WHERE " . $Clause . " ";
       
        if ($atomdb==null){
            $result = $this->db->query($query);
            $row = $result->result_array();
            return $row;
        }else{
            $result = $atomdb->query($query);
            $row = $result->result_array();
            return $row;
        }
    }
    public function get_value($Table, $Column, $Clause,$atomdb=null) {
        $query = "SELECT " . $Column . " FROM " . $Table . " WHERE " . $Clause . " ";
       
        if ($atomdb==null){
            $result = $this->db->query($query);
            $row = $result->row();
            return $row;;
        }else{
            $result = $atomdb->query($query);
            $row = $result->row();
            return $row;
        }
    }
    public function sendPhpMailer($Company_id, $toname, $toemail, $subject, $body,$atomdb=null) {
        $emailData = array();
      
        if ($atomdb==null){
            $query = "select * from smtp where status=1";
            $result = $this->db->query($query);
            $row = $result->row();
            $emailData=$row;;
        }else{
            $query = "select * from company_smtp where status=1";
            $result = $atomdb->query($query);
            $row = $result->row();
            $emailData=$row;
        }
        $Msg = "";

        if (count((array)$emailData) > 0) {
            $this->load->library('My_PHPMailer');
            $mail = new PHPMailer;
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $emailData->smtp_ipadress;
            $mail->SMTPAuth = $emailData->smtp_authentication;
            $mail->Username = $emailData->smtp_username;
            $mail->Password = $emailData->smtp_password;
            $mail->SMTPSecure = $emailData->smtp_secure;
            $mail->Port = $emailData->smtp_portno;
            $mail->XMailer = ' ';
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //$mail->AddCustomHeader('Message-ID:'.$headers['Message-ID']);
            //$mail->AddCustomHeader('X-Mailer:'.$headers['X-Mailer']);

            $mail->addAddress($toemail, $toname);
            //$toemail
            $mail->isHTML(true);
            $mail->setFrom($emailData->smtp_username, $emailData->smtp_alias);
            if (is_array($toemail)) {
                foreach ($toemail as $email) {
                    $mail->addAddress($email, $toname);
                }
            } else {
                $mail->addAddress($toemail, $toname);
            }
            $mail->SMTPKeepAlive = true;
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $subject;
            $mail->Mailer = "smtp";
            $MailFlag = $mail->send();
            if (!$MailFlag) {
                $Msg = 'Mailer Error: ' . $mail->ErrorInfo;
            }
            else{
                $MailFlag=1;
            }
        } else {
            $MailFlag = 0;
            $Msg = "Your SMTP setting is in-Active or not set,contact administrator for technical support..";
        }

        $data['sendflag'] = $MailFlag;
        $data['Msg'] = $Msg;
        return $data;
    }

    public function get_notPlayedAssUsers($assessment_id,$atomdb){
		$query = " SELECT a.user_id,b.firstname,LOWER(b.email) as email FROM `assessment_allow_users` as a LEFT JOIN device_users as b ON b.user_id=a.user_id 
		WHERE  a.user_id NOT IN (SELECT user_id FROM assessment_attempts WHERE is_completed=1 AND assessment_id=$assessment_id) AND a.assessment_id=$assessment_id";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return $result->result();
	}

    public function get_notRatingAssTariner($assessment_id,$atomdb){
		$query = "SELECT acr.assessment_id,acr.trainer_id, concat(cm.first_name,' ',cm.last_name) as trainer_name,cm.email 
                    FROM assessment_complete_rating as acr 
                    LEFT JOIN assessment_mst as am ON acr.assessment_id =am.id
                    LEFT JOIN company_users as cm ON acr.trainer_id = cm.userid
                    WHERE acr.issync = '0' AND acr.assessment_id = '".$assessment_id."' ";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return $result->result();
	}

    public function get_scheduled_notify_Users($company_id,$atomdb){
        $query = "SELECT ans.id, ans.assessment_id, ans.email_alert_id, ans.user_id, ans.role_id, ans.user_name, ans.email, ans.attempt, ae.alert_name, ae.subject, ae.message FROM assessment_notification_schedule ans
            LEFT JOIN auto_emails ae ON ae.alert_id =  ans.email_alert_id 
            WHERE attempt < 3 AND is_sent = 0 limit 1 ";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return $result->result();
    }

    public function check_notification_schedule_cron($company_id,$atomdb){
        $query = "SELECT schedule_status,cron_status FROM notification_schedule_cron";
        if ($atomdb==null){
            $result = $this->db->query($query);
        }else{
            $result = $atomdb->query($query);
        }
        return $result->result();
    }

}
?>