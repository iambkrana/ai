<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Multilang extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $acces_management = $this->check_rights('multilang');
        //if (!$acces_management->allow_access) {
           // redirect('dashboard');
       // }
        $this->acces_management = $acces_management;
        $this->load->model('multilang_model');
    }
    
    public function index(){
        $data['module_id']        = '5.1';
        $data['username']         = $this->mw_session['username'];
        $data['user_id']         = $this->mw_session['user_id'];
        $data['acces_management'] = $this->acces_management;        
        $Company_id = $this->mw_session['company_id']; 
        $user_id= $this->mw_session['user_id'];
        if ($Company_id == "") {
            $data['cmpdata'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1');
        } else {
            $data['cmpdata'] = array();
        }
        $data['Company_id'] = $Company_id;    
        
        $lang_result = $this->common_model->get_selected_values('ai_language', 'default_lang,multi_lang,lan_id,addedby,status,company_id,login_page,backend_page,pwa_page', 'company_id="' . $Company_id . '"'); //AND addedby="'.$user_id.'"
        $data['lang_result'] = $lang_result;

        
        $data['add_lang'] = $this->common_model->get_selected_values('ai_multi_language', 'ml_short,status,ml_name,ml_id,ml_actual_text', 'status=3');

        $data['multi_lang'] = $this->common_model->get_selected_values('ai_multi_language', 'ml_actual_text,status,ml_short,ml_name,ml_id', 'status!=3'); //status=2
        $data['select_lang'] = $this->common_model->get_selected_values('ai_multi_language', 'ml_actual_text,status,ml_short,ml_name,ml_id', 'status=2');

        $query = "SELECT * FROM ai_language";
        $result = $this->db->query($query);
        $data['sel_Lang'] = $result->result_array();
 
        $this->load->view('multilang/index', $data);
    }

      
    public function submit()
    {
        $log_page_lang = $_POST['log_page_lang']; 
        $back_lang = $_POST['back_lang']; 
        $pwa_lang = $_POST['pwa_lang']; 
        $default_id=$_POST['default_id'];
        $multi_lang = $_POST['multi_lang'];

            if($multi_checkbox=='' && $multi_lang==1){
                $multi_lang=0; 
            }
            if($multi_checkbox=='on' && $multi_lang==1){
                $multi_lang=1; 
            }
            if($multi_checkbox=='' && $multi_lang==0){
                $multi_lang=0; 
            }
            if($multi_checkbox=='off' && $multi_lang==0){
                $multi_lang=1; 
            }
             
                $now = date('Y-m-d H:i:s');
                $addedby=$this->mw_session['user_id'];
                $Company_id = $this->mw_session['company_id'];

                $data = array(
                    'company_id'          => $Company_id,
                    'login_page'          => $log_page_lang,
                    'backend_page'           => $back_lang,
                    'pwa_page'           => $pwa_lang,
                    'multi_lang'   => $multi_lang,
                    'default_lang' => $default_id,
                    'status'                => '1',
                    'addeddate'             => $now,
                    'addedby'               => $addedby,
                );
                 $this->common_model->insert('ai_language', $data);
                $message = 'Language Add Successfully..';
             
        echo json_encode(array('message' => $message, 'data' =>$data));
    } //-- submit

    public function edit()
    {
        $log_page_lang = $_POST['log_page_lang']; 
        $back_lang = $_POST['back_lang']; 
        $pwa_lang = $_POST['pwa_lang'];
        $modifiedby = $this->mw_session['user_id'];
        $lan_id = $_POST['lan_id'];
       
        //$multi_checkbox = $_POST['multi_checkbox'];
        $multi_lang = $_POST['multi_lang'];

        $final_check = $_POST['final_check'];
        $default_id=$_POST['default_id'];

        $id = base64_decode($lan_id);
        $now = date('Y-m-d H:i:s');
        
        // echo "<pre>"; print_r($_POST); echo "<br/>"; echo "<br/>";
           
        if($final_check==0){
            //echo "<br/>"; echo '-------$final_check-----------  0  ';
            //--- only defauir lan on baki sab off
            $multi_lang=0;
            $log_page_lang=$default_id;
            $back_lang=$default_id;
            $pwa_lang=$default_id;

            $queryNW = "select ml_id,ml_short from ai_multi_language where ml_short!='" . $default_id."' AND status!= 3";
            $query = $this->db->query($queryNW);
            $DL_multi = $query->result();
            $DL_Array=array();

            foreach ($DL_multi as $name_DL){
                $DL_Array[]=$name_DL->ml_id; 
                $DL_MULTI=$name_DL->ml_id; 
                $data1 = array(
                    'status' => 1,
                    'ml_editdate' => $now,
                    'ml_editby'   => $modifiedby
                );      
                $this->db->where('ml_id', $DL_MULTI);  
                $this->db->update('ai_multi_language', $data1);   
            }
        } //final_check--0

        if($final_check==1){
            //echo "<br/>"; echo '-------$final_check-----------  1 ';

            $multi_lang=1;
            $PS_Array=array();
            foreach($_POST['mybox'] as $value){
                $data1 = array(
                    'status' => 2,
                    'ml_editdate' => $now,
                    'ml_editby'   => $modifiedby
                );      
                $this->db->where('ml_id', $value);  
                $this->db->update('ai_multi_language', $data1);    
                $PS_Array[]=$value;
            }
             
            $All_multi= $this->common_model->get_selected_values('ai_multi_language', 'ml_id', 'status!=3'); //status=2
            $ML_Array=array();
            foreach ($All_multi as $name_ML) {
                $ML_Array[]=$name_ML->ml_id; 
            }

            if(count((array)$PS_Array)>0){
                $mlid_final = array_diff($ML_Array, $PS_Array);
                $val_final=array_values($mlid_final);

                for($p=0; $p < count($val_final) ; $p++) { 
                    $data1 = array(
                        'status' => 1,
                        'ml_editdate'             => $now,
                        'ml_editby'               => $modifiedby
                    );      
                    $this->db->where('ml_id', $val_final[$p]);
                    $this->db->update('ai_multi_language', $data1);
                }
            }
        } //final_check----1

        if($final_check=='yes'){
            //echo "<br/>"; echo '-------$final_check-----------  yes  ';
            $multi_lang=1;
            $PS_Array=array();
            foreach($_POST['mybox'] as $value){
                $data1 = array(
                    'status' => 2,
                    'ml_editdate' => $now,
                    'ml_editby'   => $modifiedby
                );      
                $this->db->where('ml_id', $value);  
                $this->db->update('ai_multi_language', $data1);    
                $PS_Array[]=$value;
            }
            
            $All_multi= $this->common_model->get_selected_values('ai_multi_language', 'ml_id', 'status!=3'); //status=2
            $ML_Array=array();
            foreach ($All_multi as $name_ML) {
                $ML_Array[]=$name_ML->ml_id; 
            }

            if(count((array)$PS_Array)>0){
                $mlid_final = array_diff($ML_Array, $PS_Array);
              
                $val_final=array_values($mlid_final);
                for($i=0; $i < count($val_final) ; $i++) { 
                    $data1 = array(
                        'status' => 1,
                        'ml_editdate'             => $now,
                        'ml_editby'               => $modifiedby
                    );      
                    
                    $this->db->where('ml_id', $val_final[$i]);
                    $this->db->update('ai_multi_language', $data1);
                }
            }
        } //final_check--yes

        if($final_check=='no'){
            //echo "<br/>"; echo '-------$final_check-----------  NO  ';
            $multi_lang=0;
            $log_page_lang=$default_id;
            $back_lang=$default_id;
            $pwa_lang=$default_id;
            
            $queryNW = "select ml_id,ml_short from ai_multi_language where ml_short!='" . $default_id."' AND status!= 3";
            $query = $this->db->query($queryNW);
            $DL_multi = $query->result();
            $DL_Array=array();
            foreach ($DL_multi as $name_DL){
                $DL_Array[]=$name_DL->ml_id; 
                $DL_MULTI=$name_DL->ml_id; 
                $data1 = array(
                    'status' => 1,
                    'ml_editdate' => $now,
                    'ml_editby'   => $modifiedby
                );      
                $this->db->where('ml_id', $DL_MULTI);  
                $this->db->update('ai_multi_language', $data1);   
            }
        } ////final_check--no

    $data = array(
        'login_page'          => $log_page_lang,
        'backend_page'           => $back_lang,
        'multi_lang'   => $multi_lang,
        'default_lang' => $default_id,
        'pwa_page'           => $pwa_lang,
        'modifieddate'             => $now,
        'modifiedby'               => $modifiedby,
    );
 //print_r($data); //print_r($_POST);
         $insert_id = $this->common_model->update('ai_language', 'lan_id', $id, $data);
        $message = 'Language Update Successfully..';
//exit;
        echo json_encode(array('message' => $message));
    } //-- edit

    public function back_language($id)
    {

        $data = array(
            'backend_page'=> $id,
        );

        $this->session->set_userdata('site_lang',  $id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function newlanguage()
    {
        $lang=$this->input->post('lan');

        $data = array(
            'backend_page'=> $lang,
        );

        $this->session->set_userdata('site_lang',  $lang);
        //$update_lang = $this->common_model->update('ai_language', 'lan_id', '1', $data);
        echo $lang;
    }

    public function adminLanguage(){
        $lang=$this->input->post('lan'); 

        $data = array(
            'backend_page'=> $lang,
        );

        $this->session->set_userdata('site_lang',  $lang);
        $update_lang = $this->common_model->update('ai_language', 'lan_id', '1', $data);
        echo $lang;
    }

    public function addLang()
    {
        //print_r($_POST);

         $lang_id = $_POST['lang_name']; 
        
            $now = date('Y-m-d H:i:s');
            $addedby=$this->mw_session['user_id'];
            $Company_id = $this->mw_session['company_id'];

            $data = array(
                //'company_id'    => $Company_id,
                //'ml_short'      => $ml_short,
                //'ml_name'       => $lang_name,
                'status'        => '1',
                'ml_addDate'    => $now,
                'ml_addby'      => $addedby,
            );
            $this->common_model->update('ai_multi_language', 'ml_id', $lang_id, $data);
            $message = 'Language Update Successfully..';
             
        echo json_encode(array('message' => $message, 'data' =>$data));
        exit;
    }
}