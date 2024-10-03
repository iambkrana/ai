<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\OutputMode;
use OpenTok\Session;
use OpenTok\Role;

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin:{$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Max-Age:86400');
        }
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
        if (!isset($_SERVER['HTTP_HOST'])) {
            $_SERVER['HTTP_HOST'] = 'aiapi.awarathon.com';
        }
        ini_set('allow_url_fopen', 1);
        ini_set('max_file_uploads', '1000');
        ini_set('memory_limit', '1024M');
        ini_set('post_max_size', '1024M');
        ini_set('max_execution_time', -1);
        // ini_set("session.cookie_secure", 1);
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('api_model');
        $this->load->helper('url');
        $this->atomdb = null;
        $this->crondb = null;
    }
    public function generate_payload($user_id, $user_token)
    {
        $this->load->library("JWT");
        $server_key = 'awar@thon';
        $ttl = 86400;

        return $this->jwt->encode(array(
            'token'     => $user_token,
            'user_id'   => $user_id,
            'issued_at' => date(DATE_ISO8601, strtotime("now")),
            'ttl'       => $ttl
        ), $server_key);
    }
    // public function token_verify(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0
    //             );
    //             $results =$this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $total =  count((array)$results);
    //             if ($total > 0) { 
    //                 foreach ($results as $row) {
    //                     if ($token == $row->token){
    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Token Verified"
    //                         );
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' => "Invalid Token"
    //                         );
    //                     }
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "User not found"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function update_all_users_token(){
    //     $message = '';
    //     $where_clause = array(
    //         'status'  => 1,
    //         'block'   => 0
    //     ); 
    //     $results          = $this->api_model->fetch_results('device_users',$where_clause);
    //     $total =  count((array)$results);
    //     if ($total > 0) {
    //         $cnt = 0;
    //         $nocnt = 0;
    //         foreach ($results as $row) {
    //             $common_user_id = $row->id;
    //             $user_id        = $row->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             //GENERATE TOKEN OF USER FOR SECURITY AND UPDATE
    //             $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));

    //             $token_data = array(
    //                 'token'   => $token
    //             );
    //             //COMMON
    //             $where_clause = array(
    //                 'id' => $common_user_id
    //             ); 
    //             $update_status    = $this->api_model->update('device_users', $where_clause, $token_data);

    //             //DOMAIN
    //             $where_clause = array(
    //                 'user_id' => $user_id
    //             );
    //             $update_status_ii = $this->api_model->update('device_users', $where_clause, $token_data,$this->atomdb);
    //             if ($update_status AND $update_status_ii){
    //                 $cnt++;
    //             }else{
    //                 $nocnt++;
    //             }

    //         }
    //         $message = "Out of ".$total." / ".$cnt." Records updated ";
    //         if ($nocnt>0){
    //             $message .= $nocnt." Records unable to updated ";
    //         }
    //     }else{
    //         $message = "No records found";
    //     } 

    //     $json = array(
    //         'success' => true,
    //         'message' => $message
    //     );
    //     echo json_encode($json);
    // } 
    // public function create_session(){
    //     $data     = array();
    //     $json     = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token_no');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id      = 0;
    //             $company_id   = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id                   = $common_user_details->user_id;
    //                 $common_opentok_session_id = (is_null($common_user_details->opentok_session_id) OR $common_user_details->opentok_session_id=='')?'':$common_user_details->opentok_session_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id         = $user_details->company_id;
    //                 $user_opentok_session_id = (is_null($user_details->opentok_session_id) OR $user_details->opentok_session_id=='')?'':$user_details->opentok_session_id;

    //                 $apiKey    = $this->config->item('tokbox_apikey');
    //                 $apiSecret = $this->config->item('tokbox_apisecret');
    //                 if (($common_opentok_session_id==$user_opentok_session_id) AND ($common_opentok_session_id!='' AND $user_opentok_session_id!='')){
    //                     $opentok   = new OpenTok($apiKey, $apiSecret);
    //                     $sessionId = $user_opentok_session_id;
    //                     $tokenId   = $opentok->generateToken($sessionId);
    //                 }else{
    //                     $opentok   = new OpenTok($apiKey, $apiSecret);
    //                     $sessionOptions = array(
    //                         'mediaMode'   => MediaMode::ROUTED
    //                     );
    //                     $session        = $opentok->createSession($sessionOptions);
    //                     $sessionId      = $session->getSessionId();
    //                     $tokenId        = $opentok->generateToken($sessionId);

    //                     $jsonOT = array(
    //                         'opentok_session_id' => $sessionId
    //                     );
    //                     $where_clause = array(
    //                         'id'           => $common_user_id,
    //                         'status'       => 1,
    //                         'block'        => 0,
    //                         'otp_verified' => 1
    //                     ); 
    //                     $this->api_model->update('device_users',$where_clause,$jsonOT);

    //                     $where_clause = array(
    //                         'user_id'      => $user_id,
    //                         'status'       => 1,
    //                         'block'        => 0,
    //                         'otp_verified' => 1
    //                     ); 
    //                     $this->api_model->update('device_users',$where_clause,$jsonOT,$this->atomdb);
    //                 }

    //                 $json = array(
    //                     'success'                  => true,
    //                     'message'                  => "Session created sucessfully",
    //                     'apiKey'                   => $apiKey,
    //                     'sessionId'                => $sessionId,
    //                     'tokenId'                  => $tokenId,
    //                     'RECORDING_BEFORE_MESSAGE' => 'Please wait, we are setting up things. It may take few more seconds!',
    //                     'RECORDING_AFTER_MESSAGE'  => 'Please wait your question is on the way.',
    //                     'RECORDING_500_MESSAGE'    => 'Internal Server Error, we are reset this question.',
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function create_token(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token_no');
    //         $payload    = $this->input->post('payload');
    //         $sessionId  = $this->input->post('sessionId');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;

    //                 $apiKey    = $this->config->item('tokbox_apikey');
    //                 $apiSecret = $this->config->item('tokbox_apisecret');
    //                 $opentok   = new OpenTok($apiKey, $apiSecret);

    //                 $tokenId     = $opentok->generateToken($sessionId);

    //                 $json = array(
    //                     'success'   => true,
    //                     'message'   => "Token created sucessfully",
    //                     'apiKey'    => $apiKey,
    //                     'sessionId' => $sessionId,
    //                     'token'     => $tokenId
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function start_archive(){
    //     $data     = array();
    //     $json     = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token_no');
    //         $payload    = $this->input->post('payload');
    //         $sessionId  = $this->input->post('sessionId');
    //         $tokenId    = $this->input->post('tokenId');
    //         $fileName   = $this->input->post('fileName');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $apiKey          = $this->config->item('tokbox_apikey');
    //                 $apiSecret       = $this->config->item('tokbox_apisecret');
    //                 $opentok         = new OpenTok($apiKey, $apiSecret);

    //                 $archiveOptions = array(
    //                     'name'       => $fileName,
    //                     'hasAudio'   => true, 
    //                     'hasVideo'   => true, 
    //                     'outputMode' => OutputMode::COMPOSED,
    //                     'resolution' => '640x480'
    //                 );
    //                 $archive   = $opentok->startArchive($sessionId, $archiveOptions);
    //                 $archiveId = $archive->id;

    //                 $json = array(
    //                     'success'   => true,
    //                     'message'   => "Archive started sucessfully",
    //                     'sessionId' => $sessionId,
    //                     'tokenId'   => $tokenId,
    //                     'archiveId' => $archiveId
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function stop_archive(){
    //     $data     = array();
    //     $json     = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token_no');
    //         $payload    = $this->input->post('payload');
    //         $sessionId  = $this->input->post('sessionId');
    //         $tokenId    = $this->input->post('tokenId');
    //         $archiveId  = $this->input->post('archiveId');
    //         if ($archiveId==""){
    //             $system_dttm = date('Y-m-d H:i:s');
    //             $json = array(
    //                 'success'  => false,
    //                 'message'  => "ARCHIVE_ID_MISSING",
    //                 'end_time' => $system_dttm
    //             );
    //         }else{
    //             $server_key = 'awar@thon';
    //             $this->load->library("JWT");
    //             try{
    //                 $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //                 $common_user_id = $JWTVerify->user_id;
    //                 $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //                 $user_id    = 0;
    //                 $company_id = 0;
    //                 $where_clause = array(
    //                     'id'           => $common_user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 
    //                 $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //                 if (count((array)$common_user_details)>0){
    //                     $user_id = $common_user_details->user_id;
    //                 }

    //                 $where_clause = array(
    //                     'user_id'      => $user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 
    //                 $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //                 if (count((array)$user_details)>0){
    //                     $user_company_id = $user_details->company_id;
    //                     $apiKey          = $this->config->item('tokbox_apikey');
    //                     $apiSecret       = $this->config->item('tokbox_apisecret');
    //                     $opentok         = new OpenTok($apiKey, $apiSecret);

    //                     $opentok->stopArchive($archiveId);
    //                     $system_dttm = date('Y-m-d H:i:s');

    //                     $json           =  array(
    //                         'success'   => true,
    //                         'message'   => "Archive details fetched successfully",
    //                         'sessionId' => $sessionId,
    //                         'tokenId'   => $tokenId,
    //                         'archiveId' => $archiveId,
    //                         'end_time'  => $system_dttm
    //                     );
    //                 }else{
    //                     $system_dttm = date('Y-m-d H:i:s');
    //                     $json = array(
    //                         'success'  => false,
    //                         'message'  => "Invalid Token",
    //                         'end_time' => $system_dttm
    //                     );
    //                 }
    //             }catch(Exception $e){
    //                 try{
    //                     $opentok->deleteArchive($archiveId);
    //                 }catch(Exception $e){
    //                 }
    //                 $system_dttm = date('Y-m-d H:i:s');
    //                 $json = array(
    //                     'success'  => false,
    //                     'message'  => "ARCHIVE_EXCEPTION",
    //                     'end_time' => $system_dttm
    //                 );
    //             }
    //         }
    //     }else{
    //         $system_dttm = date('Y-m-d H:i:s');
    //         $json = array(
    //             'success'  => false,
    //             'message'  => "Unable to post data either it is empty or not set.",
    //             'end_time' => $system_dttm
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function get_single_archive(){
    //     $data     = array();
    //     $json     = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token_no');
    //         $payload    = $this->input->post('payload');
    //         $sessionId  = $this->input->post('sessionId');
    //         $tokenId    = $this->input->post('tokenId');
    //         $archiveId  = $this->input->post('archiveId');

    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $apiKey          = $this->config->item('tokbox_apikey');
    //                 $apiSecret       = $this->config->item('tokbox_apisecret');
    //                 $opentok         = new OpenTok($apiKey, $apiSecret);

    //                 $singleArchive = $opentok->getArchive($archiveId);
    //                 $archive_id          = $singleArchive->id;
    //                 $archive_status      = $singleArchive->status;
    //                 $archive_name        = $singleArchive->name;
    //                 $archive_reason      = $singleArchive->reason;
    //                 $archive_session_id  = $singleArchive->sessionId;
    //                 $archive_project_id  = $singleArchive->projectId;
    //                 $archive_created_at  = $singleArchive->createdAt;
    //                 $archive_size        = $singleArchive->size;
    //                 $archive_duration    = $singleArchive->duration;
    //                 $archive_output_mode = $singleArchive->outputMode;
    //                 $archive_has_audio   = $singleArchive->hasAudio;
    //                 $archive_has_video   = $singleArchive->hasVideo;
    //                 $archive_sha256sum   = $singleArchive->sha256sum;
    //                 $archive_password    = $singleArchive->password;
    //                 $archive_updated_at  = $singleArchive->updatedAt;
    //                 $archive_resolution  = $singleArchive->resolution;
    //                 $archive_partner_id  = $singleArchive->partnerId;
    //                 $archive_event       = $singleArchive->event;
    //                 $archive_url         = $singleArchive->url?json_encode($singleArchive->url):'';

    //                 $json                =  array(
    //                     'success'             => true,
    //                     'message'             => "Archive details fetched successfully",
    //                     'sessionId'           => $sessionId,
    //                     'tokenId'             => $tokenId,
    //                     'archiveId'           => $archiveId,
    //                     'archive_id'          => $archive_id,
    //                     'archive_status'      => $archive_status,
    //                     'archive_name'        => $archive_name,
    //                     'archive_reason'      => $archive_reason,
    //                     'archive_session_id'  => $archive_session_id,
    //                     'archive_project_id'  => $archive_project_id,
    //                     'archive_created_at'  => $archive_created_at,
    //                     'archive_size'        => $archive_size,
    //                     'archive_duration'    => $archive_duration,
    //                     'archive_output_mode' => $archive_output_mode,
    //                     'archive_has_audio'   => $archive_has_audio,
    //                     'archive_has_video'   => $archive_has_video,
    //                     'archive_sha256sum'   => $archive_sha256sum,
    //                     'archive_password'    => $archive_password,
    //                     'archive_updated_at'  => $archive_updated_at,
    //                     'archive_resolution'  => $archive_resolution,
    //                     'archive_partner_id'  => $archive_partner_id,
    //                     'archive_event'       => $archive_event,
    //                     'archive_url'         => $archive_url,
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function delete_archive(){
    //     $data     = array();
    //     $json     = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token_no');
    //         $payload    = $this->input->post('payload');
    //         $sessionId  = $this->input->post('sessionId');
    //         $tokenId    = $this->input->post('tokenId');
    //         $archiveId  = $this->input->post('archiveId');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $apiKey          = $this->config->item('tokbox_apikey');
    //                 $apiSecret       = $this->config->item('tokbox_apisecret');
    //                 $opentok         = new OpenTok($apiKey, $apiSecret);

    //                 $opentok->deleteArchive($archiveId);

    //                 $json = array(
    //                     'success'   => true,
    //                     'message'   => "Archive deleted sucessfully",
    //                     'sessionId' => $sessionId,
    //                     'tokenId'   => $tokenId,
    //                     'archiveId' => $archiveId
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function webauto_login(){
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);

    //         $data         = array();
    //         $json         = array();
    //         $webapp_encrypted_id = $this->input->post('id');
    //         $user_id = base64_decode(urldecode($webapp_encrypted_id));

    //         $where_clause = array(
    //             'status' => 1,
    //             'block'  => 0,
    //             'id'     => $user_id
    //         );
    //         $row          = $this->api_model->fetch_record('device_users',$where_clause);

    //         $db_password  = '';
    //         if (count((array)$row) > 0) {
    //             $common_user_id = $row->id;
    //             $user_id        = $row->user_id;
    //             $company_id     = $row->company_id;
    //             $firstname      = $row->firstname;
    //             $lastname       = $row->lastname;
    //             $email          = $row->email;
    //             $mobile         = $row->mobile;
    //             $avatar         = $row->avatar;

    //             $default_dboard_tab = '';
    //             if (isset($row->default_dboard_tab)){
    //                 $default_dboard_tab = (is_null($row->default_dboard_tab) OR $row->default_dboard_tab=='')?'':$row->default_dboard_tab;
    //             }

    //             //GENERATE TOKEN OF USER FOR SECURITY AND UPDATE
    //             $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));

    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             //CHECK COMPANY HAS ENABLED EMPLOYEE VERIFICATION BY SENDING OPT VIA EMAIL.
    //             $co_where_clause = array(
    //                 'id'     => $company_id,
    //                 'status' => 1
    //             );
    //             if ($company_id=='' OR $company_id<=0){
    //                 $otp_verified = 0;
    //                 $default_dboard_tab = 0;
    //             }else{
    //                 $otp_verified = $row->otp_verified;
    //                 $eotp_result = $this->api_model->fetch_record('company', $co_where_clause,$this->atomdb);
    //                 if (count((array)$eotp_result) > 0) {
    //                     if ($eotp_result->eotp_required==0){
    //                         $otp_verified = 1;
    //                     }
    //                     if ($default_dboard_tab==''){
    //                         if (isset($eotp_result->default_dboard_tab)){
    //                             $default_dboard_tab = (is_null($eotp_result->default_dboard_tab) OR $eotp_result->default_dboard_tab=='')?0:$eotp_result->default_dboard_tab;
    //                         }else{
    //                             $default_dboard_tab = 0;
    //                         }
    //                     }
    //                 }
    //             }

    //             $token_data = array(
    //                 'token'   => $token
    //             );
    //             //COMMON
    //             $where_clause = array(
    //                 'id' => $common_user_id
    //             ); 
    //             $update_status    = $this->api_model->update('device_users', $where_clause, $token_data);

    //             //DOMAIN
    //             $where_clause = array(
    //                 'user_id' => $user_id
    //             );
    //             $update_status_ii = $this->api_model->update('device_users', $where_clause, $token_data,$this->atomdb);

    //             //CHECK IF DEVICE DETAILS HAS CHANGES THEN INSERT NEW ELSE UPDATE
    //             //COMMON
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $common_user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1,
    //                         'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                         'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                         'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                         'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                         'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                         'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                         'info_dttm'          => date('Y-m-d H:i:s')
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $common_user_id,
    //                         'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                         'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                     ); 
    //                     $device_found =$this->api_model->record_count('device_info',$where_clause);
    //                     if ($device_found>0){
    //                         $this->api_model->update('device_info',$where_clause,$jsonDevice);
    //                     }else{
    //                         $this->api_model->insert('device_info',$jsonDevice);
    //                     }
    //                 }
    //             }

    //             //DOMAIN
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1,
    //                         'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                         'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                         'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                         'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                         'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                         'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                         'info_dttm'          => date('Y-m-d H:i:s')
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $user_id,
    //                         'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                         'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                     ); 
    //                     $device_found =$this->api_model->record_count('device_info',$where_clause,$this->atomdb);
    //                     if ($device_found>0){
    //                         $this->api_model->update('device_info',$where_clause,$jsonDevice,$this->atomdb);
    //                     }else{
    //                         $this->api_model->insert('device_info',$jsonDevice,$this->atomdb);
    //                     }
    //                 }
    //             }

    //             if ($update_status AND $update_status_ii){
    //                 $payload      = $this->generate_payload($common_user_id,$token);
    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $company_name = '';
    //                 $company_logo = '';
    //                 $whereclause  = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $company_name = $company_data->company_name;
    //                     $company_logo = $company_data->company_logo;
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }
    //                 $company_path = '';                      
    //                 if ($company_logo ==''){
    //                     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                 }else{
    //                     // $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    //                     $file_path = "/assets/uploads/company/".$company_logo;
    //                     // if (file_exists($file_path_check)){
    //                         $company_path = $domin_url.$file_path;
    //                     // }else{
    //                     //     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     // } 
    //                 }

    //                 $avatar_path = '';                      
    //                 if ($avatar ==''){
    //                     $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                 }else{
    //                     $file_path_check = "../".$portal_name."/assets/uploads/avatar/".$avatar;
    //                     $file_path = "/assets/uploads/avatar/".$avatar;
    //                     if (file_exists($file_path_check)){
    //                         $avatar_path = $domin_url.$file_path;
    //                     }else{
    //                         $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                     } 
    //                 }                               
    //                 $json = array(
    //                     'success'            => true,
    //                     'message'            => "Welcome to the Awarathon",
    //                     'user_id'            => $common_user_id,
    //                     'company_id'         => $company_id,
    //                     'company_name'       => $company_name,
    //                     'firstname'          => $firstname,
    //                     'lastname'           => $lastname,
    //                     'email'              => $email,
    //                     'mobile'             => $mobile,
    //                     'avatar'             => $avatar_path,
    //                     'co_logo'            => $company_path,
    //                     'superaccess'        => false,
    //                     'token_no'           => $token,
    //                     'payload'            => $payload,
    //                     'otp_pending'        => $otp_verified==1?0:1,
    //                     'otc_pending'        => ($company_id!='' AND $company_id>0)?0:1,
    //                     'co_pending'         => ($company_id!='' AND $company_id>0)?0:1,
    //                     'default_dboard_tab' => $default_dboard_tab,
    //                     'webauto_login'      => 1
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }

    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "Invalid username/password."
    //             );
    //         }  
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function login(){
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);

    //         $data         = array();
    //         $json         = array();
    //         $email        = $this->security->xss_clean($this->input->post('email'));
    //         $password     = $this->security->xss_clean($this->input->post('password'));

    //         $where_clause = array(
    //             'status'  => 1,
    //             'block'   => 0,
    //             'email'   => $email,
    //         );
    //         $row          = $this->api_model->fetch_record('device_users',$where_clause);

    //         $db_password  = '';
    //         if (count((array)$row) > 0) {
    //             if ($this->api_model->decrypt_password($password, $row->password) == 1) {

    //                 $common_user_id = $row->id;
    //                 $user_id        = $row->user_id;
    //                 $company_id     = $row->company_id;
    //                 $firstname      = $row->firstname;
    //                 $lastname       = $row->lastname;
    //                 $email          = $row->email;
    //                 $mobile         = $row->mobile;
    //                 $avatar         = $row->avatar;

    //                 $default_dboard_tab = '';
    //                 if (isset($row->default_dboard_tab)){
    //                     $default_dboard_tab = (is_null($row->default_dboard_tab) OR $row->default_dboard_tab=='')?'':$row->default_dboard_tab;
    //                 }

    //                 //GENERATE TOKEN OF USER FOR SECURITY AND UPDATE
    //                 $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));

    //                 $this->atomdb = $this->api_model->connectDb($common_user_id);

    //                 //CHECK COMPANY HAS ENABLED EMPLOYEE VERIFICATION BY SENDING OTP VIA EMAIL.
    //                 $co_where_clause = array(
    //                     'id'     => $company_id,
    //                     'status' => 1
    //                 );
    //                 if ($company_id=='' OR $company_id<=0){
    //                     $otp_verified = 0;
    //                     $default_dboard_tab = 0;
    //                 }else{
    //                     $otp_verified = $row->otp_verified;
    //                     $eotp_result = $this->api_model->fetch_record('company', $co_where_clause,$this->atomdb);
    //                     if (count((array)$eotp_result) > 0) {
    //                         if ($eotp_result->eotp_required==0){
    //                             $otp_verified = 1;
    //                         }
    //                         if ($default_dboard_tab==''){
    //                             if (isset($eotp_result->default_dboard_tab)){
    //                                 $default_dboard_tab = (is_null($eotp_result->default_dboard_tab) OR $eotp_result->default_dboard_tab=='')?0:$eotp_result->default_dboard_tab;
    //                             }else{
    //                                 $default_dboard_tab = 0;
    //                             }
    //                         }
    //                     }
    //                 }

    //                 $token_data = array(
    //                     'token'   => $token
    //                 );
    //                 //COMMON
    //                 $where_clause = array(
    //                     'id' => $common_user_id
    //                 ); 
    //                 $update_status    = $this->api_model->update('device_users', $where_clause, $token_data);

    //                 //DOMAIN
    //                 $where_clause = array(
    //                     'user_id' => $user_id
    //                 );
    //                 $update_status_ii = $this->api_model->update('device_users', $where_clause, $token_data,$this->atomdb);

    //                 //CHECK IF DEVICE DETAILS HAS CHANGES THEN INSERT NEW ELSE UPDATE
    //                 //COMMON
    //                 if (isset($_POST['device_info'])){
    //                     $device_information = json_decode($this->input->post('device_info'));
    //                     if (count((array)$device_information)>0){
    //                         $jsonDevice = array(
    //                             'user_id'            => $common_user_id,
    //                             'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                             'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                             'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                             'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                             'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                             'model'              => isset($device_information->model)?$device_information->model:'',
    //                             'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                             'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                             'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                             'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                             'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                             'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                             'version'            => isset($device_information->version)?$device_information->version:'',
    //                             'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                             'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                             'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                             'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                             'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                             'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                             'registered_account' => '',
    //                             'user_agent'         => '',
    //                             'status'             => 1,
    //                             'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                             'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                             'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                             'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                             'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                             'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                             'info_dttm'          => date('Y-m-d H:i:s')
    //                         );
    //                         $where_clause = array(
    //                             'user_id' => $common_user_id,
    //                             'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                             'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                         ); 
    //                         $device_found =$this->api_model->record_count('device_info',$where_clause);
    //                         if ($device_found>0){
    //                             $this->api_model->update('device_info',$where_clause,$jsonDevice);
    //                         }else{
    //                             $this->api_model->insert('device_info',$jsonDevice);
    //                         }
    //                     }
    //                 }

    //                 //DOMAIN
    //                 if (isset($_POST['device_info'])){
    //                     $device_information = json_decode($this->input->post('device_info'));
    //                     if (count((array)$device_information)>0){
    //                         $jsonDevice = array(
    //                             'user_id'            => $user_id,
    //                             'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                             'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                             'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                             'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                             'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                             'model'              => isset($device_information->model)?$device_information->model:'',
    //                             'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                             'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                             'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                             'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                             'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                             'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                             'version'            => isset($device_information->version)?$device_information->version:'',
    //                             'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                             'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                             'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                             'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                             'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                             'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                             'registered_account' => '',
    //                             'user_agent'         => '',
    //                             'status'             => 1,
    //                             'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                             'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                             'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                             'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                             'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                             'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                             'info_dttm'          => date('Y-m-d H:i:s')
    //                         );
    //                         $where_clause = array(
    //                             'user_id' => $user_id,
    //                             'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                             'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                         ); 
    //                         $device_found =$this->api_model->record_count('device_info',$where_clause,$this->atomdb);
    //                         if ($device_found>0){
    //                             $this->api_model->update('device_info',$where_clause,$jsonDevice,$this->atomdb);
    //                         }else{
    //                             $this->api_model->insert('device_info',$jsonDevice,$this->atomdb);
    //                         }
    //                     }
    //                 }

    //                 if ($update_status AND $update_status_ii){
    //                     $payload      = $this->generate_payload($common_user_id,$token);
    //                     $portal_name  = '';
    //                     $domin_url    = '';
    //                     $company_name = '';
    //                     $company_logo = '';
    //                     $whereclause  = array('id' => $company_id);
    //                     $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                     if(count((array)$company_data)>0){
    //                         $company_name = $company_data->company_name;
    //                         $company_logo = $company_data->company_logo;
    //                         $portal_name  = $company_data->portal_name;
    //                         $domin_url    = $company_data->domin_url;
    //                     }
    // 					$company_data_mw = $this->api_model->fetch_record('company',$whereclause);
    // 					$is_other_hosting=0;
    //                     if(count((array)$company_data_mw)>0){
    //                         $is_other_hosting=$company_data_mw->is_other_hosting;
    //                     }
    //                     $company_path = '';                      
    //                     if ($company_logo ==''){
    //                         $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     }else{
    // 						// $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    // 						$file_path = "/assets/uploads/company/".$company_logo;
    // 						// if($is_other_hosting || file_exists($file_path_check)){
    // 							$company_path = $domin_url.$file_path;
    // 						// }else{
    // 						// 	$company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    // 						// }
    //                     }

    //                     $avatar_path = '';                      
    //                     if ($avatar ==''){
    //                         $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                     }else{
    //                         $file_path_check = "../".$portal_name."/assets/uploads/avatar/".$avatar;
    //                         $file_path = "/assets/uploads/avatar/".$avatar;
    //                         if (file_exists($file_path_check)){
    //                             $avatar_path = $domin_url.$file_path;
    //                         }else{
    //                             $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                         } 
    //                     }                               
    //                     $json = array(
    //                         'success'            => true,
    //                         'message'            => "Welcome to the Awarathon",
    //                         'user_id'            => $common_user_id,
    //                         'company_id'         => $company_id,
    //                         'company_name'       => $company_name,
    //                         'firstname'          => $firstname,
    //                         'lastname'           => $lastname,
    //                         'email'              => $email,
    //                         'mobile'             => $mobile,
    //                         'avatar'             => $avatar_path,
    //                         'co_logo'            => $company_path,
    //                         'superaccess'        => false,
    //                         'token_no'           => $token,
    //                         'payload'            => $payload,
    //                         'otp_pending'        => $otp_verified==1?0:1,
    //                         'otc_pending'        => ($company_id!='' AND $company_id>0)?0:1,
    //                         'co_pending'         => ($company_id!='' AND $company_id>0)?0:1,
    //                         'default_dboard_tab' => $default_dboard_tab,
    //                         'webauto_login'      => 0
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "Invalid Token"
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid username/password."
    //                 );
    //             }  
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "Invalid username/password."
    //             );
    //         }  
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function bsequiz_login(){
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);

    //         $data         = array();
    //         $json         = array();
    //         $email        = $this->security->xss_clean($this->input->post('email'));

    //         $where_clause = array(
    //             'status'  => 1,
    //             'block'   => 0,
    //             'email'   => $email,
    //         );
    //         $row          = $this->api_model->fetch_record('device_users',$where_clause);

    //         $db_password  = '';
    //         if (count((array)$row) > 0) {
    //             $common_user_id = $row->id;
    //             $user_id        = $row->user_id;
    //             $company_id     = $row->company_id;
    //             $firstname      = $row->firstname;
    //             $lastname       = $row->lastname;
    //             $email          = $row->email;
    //             $mobile         = $row->mobile;
    //             $avatar         = $row->avatar;

    //             $default_dboard_tab = '';
    //             if (isset($row->default_dboard_tab)){
    //                 $default_dboard_tab = (is_null($row->default_dboard_tab) OR $row->default_dboard_tab=='')?'':$row->default_dboard_tab;
    //             }

    //             //GENERATE TOKEN OF USER FOR SECURITY AND UPDATE
    //             $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));

    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             //CHECK COMPANY HAS ENABLED EMPLOYEE VERIFICATION BY SENDING OTP VIA EMAIL.
    //             $co_where_clause = array(
    //                 'id'     => $company_id,
    //                 'status' => 1
    //             );

    //             $otp_verified       = 1;
    //             $default_dboard_tab = 0;
    //             $eotp_result        = $this->api_model->fetch_record('company', $co_where_clause,$this->atomdb);
    //             if (count((array)$eotp_result) > 0) {
    //                 if ($default_dboard_tab==''){
    //                     if (isset($eotp_result->default_dboard_tab)){
    //                         $default_dboard_tab = (is_null($eotp_result->default_dboard_tab) OR $eotp_result->default_dboard_tab=='')?0:$eotp_result->default_dboard_tab;
    //                     }else{
    //                         $default_dboard_tab = 0;
    //                     }
    //                 }
    //             }


    //             $token_data = array(
    //                 'token'   => $token
    //             );
    //             //COMMON
    //             $where_clause = array(
    //                 'id' => $common_user_id
    //             ); 
    //             $update_status    = $this->api_model->update('device_users', $where_clause, $token_data);

    //             //DOMAIN
    //             $where_clause = array(
    //                 'user_id' => $user_id
    //             );
    //             $update_status_ii = $this->api_model->update('device_users', $where_clause, $token_data,$this->atomdb);

    //             //CHECK IF DEVICE DETAILS HAS CHANGES THEN INSERT NEW ELSE UPDATE
    //             //COMMON
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $common_user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1,
    //                         'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                         'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                         'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                         'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                         'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                         'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                         'info_dttm'          => date('Y-m-d H:i:s')
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $common_user_id,
    //                         'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                         'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                     ); 
    //                     $device_found =$this->api_model->record_count('device_info',$where_clause);
    //                     if ($device_found>0){
    //                         $this->api_model->update('device_info',$where_clause,$jsonDevice);
    //                     }else{
    //                         $this->api_model->insert('device_info',$jsonDevice);
    //                     }
    //                 }
    //             }

    //             //DOMAIN
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1,
    //                         'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                         'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                         'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                         'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                         'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                         'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                         'info_dttm'          => date('Y-m-d H:i:s')
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $user_id,
    //                         'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                         'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                     ); 
    //                     $device_found =$this->api_model->record_count('device_info',$where_clause,$this->atomdb);
    //                     if ($device_found>0){
    //                         $this->api_model->update('device_info',$where_clause,$jsonDevice,$this->atomdb);
    //                     }else{
    //                         $this->api_model->insert('device_info',$jsonDevice,$this->atomdb);
    //                     }
    //                 }
    //             }

    //             if ($update_status AND $update_status_ii){
    //                 $payload      = $this->generate_payload($common_user_id,$token);
    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $company_name = '';
    //                 $company_logo = '';
    //                 $whereclause  = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $company_name = $company_data->company_name;
    //                     $company_logo = $company_data->company_logo;
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }
    //                 $company_path = '';                      
    //                 if ($company_logo ==''){
    //                     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                 }else{
    //                     // $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    //                     $file_path = "/assets/uploads/company/".$company_logo;
    //                     // if (file_exists($file_path_check)){
    //                         $company_path = $domin_url.$file_path;
    //                     // }else{
    //                     //     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     // } 
    //                 }

    //                 $avatar_path = '';                      
    //                 if ($avatar ==''){
    //                     $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                 }else{
    //                     $file_path_check = "../".$portal_name."/assets/uploads/avatar/".$avatar;
    //                     $file_path = "/assets/uploads/avatar/".$avatar;
    //                     if (file_exists($file_path_check)){
    //                         $avatar_path = $domin_url.$file_path;
    //                     }else{
    //                         $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                     } 
    //                 }                               
    //                 $json = array(
    //                     'success'            => true,
    //                     'message'            => "Welcome to the Awarathon",
    //                     'user_id'            => $common_user_id,
    //                     'company_id'         => $company_id,
    //                     'company_name'       => $company_name,
    //                     'firstname'          => $firstname,
    //                     'lastname'           => $lastname,
    //                     'email'              => $email,
    //                     'mobile'             => $mobile,
    //                     'avatar'             => $avatar_path,
    //                     'co_logo'            => $company_path,
    //                     'superaccess'        => false,
    //                     'token_no'           => $token,
    //                     'payload'            => $payload,
    //                     'otp_pending'        => $otp_verified==1?0:1,
    //                     'otc_pending'        => ($company_id!='' AND $company_id>0)?0:1,
    //                     'co_pending'         => ($company_id!='' AND $company_id>0)?0:1,
    //                     'default_dboard_tab' => $default_dboard_tab,
    //                     'webauto_login'      => 0
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }

    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "Email address is not registered."
    //             );
    //         }  
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function upload(){
    //     $json           = array();
    //     $this->load->library('upload');
    //     $payload        = $this->input->post('payload');
    //     $server_key     = 'awar@thon';
    //     $this->load->library("JWT");

    //     //PAYLOAD VERIFY
    //     try{
    //         if (isset($_POST['payload'])){
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id        = 0;
    //             $company_id     = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id    = $common_user_details->user_id;
    //                 $company_id = $common_user_details->company_id;
    //             }
    //             $portal_name  = '';
    //             $domin_url    = '';
    //             $whereclause = array('id' => $company_id);
    //             $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //             if(count((array)$company_data)>0){
    //                 $portal_name  = $company_data->portal_name;
    //                 $domin_url    = $company_data->domin_url;
    //             }
    //             $upload_directory        = "../".$portal_name."/assets/uploads/avatar/";
    //             $config['overwrite']     = FALSE;
    //             $config['upload_path']   = $upload_directory;
    //             $config['allowed_types'] = 'jpg|jpeg|png';
    //             $config['file_name']     = $this->input->post('fileName');
    //             $avatar                  = $domin_url."/assets/uploads/avatar/".$this->input->post('fileName');

    //             $this->upload->initialize($config);
    //             if (!$this->upload->do_upload('file')) {
    //                 $json = array(
    //                     'success' => false,
    //                     'avatar'  => '',
    //                     'message' => $this->upload->display_errors()
    //                 );
    //             }else{
    //                 $data = array('upload_data' => $this->upload->data());
    //                 $json = array(
    //                     'success' => true,
    //                     'avatar'  => $avatar,
    //                     'message' => 'Image uploaded successfully'
    //                 );
    //             }
    //         }else{
    //             $upload_directory        = "../mwadmin/assets/uploads/avatar/";
    //             $config['overwrite']     = FALSE;
    //             $config['upload_path']   = $upload_directory;
    //             $config['allowed_types'] = 'jpg|jpeg|png';
    //             $config['file_name']     = $this->input->post('fileName');
    //             $avatar                  = base_url()."/assets/uploads/avatar/".$this->input->post('fileName');

    //             $this->upload->initialize($config);
    //             if (!$this->upload->do_upload('file')) {
    //                 $json = array(
    //                     'success' => false,
    //                     'avatar'  => '',
    //                     'message' => $this->upload->display_errors()
    //                 );
    //             }else{
    //                 $data = array('upload_data' => $this->upload->data());
    //                 $json = array(
    //                     'success' => true,
    //                     'avatar'  => $avatar,
    //                     'message' => 'Image uploaded successfully'
    //                 );
    //             }
    //         }
    //     }catch(Exception $e){
    //         $json = array(
    //             'success' => false,
    //             'avatar'  => '',
    //             'message' =>  $e->getMessage()
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function upload_web(){
    //     $json       = array();
    //     $this->load->library('upload');
    //     $payload    = $this->input->post('payload');
    //     $server_key = 'awar@thon';
    //     $this->load->library("JWT");

    //     //PAYLOAD VERIFY
    //     try{
    //         if (isset($_POST['payload'])){
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id        = 0;
    //             $company_id     = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id    = $common_user_details->user_id;
    //                 $company_id = $common_user_details->company_id;
    //             }
    //             $portal_name  = '';
    //             $domin_url    = '';
    //             $whereclause = array('id' => $company_id);
    //             $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //             if(count((array)$company_data)>0){
    //                 $portal_name  = $company_data->portal_name;
    //                 $domin_url    = $company_data->domin_url;
    //             }
    //             $upload_directory        = "../".$portal_name."/assets/uploads/avatar/";
    //             $config['overwrite']     = FALSE;
    //             $config['upload_path']   = $upload_directory;
    //             $config['allowed_types'] = 'jpg|jpeg|png';
    //             $config['file_name']     = $this->input->post('file_name');
    //             $avatar                  = $domin_url."/assets/uploads/avatar/".$this->input->post('file_name');

    //             $this->upload->initialize($config);
    //             if (!$this->upload->do_upload('image')) {
    //                 $json = array(
    //                     'success' => false,
    //                     'avatar'  => '',
    //                     'message' => $this->upload->display_errors()
    //                 );
    //             }else{
    //                 $data = array('upload_data' => $this->upload->data());
    //                 $this->load->library("image_lib");
    //                 $config['image_library']   = 'gd2';
    //                 $config['source_image']    = $upload_directory.$this->input->post('file_name');
    //                 $config['maintain_ratio']  = FALSE;
    //                 $config['new_image']       = $upload_directory.$this->input->post('file_name');
    //                 $config['width']           = 300;
    //                 $config['height']          = 300;
    //                 $config['quality'] = '100%';

    //                 $this->image_lib->initialize($config);
    //                 $this->image_lib->resize();

    //                 $json = array(
    //                     'success' => true,
    //                     'avatar'  => $avatar,
    //                     'message' => 'Image uploaded successfully'
    //                 );
    //             }
    //         }else{
    //             $upload_directory        = "../mwadmin/assets/uploads/avatar/";
    //             $config['overwrite']     = FALSE;
    //             $config['upload_path']   = $upload_directory;
    //             $config['allowed_types'] = 'jpg|jpeg|png';
    //             $config['file_name']     = $this->input->post('file_name');
    //             $avatar                  = base_url()."/assets/uploads/avatar/".$this->input->post('file_name');

    //             $this->upload->initialize($config);
    //             if (!$this->upload->do_upload('image')) {
    //                 $json = array(
    //                     'success' => false,
    //                     'avatar'  => '',
    //                     'message' => $this->upload->display_errors()
    //                 );
    //             }else{
    //                 $data = array('upload_data' => $this->upload->data());
    //                 $this->load->library("image_lib");
    //                 $config['image_library']   = 'gd2';
    //                 $config['source_image']    = $upload_directory.$this->input->post('file_name');
    //                 $config['maintain_ratio']  = FALSE;
    //                 $config['new_image']       = $upload_directory.$this->input->post('file_name');
    //                 $config['width']           = 300;
    //                 $config['height']          = 300;
    //                 $config['quality'] = '100%';

    //                 $this->image_lib->initialize($config);
    //                 $this->image_lib->resize();

    //                 $json = array(
    //                     'success' => true,
    //                     'avatar'  => $avatar,
    //                     'message' => 'Image uploaded successfully'
    //                 );
    //             }
    //         }
    //     }catch(Exception $e){
    //         $json = array(
    //             'success' => false,
    //             'avatar'  => '',
    //             'message' =>  $e->getMessage()
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function upload_video_ftp(){
    //     $json           = array();
    //     $this->load->library('upload');
    //     $payload        = $this->input->post('payload');
    //     $server_key     = 'awar@thon';
    //     $this->load->library("JWT");

    //     //PAYLOAD VERIFY
    //     try{
    //         if (isset($_POST['payload'])){
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id        = 0;
    //             $company_id     = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id    = $common_user_details->user_id;
    //                 $company_id = $common_user_details->company_id;
    //             }
    //             $portal_name  = '';
    //             $domin_url    = '';
    //             $whereclause = array('id' => $company_id);
    //             $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //             if(count((array)$company_data)>0){
    //                 $portal_name  = $company_data->portal_name;
    //                 $domin_url    = $company_data->domin_url;
    //             }
    //             $upload_directory        = "../vimeo/";
    //             $config['overwrite']     = TRUE;
    //             $config['upload_path']   = $upload_directory;
    //             $config['allowed_types'] = '*';
    //             $config['file_name']     = $this->input->post('fileName');
    //             $video_url               = "vimeo/".$this->input->post('fileName');

    //             $this->upload->initialize($config);
    //             if (!$this->upload->do_upload('file')) {
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => $this->upload->display_errors()
    //                 );
    //             }else{
    //                 $data = array('upload_data' => $this->upload->data());
    //                 $json = array(
    //                     'success' => true,
    //                     'message' => 'Video uploaded successfully'
    //                 );
    //             }
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "Invalid Token"
    //             );
    //         }
    //     }catch(Exception $e){
    //         $json = array(
    //             'success' => false,
    //             'message' =>  $e->getMessage()
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function registration(){
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);

    //         $data         = array();
    //         $json         = array();  
    //         $otp_pending  = 1;
    //         $otc_pending  = 1;
    //         $co_pending   = 1;


    //         //EMAIL DUPLICATE NOT ALLOWED
    //         $where_clause = array(
    //             'email' => trim($this->input->post('email'))
    //         ); 
    //         $user_found =$this->api_model->fetch_record('device_users',$where_clause);

    //         $where_clause = array(
    //             'username' => trim($this->input->post('email'))
    //         ); 
    //         $company_user_found = $this->api_model->fetch_record('company_users',$where_clause);

    //         if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
    //             $json = array(
    //                 'otp_pending' => '',
    //                 'otc_pending' => '',
    //                 'co_pending'  => '',
    //                 'success'     => false,
    //                 'message'     => "Invalid email address"
    //             );
    //         }else if ($this->input->post('email')=='' OR $this->input->post('email')==NULL OR IS_NULL($this->input->post('email'))){
    //             $json = array(
    //                 'otp_pending' => '',
    //                 'otc_pending' => '',
    //                 'co_pending'  => '',
    //                 'success'     => false,
    //                 'message'     => "Email address required"
    //             );
    //         }else if (count((array)$company_user_found)>0){
    //             $json = array(
    //                 'otp_pending' => '',
    //                 'otc_pending' => '',
    //                 'co_pending'  => '',
    //                 'success'     => false,
    //                 'message'     => "Email address already registered"
    //             );
    //         }else if (count((array)$user_found)>0){
    //             $company_id   = $user_found->company_id;
    //             $otp_verified = $user_found->otp_verified;
    //             if ($otp_verified==0 OR $otp_verified=='' OR $otp_verified==NULL OR IS_NULL($otp_verified)){
    //                 $otp_pending = 1;
    //             }else{
    //                 if ($otp_verified==1){
    //                     $otp_pending = 0;
    //                 }
    //             }
    //             if ($company_id=='' OR $company_id==NULL OR IS_NULL($company_id)){
    //                 $otc_pending = 1;
    //                 $co_pending = 1;
    //             }else{
    //                 $otc_pending = 0;
    //                 $co_pending = 0;
    //             }
    //             $json = array(
    //                 'otp_pending' => $otp_pending,
    //                 'otc_pending' => $otc_pending,
    //                 'co_pending'  => $co_pending,
    //                 'success'     => false,
    //                 'message'     => "Email address already registered"
    //             );
    //         }else{
    //             $data = array(
    //                 'firstname'         => $this->input->post('firstname'),
    //                 'lastname'          => $this->input->post('lastname'),
    //                 'mobile'            => $this->input->post('mobile'),
    //                 'email'             => $this->input->post('email'),
    //                 'password'          => $this->api_model->encrypt_password($this->input->post('password')),
    //                 'avatar'            => $this->input->post('avatar'),
    //                 'registration_date' => date('Y-m-d'),
    //                 'last_access_date'  => date('Y-m-d'),
    //                 'status'            => 1,
    //                 'block'             => 0,
    //                 'fb_registration'   => 0
    //             );
    //             $common_user_id = $this->api_model->insert('device_users',$data);

    //             //GENERATE OTP AND TOKEN OF USER FOR SECURITY AND UPDATE
    //             $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));
    //             $OTP   = sprintf("%06d",rand(1,999999));
    //             $token_data = array(
    //                 'token'            => $token,
    //                 'otp'              => $OTP,
    //                 'otp_last_attempt' => date('Y-m-d H:i:s'),
    //                 'otp_verified'     => 0
    //             );
    //             $where_clause = array(
    //                 'id' => $common_user_id,
    //             ); 
    //             $update_status = $this->api_model->update('device_users', $where_clause, $token_data);

    //             //INSERT DEVICE INFORMATION
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $common_user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1
    //                     );
    //                     $device_id = $this->api_model->insert('device_info',$jsonDevice);
    //                 }
    //             }

    //             if ($update_status){
    //                 $payload = $this->generate_payload($common_user_id,$token);

    //                 $avatar_path = ''; 
    //                 $avatar      = $this->input->post('avatar');                     
    //                 if ($avatar ==''){
    //                     $avatar_path = base_url()."/mwadmin/assets/uploads/avatar/no-avatar.jpg";
    //                 }else{
    //                     $file_path_check = "../mwadmin/assets/uploads/avatar/".$avatar;
    //                     $file_path = "/mwadmin/assets/uploads/avatar/".$avatar;
    //                     if (file_exists($file_path_check)){
    //                         $avatar_path = base_url().$file_path;
    //                     }else{
    //                         $avatar_path = base_url()."/mwadmin/assets/uploads/avatar/no-avatar.jpg";
    //                     } 
    //                 } 

    //                 $json = array(
    //                     'user_id'       => $common_user_id,
    //                     'company_id'    => '',
    //                     'company_name'  => '',
    //                     'firstname'     => $this->input->post('firstname'),
    //                     'lastname'      => $this->input->post('lastname'),
    //                     'mobile'        => $this->input->post('mobile'),
    //                     'email'         => $this->input->post('email'),
    //                     'avatar'        => $avatar_path,
    //                     'co_logo'       => '',
    //                     'superaccess'   => false,
    //                     'token'         => $token,
    //                     'payload'       => $payload,
    //                     'otp_pending'   => $otp_pending,
    //                     'otc_pending'   => $otc_pending,
    //                     'co_pending'    => $co_pending,
    //                     'success'       => true,
    //                     'message'       => "Registration completed successfully",
    //                     'webauto_login' => 0
    //                 );

    //                 //SEND OTP TO REGISTER USER.
    //                 $fullname      = $this->input->post('firstname')." ".$this->input->post('lastname');
    //                 $email_address = $this->input->post('email');
    //                 $subject       = "Awarathon 'My Account' One Time Password(OTP)";
    //                 $body          = 'The One Time Password (OTP) for your account on The Awarathon App is : '.$OTP.'<br/><br/>
    //                                 Please Note:<br/>This OTP is valid for 15 minutes only.<br/>
    //                                 OTP has been sent to your preferred email registered with us.<br/>
    //                                 IMPORTANT: Please do not reply to this message. For any queries, please email us at product@awarathon.com<br/><br/>
    //                                 DISCLAIMER: This communication is confidential and privileged and is directed to and for the use of the addressee only. 
    //                                 The recipient if not the addressee should not use this message if erroneously received, 
    //                                 and access and use of this e-mail in any manner by anyone other than the addressee is unauthorized. 
    //                                 The recipient acknowledges that awarathon.com may be unable to exercise control or ensure or guarantee the integrity of the 
    //                                 text of the email message and the text is not warranted as to completeness and accuracy. 
    //                                 Before opening and accessing the attachment, if any, please check and scan for virus.';
    //                 $from_name     = 'Awarathon';
    //                 $from_email    = "no-reply@awarathon.com";
    //                 $to_array      = $fullname."|".$email_address;
    //                 $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');

    //             }else{
    //                 $json = array(
    //                     'otp_pending' => '',
    //                     'otc_pending' => '',
    //                     'co_pending'  => '',
    //                     'success'     => false,
    //                     'message'     => "Invalid Token"
    //                 );
    //             }
    //         }
    //     }else{
    //         $json = array(
    //             'otp_pending' => '',
    //             'otc_pending' => '',
    //             'co_pending'  => '',
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function update_profile(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $firstname          = '';
    //             $lastname           = '';
    //             $email              = '';
    //             $mobile             = '';
    //             $avatar             = '';
    //             $token              = '';
    //             $otp_verified       = '';
    //             $default_dboard_tab = '';

    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $common_user_id     = $common_user_details->id;
    //                 $user_id            = $common_user_details->user_id;
    //                 $company_id         = $common_user_details->company_id;
    //                 $firstname          = $common_user_details->firstname;
    //                 $lastname           = $common_user_details->lastname;
    //                 $email              = $common_user_details->email;
    //                 $mobile             = $common_user_details->mobile;
    //                 $avatar             = $common_user_details->avatar;
    //                 $token              = $common_user_details->token;
    //                 $otp_verified       = $common_user_details->otp_verified;
    //                 $default_dboard_tab = $common_user_details->default_dboard_tab;
    //             }

    //             if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
    //                 $json = array(
    //                     'success'     => false,
    //                     'message'     => "Invalid email address"
    //                 );
    //             }else if ($this->input->post('email')=='' OR $this->input->post('email')==NULL OR IS_NULL($this->input->post('email'))){
    //                 $json = array(
    //                     'success'     => false,
    //                     'message'     => "Email address required"
    //                 );
    //             }else{
    //                 $data = array(
    //                     'firstname'         => $this->input->post('firstname'),
    //                     'lastname'          => $this->input->post('lastname'),
    //                     'mobile'            => $this->input->post('mobile'),
    //                     'email'             => $this->input->post('email'),
    //                     'avatar'            => $this->input->post('avatar_file_name'),
    //                     'last_access_date'  => date('Y-m-d')
    //                 );

    //                 //COMMON
    //                 $where_clause = array(
    //                     'id'           => $common_user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 );
    //                 $update_status = $this->api_model->update('device_users', $where_clause, $data);

    //                 //DOMAIN
    //                 $where_clause = array(
    //                     'user_id'      => $user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 );
    //                 $update_status_ii = $this->api_model->update('device_users', $where_clause, $data,$this->atomdb);

    //                 if ($update_status AND $update_status_ii){
    //                     $payload      = $this->generate_payload($common_user_id,$token);
    //                     $portal_name  = '';
    //                     $domin_url    = '';
    //                     $company_name = '';
    //                     $company_logo = '';
    //                     $whereclause  = array('id' => $company_id);
    //                     $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                     if(count((array)$company_data)>0){
    //                         $company_name = $company_data->company_name;
    //                         $company_logo = $company_data->company_logo;
    //                         $portal_name  = $company_data->portal_name;
    //                         $domin_url    = $company_data->domin_url;
    //                     }
    //                     $company_path = '';                      
    //                     if ($company_logo ==''){
    //                         $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     }else{
    //                         // $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    //                         $file_path = "/assets/uploads/company/".$company_logo;
    //                         // if (file_exists($file_path_check)){
    //                             $company_path = $domin_url.$file_path;
    //                         // }else{
    //                         //     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                         // } 
    //                     }

    //                     $avatar_path = '';                      
    //                     if ($avatar ==''){
    //                         $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                     }else{
    //                         $file_path_check = "../".$portal_name."/assets/uploads/avatar/".$avatar;
    //                         $file_path = "/assets/uploads/avatar/".$avatar;
    //                         if (file_exists($file_path_check)){
    //                             $avatar_path = $domin_url.$file_path;
    //                         }else{
    //                             $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                         } 
    //                     }                               
    //                     $json = array(
    //                         'success'            => true,
    //                         'message'            => "Profile updated successfully.",
    //                         'user_id'            => $common_user_id,
    //                         'company_id'         => $company_id,
    //                         'company_name'       => $company_name,
    //                         'firstname'          => $firstname,
    //                         'lastname'           => $lastname,
    //                         'email'              => $email,
    //                         'mobile'             => $mobile,
    //                         'avatar'             => $avatar_path,
    //                         'co_logo'            => $company_path,
    //                         'superaccess'        => false,
    //                         'token_no'           => $token,
    //                         'payload'            => $payload,
    //                         'otp_pending'        => $otp_verified==1?0:1,
    //                         'otc_pending'        => ($company_id!='' AND $company_id>0)?0:1,
    //                         'co_pending'         => ($company_id!='' AND $company_id>0)?0:1,
    //                         'default_dboard_tab' => $default_dboard_tab,
    //                         'webauto_login'      => 0
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "Internal server error, we are unable to update profile. Please try again"
    //                     );
    //                 }
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'otp_pending' => '',
    //             'otc_pending' => '',
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }    
    // public function forgot(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST        = json_decode($_jsonObj, TRUE);
    //         $new_password = sprintf("%06d",rand(1,999999));
    //         //GET USERS DETAILS USING EMAIL ADDRESS
    //         $where_clause = array(
    //             'status' => 1,
    //             'block'  => 0,
    //             'email'  => $this->input->post('email')
    //         ); 
    //         $device_user_details =$this->api_model->fetch_record('device_users',$where_clause);

    //         if (count((array)$device_user_details)>0){
    //             $common_user_id = $device_user_details->id;
    //             $user_id        = $device_user_details->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $where_clause = array(
    //                 'status' => 1,
    //                 'block'  => 0,
    //                 'email'  => $this->input->post('email')
    //             ); 
    //             $forgot_pwd_email_allow = true;
    //             $forgot_pwd_email_limit = 0;
    //             $forgot_pwd_email_dttm = date('Y-m-d H:i:s');
    //             $email_limit_result =$this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$email_limit_result)>0){
    //                 $forgot_pwd_email_dttm  = $email_limit_result->forgot_pwd_email_dttm;
    //                 $forgot_pwd_email_limit = $forgot_pwd_email_limit + ((int) $email_limit_result->forgot_pwd_email_limit);
    //                 if ( (strtotime(date('Y-m-d H:i:s')) - strtotime($forgot_pwd_email_dttm)) < 3600){
    //                     $forgot_pwd_email_allow = false;
    //                 }else{
    //                     $reset_data_domain = array(
    //                         'forgot_pwd_email_dttm'  => date('Y-m-d H:i:s'),
    //                         'forgot_pwd_email_limit' => 0,
    //                     );
    //                     $update_status_i = $this->api_model->update('device_users', $where_clause, $reset_data_domain,$this->atomdb);
    //                     $forgot_pwd_email_allow = true;
    //                     $forgot_pwd_email_limit = 0;
    //                 }
    //             }
    //             if ($forgot_pwd_email_limit<5 AND $forgot_pwd_email_allow = true){
    //                 $reset_data_mwadmin = array(
    //                     'password' => $this->api_model->encrypt_password($new_password)
    //                 );
    //                 $reset_data_domain = array(
    //                     'forgot_pwd_email_dttm'  => date('Y-m-d H:i:s'),
    //                     'forgot_pwd_email_limit' => $forgot_pwd_email_limit+1,
    //                     'password'               => $this->api_model->encrypt_password($new_password)
    //                 );
    //                 $update_status   = $this->api_model->update('device_users', $where_clause, $reset_data_mwadmin);
    //                 $update_status_i = $this->api_model->update('device_users', $where_clause, $reset_data_domain,$this->atomdb);
    //                 if ($update_status AND $update_status_i){
    //                     //SEND FORGOT PASSWORD RESET EMAIL
    //                     $fullname      = $device_user_details->firstname." ".$device_user_details->lastname;
    //                     $email_address = $device_user_details->email;
    //                     $subject       = "Awarathon Password Reset";
    //                     $body          = 'Your password has been reset successfully. Your new password is '.$new_password." ";
    //                     $from_name     = 'Awarathon';
    //                     $from_email    = "no-reply@awarathon.com";
    //                     $to_array      = $fullname."|".$email_address;
    //                     $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "An email containing information on how to reset your password has been sent to ".$email_address
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "Sorry, we are unable to recover your account. Please try again"
    //                     );
    //                 } 
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "You reached maximum attempts. Please try after sometime."
    //                 );
    //             }                   
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "The email address is not valid. Please verify that you have entered the correct information."
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function otp_verification(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $payload    = $this->input->post('payload');
    //         $otp        = $this->input->post('otp');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 0
    //             ); 
    //             $results =$this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $total =  count((array)$results);
    //             if ($total > 0) {
    //                 foreach ($results as $row) {
    //                     $otp_last_attempt = $row->otp_last_attempt;

    //                     $datetime1 = strtotime($otp_last_attempt);
    //                     $datetime2 = strtotime(date('Y-m-d H:i:s'));
    //                     $interval  = abs($datetime2 - $datetime1);
    //                     $minutes   = round($interval / 60);

    //                     if ($minutes<=15){
    //                         $where_clause = array(
    //                             'otp'     => $otp,
    //                             'user_id' => $user_id
    //                         ); 
    //                         $otp_found = $this->api_model->record_count('device_users',$where_clause,$this->atomdb);
    //                         if ($otp_found==1){
    //                             $data = array(
    //                                 'otp_last_attempt' => date('Y-m-d H:i:s'),
    //                                 'otp_verified'     => 1
    //                             );
    //                             $where_clause = array(
    //                                 'otp' => $otp,
    //                                 'id'  => $common_user_id
    //                             ); 
    //                             $update_status = $this->api_model->update('device_users', $where_clause,$data);

    //                             $where_clause = array(
    //                                 'otp'     => $otp,
    //                                 'user_id' => $user_id
    //                             ); 
    //                             $update_status_i   = $this->api_model->update('device_users', $where_clause,$data,$this->atomdb);
    //                             if ($update_status AND $update_status_i){
    //                                 $json = array(
    //                                     'success' => true,
    //                                     'message' => "OTP Verified"
    //                                 );
    //                             }else{
    //                                 $data = array(
    //                                     'otp_last_attempt' => date('Y-m-d H:i:s'),
    //                                     'otp_verified'     => 0
    //                                 );
    //                                 $where_clause = array(
    //                                     'otp' => $otp,
    //                                     'id'  => $common_user_id
    //                                 ); 
    //                                 $update_status = $this->api_model->update('device_users', $where_clause,$data);

    //                                 $where_clause = array(
    //                                     'otp'     => $otp,
    //                                     'user_id' => $user_id
    //                                 ); 
    //                                 $update_status_i   = $this->api_model->update('device_users', $where_clause,$data,$this->atomdb);

    //                                 $json = array(
    //                                     'success' => true,
    //                                     'message' => "OTP verification failed, Please try again"
    //                                 );
    //                             }
    //                         }else{
    //                             $data = array(
    //                                 'otp_last_attempt' => date('Y-m-d H:i:s'),
    //                                 'otp_verified'     => 0
    //                             );
    //                             $where_clause = array(
    //                                 'id'  => $common_user_id
    //                             ); 
    //                             $update_status = $this->api_model->update('device_users', $where_clause,$data);

    //                             $where_clause = array(
    //                                 'user_id' => $user_id
    //                             ); 
    //                             $update_status_i   = $this->api_model->update('device_users', $where_clause,$data,$this->atomdb);

    //                             $json = array(
    //                                 'success' => false,
    //                                 'message' => "Invalid OTP"
    //                             );
    //                         }
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' => "The One-Time Password (OTP) will be valid for 15 minutes from the time of generation, after which it expires."
    //                         );
    //                     }
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => true,
    //                     'message' => "OTP is already verified"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function otp_resend(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $OTP   = sprintf("%06d",rand(1,999999));
    //             $data = array(
    //                 'otp'              => $OTP,
    //                 'otp_last_attempt' => date('Y-m-d H:i:s'),
    //                 'otp_verified'     => 0
    //             );
    //             $where_clause = array(
    //                 'status' => 1,
    //                 'block'  => 0,
    //                 'id'     => $common_user_id
    //             ); 
    //             $update_status = $this->api_model->update('device_users', $where_clause, $data);
    //             $where_clause = array(
    //                 'status'  => 1,
    //                 'block'   => 0,
    //                 'user_id' => $user_id
    //             ); 
    //             $update_status_i   = $this->api_model->update('device_users', $where_clause, $data,$this->atomdb);

    //             if ($update_status AND $update_status_i){
    //                 $results =$this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //                 $total =  count($results);
    //                 if ($total > 0) {
    //                     foreach ($results as $row) {
    //                         $firstname     = $row->firstname;
    //                         $lastname      = $row->lastname;
    //                         $email_address = $row->email;
    //                     }
    //                 }    

    //                 //SEND OTP TO REGISTER USER.
    //                 $fullname      = $firstname." ".$lastname;
    //                 $subject       = "Awarathon 'My Account' One Time Password(OTP)";
    //                 $body          = 'Use '.$OTP.' as one time password (OTP) to login to your Awarathon account. 
    //                                 Do not share this OTP to anyone for security reasons. Valid for 15 minutes.';
    //                 $from_name     = 'Awarathon';
    //                 $from_email    = "no-reply@awarathon.com";
    //                 $to_array      = $fullname."|".$email_address;
    //                 $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');
    //                 $json = array(
    //                     'success' => true,
    //                     'message' => "OTP resend sucessfully"
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Internal server error unable to send OTP, Please try again."
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function company_otp_verification(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $payload    = $this->input->post('payload');
    //         $otp        = $this->input->post('otp');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 // 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'otp'    => $otp,
    //                 'status' => 1
    //             ); 
    //             $co_common_details=$this->api_model->fetch_record('company',$where_clause);
    //             if (count((array)$co_common_details)==1){
    //                 $company_id   = $co_common_details->id;
    //                 $this->atomdb = $this->api_model->connectCo($company_id);
    //                 $co_domain_details = $this->api_model->fetch_record('company',$where_clause,$this->atomdb);
    //                 $where_clause = array(
    //                     'company.otp'               => $this->input->post('otp'),
    //                     'company.status'            => 1
    //                 ); 
    //                 $results =$this->api_model->device_users($where_clause);

    //                 if (count((array)$results) > 0 && count((array)$co_domain_details) >0) {
    //                     foreach ($results as $row) {
    //                         $company_id       = $row->id;
    //                         $company_code     = $row->company_code;
    //                         $company_name     = $row->company_name;
    //                         $users_restrict   = $row->users_restrict;
    //                         $app_users_count  = $row->app_users_count;
    //                         $registered_users = $row->registered_users;
    //                         if ($users_restrict==1){
    //                             if ($app_users_count>0){
    //                                 if (($registered_users+1)<=$app_users_count){
    //                                     $json = array(
    //                                         'company_id'   => $company_id,
    //                                         'company_name' => $company_name,
    //                                         'success'      => true,
    //                                         'message'      => "Verification Completed"
    //                                     );
    //                                 }else{
    //                                     $json = array(
    //                                         'company_id'   => '',
    //                                         'company_name' => '',
    //                                         'success'      => false,
    //                                         'message'      => "Registration Quota Completed. Unable to complete registration"
    //                                     );
    //                                 }
    //                             }else{
    //                                 $json = array(
    //                                     'company_id'   => '',
    //                                     'company_name' => '',
    //                                     'success'      => false,
    //                                     'message'      => "Registration Quota Completed. Unable to complete registration"
    //                                 );
    //                             }
    //                         }else{
    //                             $json = array(
    //                                 'company_id'   => $company_id,
    //                                 'company_name' => $company_name,
    //                                 'success'      => true,
    //                                 'message'      => "Verification Completed"
    //                             );
    //                         }
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'company_id'   => '',
    //                         'company_name' => '',
    //                         'success'      => false,
    //                         'message'      => "Invalid Company Code"
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'company_id'   => '',
    //                     'company_name' => '',
    //                     'success'      => false,
    //                     'message'      => "Invalid Company Code"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'company_id'   => '',
    //                 'company_name' => '',
    //                 'success'      => false,
    //                 'message'      => $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'company_id'   => '',
    //             'company_name' => '',
    //             'success'      => false,
    //             'message'      => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function company_confirmation(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj     = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);

    //         $payload    = $this->input->post('payload');
    //         $confirm    = $this->input->post('confirm');
    //         $company_id = $this->input->post('company_id');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;

    //             if ($confirm=='Y'){
    //                 $this->atomdb   = $this->api_model->connectCo($company_id);

    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $whereclause  = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause);
    //                 if(count((array)$company_data)>0){
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }

    //                 //FETCH EXISTING COMMON DEVICE USERS DETAILS
    //                 $where_clause = array(
    //                     'id'     => $common_user_id,
    //                     'status' => 1,
    //                     'block'  => 0
    //                 );
    //                 $common_user_result = $this->api_model->fetch_record('device_users', $where_clause);
    //                 if (count((array)$common_user_result) > 0) {
    //                     $istester          = $common_user_result->istester;
    //                     $password          = $common_user_result->password;
    //                     $firstname         = $common_user_result->firstname;
    //                     $lastname          = $common_user_result->lastname;
    //                     $mobile            = $common_user_result->mobile;
    //                     $email             = $common_user_result->email;
    //                     $avatar            = $common_user_result->avatar;
    //                     $registration_date = $common_user_result->registration_date;
    //                     $last_access_date  = $common_user_result->last_access_date;
    //                     $status            = $common_user_result->status;
    //                     $block             = $common_user_result->block;
    //                     $fb_registration   = $common_user_result->fb_registration;
    //                     $token             = $common_user_result->token;
    //                     $modifiedby        = $common_user_result->modifiedby;
    //                     $modifieddate      = $common_user_result->modifieddate;
    //                     $otp               = $common_user_result->otp;
    //                     $otp_last_attempt  = $common_user_result->otp_last_attempt;
    //                     $otp_verified      = $common_user_result->otp_verified;


    //                     $avatar_path = '';                      
    //                     if ($avatar ==''){
    //                         $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                     }else{
    //                         $common_file_path_check = "../mwadmin/assets/uploads/avatar/".$avatar;
    //                         $domain_file_path_check = "../".$portal_name."/assets/uploads/avatar/".$avatar;
    //                         if (file_exists($common_file_path_check)){
    //                             if (!copy($common_file_path_check, $domain_file_path_check)) {
    //                                 $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                             }else{
    //                                 $avatar_path = $domin_url."/assets/uploads/avatar/".$avatar;
    //                                 if (!unlink($common_file_path_check)){
    //                                 }else{
    //                                 }
    //                             }
    //                         }else{
    //                             $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                         } 
    //                     }  


    //                     $where_clause = array(
    //                         'status'=> 1,
    //                         'email' => $email
    //                     ); 
    //                     $user_found =$this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //                     if (count((array)$user_found)<=0){
    //                         //USER CREATION IN DOMAIN DATABASE
    //                         $data = array(
    //                             'company_id'        => $company_id,
    //                             'firstname'         => $firstname,
    //                             'lastname'          => $lastname,
    //                             'mobile'            => $mobile,
    //                             'email'             => $email,
    //                             'password'          => $password,
    //                             'avatar'            => $avatar,
    //                             'registration_date' => $registration_date,
    //                             'last_access_date'  => $last_access_date,
    //                             'status'            => $status,
    //                             'block'             => $block,
    //                             'fb_registration'   => $fb_registration,
    //                             'otp'               => $otp,
    //                             'otp_last_attempt'  => $otp_last_attempt,
    //                             'otp_verified'      => $otp_verified
    //                         );
    //                         $user_id = $this->api_model->insert('device_users',$data,$this->atomdb);
    //                     }else{
    //                         $user_id = $user_found->user_id;
    //                     }

    //                     //TOKEN UPDATION IN DOMAIN DATABASE
    //                     $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));
    //                     $token_data = array(
    //                         'token'            => $token
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $user_id,
    //                     ); 
    //                     $update_status = $this->api_model->update('device_users', $where_clause, $token_data,$this->atomdb);

    //                     //TOKEN UPDATION IN COMMON DATABASE
    //                     $token_data = array(
    //                         'user_id'    => $user_id,
    //                         'company_id' => $company_id,
    //                         'token'      => $token
    //                     );
    //                     $where_clause = array(
    //                         'id' => $common_user_id,
    //                     ); 
    //                     $update_status = $this->api_model->update('device_users', $where_clause, $token_data);

    //                     //CHECK IF DEVICE DETAILS HAS CHANGES THEN INSERT NEW ELSE UPDATE
    //                     //INSERT DEVICE INFORMATION
    //                     $device_information_post  = $this->input->post('device_info',true);
    //                     if (count((array)$device_information_post)>0){
    //                         $device_information = json_decode($device_information_post);
    //                         $jsonDevice = array(
    //                             'user_id'            => $user_id,
    //                             'app_name'           => (!isset($device_information->app_name) OR is_null($device_information->app_name))?'':$device_information->app_name,
    //                             'package_name'       => (!isset($device_information->package_name) OR is_null($device_information->package_name))?'':$device_information->package_name,
    //                             'version_code'       => (!isset($device_information->version_code) OR is_null($device_information->version_code))?'':$device_information->version_code,
    //                             'version_number'     => (!isset($device_information->version_number) OR is_null($device_information->version_number))?'':$device_information->version_number,
    //                             'cordova'            => (!isset($device_information->cordova) OR is_null($device_information->cordova))?'':$device_information->cordova,
    //                             'model'              => (!isset($device_information->model) OR is_null($device_information->model))?'':$device_information->model,
    //                             'platform'           => (!isset($device_information->platform) OR is_null($device_information->platform))?'':$device_information->platform,
    //                             'uuid'               => (!isset($device_information->uuid) OR is_null($device_information->uuid))?'':$device_information->uuid,
    //                             'imei'               => (!isset($device_information->imei) OR is_null($device_information->imei))?'':$device_information->imei,
    //                             'imsi'               => (!isset($device_information->imsi) OR is_null($device_information->imsi))?'':$device_information->imsi,
    //                             'iccid'              => (!isset($device_information->iccid) OR is_null($device_information->iccid))?'':$device_information->iccid,
    //                             'mac'                => (!isset($device_information->mac) OR is_null($device_information->mac))?'':$device_information->mac,
    //                             'version'            => (!isset($device_information->version) OR is_null($device_information->version))?'':$device_information->version,
    //                             'manufacturer'       => (!isset($device_information->manufacturer) OR is_null($device_information->manufacturer))?'':$device_information->manufacturer,
    //                             'is_virtual'         => (!isset($device_information->is_virtual) OR is_null($device_information->is_virtual))?'':$device_information->is_virtual,
    //                             'serial'             => (!isset($device_information->serial) OR is_null($device_information->serial))?'':$device_information->serial,
    //                             'memory'             => (!isset($device_information->memory) OR is_null($device_information->memory))?'':$device_information->memory,
    //                             'cpumhz'             => (!isset($device_information->cpumhz) OR is_null($device_information->cpumhz))?'':$device_information->cpumhz,
    //                             'totalstorage'       => (!isset($device_information->totalstorage) OR is_null($device_information->totalstorage))?'':$device_information->totalstorage,
    //                             'registered_account' => '',
    //                             'user_agent'         => '',
    //                             'status'             => 1,
    //                             'ip_address'         => (!isset($device_information->ip_address) OR is_null($device_information->ip_address))?'':$device_information->ip_address,
    //                             'latitude'           => (!isset($device_information->latitude) OR is_null($device_information->latitude))?'':$device_information->latitude,
    //                             'longitude'          => (!isset($device_information->longitude) OR is_null($device_information->longitude))?'':$device_information->longitude,
    //                             'browser_agent'      => (!isset($device_information->browser_agent) OR is_null($device_information->browser_agent))?'':$device_information->browser_agent,
    //                             'time_open'          => (!isset($device_information->time_open) OR is_null($device_information->time_open))?'':$device_information->time_open,
    //                             'organisation'       => (!isset($device_information->organisation) OR is_null($device_information->organisation))?'':$device_information->organisation,
    //                             'info_dttm'          => date('Y-m-d H:i:s')
    //                         );
    //                         $where_clause = array(
    //                             'user_id' => $common_user_id,
    //                             'serial'  => (!isset($device_information->serial) OR is_null($device_information->serial))?'':$device_information->serial,
    //                             'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                         ); 
    //                         $device_found =$this->api_model->record_count('device_info',$where_clause);
    //                         if ($device_found>0){
    //                             $this->api_model->update('device_info',$where_clause,$jsonDevice);
    //                         }else{
    //                             $this->api_model->insert('device_info',$jsonDevice);
    //                         }
    //                         $where_clause = array(
    //                             'user_id' => $user_id,
    //                             'serial'  => (!isset($device_information->serial) OR is_null($device_information->serial))?'':$device_information->serial,
    //                             'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                         );
    //                         $device_found =$this->api_model->record_count('device_info',$where_clause,$this->atomdb);
    //                         if ($device_found>0){
    //                             $this->api_model->update('device_info',$where_clause,$jsonDevice,$this->atomdb);
    //                         }else{
    //                             $this->api_model->insert('device_info',$jsonDevice,$this->atomdb);
    //                         }
    //                     }

    //                     $co_where_clause = array(
    //                         'id'     => $company_id,
    //                         'status' => 1
    //                     );
    //                     $eotp_required = 0;
    //                     $eotp_result = $this->api_model->fetch_record('company', $co_where_clause);
    //                     if (count((array)$eotp_result) > 0) {
    //                         $eotp_required = $eotp_result->eotp_required;
    //                     }
    //                     if ($eotp_required==0){
    //                         $data = array('company_id' => $company_id,'otp_verified'=>1);
    //                     }
    //                     if ($eotp_required==1){
    //                         $data = array('company_id' => $company_id,'otp_verified'=>0);
    //                     }
    //                     $where_clause = array(
    //                         'id' => $common_user_id
    //                     );
    //                     $update_status   = $this->api_model->update('device_users', $where_clause,$data);

    //                     $where_clause = array(
    //                         'user_id' => $user_id
    //                     );
    //                     $update_status_i = $this->api_model->update('device_users', $where_clause,$data,$this->atomdb);
    //                     if ($update_status AND $update_status_i){

    //                         //CHECK COMPANY HAS ENABLED EMPLOYEE VERIFICATION BY SENDING OPT VIA EMAIL.
    //                         $where_clause = array(
    //                             'id'     => $company_id,
    //                             'status' => 1
    //                         );
    //                         $eotp_required = 0;
    //                         $eotp_result = $this->api_model->fetch_record('company', $where_clause);
    //                         if (count((array)$eotp_result) > 0) {
    //                             $eotp_required = $eotp_result->eotp_required;
    //                         }
    //                         $json = array(
    //                             'success'       => true,
    //                             'avatar'        => $avatar_path,
    //                             'message'       => "Your registration with selected company is updated successfully.",
    //                             'eotp_required' => $eotp_required
    //                         );
    //                     }else{
    //                         $json = array(
    //                             'success'       => false,
    //                             'avatar'        => '',
    //                             'message'       => "Internal server error, Please try again"
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success'      => false,
    //                         'avatar'        => '',
    //                         'message'      => "Internal server error, Please try again"
    //                     );
    //                 }
    //             }else{
    //                 $data = array('company_id' => '','otp_verified'=>0);
    //                 $where_clause = array(
    //                     'id' => $common_user_id
    //                 );
    //                 $update_status   = $this->api_model->update('device_users', $where_clause,$data);

    //                 if ($update_status){
    //                     $json = array(
    //                         'success'      => false,
    //                         'avatar'        => '',
    //                         'message'      => "You have rejected the selected company, Please try using another company code."
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success'      => false,
    //                         'avatar'        => '',
    //                         'message'      => "Internal server error, Please try again"
    //                     );
    //                 }
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success'      => false,
    //                 'avatar'        => '',
    //                 'message'      => $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'      => false,
    //             'avatar'        => '',
    //             'message'      => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function reset_password(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj     = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE); 
    //         $payload    = $this->input->post('payload');
    //         $currentpwd = $this->input->post('currentpwd');
    //         $newpwd     = $this->input->post('newpwd');
    //         $confirmpwd = $this->input->post('confirmpwd');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         if ($currentpwd == ''){
    //             $json = array(
    //                 'success'      => false,
    //                 'message'      => "Current password is required."
    //             );
    //         }else if ($newpwd == ''){
    //             $json = array(
    //                 'success'      => false,
    //                 'message'      => "New password is required."
    //             );
    //         }else if ($confirmpwd == ''){
    //             $json = array(
    //                 'success'      => false,
    //                 'message'      => "Confirm password is required."
    //             );
    //         }else if ($newpwd !== $confirmpwd){
    //             $json = array(
    //                 'success'      => false,
    //                 'message'      => "Your new password and confirmation password do not match."
    //             );
    //         }else{
    //             //PAYLOAD VERIFY
    //             try{
    //                 $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //                 $common_user_id = $JWTVerify->user_id;
    //                 $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //                 $user_id    = 0;
    //                 $company_id = 0;
    //                 $where_clause = array(
    //                     'id'           => $common_user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 
    //                 $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //                 if (count((array)$common_user_details)>0){
    //                     $user_id = $common_user_details->user_id;
    //                 }

    //                 $where_clause = array(
    //                     'user_id' => $user_id,
    //                     'status'  => 1,
    //                     'block'   => 0
    //                 );
    //                 $device_user_details          = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //                 if (count((array)$device_user_details) > 0) {
    //                     $current_db_password_hash = $device_user_details->password;
    //                     if ($this->api_model->decrypt_password($currentpwd, $current_db_password_hash) == 1) {
    //                         $new_password_hash = $this->api_model->encrypt_password($newpwd);
    //                         $data = array('password' => $new_password_hash);

    //                         $where_clause = array(
    //                             'id'           => $common_user_id,
    //                             'status'       => 1,
    //                             'block'        => 0,
    //                             'otp_verified' => 1
    //                         ); 
    //                         $update_status = $this->api_model->update('device_users', $where_clause, $data);

    //                         $where_clause = array(
    //                             'user_id'      => $user_id,
    //                             'status'       => 1,
    //                             'block'        => 0,
    //                             'otp_verified' => 1
    //                         ); 
    //                         $update_status_ii   = $this->api_model->update('device_users', $where_clause, $data,$this->atomdb);
    //                         if ($update_status AND $update_status_ii){
    //                             $json = array(
    //                                 'success'      => true,
    //                                 'message'      => "Your password updated successfully."
    //                             );
    //                         }else{
    //                             $json = array(
    //                                 'success'      => false,
    //                                 'message'      => "Internal server error, Please try again"
    //                             );
    //                         }
    //                     }else{
    //                         $json = array(
    //                             'success'      => false,
    //                             'message'      => "Your current password does not match with our application."
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success'      => false,
    //                         'message'      => "Unable to reset password. Please try again."
    //                     );
    //                 }
    //             }catch(Exception $e){
    //                 $json = array(
    //                     'success'      => false,
    //                     'message'      => $e->getMessage()
    //                 );
    //             }
    //         }
    //     }else{
    //         $json = array(
    //             'success'      => false,
    //             'message'      => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);       
    // }
    // public function workshop_live(){
    //     $data     = array();
    //     $advtData = array();
    //     $json     = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST       = json_decode($_jsonObj, TRUE);
    //         $payload     = $this->input->post('payload');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $company_name ='';
    //                 $company_logo ='';
    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $whereclause = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $company_name = $company_data->company_name;
    //                     $company_logo = $company_data->company_logo;
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }
    //                 $company_data_mw = $this->api_model->fetch_record('company',$whereclause);
    //                 $is_other_hosting=0;
    //                 if(count((array)$company_data_mw)>0){
    //                     $is_other_hosting=$company_data_mw->is_other_hosting;
    //                 }
    //                 $company_path = '';                      
    //                 if ($company_logo ==''){
    //                     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                 }else{
    //                     $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    //                     $file_path = "/assets/uploads/company/".$company_logo;
    //                     if($is_other_hosting || file_exists($file_path_check)){
    //                         $company_path = $domin_url.$file_path;
    //                     }else{
    //                         $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     }
    //                 }

    //                 //REOPEN WORKSHOP FOR COMPLETED USER. IF ANY TOTAL QUESTIONS AND PLAYED QUESTIONS MISMATCH.
    //                 $refresh_history_tab = 'N';
    //                 $temp_workshop_ongoing_details   = $this->api_model->fetch_workshop_ongoing($company_id,$user_id,$this->atomdb);
    //                 $temp_total     = count((array)$temp_workshop_ongoing_details);
    //                 if ($temp_total > 0) {
    //                     foreach ($temp_workshop_ongoing_details as $temp_wrkshp_ongoing_row) {
    //                         $workshop_id         = $temp_wrkshp_ongoing_row->id;
    //                         $workshop_session    = $temp_wrkshp_ongoing_row->workshop_session;
    //                         $all_questions_fired = $temp_wrkshp_ongoing_row->all_questions_fired;
    //                         $all_feedbacks_fired = $temp_wrkshp_ongoing_row->all_feedbacks_fired;

    //                         if ($all_questions_fired>0 OR $all_feedbacks_fired>0){
    //                             $total_wksh_ques_lefttoplay = 0;
    //                             $total_feed_ques_lefttoplay = 0;
    //                             $total_workshop_questions   = 0;
    //                             $total_feedback_questions   = 0;
    //                             $total_questions_played     = 0;
    //                             $total_feedback_played      = 0;
    //                             $total_questions            = 0;
    //                             $total_played               = 0;
    //                             $totalLefttoPlay            = 0;
    //                             if ($workshop_session=='PRE'){
    //                                 $total_questions_result          = $this->api_model->fetch_pre_questions_count($company_id,$workshop_id,$user_id,$this->atomdb); 
    //                                 if (count((array)$total_questions_result)>0){
    //                                     $total_workshop_questions        = $total_questions_result->total_questions;
    //                                 }
    //                                 $total_feedback_questions_result = $this->api_model->fetch_pre_feedback_questions_count($company_id,$workshop_id,$user_id,$this->atomdb);
    //                                 if (count((array)$total_feedback_questions_result)>0){
    //                                     $total_feedback_questions        = $total_feedback_questions_result->total_questions;
    //                                 }
    //                             }
    //                             if ($workshop_session=='POST'){
    //                                 $total_questions_result          = $this->api_model->fetch_post_questions_count($company_id,$workshop_id,$user_id,$this->atomdb); 
    //                                 if (count((array)$total_questions_result)>0){
    //                                     $total_workshop_questions        = $total_questions_result->total_questions;
    //                                 }
    //                                 $total_feedback_questions_result = $this->api_model->fetch_post_feedback_questions_count($company_id,$workshop_id,$user_id,$this->atomdb);
    //                                 if (count((array)$total_feedback_questions_result)>0){
    //                                     $total_feedback_questions        = $total_feedback_questions_result->total_questions;
    //                                 }
    //                             }
    //                             $total_questions        = ($total_workshop_questions + $total_feedback_questions);

    //                             $total_atom_result      = $this->api_model->fetch_atom_results_count($company_id,$workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                             if (count((array)$total_atom_result)>0){
    //                                 $total_questions_played = $total_atom_result->total;
    //                             }
    //                             $total_atom_feedback    = $this->api_model->fetch_atom_feedback_count($company_id,$workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                             if (count((array)$total_atom_feedback)>0){
    //                                 $total_feedback_played  = $total_atom_feedback->total;
    //                             }
    //                             $total_played    = ($total_questions_played + $total_feedback_played);
    //                             $totalLefttoPlay = ($total_questions - $total_played);

    //                             $total_wksh_ques_lefttoplay = ($total_workshop_questions - $total_questions_played);
    //                             $total_feed_ques_lefttoplay = ($total_feedback_questions - $total_feedback_played);

    //                             if ($totalLefttoPlay>0){
    //                                 if (($total_wksh_ques_lefttoplay>0 AND $all_questions_fired==1) OR ($total_feed_ques_lefttoplay>0 AND $all_feedbacks_fired==1)){
    //                                     $refresh_history_tab = 'Y';
    //                                     $where_clause = array(
    //                                         'user_id'          => $user_id,
    //                                         'workshop_id'      => $workshop_id,
    //                                         'workshop_session' => $workshop_session,
    //                                     );
    //                                     $wrkover_updt_data = array(
    //                                         'all_questions_fired' => (($total_wksh_ques_lefttoplay>0)?0:1),
    //                                         'all_feedbacks_fired' => (($total_feed_ques_lefttoplay>0)?0:1),
    //                                     );
    //                                     $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);
    //                                 }                                    
    //                             }
    //                         }
    //                     }
    //                 }


    //                 //ONGOING
    //                 $workshop_ongoing_details   = $this->api_model->fetch_workshop_ongoing($company_id,$user_id,$this->atomdb);
    //                 $total     = count((array)$workshop_ongoing_details);
    //                 if ($total > 0) {
    //                     foreach ($workshop_ongoing_details as $wrkshp_ongoing_row) {
    //                         $end_time_display    = $wrkshp_ongoing_row->end_time_display;

    //                         $workshop_allowed = 1;
    //                         $restricted_users = $wrkshp_ongoing_row->restricted_users;
    //                         if ($restricted_users>0){
    //                             $where_clause = array(
    //                                 'workshop_id' => $wrkshp_ongoing_row->id,
    //                                 'user_id'     => $user_id
    //                             );
    //                             $wu_row          = $this->api_model->fetch_record('workshop_users',$where_clause,$this->atomdb);
    //                             if (count((array)$wu_row)==0) {
    //                                 $workshop_allowed = 0;
    //                             }
    //                         }
    //                         if ($workshop_allowed==1){
    //                             $workshop_image = "assets/images/workshop-blank.jpg";
    //                             if ($wrkshp_ongoing_row->workshop_image!=''){
    //                                 $file_name = $wrkshp_ongoing_row->workshop_image;
    //                                 $file_path_check = "../".$portal_name."/assets/uploads/workshop/".$file_name;
    //                                 $file_path = "/assets/uploads/workshop/".$file_name;
    //                                 if ($file_name!=''){
    //                                     // if (file_exists($file_path_check)){
    //                                         $workshop_image = $domin_url.$file_path;     
    //                                     // }else{
    //                                     //     $workshop_image = "assets/images/workshop-blank.jpg";     
    //                                     // }   
    //                                 }else{
    //                                     $workshop_image = "assets/images/workshop-blank.jpg";
    //                                 }                            
    //                             }
    //                             $otp_required = 0;
    //                             if ($wrkshp_ongoing_row->otp!=''){
    //                                 $otp_required = 1;
    //                             }
    //                             $correct          = 0;
    //                             $wrong            = 0;
    //                             $time_out         = 0;
    //                             $preference_count = 0;
    //                             $information_form_filled = 'Y';
    //                             if ($wrkshp_ongoing_row->feedbackform_id>0){
    //                                 $where_clause = array(
    //                                     'company_id'     => $company_id,
    //                                     'user_id'        => $user_id,
    //                                     'workshop_id'    => $wrkshp_ongoing_row->id,
    //                                     'form_header_id' => $wrkshp_ongoing_row->feedbackform_id
    //                                 ); 
    //                                 $info_found = $this->api_model->record_count('feedback_form_data',$where_clause,$this->atomdb);
    //                                 if ($info_found<=0){
    //                                     $information_form_filled = 'N';
    //                                 }
    //                             }
    //                             $workshop_score_result = $this->api_model->fetch_completed_score(
    //                                                                 $wrkshp_ongoing_row->company_id,
    //                                                                 $user_id,
    //                                                                 $wrkshp_ongoing_row->id,
    //                                                                 $wrkshp_ongoing_row->workshop_session,
    //                                                                 $this->atomdb);
    //                             if (count((array)$workshop_score_result)>0){
    //                                 foreach ($workshop_score_result as $workshop_score) {
    //                                     $correct  = $workshop_score->correct;
    //                                     $wrong    = $workshop_score->wrong;
    //                                     $time_out = $workshop_score->time_out;
    //                                 }
    //                             }
    //                             $feedback_score_result = $this->api_model->fetch_atom_feedback_count_full(
    //                                                             $wrkshp_ongoing_row->company_id,
    //                                                             $wrkshp_ongoing_row->id,
    //                                                             $user_id,
    //                                                             $wrkshp_ongoing_row->workshop_session,
    //                                                             $this->atomdb);
    //                             if (count((array)$feedback_score_result)>0){
    //                                 $preference_count  = $feedback_score_result->total;
    //                             }
    //                             array_push($data, array(
    //                                 'workshop_tab'            => 'ongoing',
    //                                 'workshop_session'        => $wrkshp_ongoing_row->workshop_session,
    //                                 'id'                      => $wrkshp_ongoing_row->id,
    //                                 'workshop_date'           => $wrkshp_ongoing_row->workshop_date,
    //                                 'workshop_name'           => $wrkshp_ongoing_row->workshop_name,
    //                                 'company_id'              => $wrkshp_ongoing_row->company_id,
    //                                 'timer'                   => $wrkshp_ongoing_row->timer,
    //                                 'powered_by'              => $wrkshp_ongoing_row->powered_by,
    //                                 'short_description'       => $wrkshp_ongoing_row->short_description,
    //                                 'long_description'        => $wrkshp_ongoing_row->long_description,
    //                                 'workshop_url'            => $wrkshp_ongoing_row->workshop_url,
    //                                 'workshop_image'          => $workshop_image,
    //                                 'start_date'              => $wrkshp_ongoing_row->start_date,
    //                                 'end_date'                => $wrkshp_ongoing_row->end_date,
    //                                 'pre_start_date'          => $wrkshp_ongoing_row->pre_start_date,
    //                                 'pre_start_date_dmy'      => date("d-m-Y", strtotime($wrkshp_ongoing_row->pre_start_date)),
    //                                 'pre_start_time'          => $wrkshp_ongoing_row->pre_start_time,
    //                                 'pre_end_date'            => $wrkshp_ongoing_row->pre_end_date,
    //                                 'pre_end_date_dmy'        => date("d-m-Y", strtotime($wrkshp_ongoing_row->pre_end_date)),
    //                                 'pre_end_time'            => $wrkshp_ongoing_row->pre_end_time,
    //                                 'pre_time_status'         => $wrkshp_ongoing_row->pre_time_status,
    //                                 'post_start_date'         => $wrkshp_ongoing_row->post_start_date,
    //                                 'post_start_date_dmy'     => date("d-m-Y", strtotime($wrkshp_ongoing_row->post_start_date)),
    //                                 'post_start_time'         => $wrkshp_ongoing_row->post_start_time,
    //                                 'post_end_date'           => $wrkshp_ongoing_row->post_end_date,
    //                                 'post_end_date_dmy'       => date("d-m-Y", strtotime($wrkshp_ongoing_row->post_end_date)),
    //                                 'post_end_time'           => $wrkshp_ongoing_row->post_end_time,
    //                                 'post_time_status'        => $wrkshp_ongoing_row->post_time_status,
    //                                 'point_multiplier'        => $wrkshp_ongoing_row->point_multiplier,
    //                                 'otp_required'            => $otp_required,
    //                                 'is_registered'           => $wrkshp_ongoing_row->is_registered,
    //                                 'wc_heading'              => $wrkshp_ongoing_row->heading,
    //                                 'wc_message'              => $wrkshp_ongoing_row->message,
    //                                 'information_form_id'     => $wrkshp_ongoing_row->feedbackform_id,
    //                                 'information_form_filled' => $information_form_filled,
    //                                 'score_correct'           => $correct,
    //                                 'score_wrong'             => ($wrong+$time_out),
    //                                 'score_time_out'          => $time_out,
    //                                 'score_preference'        => $preference_count,
    //                                 'all_questions_fired'     => $wrkshp_ongoing_row->all_questions_fired,
    //                                 'all_feedbacks_fired'     => $wrkshp_ongoing_row->all_feedbacks_fired,
    //                                 'session_preclose'        => $wrkshp_ongoing_row->session_preclose,
    //                                 'end_time_display'        => ($end_time_display==1 OR $end_time_display=='1')?1:0,
    //                                 'questionset_type'        => $wrkshp_ongoing_row->questionset_type,
    //                             )); 
    //                         }
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'         => $data,
    //                     'company_name' => $company_name,
    //                     'co_logo'      => $company_path,
    //                     'success'      => true,
    //                     'message'      => 'Workshop data loaded successfully.',
    //                     'refresh_tabs' => $refresh_history_tab
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function workshops_completed(){
    //     $data     = array();
    //     $json     = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST       = json_decode($_jsonObj, TRUE);
    //         $payload     = $this->input->post('payload');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id      = $JWTVerify->user_id;
    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $whereclause = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }

    //                 //COMPLETED
    //                 $workshop_completed_details   = $this->api_model->fetch_workshop_completed($company_id,$user_id,$this->atomdb);
    //                 $total     = count((array)$workshop_completed_details);
    //                 if ($total > 0) {
    //                     foreach ($workshop_completed_details as $wrkshp_completed_row) {
    //                         $workshop_allowed = 1;
    //                         $restricted_users = $wrkshp_completed_row->restricted_users;

    //                         if ($restricted_users>0){
    //                             $where_clause = array(
    //                                 'workshop_id' => $wrkshp_completed_row->id,
    //                                 'user_id'     => $user_id
    //                             );
    //                             $wu_row          = $this->api_model->fetch_record('workshop_users',$where_clause,$this->atomdb);
    //                             if (count((array)$wu_row)==0) {
    //                                 $workshop_allowed = 0;
    //                             }
    //                         }
    //                         if ($workshop_allowed==1){
    //                             $workshop_image = "assets/images/workshop-blank.jpg";
    //                             if ($wrkshp_completed_row->workshop_image!=''){
    //                                 $file_name = $wrkshp_completed_row->workshop_image;
    //                                 $file_path_check = "../".$portal_name."/assets/uploads/workshop/".$file_name;
    //                                 $file_path = "/assets/uploads/workshop/".$file_name;
    //                                 if ($file_name!=''){
    //                                     if (file_exists($file_path_check)){
    //                                         $workshop_image = $domin_url.$file_path;      
    //                                     }else{
    //                                         $workshop_image = "assets/images/workshop-blank.jpg";     
    //                                     }                               
    //                                 }else{
    //                                     $workshop_image = "assets/images/workshop-blank.jpg";
    //                                 }
    //                             }
    //                             $otp_required = 0;
    //                             if ($wrkshp_completed_row->otp!=''){
    //                                 $otp_required = 1;
    //                             }
    //                             $correct          = 0;
    //                             $wrong            = 0;
    //                             $time_out         = 0;
    //                             $preference_count = 0;
    //                             $workshop_score_result = $this->api_model->fetch_completed_score(
    //                                                                 $wrkshp_completed_row->company_id,
    //                                                                 $user_id,
    //                                                                 $wrkshp_completed_row->id,
    //                                                                 $wrkshp_completed_row->workshop_session,
    //                                                                 $this->atomdb);
    //                             if (count((array)$workshop_score_result)>0){
    //                                 foreach ($workshop_score_result as $workshop_score) {
    //                                     $correct  = $workshop_score->correct;
    //                                     $wrong    = $workshop_score->wrong;
    //                                     $time_out = $workshop_score->time_out;
    //                                 }
    //                             }
    //                             $feedback_score_result = $this->api_model->fetch_atom_feedback_count(
    //                                                             $wrkshp_completed_row->company_id,
    //                                                             $wrkshp_completed_row->id,
    //                                                             $user_id,
    //                                                             $wrkshp_completed_row->workshop_session,
    //                                                             $this->atomdb);
    //                             if (count((array)$feedback_score_result)>0){
    //                                 $preference_count  = $feedback_score_result->total;
    //                             }    
    //                             array_push($data, array(
    //                                 'workshop_tab'        => 'completed',
    //                                 'workshop_session'    => $wrkshp_completed_row->workshop_session,
    //                                 'id'                  => $wrkshp_completed_row->id,
    //                                 'workshop_date'       => $wrkshp_completed_row->workshop_date,
    //                                 'workshop_name'       => $wrkshp_completed_row->workshop_name,
    //                                 'company_id'          => $wrkshp_completed_row->company_id,
    //                                 'timer'               => $wrkshp_completed_row->timer,
    //                                 'powered_by'          => $wrkshp_completed_row->powered_by,
    //                                 'short_description'   => $wrkshp_completed_row->short_description,
    //                                 'long_description'    => $wrkshp_completed_row->long_description,
    //                                 'workshop_url'        => $wrkshp_completed_row->workshop_url,
    //                                 'workshop_image'      => $workshop_image,
    //                                 'start_date'          => $wrkshp_completed_row->start_date,
    //                                 'end_date'            => $wrkshp_completed_row->end_date,
    //                                 'pre_start_date'      => $wrkshp_completed_row->pre_start_date,
    //                                 'pre_start_date_dmy'  => date("d-m-Y", strtotime($wrkshp_completed_row->pre_start_date)),
    //                                 'pre_start_time'      => $wrkshp_completed_row->pre_start_time,
    //                                 'pre_end_date'        => $wrkshp_completed_row->pre_end_date,
    //                                 'pre_end_date_dmy'    => date("d-m-Y", strtotime($wrkshp_completed_row->pre_end_date)),
    //                                 'pre_end_time'        => $wrkshp_completed_row->pre_end_time,
    //                                 'pre_time_status'     => $wrkshp_completed_row->pre_time_status,
    //                                 'post_start_date'     => $wrkshp_completed_row->post_start_date,
    //                                 'post_start_date_dmy' => date("d-m-Y", strtotime($wrkshp_completed_row->post_start_date)),
    //                                 'post_start_time'     => $wrkshp_completed_row->post_start_time,
    //                                 'post_end_date'       => $wrkshp_completed_row->post_end_date,
    //                                 'post_end_date_dmy'   => date("d-m-Y", strtotime($wrkshp_completed_row->post_end_date)),
    //                                 'post_end_time'       => $wrkshp_completed_row->post_end_time,
    //                                 'post_time_status'    => $wrkshp_completed_row->post_time_status,
    //                                 'point_multiplier'    => $wrkshp_completed_row->point_multiplier,
    //                                 'otp_required'        => $otp_required,
    //                                 'is_registered'       => $wrkshp_completed_row->is_registered,
    //                                 'wc_heading'          => $wrkshp_completed_row->heading,
    //                                 'wc_message'          => $wrkshp_completed_row->message,
    //                                 'score_correct'       => $correct,
    //                                 'score_wrong'         => ($wrong+$time_out),
    //                                 'score_time_out'      => $time_out,
    //                                 'score_preference'    => $preference_count,
    //                                 'all_questions_fired' => $wrkshp_completed_row->all_questions_fired,
    //                                 'all_feedbacks_fired' => $wrkshp_completed_row->all_feedbacks_fired,
    //                                 'session_preclose'    => $wrkshp_completed_row->session_preclose,
    //                             ));
    //                         }
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'    => $data,
    //                     'success' => true,
    //                     'message' => 'Workshop data loaded successfully.'
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);        
    // }
    // public function workshops_reports(){
    //     $data     = array();
    //     $json     = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST       = json_decode($_jsonObj, TRUE);
    //         $payload     = $this->input->post('payload');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id = $user_details->company_id;
    //                 $is_tester  = 'N';
    //                 if ($user_details->istester=='1'){
    //                     $is_tester = 'Y';
    //                 }

    //                 //LIST OF COMPLETED WORKSHOP HAVING BOTH SESSION EXISTS 
    //                 $workshops  = $this->api_model->fetch_cmplt_wksh_prepost_session($company_id,$user_id,$this->atomdb);

    //                 $wksh_count = count((array)$workshops);
    //                 if ($wksh_count > 0) {
    //                     foreach ($workshops as $workshops_row) {
    //                         $workshop_id   = $workshops_row->id;
    //                         $workshop_name = $workshops_row->workshop_name;

    //                         // CHECK USER PLAYED IN BOTH SESSION THEN ONLY SHOW THAT WORKSHOP
    //                         $wksh_session_count = $this->api_model->fetch_play_result_exists($company_id,$user_id,$workshop_id,$this->atomdb);


    //                         // IF RECORD COUNT IS 2 MEANS WE FOUND BOTH PRE AND POST SESSION.
    //                         if (count((array)$wksh_session_count)==2){

    //                             $pre_accuracy    = 0;
    //                             $post_accuracy   = 0;
    //                             $pre_topic_name  = '';
    //                             $post_topic_name = '';
    //                             $rank            = 0;

    //                             //FIND THE ACCURACY OF PRE & POST OF OVERALL WORKSHOP
    //                             $user_pre_post_accuracy = $this->api_model->fetch_wksh_user_pre_post_accuracy($company_id,$user_id,$workshop_id,$is_tester,$this->atomdb);
    //                             $user_pre_topic         = $this->api_model->fetch_wksh_user_best_pre_topic($company_id,$user_id,$workshop_id,$this->atomdb);
    //                             $user_post_topic        = $this->api_model->fetch_wksh_user_best_post_topic($company_id,$user_id,$workshop_id,$this->atomdb);

    //                             if (count((array)$user_pre_post_accuracy)>0){
    //                                 foreach ($user_pre_post_accuracy as $prpoaccu) {
    //                                     $pre_accuracy  = $prpoaccu->pre_average;
    //                                     $post_accuracy = $prpoaccu->post_average;
    //                                     $rank          = $prpoaccu->rank;
    //                                 }
    //                             }
    //                             if (count((array)$user_pre_topic)>0){
    //                                 foreach ($user_pre_topic as $userpretopic) {
    //                                     $pre_topic_name = $userpretopic->topic_name;
    //                                 }
    //                             }
    //                             if (count((array)$user_post_topic)>0){
    //                                 foreach ($user_post_topic as $userposttopic) {
    //                                     $post_topic_name = $userposttopic->topic_name;
    //                                 }
    //                             }

    //                             array_push($data, array(
    //                                 'company_id'    => $company_id,
    //                                 'user_id'       => $user_id,
    //                                 'workshop_id'   => $workshop_id,
    //                                 'workshop_name' => $workshop_name,
    //                                 'pre_topic'     => $pre_topic_name,
    //                                 'pre_accuracy'  => $pre_accuracy,
    //                                 'post_topic'    => $post_topic_name,
    //                                 'post_accuracy' => $post_accuracy,
    //                                 'rank'          => $rank
    //                             ));
    //                         }
    //                     }
    //                     $json = array(
    //                         'data'    => $data,
    //                         'success' => true,
    //                         'message' => 'Workshop data loaded successfully.'
    //                     );
    //                 }else{
    //                     //NO WORKSHOP FOUND  
    //                     $json = array(
    //                         'data'    => $data,
    //                         'success' => true,
    //                         'message' => 'No workshop available.'
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json); 
    // }
    // public function workshops_ongoing_completed(){
    //     $data     = array();
    //     $json     = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST       = json_decode($_jsonObj, TRUE);
    //         $payload     = $this->input->post('payload');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $whereclause = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }

    //                 //ONGOING
    //                 $workshop_ongoing_details   = $this->api_model->fetch_workshop_ongoing($company_id,$user_id,$this->atomdb);
    //                 $total     = count((array)$workshop_ongoing_details);
    //                 if ($total > 0) {
    //                     foreach ($workshop_ongoing_details as $wrkshp_ongoing_row) {
    //                         $workshop_allowed = 1;
    //                         $restricted_users = $wrkshp_ongoing_row->restricted_users;

    //                         if ($restricted_users>0){
    //                             $where_clause = array(
    //                                 'workshop_id' => $wrkshp_ongoing_row->id,
    //                                 'user_id'     => $user_id
    //                             );
    //                             $wu_row          = $this->api_model->fetch_record('workshop_users',$where_clause,$this->atomdb);
    //                             if (count((array)$wu_row)==0) {
    //                                 $workshop_allowed = 0;
    //                             }
    //                         }
    //                         if ($workshop_allowed==1){
    //                             $workshop_image = "assets/images/workshop-blank.jpg";
    //                             if ($wrkshp_ongoing_row->workshop_image!=''){
    //                                 $file_name = $wrkshp_ongoing_row->workshop_image;
    //                                 $file_path_check = "../".$portal_name."/assets/uploads/workshop/".$file_name;
    //                                 $file_path = "/assets/uploads/workshop/".$file_name;
    //                                 if ($file_name!=''){
    //                                     if (file_exists($file_path_check)){
    //                                         $workshop_image = $domin_url.$file_path;     
    //                                     }else{
    //                                         $workshop_image = "assets/images/workshop-blank.jpg";     
    //                                     }                               
    //                                 }else{
    //                                     $workshop_image = "assets/images/workshop-blank.jpg";
    //                                 }                                 }
    //                             $otp_required = 0;
    //                             if ($wrkshp_ongoing_row->otp!=''){
    //                                 $otp_required = 1;
    //                             }
    //                             $correct  = 0;
    //                             $wrong    = 0;
    //                             $time_out = 0;
    //                             $workshop_score_result = $this->api_model->fetch_completed_score(
    //                                                                 $wrkshp_ongoing_row->company_id,
    //                                                                 $user_id,
    //                                                                 $wrkshp_ongoing_row->id,
    //                                                                 $wrkshp_ongoing_row->workshop_session,
    //                                                                 $this->atomdb);
    //                             if (count((array)$workshop_score_result)>0){
    //                                 foreach ($workshop_score_result as $workshop_score) {
    //                                     $correct  = $workshop_score->correct;
    //                                     $wrong    = $workshop_score->wrong;
    //                                     $time_out = $workshop_score->time_out;
    //                                 }
    //                             }
    //                             array_push($data, array(
    //                                 'workshop_tab'        => 'ongoing',
    //                                 'workshop_session'    => $wrkshp_ongoing_row->workshop_session,
    //                                 'id'                  => $wrkshp_ongoing_row->id,
    //                                 'workshop_date'       => $wrkshp_ongoing_row->workshop_date,
    //                                 'workshop_name'       => $wrkshp_ongoing_row->workshop_name,
    //                                 'company_id'          => $wrkshp_ongoing_row->company_id,
    //                                 'timer'               => $wrkshp_ongoing_row->timer,
    //                                 'powered_by'          => $wrkshp_ongoing_row->powered_by,
    //                                 'short_description'   => $wrkshp_ongoing_row->short_description,
    //                                 'long_description'    => $wrkshp_ongoing_row->long_description,
    //                                 'workshop_url'        => $wrkshp_ongoing_row->workshop_url,
    //                                 'workshop_image'      => $workshop_image,
    //                                 'start_date'          => $wrkshp_ongoing_row->start_date,
    //                                 'end_date'            => $wrkshp_ongoing_row->end_date,
    //                                 'pre_start_date'      => $wrkshp_ongoing_row->pre_start_date,
    //                                 'pre_start_date_dmy'  => date("d-m-Y", strtotime($wrkshp_ongoing_row->pre_start_date)),
    //                                 'pre_start_time'      => $wrkshp_ongoing_row->pre_start_time,
    //                                 'pre_end_date'        => $wrkshp_ongoing_row->pre_end_date,
    //                                 'pre_end_date_dmy'    => date("d-m-Y", strtotime($wrkshp_ongoing_row->pre_end_date)),
    //                                 'pre_end_time'        => $wrkshp_ongoing_row->pre_end_time,
    //                                 'pre_time_status'     => $wrkshp_ongoing_row->pre_time_status,
    //                                 'post_start_date'     => $wrkshp_ongoing_row->post_start_date,
    //                                 'post_start_date_dmy' => date("d-m-Y", strtotime($wrkshp_ongoing_row->post_start_date)),
    //                                 'post_start_time'     => $wrkshp_ongoing_row->post_start_time,
    //                                 'post_end_date'       => $wrkshp_ongoing_row->post_end_date,
    //                                 'post_end_date_dmy'   => date("d-m-Y", strtotime($wrkshp_ongoing_row->post_end_date)),
    //                                 'post_end_time'       => $wrkshp_ongoing_row->post_end_time,
    //                                 'post_time_status'    => $wrkshp_ongoing_row->post_time_status,
    //                                 'point_multiplier'    => $wrkshp_ongoing_row->point_multiplier,
    //                                 'otp_required'        => $otp_required,
    //                                 'is_registered'       => $wrkshp_ongoing_row->is_registered,
    //                                 'wc_heading'          => $wrkshp_ongoing_row->heading,
    //                                 'wc_message'          => $wrkshp_ongoing_row->message,
    //                                 'score_correct'       => $correct,
    //                                 'score_wrong'         => $wrong,
    //                                 'score_time_out'      => $time_out,
    //                                 'all_questions_fired' => $wrkshp_ongoing_row->all_questions_fired,
    //                                 'all_feedbacks_fired' => $wrkshp_ongoing_row->all_feedbacks_fired,
    //                                 'session_preclose'    => $wrkshp_ongoing_row->session_preclose,
    //                             )); 
    //                         }
    //                     }
    //                 }

    //                 //COMPLETED
    //                 $workshop_completed_details   = $this->api_model->fetch_workshop_completed($company_id,$user_id,$this->atomdb);
    //                 $total     = count((array)$workshop_completed_details);
    //                 if ($total > 0) {
    //                     foreach ($workshop_completed_details as $wrkshp_completed_row) {
    //                         $workshop_allowed = 1;
    //                         $restricted_users = $wrkshp_completed_row->restricted_users;

    //                         if ($restricted_users>0){
    //                             $where_clause = array(
    //                                 'workshop_id' => $wrkshp_completed_row->id,
    //                                 'user_id'     => $user_id
    //                             );
    //                             $wu_row          = $this->api_model->fetch_record('workshop_users',$where_clause,$this->atomdb);
    //                             if (count((array)$wu_row)==0) {
    //                                 $workshop_allowed = 0;
    //                             }
    //                         }
    //                         if ($workshop_allowed==1){
    //                             $workshop_image = "assets/images/workshop-blank.jpg";
    //                             if ($wrkshp_completed_row->workshop_image!=''){
    //                                 $file_name = $wrkshp_completed_row->workshop_image;
    //                                 $file_path_check = "../".$portal_name."/assets/uploads/workshop/".$file_name;
    //                                 $file_path = "/assets/uploads/workshop/".$file_name;

    //                                 if (file_exists($file_path_check)){
    //                                     $workshop_image = $domin_url.$file_path;      
    //                                 }else{
    //                                     $workshop_image = "assets/images/workshop-blank.jpg";     
    //                                 }                               
    //                             }
    //                             $otp_required = 0;
    //                             if ($wrkshp_completed_row->otp!=''){
    //                                 $otp_required = 1;
    //                             }
    //                             $correct  = 0;
    //                             $wrong    = 0;
    //                             $time_out = 0;
    //                             $workshop_score_result = $this->api_model->fetch_completed_score(
    //                                                                 $wrkshp_completed_row->company_id,
    //                                                                 $user_id,
    //                                                                 $wrkshp_completed_row->id,
    //                                                                 $wrkshp_completed_row->workshop_session,
    //                                                                 $this->atomdb);
    //                             if (count((array)$workshop_score_result)>0){
    //                                 foreach ($workshop_score_result as $workshop_score) {
    //                                     $correct  = $workshop_score->correct;
    //                                     $wrong    = $workshop_score->wrong;
    //                                     $time_out = $workshop_score->time_out;
    //                                 }
    //                             }    
    //                             array_push($data, array(
    //                                 'workshop_tab'        => 'completed',
    //                                 'workshop_session'    => $wrkshp_completed_row->workshop_session,
    //                                 'id'                  => $wrkshp_completed_row->id,
    //                                 'workshop_date'       => $wrkshp_completed_row->workshop_date,
    //                                 'workshop_name'       => $wrkshp_completed_row->workshop_name,
    //                                 'company_id'          => $wrkshp_completed_row->company_id,
    //                                 'timer'               => $wrkshp_completed_row->timer,
    //                                 'powered_by'          => $wrkshp_completed_row->powered_by,
    //                                 'short_description'   => $wrkshp_completed_row->short_description,
    //                                 'long_description'    => $wrkshp_completed_row->long_description,
    //                                 'workshop_url'        => $wrkshp_completed_row->workshop_url,
    //                                 'workshop_image'      => $workshop_image,
    //                                 'start_date'          => $wrkshp_completed_row->start_date,
    //                                 'end_date'            => $wrkshp_completed_row->end_date,
    //                                 'pre_start_date'      => $wrkshp_completed_row->pre_start_date,
    //                                 'pre_start_date_dmy'  => date("d-m-Y", strtotime($wrkshp_completed_row->pre_start_date)),
    //                                 'pre_start_time'      => $wrkshp_completed_row->pre_start_time,
    //                                 'pre_end_date'        => $wrkshp_completed_row->pre_end_date,
    //                                 'pre_end_date_dmy'    => date("d-m-Y", strtotime($wrkshp_completed_row->pre_end_date)),
    //                                 'pre_end_time'        => $wrkshp_completed_row->pre_end_time,
    //                                 'pre_time_status'     => $wrkshp_completed_row->pre_time_status,
    //                                 'post_start_date'     => $wrkshp_completed_row->post_start_date,
    //                                 'post_start_date_dmy' => date("d-m-Y", strtotime($wrkshp_completed_row->post_start_date)),
    //                                 'post_start_time'     => $wrkshp_completed_row->post_start_time,
    //                                 'post_end_date'       => $wrkshp_completed_row->post_end_date,
    //                                 'post_end_date_dmy'   => date("d-m-Y", strtotime($wrkshp_completed_row->post_end_date)),
    //                                 'post_end_time'       => $wrkshp_completed_row->post_end_time,
    //                                 'post_time_status'    => $wrkshp_completed_row->post_time_status,
    //                                 'point_multiplier'    => $wrkshp_completed_row->point_multiplier,
    //                                 'otp_required'        => $otp_required,
    //                                 'is_registered'       => $wrkshp_completed_row->is_registered,
    //                                 'wc_heading'          => $wrkshp_completed_row->heading,
    //                                 'wc_message'          => $wrkshp_completed_row->message,
    //                                 'score_correct'       => $correct,
    //                                 'score_wrong'         => $wrong,
    //                                 'score_time_out'      => $time_out,
    //                                 'all_questions_fired' => $wrkshp_completed_row->all_questions_fired,
    //                                 'all_feedbacks_fired' => $wrkshp_completed_row->all_feedbacks_fired,
    //                                 'session_preclose'    => $wrkshp_completed_row->session_preclose,
    //                             ));
    //                         }
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'    => $data,
    //                     'success' => true,
    //                     'message' => 'Workshop data loaded successfully.'
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function workshop_without_otc(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 

    //             //IF PAYLOAD VERIFY THEN RETRIVE USERS DETAILS FROM DATABASE
    //             $user_details = $this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $user_count =  count((array)$user_details);
    //             if ($user_count > 0) {
    //                 foreach ($user_details as $row) {


    //                 }
    //                 //CHECK USER IS REGISTERED FOR WORKSHOP.
    //                 $where_clause = array(
    //                     'user_id'          => $user_id,
    //                     'workshop_id'      => $workshop_id,
    //                     'workshop_session' => $workshop_session,
    //                 ); 
    //                 $workshop_registered_users_count = $this->api_model->record_count('workshop_registered_users',$where_clause,$this->atomdb);
    //                 if ($workshop_registered_users_count>0){
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "You are registered participant of this workshop."
    //                     );
    //                 }else{
    //                     $data = array(
    //                         'workshop_id'          => $workshop_id,
    //                         'user_id'              => $user_id,
    //                         'registered_date_time' => date('Y-m-d H:i:s'),
    //                         'otp_verified'         => 0,
    //                         'workshop_session'     => $workshop_session
    //                     );
    //                     $wru_txn_id = $this->api_model->insert('workshop_registered_users',$data,$this->atomdb);
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "You are now registered for this Workshop."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function workshop_otc(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $otp              = $this->input->post('otp');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 

    //             //IF PAYLOAD VERIFY THEN RETRIVE USERS DETAILS FROM DATABASE
    //             $user_details = $this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $user_count =  count((array)$user_details);
    //             if ($user_count > 0) {
    //                 foreach ($user_details as $row) {


    //                 }

    //                 //VERIFY WORKSHOP OTC IN DATABASE
    //                 $where_clause = array(
    //                     'otp' => $otp,
    //                     'id'  => $workshop_id
    //                 ); 
    //                 $otp_found = $this->api_model->record_count('workshop',$where_clause,$this->atomdb);
    //                 if ($otp_found==1){

    //                     //CHECK USER IS REGISTERED FOR WORKSHOP.
    //                     $where_clause = array(
    //                         'user_id'          => $user_id,
    //                         'workshop_id'      => $workshop_id,
    //                         'workshop_session' => $workshop_session,
    //                     ); 
    //                     $workshop_registered_users_count = $this->api_model->record_count('workshop_registered_users',$where_clause,$this->atomdb);
    //                     if ($workshop_registered_users_count>0){
    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Workshop One Time Code(OTC) Already Verified"
    //                         );
    //                     }else{
    //                         $data = array(
    //                             'workshop_id'          => $workshop_id,
    //                             'user_id'              => $user_id,
    //                             'registered_date_time' => date('Y-m-d H:i:s'),
    //                             'otp_verified'         => 1,
    //                             'workshop_session'     => $workshop_session

    //                         );
    //                         $wru_txn_id = $this->api_model->insert('workshop_registered_users',$data,$this->atomdb);
    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Workshop One Time Code(OTC) Verified"
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "Invalid Workshop One Time Code(OTC)"
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function common_advertisement(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST       = json_decode($_jsonObj, TRUE);
    //         $payload     = $this->input->post('payload');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id      = $JWTVerify->user_id;
    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 

    //             //IF PAYLOAD VERIFY THEN RETRIVE COMMON ADVERTISEMENTS OF COMPANY.
    //             $user_details =$this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $total =  count((array)$user_details);
    //             if ($total > 0) { 
    //                 foreach ($user_details as $user_row) {
    //                     $company_id   = $user_row->company_id;
    //                     $portal_name  = '';
    //                     $domin_url    = '';
    //                     $whereclause  = array('id' => $company_id);
    //                     $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                     if(count((array)$company_data)>0){
    //                         $portal_name  = $company_data->portal_name;
    //                         $domin_url    = $company_data->domin_url;
    //                     }
    //                     if ($company_id>0){
    //                         $advertisement_details = $this->api_model->fetch_common_advertisement($company_id,$this->atomdb);
    //                         if (count((array)$advertisement_details)>0){
    //                             foreach ($advertisement_details as $ads_row) {
    //                                 $advt_id     = $ads_row->id;
    //                                 $advt_name   = $ads_row->advt_name;
    //                                 $advt_url    = $ads_row->url;
    //                                 $advt_banner = $ads_row->thumbnail_image;
    //                                 $advtPath    = '';
    //                                 if ($advt_banner!=''){
    //                                     $file_path = "../".$portal_name."/assets/uploads/advertisement/".$advt_banner;
    //                                     if (file_exists($file_path)){
    //                                         $advtPath = $domin_url."/assets/uploads/advertisement/".$advt_banner;     
    //                                     }else{
    //                                         $advtPath = $domin_url."/assets/uploads/advertisement/no-advt.jpg";   
    //                                     } 
    //                                 }else{
    //                                     $advtPath =  $domin_url."/assets/uploads/advertisement/no-advt.jpg";  
    //                                 }
    //                                 array_push($data, array(
    //                                     'advt_id'     => $advt_id,
    //                                     'advt_name'   => $advt_name,
    //                                     'advt_url'    => $advt_url,
    //                                     'advt_banner' => $advtPath,
    //                                     'seconds'     => 5
    //                                 ));
    //                             }
    //                             $json         =  array(
    //                                 'data'    => $data,
    //                                 'success' => true,
    //                                 'message' => "Advertisement loaded succesfully"
    //                             );
    //                         }else{
    //                             $json         =  array(
    //                                 'success' => false,
    //                                 'message' => "NO_ADVERTISEMENT"
    //                             );
    //                         }
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' => "Company id is missing"
    //                         );
    //                     }
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function workshop_advertisement(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST       = json_decode($_jsonObj, TRUE);
    //         $payload     = $this->input->post('payload');
    //         $workshop_id = $this->input->post('workshop_id');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id      = $JWTVerify->user_id;
    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 

    //             //IF PAYLOAD VERIFY THEN RETRIVE COMMON ADVERTISEMENTS OF COMPANY.
    //             $user_details =$this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $total =  count((array)$user_details);
    //             if ($total > 0) { 
    //                 foreach ($user_details as $user_row) {
    //                     $company_id            = $user_row->company_id;
    //                     $portal_name  = '';
    //                     $domin_url    = '';
    //                     $whereclause  = array('id' => $company_id);
    //                     $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                     if(count((array)$company_data)>0){
    //                         $portal_name  = $company_data->portal_name;
    //                         $domin_url    = $company_data->domin_url;
    //                     }
    //                     if ($company_id>0){
    //                         $wksh_advertisement_details = $this->api_model->fetch_workshop_advertisement($workshop_id,$this->atomdb);
    //                         $advertisement_details      = $this->api_model->fetch_common_advertisement($company_id,$this->atomdb);

    //                         if (count((array)$wksh_advertisement_details)>0){
    //                             foreach ($wksh_advertisement_details as $ads_row) {
    //                                 $advt_name   = '';
    //                                 $advt_url    = $ads_row->url;
    //                                 $advt_banner = $ads_row->thumbnail_image;
    //                                 $advtPath    = '';
    //                                 if ($advt_banner!=''){
    //                                     $file_path = "../".$portal_name."/assets/uploads/workshop/banners/".$advt_banner;
    //                                     if (file_exists($file_path)){
    //                                         $advtPath = $domin_url."/assets/uploads/workshop/banners/".$advt_banner;     
    //                                     }else{
    //                                         $advtPath =  $domin_url."/assets/uploads/workshop/banners/no-advt.jpg";   
    //                                     } 
    //                                 }else{
    //                                     $advtPath =  $domin_url."/assets/uploads/workshop/banners/no-advt.jpg";  
    //                                 }
    //                                 array_push($data, array(
    //                                     'advt_name'   => $advt_name,
    //                                     'advt_url'    => $advt_url,
    //                                     'advt_banner' => $advtPath,
    //                                     'seconds'     => 5
    //                                 ));
    //                             }
    //                             $json         =  array(
    //                                 'data'    => $data,
    //                                 'success' => true,
    //                                 'message' => "Advertisement loaded succesfully"
    //                             );
    //                         } else if (count((array)$advertisement_details)>0){
    //                             foreach ($advertisement_details as $ads_row) {
    //                                 $advt_name   = $ads_row->advt_name;
    //                                 $advt_url    = $ads_row->url;
    //                                 $advt_banner = $ads_row->thumbnail_image;
    //                                 $advtPath    = '';
    //                                 if ($advt_banner!=''){
    //                                     $file_path = "../".$portal_name."/assets/uploads/advertisement/".$advt_banner;
    //                                     if (file_exists($file_path)){
    //                                         $advtPath = $domin_url."/assets/uploads/advertisement/".$advt_banner;     
    //                                     }else{
    //                                         $advtPath = $domin_url."/assets/uploads/advertisement/no-advt.jpg";   
    //                                     } 
    //                                 }else{
    //                                     $advtPath =  $domin_url."/assets/uploads/advertisement/no-advt.jpg";  
    //                                 }
    //                                 array_push($data, array(
    //                                     'advt_name'   => $advt_name,
    //                                     'advt_url'    => $advt_url,
    //                                     'advt_banner' => $advtPath,
    //                                     'seconds'     => 5
    //                                 ));
    //                             }
    //                             $json         =  array(
    //                                 'data'    => $data,
    //                                 'success' => true,
    //                                 'message' => "Advertisement loaded succesfully"
    //                             );
    //                         }else{
    //                             $json         =  array(
    //                                 'success' => false,
    //                                 'message' => "NO_ADVERTISEMENT"
    //                             );
    //                         }
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' => "Company id is missing"
    //                         );
    //                     }
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function advertisement_status_update(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify    = $this->jwt->decode($payload, $server_key);
    //             $common_user_id      = $JWTVerify->user_id;
    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;

    //                 $data = array(
    //                     'company_id'     => $company_id,
    //                     'user_id'        => $user_id,
    //                     'advt_id'        => $this->input->post('advt_id'),
    //                     'module_type'    => $this->input->post('module_type'),
    //                     'advt_view_dttm' => $this->input->post('advt_view_dttm')
    //                 );
    //                 $this->api_model->insert('advertisement_details',$data,$this->atomdb);

    //                 $json = array(
    //                     'success' => true,
    //                     'message' => "Advertisement details submitted successfully."
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'otp_pending' => '',
    //             'otc_pending' => '',
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }    
    // public function check_workshop_status($workshop_id,$user_id,$workshop_session,$atomdb=null){
    //     $workshop_status = 'false';
    //     $where_clause = array(
    //         'workshop_id'      => $workshop_id,
    //         'user_id'          => $user_id,
    //         'workshop_session' => $workshop_session,
    //     );
    //     $workshop_registered_users_results = $this->api_model->fetch_record('workshop_registered_users',$where_clause,$atomdb);
    //     if (count((array)$workshop_registered_users_results)>0){
    //         $all_questions_fired = $workshop_registered_users_results->all_questions_fired;
    //         $all_feedbacks_fired = $workshop_registered_users_results->all_feedbacks_fired;
    //     }else{
    //         //USER HAS NOT REGISTERED HIM SELF FOR WORKSHOP OR NO ENTRY AVAILABLE FOR USER IN REGISTRATION TABLE
    //         $all_questions_fired = 1;
    //         $all_feedbacks_fired = 1;
    //     }

    //     if ($all_questions_fired==1 AND $all_feedbacks_fired==1){
    //         $workshop_status = 'true';
    //     }else{
    //         $workshop_status = 'false';
    //     }
    //     return array(
    //         'status' => $workshop_status,
    //         'all_questions_fired' => $all_questions_fired,
    //         'all_feedbacks_fired' => $all_feedbacks_fired
    //     );
    // }
    // public function fetch_questions(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');

    //         $webauto_login = 0;
    //         if (isset($_POST['webauto_login'])){
    //             $webauto_login = (is_null($this->input->post('webauto_login')) OR $this->input->post('webauto_login')=='')?0:$this->input->post('webauto_login');
    //         }

    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");

    //         if ($workshop_id!='' AND $workshop_session!=''){

    //             //PAYLOAD VERIFY
    //             try{
    //                 $JWTVerify = $this->jwt->decode($payload, $server_key);
    //                 $common_user_id = $JWTVerify->user_id;
    //                 $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //                 $user_id    = 0;
    //                 $company_id = 0;
    //                 $where_clause = array(
    //                     'id'           => $common_user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 
    //                 $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //                 if (count((array)$common_user_details)>0){
    //                     $user_id = $common_user_details->user_id;
    //                 }

    //                 $where_clause = array(
    //                     'user_id'      => $user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 

    //                 //FIND THE COMPANY OF USER
    //                 $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //                 if (count((array)$user_details)>0){
    //                     $company_id   = $user_details->company_id;
    //                     $company_name ='';
    //                     $company_logo ='';
    //                     $portal_name  = '';
    //                     $domin_url    = '';
    //                     $whereclause = array('id' => $company_id);
    //                     $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                     if(count((array)$company_data)>0){
    //                         $company_name = $company_data->company_name;
    //                         $company_logo = $company_data->company_logo;
    //                         $portal_name  = $company_data->portal_name;
    //                         $domin_url    = $company_data->domin_url;
    //                     }
    //                     $company_path = '';                      
    //                     if ($company_logo ==''){
    //                         $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     }else{
    //                         // $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    //                         $file_path = "/assets/uploads/company/".$company_logo;
    //                         // if (file_exists($file_path_check)){
    //                             $company_path = $domin_url.$file_path;
    //                         // }else{
    //                         //     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                         // } 
    //                     }

    //                     //FETCH THE WORKSHOP DETAILS.
    //                     $workshop_details   = $this->api_model->fetch_workshop_details($company_id,$workshop_id,$this->atomdb);
    //                     $total     = count((array)$workshop_details);
    //                     if ($total > 0) {
    //                         foreach ($workshop_details as $workshop_row) {
    //                             $workshop_name       = $workshop_row->workshop_name;
    //                             $workshop_timer      = $workshop_row->timer;
    //                             $point_multiplier    = $workshop_row->point_multiplier;
    //                             $payback_option      = $workshop_row->payback_option;
    //                             $play_all_feedback   = $workshop_row->play_all_feedback;
    //                             $heading             = $workshop_row->heading;
    //                             $message             = $workshop_row->message;
    //                             $fset_pre_trigger    = $workshop_row->fset_pre_trigger;
    //                             $fset_post_trigger   = $workshop_row->fset_post_trigger;
    //                             $workshop_powered_by = $workshop_row->powered_by;
    //                             $questionset_type    = $workshop_row->questionset_type;
    //                             $pre_time_status     = $workshop_row->pre_time_status;
    //                             $post_time_status    = $workshop_row->post_time_status;
    //                             $questions_order     = (is_null($workshop_row->questions_order) OR $workshop_row->questions_order=='' OR $workshop_row->questions_order==0 OR $workshop_row->questions_order=='0')?'':$workshop_row->questions_order;
    //                             //$questions_order Value is 1 means RANDOM
    //                             //$questions_order Value is 2 means SEQUNCE
    //                         }

    //                         if ($workshop_session=="PRE"){
    //                             if ($pre_time_status==1){
    //                                 $where_clause = array(
    //                                     'user_id'          => $user_id,
    //                                     'workshop_id'      => $workshop_id,
    //                                     'workshop_session' => $workshop_session
    //                                 );
    //                                 $wrkover_updt_data = array(
    //                                     'all_questions_fired'   => 1,
    //                                     'all_feedbacks_fired'   => 1,
    //                                     'session_preclose'      => 1,
    //                                     'session_preclose_dttm' => date('Y-m-d H:i:s')
    //                                 );
    //                                 $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);

    //                                 //CUSTOMIZED FOR ELP
    //                                 // if ((int)$webauto_login==1){
    //                                 //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                 // }

    //                                 $json = array(
    //                                     'success' => false,
    //                                     'message' =>  "WORKSHOP_COMPLETED"
    //                                 );
    //                                 echo json_encode($json);
    //                                 exit; 
    //                             }
    //                         }
    //                         if ($workshop_session=="POST"){
    //                             if ($post_time_status==1){
    //                                 $where_clause = array(
    //                                     'user_id'          => $user_id,
    //                                     'workshop_id'      => $workshop_id,
    //                                     'workshop_session' => $workshop_session,
    //                                 );
    //                                 $wrkover_updt_data = array(
    //                                     'all_questions_fired'   => 1,
    //                                     'all_feedbacks_fired'   => 1,
    //                                     'session_preclose'      => 1,
    //                                     'session_preclose_dttm' => date('Y-m-d H:i:s')
    //                                 );
    //                                 $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);

    //                                 //CUSTOMIZED FOR ELP
    //                                 // if ((int)$webauto_login==1){
    //                                 //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                 // }

    //                                 $json = array(
    //                                     'success' => false,
    //                                     'message' =>  "WORKSHOP_COMPLETED"
    //                                 );
    //                                 echo json_encode($json);
    //                                 exit; 
    //                             }
    //                         }

    //                         $total_questions          = 0;
    //                         $total_workshop_questions = 0;
    //                         $total_feedback_questions = 0;
    //                         $total_questions_played   = 0;
    //                         $total_played             = 0;

    //                         // QUESTION SET AND FEEDBACK SET - START
    //                         if ($questionset_type==1 OR $questionset_type=='1'){

    //                             if ($workshop_session=="PRE"){
    //                                 $total_questions_result          = $this->api_model->fetch_pre_questions_count($company_id,$workshop_id,$user_id,$this->atomdb); 
    //                                 if (count((array)$total_questions_result)>0){
    //                                     $total_workshop_questions        = $total_questions_result->total_questions;
    //                                 }
    //                                 $total_feedback_questions_result = $this->api_model->fetch_pre_feedback_questions_count($company_id,$workshop_id,$user_id,$this->atomdb);
    //                                 if (count((array)$total_feedback_questions_result)>0){
    //                                     $total_feedback_questions        = $total_feedback_questions_result->total_questions;
    //                                 }
    //                                 if ($fset_pre_trigger<=0){
    //                                     $total_feedback_questions = 0;
    //                                 }
    //                             }
    //                             if ($workshop_session=="POST"){
    //                                 $total_questions_result          = $this->api_model->fetch_post_questions_count($company_id,$workshop_id,$user_id,$this->atomdb); 
    //                                 if (count((array)$total_questions_result)>0){
    //                                     $total_workshop_questions        = $total_questions_result->total_questions;
    //                                 }
    //                                 $total_feedback_questions_result = $this->api_model->fetch_post_feedback_questions_count($company_id,$workshop_id,$user_id,$this->atomdb);
    //                                 if (count((array)$total_feedback_questions_result)>0){
    //                                     $total_feedback_questions        = $total_feedback_questions_result->total_questions;
    //                                 }
    //                                 if ($fset_post_trigger<=0){
    //                                     $total_feedback_questions = 0;
    //                                 }
    //                             }

    //                             //GET TOTAL QUESTIONS TO BE FIERED.
    //                             $total_questions        = $total_workshop_questions + $total_feedback_questions;

    //                             $total_atom_result      = $this->api_model->fetch_atom_results_count($company_id,$workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                             if (count((array)$total_atom_result)>0){
    //                                 $total_questions_played = $total_atom_result->total;
    //                             }
    //                             $total_atom_feedback    = $this->api_model->fetch_atom_feedback_count($company_id,$workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                             if (count((array)$total_atom_feedback)>0){
    //                                 $total_feedback_played  = $total_atom_feedback->total;
    //                             }
    //                             //GET TOTAL QUESTIONS PLAYED.
    //                             if ($workshop_session=="PRE"){
    //                                 if ($fset_pre_trigger<=0){
    //                                     $total_feedback_played = 0;
    //                                 }
    //                             }
    //                             if ($workshop_session=="POST"){
    //                                 if ($fset_post_trigger<=0){
    //                                     $total_feedback_played = 0;
    //                                 }
    //                             }

    //                             $total_played    = ($total_questions_played + $total_feedback_played);
    //                             $totalLefttoPlay = ($total_questions - $total_played);

    //                             if ($totalLefttoPlay<=0){
    //                                 $where_clause = array(
    //                                     'user_id'          => $user_id,
    //                                     'workshop_id'      => $workshop_id,
    //                                     'workshop_session' => $workshop_session,
    //                                 );
    //                                 $wrkover_updt_data = array(
    //                                     'all_questions_fired' => 1,
    //                                     'all_feedbacks_fired' => 1
    //                                 );
    //                                 $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);

    //                                 //CUSTOMIZED FOR ELP
    //                                 // if ((int)$webauto_login==1){
    //                                 //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                 // }

    //                                 $json = array(
    //                                     'success' => false,
    //                                     'message' =>  "WORKSHOP_COMPLETED"
    //                                 );
    //                                 echo json_encode($json);
    //                                 exit;
    //                             }
    //                             $workshopQuestionLefttoPlay = ($total_workshop_questions-$total_questions_played);
    //                             $feedbackQuestionLefttoPlay = ($total_feedback_questions-$total_feedback_played);
    //                             $workshop_status = $this->check_workshop_status($workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                             if ($workshopQuestionLefttoPlay<=0 AND $workshop_status['all_questions_fired']==0){
    //                                 $where_clause = array(
    //                                     'user_id'          => $user_id,
    //                                     'workshop_id'      => $workshop_id,
    //                                     'workshop_session' => $workshop_session,
    //                                 );
    //                                 $wrkover_updt_data = array(
    //                                     'all_questions_fired' => 1
    //                                 );
    //                                 $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);
    //                             }
    //                             if ($feedbackQuestionLefttoPlay<=0 AND $workshop_status['all_feedbacks_fired']==0){
    //                                 $where_clause = array(
    //                                     'user_id'          => $user_id,
    //                                     'workshop_id'      => $workshop_id,
    //                                     'workshop_session' => $workshop_session,
    //                                 );
    //                                 $wrkover_updt_data = array(
    //                                     'all_feedbacks_fired' => 1
    //                                 );
    //                                 $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);
    //                             }

    //                             $workshop_status = $this->check_workshop_status($workshop_id,$user_id,$workshop_session,$this->atomdb);

    //                             if ($workshop_status['status']=='true'){
    //                                 //CUSTOMIZED FOR ELP
    //                                 // if ((int)$webauto_login==1){
    //                                 //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                 // }

    //                                 $json = array(
    //                                     'success' => false,
    //                                     'message' =>  "WORKSHOP_COMPLETED"
    //                                 );
    //                             }else if ($workshop_status['status']=='false'){
    //                                 $feedbackTrigger = false;

    //                                 if ($feedbackQuestionLefttoPlay<=0 AND $workshop_status['all_feedbacks_fired']==1){
    //                                     $feedbackTrigger = false;
    //                                 }else{
    //                                     if (($play_all_feedback==1) AND ($workshopQuestionLefttoPlay<=0 AND $workshop_status['all_questions_fired']==1)){
    //                                         if ($feedbackQuestionLefttoPlay>0 AND $workshop_status['all_feedbacks_fired']==0){
    //                                             $feedbackTrigger = true;
    //                                         }
    //                                     }else if (($play_all_feedback==0) AND ($workshopQuestionLefttoPlay<=0 AND $workshop_status['all_questions_fired']==1)){
    //                                         $feedbackTrigger = false;
    //                                     }else if ($workshop_session=="PRE"){
    //                                         if ($fset_pre_trigger>0 AND $total_played>0){
    //                                             if ((($total_played+1) % ($fset_pre_trigger+1))===0){
    //                                                 $feedbackTrigger=true;
    //                                             } 
    //                                         }
    //                                     }else if ($workshop_session=="POST"){
    //                                         if ($fset_post_trigger>0 AND $total_played>0){
    //                                             if ((($total_played+1) % ($fset_post_trigger+1))===0){
    //                                                 $feedbackTrigger=true;
    //                                             } 
    //                                         }
    //                                     }
    //                                 }
    //                                 // CHECK FEEDBACK QUESTION FIERED OR WORKSHOP QUESTION
    //                                 if ($feedbackTrigger==true){
    //                                     if ($workshop_session=="PRE"){
    //                                         $feedback_result =  $this->api_model->fetch_pre_feedback_questions($company_id,$workshop_id,$user_id,$questions_order,$this->atomdb);
    //                                     }
    //                                     if ($workshop_session=="POST"){
    //                                         $feedback_result =  $this->api_model->fetch_post_feedback_questions($company_id,$workshop_id,$user_id,$questions_order,$this->atomdb);
    //                                     }
    //                                     $question_count =  count((array)$feedback_result);
    //                                     if ($question_count>0){
    //                                         foreach ($feedback_result as $question_row) {
    //                                             $question_id         = $question_row->id;
    //                                             $feedbackset_id      = $question_row->feedbackset_id;
    //                                             $feedback_type_id    = $question_row->type_id;
    //                                             $feedback_subtype_id = $question_row->subtype_id;
    //                                             $question_title      = $question_row->question_title;
    //                                             $option_a            = $question_row->option_a;
    //                                             $option_b            = $question_row->option_b;
    //                                             $option_c            = $question_row->option_c;
    //                                             $option_d            = $question_row->option_d;
    //                                             $option_e            = $question_row->option_e;
    //                                             $option_f            = $question_row->option_f;
    //                                             $weight_a            = $question_row->weight_a;
    //                                             $weight_b            = $question_row->weight_b;
    //                                             $weight_c            = $question_row->weight_c;
    //                                             $weight_d            = $question_row->weight_d;
    //                                             $weight_e            = $question_row->weight_e;
    //                                             $weight_f            = $question_row->weight_f;
    //                                             $multiple_allow      = $question_row->multiple_allow;
    //                                             $feedback_set_title  = $question_row->feedback_set_title;
    //                                             $type                = $question_row->type;
    //                                             $sub_type            = $question_row->sub_type;
    //                                             $qset_powered_by     = $question_row->powered_by;
    //                                             $company_name        = $question_row->company_name;
    //                                             $questionset_timer   = $question_row->timer;
    //                                             $tip                 = $question_row->tip;
    //                                             $hint_image          = $question_row->hint_image;
    //                                             $question_type       = $question_row->question_type;
    //                                             $min_length          = $question_row->min_length;
    //                                             $max_length          = $question_row->max_length;
    //                                             $question_timer      = $question_row->question_timer;
    //                                             $text_weightage      = $question_row->text_weightage;
    //                                         }

    //                                         $feedback_hint_image ='';
    //                                         if ($hint_image!=''){
    //                                             $file_check_path = "../".$portal_name."/assets/uploads/feedback_questions/".$hint_image;
    //                                             $file_path = "/assets/uploads/feedback_questions/".$hint_image;
    //                                             if (file_exists($file_check_path)){
    //                                                 $feedback_hint_image = $domin_url.$file_path;     
    //                                             }                               
    //                                         }                                            
    //                                         $json = array(
    //                                             'success'             => true,
    //                                             'message'             => "",
    //                                             'is_wq_fq'            => 'FEEDBACK_QUESTION',
    //                                             'company_id'          => $company_id,
    //                                             'user_id'             => $user_id,
    //                                             'workshop_id'         => $workshop_id,
    //                                             'workshop_session'    => $workshop_session,
    //                                             'feedbackset_id'      => $feedbackset_id,
    //                                             'feedback_type_id'    => $feedback_type_id,
    //                                             'feedback_subtype_id' => $feedback_subtype_id,
    //                                             'feedback_id'         => $question_id,
    //                                             'workshop_name'       => $workshop_name,
    //                                             'question_title'      => $question_title,
    //                                             'option_a'            => $option_a,
    //                                             'option_b'            => $option_b,
    //                                             'option_c'            => $option_c,
    //                                             'option_d'            => $option_d,
    //                                             'option_e'            => $option_e,
    //                                             'option_f'            => $option_f,
    //                                             'weight_a'            => $weight_a,
    //                                             'weight_b'            => $weight_b,
    //                                             'weight_c'            => $weight_c,
    //                                             'weight_d'            => $weight_d,
    //                                             'weight_e'            => $weight_e,
    //                                             'weight_f'            => $weight_f,
    //                                             'tip'                 => $tip,
    //                                             'hint_image'          => $feedback_hint_image,
    //                                             'question_type'       => $question_type,
    //                                             'min_length'          => $min_length,
    //                                             'max_length'          => $max_length,
    //                                             'question_timer'      => $question_timer,
    //                                             'text_weightage'      => $text_weightage,
    //                                             'company_name'        => $company_name,
    //                                             'feedback_set_title'  => $feedback_set_title,
    //                                             'type'                => $type,
    //                                             'sub_type'            => $sub_type,
    //                                             'powered_by'          => ($qset_powered_by!='')?$qset_powered_by:$workshop_powered_by,
    //                                             'multiplier'          => $point_multiplier,
    //                                             'timer'               => ($questionset_timer!='' AND $questionset_timer>0)?$questionset_timer:$workshop_timer,
    //                                             'total_questions'     => $total_questions,
    //                                             'total_played'        => $total_played,
    //                                             'multiple_allow'      => $multiple_allow,
    //                                             'company_logo'        => $company_path,
    //                                             'server_date_time'    => date('Y-m-d H:i:s')
    //                                         );
    //                                     }else{
    //                                         //UPDATE THE STAUTS TO KNOW ALL FEEDBACK QUESTION HAS BEEN FIERED. 
    //                                         $wrkover_updt_data = array(
    //                                             'all_feedbacks_fired' => 1
    //                                         );
    //                                         $where_clause = array(
    //                                             'user_id'           => $user_id,
    //                                             'workshop_id'       => $workshop_id,
    //                                             'workshop_session'  => $workshop_session
    //                                         ); 
    //                                         $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);
    //                                         $workshop_status       = $this->check_workshop_status($workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                                         if ($workshop_status['status']=='true'){
    //                                             //CUSTOMIZED FOR ELP
    //                                             // if ((int)$webauto_login==1){
    //                                             //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                             // }

    //                                             $json = array(
    //                                                 'success' => false,
    //                                                 'message' =>  "WORKSHOP_COMPLETED"
    //                                             );
    //                                         }
    //                                     }
    //                                 }else{
    //                                     if ($workshop_session=="PRE"){
    //                                         $questions_result                = $this->api_model->fetch_pre_questions($company_id,$workshop_id,$user_id,$questions_order,$this->atomdb); 
    //                                     }
    //                                     if ($workshop_session=="POST"){
    //                                         $questions_result                = $this->api_model->fetch_post_questions($company_id,$workshop_id,$user_id,$questions_order,$this->atomdb); 
    //                                     }                                        
    //                                     $question_count =  count((array)$questions_result);
    //                                     if ($question_count>0){
    //                                         foreach ($questions_result as $question_row) {
    //                                             $question_id       = $question_row->id;
    //                                             $questionset_id    = $question_row->questionset_id;
    //                                             $trainer_id        = $question_row->trainer_id;
    //                                             $topic_id          = $question_row->topic_id;
    //                                             $subtopic_id       = $question_row->subtopic_id;
    //                                             $question_title    = $question_row->question_title;
    //                                             $option_a          = $question_row->option_a;
    //                                             $option_b          = $question_row->option_b;
    //                                             $option_c          = $question_row->option_c;
    //                                             $option_d          = $question_row->option_d;
    //                                             $correct_answer    = $question_row->correct_answer;
    //                                             $tip               = $question_row->tip;
    //                                             $hint_image        = $question_row->hint_image;
    //                                             $youtube_link      = $question_row->youtube_link;
    //                                             $company_name      = $question_row->company_name;
    //                                             $topic             = $question_row->topic;
    //                                             $sub_topic         = $question_row->sub_topic;
    //                                             $title             = $question_row->title;
    //                                             $qset_powered_by   = $question_row->powered_by;
    //                                             $reward_multiplier = $question_row->reward;
    //                                             $weight            = $question_row->weight;
    //                                             $questionset_timer = $question_row->timer;
    //                                             $hide_answer       = $question_row->hide_answer;
    //                                         }
    //                                         $question_hint_image ='';
    //                                         if ($hint_image!=''){
    //                                             $file_check_path = "../".$portal_name."/assets/uploads/questions/".$hint_image;
    //                                             $file_path = "/assets/uploads/questions/".$hint_image;
    //                                             if (file_exists($file_check_path)){
    //                                                 $question_hint_image = $domin_url.$file_path;     
    //                                             }                               
    //                                         }

    //                                         $json = array(
    //                                             'success'           => true,
    //                                             'message'           => "",
    //                                             'is_wq_fq'          => 'WORKSHOP_QUESTION',
    //                                             'company_id'        => $company_id,
    //                                             'user_id'           => $user_id,
    //                                             'workshop_id'       => $workshop_id,
    //                                             'workshop_session'  => $workshop_session,
    //                                             'questionset_id'    => $questionset_id,
    //                                             'trainer_id'        => $trainer_id,
    //                                             'topic_id'          => $topic_id,
    //                                             'subtopic_id'       => $subtopic_id,
    //                                             'question_id'       => $question_id,
    //                                             'workshop_name'     => $workshop_name,
    //                                             'question_title'    => $question_title,
    //                                             'option_a'          => $option_a,
    //                                             'option_b'          => $option_b,
    //                                             'option_c'          => $option_c,
    //                                             'option_d'          => $option_d,
    //                                             'correct_answer'    => $correct_answer,
    //                                             'tip'               => $tip,
    //                                             'hint_image'        => $question_hint_image,
    //                                             'youtube_link'      => $youtube_link,
    //                                             'company_name'      => $company_name,
    //                                             'questionset_title' => $title,
    //                                             'topic'             => $topic,
    //                                             'sub_topic'         => $sub_topic,
    //                                             'powered_by'        => ($qset_powered_by!='')?$qset_powered_by:$workshop_powered_by,
    //                                             'weight'            => $weight,
    //                                             'multiplier'        => ($reward_multiplier!='' AND $reward_multiplier>0)?$reward_multiplier:$point_multiplier,
    //                                             'timer'             => ($questionset_timer!='' AND $questionset_timer>0)?$questionset_timer:$workshop_timer,
    //                                             'total_questions'   => $total_questions,
    //                                             'total_played'      => $total_played,
    //                                             'multiple_allow'    => 0,
    //                                             'company_logo'      => $company_path,
    //                                             'server_date_time'  => date('Y-m-d H:i:s'),
    //                                             'hide_answer'       => $hide_answer
    //                                         );
    //                                     }else{
    //                                         if ($total_feedback_questions<=0){
    //                                             //UPDATE THE STAUTS TO KNOW ALL QUESTION HAS BEEN FIERED. 
    //                                             $wrkover_updt_data = array(
    //                                                 'all_questions_fired' => 1,
    //                                                 'all_feedbacks_fired' => 1
    //                                             );
    //                                         }else{
    //                                             $feedbackLeftToPlay = ($total_feedback_questions-$total_feedback_played);
    //                                             if ($feedbackLeftToPlay>0 AND $play_all_feedback==1){
    //                                                 $wrkover_updt_data = array(
    //                                                     'all_questions_fired' => 1
    //                                                 );
    //                                             }else{
    //                                                 $wrkover_updt_data = array(
    //                                                     'all_questions_fired' => 1,
    //                                                     'all_feedbacks_fired' => 1
    //                                                 );
    //                                             }
    //                                         }
    //                                         $where_clause = array(
    //                                             'user_id'           => $user_id,
    //                                             'workshop_id'       => $workshop_id,
    //                                             'workshop_session'  => $workshop_session
    //                                         ); 
    //                                         $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);
    //                                         $workshop_status       = $this->check_workshop_status($workshop_id,$user_id,$workshop_session,$this->atomdb);

    //                                         if ($workshop_status['status']=='true'){
    //                                             //CUSTOMIZED FOR ELP
    //                                             // if ((int)$webauto_login==1){
    //                                             //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                             // }
    //                                             $json = array(
    //                                                 'success' => false,
    //                                                 'message' =>  "WORKSHOP_COMPLETED"
    //                                             );
    //                                         }else{
    //                                             //FEEDBACK QUESTION AVAILABLE TO PLAY ???
    //                                             // WHEN DOME BODY TRYING TO PLAY WITH DATABASE.

    //                                             //CUSTOMIZED FOR ELP
    //                                             // if ((int)$webauto_login==1){
    //                                             //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                             // }
    //                                             $json = array(
    //                                                 'success' => false,
    //                                                 'message' =>  "WORKSHOP_COMPLETED"
    //                                             );
    //                                         }
    //                                     }
    //                                 }
    //                             }else{
    //                                 //NEVER HAPPEN THIS CASE BUT FOR SECURITY REASON.
    //                                 // WHEN DOME BODY TRYING TO PLAY WITH DATABASE.

    //                                 //CUSTOMIZED FOR ELP
    //                                 // if ((int)$webauto_login==1){
    //                                 //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                 // }

    //                                 $json = array(
    //                                     'success' => false,
    //                                     'message' =>  "WORKSHOP_COMPLETED"
    //                                 );
    //                             }
    //                         }else if ($questionset_type==2 OR $questionset_type=='2'){ // FEEDBACK SET - START


    //                             if ($workshop_session=="PRE"){
    //                                 $total_feedback_questions_result = $this->api_model->fetch_pre_feedback_questions_count($company_id,$workshop_id,$user_id,$this->atomdb);
    //                                 if (count((array)$total_feedback_questions_result)>0){
    //                                     $total_feedback_questions        = $total_feedback_questions_result->total_questions;
    //                                 }
    //                             }
    //                             if ($workshop_session=="POST"){
    //                                 $total_feedback_questions_result = $this->api_model->fetch_post_feedback_questions_count($company_id,$workshop_id,$user_id,$this->atomdb);
    //                                 if (count((array)$total_feedback_questions_result)>0){
    //                                     $total_feedback_questions        = $total_feedback_questions_result->total_questions;
    //                                 }
    //                             }
    //                             //GET TOTAL QUESTIONS TO BE FIERED.
    //                             $total_questions        = $total_feedback_questions;

    //                             $total_atom_feedback    = $this->api_model->fetch_atom_feedback_count($company_id,$workshop_id,$user_id,$workshop_session,$this->atomdb);
    //                             if (count((array)$total_atom_feedback)>0){
    //                                 $total_feedback_played  = $total_atom_feedback->total;
    //                             }

    //                             $total_played    = $total_feedback_played;
    //                             $totalLefttoPlay = ($total_questions - $total_played);




    //                             $where_clause = array(
    //                                 'workshop_id'      => $workshop_id,
    //                                 'user_id'          => $user_id,
    //                                 'workshop_session' => $workshop_session,
    //                             );
    //                             $workshop_registered_users_results = $this->api_model->fetch_record('workshop_registered_users',$where_clause,$this->atomdb);
    //                             $all_questions_fired = $workshop_registered_users_results->all_questions_fired;
    //                             $all_feedbacks_fired = $workshop_registered_users_results->all_feedbacks_fired;

    //                             if ($all_questions_fired==0 OR $all_feedbacks_fired==0){
    //                                 if ($workshop_session=="PRE"){
    //                                     $feedback_result =  $this->api_model->fetch_pre_feedback_questions($company_id,$workshop_id,$user_id,$questions_order,$this->atomdb);
    //                                 }
    //                                 if ($workshop_session=="POST"){
    //                                     $feedback_result =  $this->api_model->fetch_post_feedback_questions($company_id,$workshop_id,$user_id,$questions_order,$this->atomdb);
    //                                 }
    //                                 $question_count =  count((array)$feedback_result);
    //                                 if ($question_count>0){
    //                                     foreach ($feedback_result as $question_row) {
    //                                         $question_id         = $question_row->id;
    //                                         $feedbackset_id      = $question_row->feedbackset_id;
    //                                         $feedback_type_id    = $question_row->type_id;
    //                                         $feedback_subtype_id = $question_row->subtype_id;
    //                                         $question_title      = $question_row->question_title;
    //                                         $option_a            = $question_row->option_a;
    //                                         $option_b            = $question_row->option_b;
    //                                         $option_c            = $question_row->option_c;
    //                                         $option_d            = $question_row->option_d;
    //                                         $option_e            = $question_row->option_e;
    //                                         $option_f            = $question_row->option_f;
    //                                         $weight_a            = $question_row->weight_a;
    //                                         $weight_b            = $question_row->weight_b;
    //                                         $weight_c            = $question_row->weight_c;
    //                                         $weight_d            = $question_row->weight_d;
    //                                         $weight_e            = $question_row->weight_e;
    //                                         $weight_f            = $question_row->weight_f;
    //                                         $multiple_allow      = $question_row->multiple_allow;
    //                                         $feedback_set_title  = $question_row->feedback_set_title;
    //                                         $type                = $question_row->type;
    //                                         $sub_type            = $question_row->sub_type;
    //                                         $qset_powered_by     = $question_row->powered_by;
    //                                         $company_name        = $question_row->company_name;
    //                                         $questionset_timer   = $question_row->timer;
    //                                         $tip                 = $question_row->tip;
    //                                         $hint_image          = $question_row->hint_image;
    //                                         $question_type       = $question_row->question_type;
    //                                         $min_length          = $question_row->min_length;
    //                                         $max_length          = $question_row->max_length;
    //                                         $question_timer      = $question_row->question_timer;
    //                                         $text_weightage      = $question_row->text_weightage;
    //                                     }

    //                                     $feedback_hint_image ='';
    //                                     if ($hint_image!=''){
    //                                         $file_check_path = "../".$portal_name."/assets/uploads/feedback_questions/".$hint_image;
    //                                         $file_path = "/assets/uploads/feedback_questions/".$hint_image;
    //                                         if (file_exists($file_check_path)){
    //                                             $feedback_hint_image = $domin_url.$file_path;     
    //                                         }                               
    //                                     } 

    //                                     $json = array(
    //                                         'success'             => true,
    //                                         'message'             => "",
    //                                         'is_wq_fq'            => 'FEEDBACK_QUESTION',
    //                                         'company_id'          => $company_id,
    //                                         'user_id'             => $user_id,
    //                                         'workshop_id'         => $workshop_id,
    //                                         'workshop_session'    => $workshop_session,
    //                                         'feedbackset_id'      => $feedbackset_id,
    //                                         'feedback_type_id'    => $feedback_type_id,
    //                                         'feedback_subtype_id' => $feedback_subtype_id,
    //                                         'feedback_id'         => $question_id,
    //                                         'workshop_name'       => $workshop_name,
    //                                         'question_title'      => $question_title,
    //                                         'option_a'            => $option_a,
    //                                         'option_b'            => $option_b,
    //                                         'option_c'            => $option_c,
    //                                         'option_d'            => $option_d,
    //                                         'option_e'            => $option_e,
    //                                         'option_f'            => $option_f,
    //                                         'weight_a'            => $weight_a,
    //                                         'weight_b'            => $weight_b,
    //                                         'weight_c'            => $weight_c,
    //                                         'weight_d'            => $weight_d,
    //                                         'weight_e'            => $weight_e,
    //                                         'weight_f'            => $weight_f,
    //                                         'tip'                 => $tip,
    //                                         'hint_image'          => $feedback_hint_image,
    //                                         'question_type'       => $question_type,
    //                                         'min_length'          => $min_length,
    //                                         'max_length'          => $max_length,
    //                                         'question_timer'      => $question_timer,
    //                                         'text_weightage'      => $text_weightage,
    //                                         'company_name'        => $company_name,
    //                                         'feedback_set_title'  => $feedback_set_title,
    //                                         'type'                => $type,
    //                                         'sub_type'            => $sub_type,
    //                                         'powered_by'          => ($qset_powered_by!='')?$qset_powered_by:$workshop_powered_by,
    //                                         'timer'               => ($questionset_timer!='' AND $questionset_timer>0)?$questionset_timer:$workshop_timer,
    //                                         'multiplier'          => $point_multiplier,
    //                                         'total_questions'     => $total_questions,
    //                                         'total_played'        => $total_played,
    //                                         'multiple_allow'      => $multiple_allow,
    //                                         'company_logo'        => $company_path,
    //                                         'server_date_time'    => date('Y-m-d H:i:s')
    //                                     );
    //                                 }else{
    //                                     //UPDATE THE STAUTS TO KNOW ALL WORKSHOP & FEEDBACK QUESTION HAS BEEN FIERED. 
    //                                     $wrkover_updt_data = array(
    //                                         'all_questions_fired' => 1,
    //                                         'all_feedbacks_fired' => 1
    //                                     );
    //                                     $where_clause = array(
    //                                         'user_id'           => $user_id,
    //                                         'workshop_id'       => $workshop_id,
    //                                         'workshop_session'  => $workshop_session
    //                                     ); 
    //                                     $wrkover_update_status = $this->api_model->update('workshop_registered_users', $where_clause, $wrkover_updt_data,$this->atomdb);

    //                                     //CUSTOMIZED FOR ELP
    //                                     // if ((int)$webauto_login==1){
    //                                     //     $internship_update_status = $this->api_model->update_internship($user_id,$workshop_id,$this->atomdb);
    //                                     // }
    //                                     $json = array(
    //                                         'success' => false,
    //                                         'message' =>  "WORKSHOP_COMPLETED"
    //                                     );
    //                                 }
    //                             }else{
    //                                 $json = array(
    //                                     'success' => false,
    //                                     'message' =>  "WORKSHOP_COMPLETED"
    //                                 );
    //                             }
    //                         }else{
    //                             $json = array(
    //                                 'success' => false,
    //                                 'message' =>  "Workshop doesnot have question set type i.e question set or feedback set"
    //                             );
    //                         }
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' =>  "Workshop is inactive or deleted. Please contact administrator"
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Invalid Token"
    //                     );
    //                 }
    //             }catch(Exception $e){
    //                 $json = array(
    //                     'success' => false,
    //                     'message' =>  $e->getMessage()
    //                 );
    //             }
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  "Workshop id Or Session is missing."
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'otp_pending' => '',
    //             'otc_pending' => '',
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }  
    // public function update_quiz_result(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");
    //         if ($workshop_id!=='' AND $workshop_session!==''){
    //             try{
    //                 $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //                 $common_user_id = $JWTVerify->user_id;
    //                 $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //                 $user_id    = 0;
    //                 $company_id = 0;
    //                 $where_clause = array(
    //                     'id'           => $common_user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 
    //                 $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //                 if (count((array)$common_user_details)>0){
    //                     $user_id = $common_user_details->user_id;
    //                 }

    //                 $where_clause = array(
    //                     'user_id'      => $user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 

    //                 //FIND THE COMPANY OF USER
    //                 $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //                 if (count((array)$user_details)>0){
    //                     $company_id   = $user_details->company_id;

    //                     $where_clause = array(
    //                         'company_id'       => $company_id,
    //                         'user_id'          => $user_id,
    //                         'workshop_id'      => $workshop_id,
    //                         'workshop_session' => $workshop_session,
    //                         'questionset_id'   => $this->input->post('questionset_id'),
    //                         'trainer_id'       => $this->input->post('trainer_id'),
    //                         'topic_id'         => $this->input->post('topic_id'),
    //                         'subtopic_id'      => $this->input->post('subtopic_id'),
    //                         'question_id'      => $this->input->post('question_id'),
    //                     ); 
    //                     $atom_result_exists = $this->api_model->fetch_results('atom_results',$where_clause,$this->atomdb);
    //                     if (count((array)$atom_result_exists)>0){
    //                         //ALREADY EXISTS IN DATABASE
    //                     }else{
    //                         $data = array(
    //                             'company_id'       => $company_id,
    //                             'user_id'          => $user_id,
    //                             'workshop_id'      => $workshop_id,
    //                             'workshop_session' => $workshop_session,
    //                             'questionset_id'   => $this->input->post('questionset_id'),
    //                             'trainer_id'       => $this->input->post('trainer_id'),
    //                             'topic_id'         => $this->input->post('topic_id'),
    //                             'subtopic_id'      => $this->input->post('subtopic_id'),
    //                             'question_id'      => $this->input->post('question_id'),
    //                             'start_dttm'       => $this->input->post('start_dttm'),
    //                             'end_dttm'         => $this->input->post('end_dttm'),
    //                             'seconds'          => $this->input->post('seconds'),
    //                             'option_clicked'   => $this->input->post('option_clicked'),
    //                             'correct_answer'   => $this->input->post('correct_answer'),
    //                             'is_correct'       => $this->input->post('is_correct'),
    //                             'is_wrong'         => $this->input->post('is_wrong'),
    //                             'is_timeout'       => $this->input->post('is_timeout'),
    //                             'multiplier'       => $this->input->post('multiplier'),
    //                             'weight'           => $this->input->post('weight'),
    //                             'timer'            => $this->input->post('timer')
    //                         );
    //                         $this->api_model->insert('atom_results',$data,$this->atomdb);
    //                     }

    //                     $json = array(
    //                         'success' => true,
    //                         'message' =>  "Awarathon Quiz Result Updated Successfully."
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Invalid Token"
    //                     );
    //                 }
    //             }catch(Exception $e){
    //                 $json = array(
    //                     'success' => false,
    //                     'message' =>  $e->getMessage()
    //                 );
    //             }
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  "Workshop id Or Session is missing."
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }  
    // public function update_feedback_result(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");
    //         if ($workshop_id!=='' AND $workshop_session!==''){
    //             try{
    //                 $JWTVerify = $this->jwt->decode($payload, $server_key);
    //                 $common_user_id = $JWTVerify->user_id;
    //                 $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //                 $user_id    = 0;
    //                 $company_id = 0;
    //                 $where_clause = array(
    //                     'id'           => $common_user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 
    //                 $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //                 if (count((array)$common_user_details)>0){
    //                     $user_id = $common_user_details->user_id;
    //                 }

    //                 $where_clause = array(
    //                     'user_id'      => $user_id,
    //                     'status'       => 1,
    //                     'block'        => 0,
    //                     'otp_verified' => 1
    //                 ); 

    //                 //FIND THE COMPANY OF USER
    //                 $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //                 if (count((array)$user_details)>0){
    //                     $company_id   = $user_details->company_id;

    //                     $where_clause = array(
    //                         'company_id'          => $company_id,
    //                         'user_id'             => $user_id,
    //                         'workshop_id'         => $workshop_id,
    //                         'workshop_session'    => $workshop_session,
    //                         'feedbackset_id'      => $this->input->post('feedbackset_id'),
    //                         'feedback_type_id'    => $this->input->post('feedback_type_id'),
    //                         'feedback_subtype_id' => $this->input->post('feedback_subtype_id'),
    //                         'feedback_id'         => $this->input->post('feedback_id'),
    //                     ); 
    //                     $atom_feedback_exists = $this->api_model->fetch_results('atom_feedback',$where_clause,$this->atomdb);
    //                     if (count((array)$atom_feedback_exists)>0){
    //                         //ALREADY EXISTS IN DATABASE
    //                     }else{
    //                         $data = array(
    //                             'company_id'          => $company_id,
    //                             'user_id'             => $user_id,
    //                             'workshop_id'         => $workshop_id,
    //                             'workshop_session'    => $workshop_session,
    //                             'feedbackset_id'      => $this->input->post('feedbackset_id'),
    //                             'feedback_type_id'    => $this->input->post('feedback_type_id'),
    //                             'feedback_subtype_id' => $this->input->post('feedback_subtype_id'),
    //                             'feedback_id'         => $this->input->post('feedback_id'),
    //                             'start_dttm'          => $this->input->post('start_dttm'),
    //                             'end_dttm'            => $this->input->post('end_dttm'),
    //                             'option_a'            => $this->input->post('option_a'),
    //                             'weight_a'            => $this->input->post('weight_a'),
    //                             'option_b'            => $this->input->post('option_b'),
    //                             'weight_b'            => $this->input->post('weight_b'),
    //                             'option_c'            => $this->input->post('option_c'),
    //                             'weight_c'            => $this->input->post('weight_c'),
    //                             'option_d'            => $this->input->post('option_d'),
    //                             'weight_d'            => $this->input->post('weight_d'),
    //                             'option_e'            => $this->input->post('option_e'),
    //                             'weight_e'            => $this->input->post('weight_e'),
    //                             'option_f'            => $this->input->post('option_f'),
    //                             'weight_f'            => $this->input->post('weight_f'),
    //                             'seconds'             => $this->input->post('seconds'),
    //                             'timer'               => $this->input->post('timer'),
    //                             'is_timeout'          => $this->input->post('is_timeout'),
    //                             'multiplier'          => $this->input->post('multiplier'),
    //                             'question_type'       => $this->input->post('question_type'),
    //                             'weightage'           => $this->input->post('text_weightage'),
    //                             'feedback_answer'     => $this->input->post('feedback_answer')
    //                         );
    //                         $this->api_model->insert('atom_feedback',$data,$this->atomdb);
    //                     }
    //                     $json = array(
    //                         'success' => true,
    //                         'message' =>  "Awarathon Feedback Result Updated Successfully."
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Invalid Token"
    //                     );
    //                 }
    //             }catch(Exception $e){
    //                 $json = array(
    //                     'success' => false,
    //                     'message' =>  $e->getMessage()
    //                 );
    //             }
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  "Workshop id Or Session is missing."
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function atom_agreement(){
    //     $data         = array();
    //     $json         = array();

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $payload    = $this->input->post('payload');
    //         $terms_name = $this->input->post('terms_name');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 

    //             //FIND THE COMPANY OF USER
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){

    //                 $where_clause = array(
    //                     'terms_name' => $terms_name
    //                 ); 
    //                 $atom_agreement = $this->api_model->fetch_record('atom_agreement',$where_clause);

    //                 if (count((array)$atom_agreement)>0){
    //                     $agreement = $atom_agreement->remarks;
    //                     $url       = $atom_agreement->url;
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => $agreement,
    //                         'url'     => $url
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => '',
    //                         'url'     => ''
    //                     );
    //                 }

    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' =>  "Invalid Token"
    //                 );
    //             }

    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);

    // }
    // public function workshops_over_score(){
    //     $data     = array();
    //     $json     = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");
    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id       = $user_details->company_id;
    //                 $correct          = 0;
    //                 $wrong            = 0;
    //                 $time_out         = 0;
    //                 $preference_count = 0;
    //                 $workshop_score_result = $this->api_model->fetch_completed_score(
    //                                                 $company_id,
    //                                                 $user_id,
    //                                                 $workshop_id,
    //                                                 $workshop_session,
    //                                                 $this->atomdb);
    //                 if (count((array)$workshop_score_result)>0){
    //                     foreach ($workshop_score_result as $workshop_score) {
    //                         $correct  = $workshop_score->correct;
    //                         $wrong    = $workshop_score->wrong;
    //                         $time_out = $workshop_score->time_out;
    //                     }
    //                 }
    //                 $total = $correct + $wrong + $time_out;
    //                 // $selfavg = ($correct * 100/$total);
    //                 $wc_over_message = "Great Job!";
    //                 $show_namaste_image = 0;
    //                 // BSE QUIZ CUSTOMIZE
    //                 // if ($selfavg >=80 ){
    //                 //     $show_namaste_image = 0;
    //                 //     $wc_over_message = "Congratulations you are a winner";
    //                 // }else{
    //                 //     $show_namaste_image = 1;
    //                 //     $wc_over_message = "Thank you for participating";
    //                 // }

    //                 $feedback_score_result = $this->api_model->fetch_atom_feedback_count(
    //                                                 $company_id,
    //                                                 $workshop_id,
    //                                                 $user_id,
    //                                                 $workshop_session,
    //                                                 $this->atomdb);
    //                 if (count((array)$feedback_score_result)>0){
    //                     $preference_count  = $feedback_score_result->total;
    //                 }
    //                 $json = array(
    //                     'success'            => true,
    //                     'message'            => "Workshop final score.",
    //                     'wc_over_message'    => $wc_over_message,
    //                     'show_namaste_image' => $show_namaste_image,
    //                     'score_correct'      => $correct,
    //                     'score_wrong'        => ($wrong+$time_out),
    //                     'score_time_out'     => $time_out,
    //                     'score_preference'   => $preference_count
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }    
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function fetch_information_form(){
    //     // fetch_information_form
    //     $data             = array();
    //     $form_header_data = array();
    //     $json             = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $form_id          = $this->input->post('form_id');
    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");
    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id = $user_details->company_id;
    //                 $iForm      = $this->api_model->fetch_information_form($company_id,$form_id,$this->atomdb);

    //                 //CHECK USER IS REGISTERED FOR WORKSHOP.
    //                 $otp_verified = 'N';
    //                 $where_clause = array(
    //                     'user_id'          => $user_id,
    //                     'workshop_id'      => $workshop_id,
    //                     'workshop_session' => $workshop_session
    //                 ); 
    //                 $workshop_registered_users_count = $this->api_model->record_count('workshop_registered_users',$where_clause,$this->atomdb);
    //                 if ($workshop_registered_users_count>0){
    //                     $otp_verified = 'Y';
    //                 }

    //                 $rid = 0;
    //                 if (count((array)$iForm)>0){
    //                     foreach ($iForm as $iRow) {
    //                         $form_header_id     = $iRow->form_header_id;
    //                         $form_name          = $iRow->form_name;
    //                         $short_description  = $iRow->short_description;
    //                         $form_detail_id     = $iRow->form_detail_id;
    //                         $field_name         = $iRow->field_name;
    //                         $field_display_name = $iRow->field_display_name;
    //                         $field_type         = $iRow->field_type;
    //                         $default_value      = $iRow->default_value;
    //                         $is_required        = $iRow->is_required;

    //                         $temp_field_name = 'field_'.$form_detail_id;
    //                         if ($rid==0){
    //                             array_push($form_header_data, array(
    //                                 'form_header_id'    => $form_header_id,
    //                                 'form_name'         => $form_name,
    //                                 'short_description' => $short_description
    //                             ));
    //                         }
    //                         $dropdownArray = explode(",",$default_value);
    //                         array_push($data, array(
    //                             'form_detail_id'     => $form_detail_id,
    //                             'field_name'         => $temp_field_name,
    //                             'field_display_name' => $field_display_name,
    //                             'field_type'         => $field_type,
    //                             'default_value'      => $dropdownArray,
    //                             'is_required'        => $is_required,
    //                             $temp_field_name     => ''
    //                         ));
    //                         $rid++;
    //                     }
    //                     $json = array(
    //                         'success'      => true,
    //                         'message'      => "Information form generated",
    //                         'otp_verified' => $otp_verified,   
    //                         'form_header'  => $form_header_data,
    //                         'form_details' => $data
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "Information form details not found"
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }    
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function update_information_form(){
    //     $data     = array();
    //     $json     = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $form_header      = $this->input->post('form_header');
    //         $form_details     = $this->input->post('form_details');
    //         $form_values      = $this->input->post('form_values');
    //         $server_key  = 'awar@thon';
    //         $this->load->library("JWT");
    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id       = $user_details->company_id;
    //                 foreach($form_header as $hForm){
    //                     $form_id           = $hForm['form_header_id'];
    //                     $form_name         = $hForm['form_name'];
    //                     $short_description = $hForm['short_description'];
    //                 }

    //                 foreach($form_details as $dForm){
    //                     $form_detail_id = $dForm['form_detail_id'];
    //                     $field_name     = $dForm['field_name'];
    //                     $field_value    = $form_values[$field_name];

    //                     $where_clause = $data =  array(
    //                         'company_id'      => $company_id,
    //                         'user_id'         => $user_id,
    //                         'workshop_id'     => $workshop_id,
    //                         'form_header_id'  => $form_id,
    //                         'form_detail_id'  => $form_detail_id
    //                     );
    //                     $infoFound = $this->api_model->record_count('feedback_form_data',$where_clause,$this->atomdb);
    //                     if ($infoFound>0){
    //                         $data =  array(
    //                             'field_value'     => $field_value,
    //                             'submission_dttm' => date('Y-m-d H:i:s')
    //                         );
    //                         $this->api_model->update('feedback_form_data',$where_clause,$data,$this->atomdb);
    //                     }else{
    //                         $data =  array(
    //                             'company_id'      => $company_id,
    //                             'user_id'         => $user_id,
    //                             'workshop_id'     => $workshop_id,
    //                             'form_header_id'  => $form_id,
    //                             'form_detail_id'  => $form_detail_id,
    //                             'field_value'     => $field_value,
    //                             'submission_dttm' => date('Y-m-d H:i:s')
    //                         );
    //                         $infoId = $this->api_model->insert('feedback_form_data',$data,$this->atomdb);
    //                     }
    //                 }
    //                 $json = array(
    //                     'success' => true,
    //                     'message' => "Information Form Updated Successfully"
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }    
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);    
    // }
    // public function graph_user_topic_subtopic_accuracy(){
    //     $data     = array();
    //     $json     = array(); 

    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST            = json_decode($_jsonObj, TRUE);
    //         $payload          = $this->input->post('payload');
    //         $workshop_id      = $this->input->post('workshop_id');
    //         $workshop_session = $this->input->post('workshop_session');
    //         $graph_type       = $this->input->post('graph_type');
    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");
    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id       = $user_details->company_id;
    //                 if ($workshop_session=='PRE'){
    //                     if ($graph_type=='live'){
    //                         $graphResult =  $this->api_model->fetch_wksh_user_topic_subtopic_pre_accuracy_live($company_id,$user_id,$workshop_id,$this->atomdb);
    //                         $overallscoreResult = $this->api_model->fetch_wksh_user_pre_rank_live($company_id,$user_id,$workshop_id,$this->atomdb);
    //                     }else{
    //                         $graphResult =  $this->api_model->fetch_wksh_user_topic_subtopic_pre_accuracy($company_id,$user_id,$workshop_id,$this->atomdb);
    //                         $overallscoreResult = $this->api_model->fetch_wksh_user_pre_rank($company_id,$user_id,$workshop_id,$this->atomdb);
    //                     }
    //                 }
    //                 if ($workshop_session=='POST'){
    //                     if ($graph_type=='live'){
    //                         $graphResult =  $this->api_model->fetch_wksh_user_topic_subtopic_post_accuracy_live($company_id,$user_id,$workshop_id,$this->atomdb);
    //                         $overallscoreResult = $this->api_model->fetch_wksh_user_post_rank_live($company_id,$user_id,$workshop_id,$this->atomdb);
    //                     }else{
    //                         $graphResult =  $this->api_model->fetch_wksh_user_topic_subtopic_post_accuracy($company_id,$user_id,$workshop_id,$this->atomdb);
    //                         $overallscoreResult = $this->api_model->fetch_wksh_user_post_rank($company_id,$user_id,$workshop_id,$this->atomdb);
    //                     }
    //                 }
    //                 if (count((array)$graphResult)>0){
    //                     $dataset    = array();
    //                     $categories = array();
    //                     $wksh_accuracy= 0;
    //                     $wksh_rank = 0;
    //                     foreach ($graphResult as $graphRow) {
    //                         $topic_name    = $graphRow->topic_name;
    //                         $subtopic_name = $graphRow->subtopic_name;
    //                         $accuracy      = $graphRow->average;
    //                         $dataset[]     = $accuracy; 
    //                         if ($subtopic_name==''){
    //                             $categories[] .= $topic_name;
    //                         }else{
    //                             $categories[] .= $topic_name.'-'.$subtopic_name;
    //                         }

    //                     }
    //                     if (count((array)$overallscoreResult)>0){
    //                         foreach ($overallscoreResult as $scoreRow) {
    //                             $wksh_accuracy = $scoreRow->average;
    //                             $wksh_rank     = $scoreRow->rank;
    //                             if ($wksh_accuracy!='' AND $wksh_accuracy>0){
    //                                 $wksh_accuracy = $wksh_accuracy."%";
    //                             }
    //                         }
    //                     }
    //                     $json = array(
    //                         'dataset'       => json_encode($dataset,JSON_NUMERIC_CHECK),
    //                         'categories'    => json_encode($categories),
    //                         'wksh_accuracy' => $wksh_accuracy,
    //                         'wksh_rank'     => $wksh_rank,
    //                         'success'       => true,
    //                         'message'       => "Graph result loaded successfully",
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "NO_RESULT"
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }    
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);     
    // }

    // // public function workshop(){
    // //     $workshop_array = $this->api_model->fetch_workshop();
    // //     echo json_encode($workshop_array);
    // // }
    // public function feedback(){
    //     $feedback_array = $this->api_model->fetch_feedback();
    //     echo json_encode($feedback_array);
    // }
    // public function advertisement(){
    //     $advertisement_array = $this->api_model->fetch_advertisement();
    //     echo json_encode($advertisement_array);
    // }
    // public function video_assessment_list(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $where_clause = array(
    //                     'status'      => 1
    //                 ); 
    //                 $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //                 $video_assessment_result = $this->api_model->fetch_video_assessment($user_id,$this->atomdb);
    //                 if (count((array)$video_assessment_result)>0) {
    //                     foreach ($video_assessment_result as $va_row) {
    //                         $assessment_id          = $va_row->id;
    //                         $assessment_name        = $va_row->assessment;
    //                         $code                   = $va_row->code;
    //                         $description            = $va_row->description;
    //                         $assessment_type        = $va_row->assessment_type;
    //                         $assessment_type_name   = $va_row->assessment_type_name;
    //                         $assessment_start_date  = $va_row->start_date;
    //                         $assessment_end_date    = $va_row->end_date;
    //                         $instruction            = $va_row->instruction;
    //                         $number_attempts        = $va_row->number_attempts;
    //                         $assessment_attempts    = $va_row->assessment_attempts;
    //                         $otp_verified           = (is_null($code) OR $code=='')?1:$va_row->otp_verified;
    //                         $is_completed           = (is_null($va_row->is_completed) OR $va_row->is_completed=='')?0:$va_row->is_completed;
    //                         $total_question         = 0;
    //                         $total_time             = 0;
    //                         $assessment_time_result = $this->api_model->fetch_assessment_total_time($assessment_id,$this->atomdb); 
    //                         if (count((array)$assessment_time_result)>0) {
    //                             foreach ($assessment_time_result as $atr_row) {
    //                                 $total_question = $atr_row->total_question;
    //                                 $total_time     = $atr_row->total_time;
    //                             }
    //                         }
    //                         $is_situation   = $va_row->is_situation;

    //                         $video_uploaded_count = 0;
    //                         $total_uploaded =$this->api_model->fetch_assessment_total_uploaded_videos_count($company_id,$user_id,$assessment_id,$this->atomdb);
    //                         if (count((array)$total_uploaded)>0){
    //                             $video_uploaded_count = count((array)$total_uploaded);
    //                         }
    //                         array_push($data, array(
    //                             'assessment_id'         => $assessment_id,
    //                             'assessment_name'       => $assessment_name,
    //                             'otp'                   => $code,
    //                             'read_more'             => $description,
    //                             'assessment_type'       => $assessment_type,
    //                             'assessment_type_name'  => strtoupper($assessment_type_name),
    //                             'assessment_start_date' => $assessment_start_date,
    //                             'assessment_end_date'   => $assessment_end_date,
    //                             'instruction'           => $instruction,
    //                             'attempts_allowed'      => $number_attempts,
    //                             'total_attempts'        => $assessment_attempts,
    //                             'is_situation'          => $is_situation==1?'Situation Based':'Q&A Based',
    //                             'total_question'        => $total_question,
    //                             'total_time'            => $total_time,
    //                             'otp_verified'          => $otp_verified,
    //                             'is_completed'          => $is_completed,
    //                             'assessment_status'     => '',
    //                             'video_uploaded_count'  => $video_uploaded_count,
    //                         ));
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'                           => $data,
    //                     'vimeo_credentials'              => $vimeo_credentials,
    //                     'success'                        => true,
    //                     'message'                        => 'Assessment data loaded successfully.',
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success'                        => false,
    //                     'message'                        => "Invalid Token",
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success'                        => false,
    //                 'message'                        => $e->getMessage(),
    //                 'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                 'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                 'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                 'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                 'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                 'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                 'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                 'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                 'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                 'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                 'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                 'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                 'VIMEO_CALLBACK_TIMER'           => 20000,
    //                 'PBWRAPPER_BG'                   => '#999999',
    //                 'PBFONT_CLR'                     => '#ffffff',
    //                 'PBU_BG'                         => '#349b82',
    //                 'PBC_BG'                         => '#ff7739',
    //                 'PBF_BG'                         => '#f85252',
    //                 'PBS_BG'                         => '#349b82',
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'                        => false,
    //             'message'                        => "Unable to post data either it is empty or not set.",
    //             'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //             'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //             'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //             'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //             'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //             'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //             'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //             'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //             'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //             'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //             'PENDING_POPUP_MESSAGE_SUB'      => '',
    //             'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //             'VIMEO_CALLBACK_TIMER'           => 20000,
    //             'PBWRAPPER_BG'                   => '#999999',
    //             'PBFONT_CLR'                     => '#ffffff',
    //             'PBU_BG'                         => '#349b82',
    //             'PBC_BG'                         => '#ff7739',
    //             'PBF_BG'                         => '#f85252',
    //             'PBS_BG'                         => '#349b82',
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function video_assessment_list_ftp(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $where_clause = array(
    //                     'status'      => 1
    //                 ); 
    //                 $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //                 $video_assessment_result = $this->api_model->fetch_video_assessment_ftp($user_id,$this->atomdb);
    //                 if (count((array)$video_assessment_result)>0) {
    //                     foreach ($video_assessment_result as $va_row) {
    //                         $assessment_id          = $va_row->id;
    //                         $assessment_name        = $va_row->assessment;
    //                         $code                   = $va_row->code;
    //                         $description            = $va_row->description;
    //                         $assessment_type        = $va_row->assessment_type;
    //                         $assessment_type_name   = $va_row->assessment_type_name;
    //                         $assessment_start_date  = $va_row->start_date;
    //                         $assessment_end_date    = $va_row->end_date;
    //                         $instruction            = $va_row->instruction;
    //                         $number_attempts        = $va_row->number_attempts;
    //                         $assessment_attempts    = $va_row->assessment_attempts;
    //                         $otp_verified           = (is_null($code) OR $code=='')?1:$va_row->otp_verified;
    //                         $is_completed           = (is_null($va_row->is_completed) OR $va_row->is_completed=='')?0:$va_row->is_completed;
    //                         $ftpto_vimeo_uploaded   = (is_null($va_row->ftpto_vimeo_uploaded) OR $va_row->ftpto_vimeo_uploaded=='')?0:$va_row->ftpto_vimeo_uploaded;
    //                         $total_question         = 0;
    //                         $total_time             = 0;

    //                         if ((int)$assessment_attempts >= (int)$number_attempts){
    //                             $assessment_attempts = $number_attempts;
    //                         }

    //                         $assessment_time_result = $this->api_model->fetch_assessment_total_time($assessment_id,$this->atomdb); 
    //                         if (count((array)$assessment_time_result)>0) {
    //                             foreach ($assessment_time_result as $atr_row) {
    //                                 $total_question = $atr_row->total_question;
    //                                 $total_time     = $atr_row->total_time;
    //                             }
    //                         }
    //                         $is_situation   = $va_row->is_situation;

    //                         $video_uploaded_count = 0;
    //                         $video_uploaded_count_ftp = 0;
    //                         $total_uploaded =$this->api_model->fetch_assessment_total_uploaded_videos_count($company_id,$user_id,$assessment_id,$this->atomdb);
    //                         $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($company_id,$user_id,$assessment_id,$this->atomdb);
    //                         if (count((array)$total_uploaded)>0){
    //                             $video_uploaded_count = count((array)$total_uploaded);
    //                         }
    //                         if (count((array)$total_uploaded_ftp)>0){
    //                             $video_uploaded_count_ftp = count((array)$total_uploaded_ftp);
    //                         }
    //                         array_push($data, array(
    //                             'assessment_id'            => $assessment_id,
    //                             'assessment_name'          => $assessment_name,
    //                             'otp'                      => $code,
    //                             'read_more'                => $description,
    //                             'assessment_type'          => $assessment_type,
    //                             'assessment_type_name'     => strtoupper($assessment_type_name),
    //                             'assessment_start_date'    => $assessment_start_date,
    //                             'assessment_end_date'      => $assessment_end_date,
    //                             'instruction'              => $instruction,
    //                             'attempts_allowed'         => $number_attempts,
    //                             'total_attempts'           => $assessment_attempts,
    //                             'is_situation'             => $is_situation==1?'Situation Based':'Q&A Based',
    //                             'total_question'           => $total_question,
    //                             'total_time'               => $total_time,
    //                             'otp_verified'             => $otp_verified,
    //                             'is_completed'             => $is_completed,
    //                             'ftpto_vimeo_uploaded'     => $ftpto_vimeo_uploaded,
    //                             'assessment_status'        => '',
    //                             'video_uploaded_count'     => $video_uploaded_count,
    //                             'video_uploaded_count_ftp' => $video_uploaded_count_ftp,
    //                         ));
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'                           => $data,
    //                     'vimeo_credentials'              => $vimeo_credentials,
    //                     'success'                        => true,
    //                     'message'                        => 'Assessment data loaded successfully.',
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success'                        => false,
    //                     'message'                        => "Invalid Token",
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success'                        => false,
    //                 'message'                        => $e->getMessage(),
    //                 'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                 'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                 'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                 'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                 'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                 'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                 'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                 'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                 'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                 'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                 'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                 'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                 'VIMEO_CALLBACK_TIMER'           => 20000,
    //                 'PBWRAPPER_BG'                   => '#999999',
    //                 'PBFONT_CLR'                     => '#ffffff',
    //                 'PBU_BG'                         => '#349b82',
    //                 'PBC_BG'                         => '#ff7739',
    //                 'PBF_BG'                         => '#f85252',
    //                 'PBS_BG'                         => '#349b82',
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'                        => false,
    //             'message'                        => "Unable to post data either it is empty or not set.",
    //             'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //             'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //             'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //             'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //             'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //             'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //             'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //             'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //             'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //             'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //             'PENDING_POPUP_MESSAGE_SUB'      => '',
    //             'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //             'VIMEO_CALLBACK_TIMER'           => 20000,
    //             'PBWRAPPER_BG'                   => '#999999',
    //             'PBFONT_CLR'                     => '#ffffff',
    //             'PBU_BG'                         => '#349b82',
    //             'PBC_BG'                         => '#ff7739',
    //             'PBF_BG'                         => '#f85252',
    //             'PBS_BG'                         => '#349b82',
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function video_assessment_history_list(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $where_clause = array(
    //                     'status'      => 1
    //                 ); 
    //                 $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //                 $video_assessment_result = $this->api_model->fetch_video_assessment_history($user_id,$this->atomdb);
    //                 if (count((array)$video_assessment_result)>0) {
    //                     foreach ($video_assessment_result as $va_row) {
    //                         $assessment_id          = $va_row->id;
    //                         $assessment_name        = $va_row->assessment;
    //                         $code                   = $va_row->code;
    //                         $description            = $va_row->description;
    //                         $assessment_type        = $va_row->assessment_type;
    //                         $assessment_type_name   = $va_row->assessment_type_name;
    //                         $assessment_start_date  = $va_row->start_date;
    //                         $assessment_end_date    = $va_row->end_date;
    //                         $instruction            = $va_row->instruction;
    //                         $number_attempts        = $va_row->number_attempts;
    //                         $assessment_attempts    = $va_row->assessment_attempts;
    //                         $otp_verified           = (is_null($code) OR $code=='')?1:$va_row->otp_verified;
    //                         $is_completed           = $va_row->is_completed;
    //                         $total_question         = 0;
    //                         $total_time             = 0;
    //                         $assessment_time_result = $this->api_model->fetch_assessment_total_time($assessment_id,$this->atomdb); 
    //                         if (count((array)$assessment_time_result)>0) {
    //                             foreach ($assessment_time_result as $atr_row) {
    //                                 $total_question = $atr_row->total_question;
    //                                 $total_time     = $atr_row->total_time;
    //                             }
    //                         }
    //                         $is_situation   = $va_row->is_situation;
    //                         array_push($data, array(
    //                             'assessment_id'         => $assessment_id,
    //                             'assessment_name'       => $assessment_name,
    //                             'otp'                   => $code,
    //                             'read_more'             => $description,
    //                             'assessment_type'       => $assessment_type,
    //                             'assessment_type_name'  => strtoupper($assessment_type_name),
    //                             'assessment_start_date' => $assessment_start_date,
    //                             'assessment_end_date'   => $assessment_end_date,
    //                             'instruction'           => $instruction,
    //                             'attempts_allowed'      => $number_attempts,
    //                             'total_attempts'        => $assessment_attempts,
    //                             'is_situation'          => $is_situation==1?'Situation Based':'Q&A Based',
    //                             'total_question'        => $total_question,
    //                             'total_time'            => $total_time,
    //                             'otp_verified'          => $otp_verified,
    //                             'is_completed'          => $is_completed
    //                         ));
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'              => $data,
    //                     'vimeo_credentials' => $vimeo_credentials,
    //                     'success'           => true,
    //                     'message'           => 'Assessment data loaded successfully.'
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function video_assessment_history_list_ftp(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;
    //                 $where_clause = array(
    //                     'status'      => 1
    //                 );
    //                 $domin_url    = '';
    //                 $whereclause  = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $domin_url    = $company_data->domin_url;
    //                 }
    //                 $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //                 $video_assessment_result = $this->api_model->fetch_video_assessment_history_ftp($user_id,$this->atomdb);
    //                 if (count((array)$video_assessment_result)>0) {
    //                     foreach ($video_assessment_result as $va_row) {
    //                         $assessment_id          = $va_row->id;
    //                         $assessment_name        = $va_row->assessment;
    //                         $code                   = $va_row->code;
    //                         $description            = $va_row->description;
    //                         $assessment_type        = $va_row->assessment_type;
    //                         $assessment_type_name   = $va_row->assessment_type_name;
    //                         $assessment_start_date  = $va_row->start_date;
    //                         $assessment_end_date    = $va_row->end_date;
    //                         $instruction            = $va_row->instruction;
    //                         $number_attempts        = $va_row->number_attempts;
    //                         $assessment_attempts    = $va_row->assessment_attempts;
    //                         $otp_verified           = (is_null($code) OR $code=='')?1:$va_row->otp_verified;
    //                         $is_completed           = $va_row->is_completed;
    //                         $ftpto_vimeo_uploaded   = $va_row->ftpto_vimeo_uploaded;
    //                         $report_type            = $va_row->report_type;
    //                         $total_question         = 0;
    //                         $total_time             = 0;
    //                         $assessment_time_result = $this->api_model->fetch_assessment_total_time($assessment_id,$this->atomdb); 
    //                         if (count((array)$assessment_time_result)>0) {
    //                             foreach ($assessment_time_result as $atr_row) {
    //                                 $total_question = $atr_row->total_question;
    //                                 $total_time     = $atr_row->total_time;
    //                             }
    //                         }
    //                         $is_situation                  = $va_row->is_situation;
    //                         $total_questions_played        = 0;
    //                         $total_task_completed          = 0;
    //                         $total_manual_rating_completed = 0;
    //                         $total_question_mapped         = 0;
    //                         $show_ai_pdf                   = false;
    //                         $show_manual_pdf               = false;
    //                         $is_schdule_running            = false;    
    //                         $_score_imported               = false;
    //                         $show_reports_flag             = false;
    //                         $pdf_url                       = "";
    //                         $mpdf_url                      = "";
    //                         $cpdf_url                      = "";

    //                         $_total_played_result = $this->api_model->fetch_assessment_video_count($company_id,$assessment_id,$this->atomdb);
    //                         if (count((array)$_total_played_result)>0){
    //                             foreach ($_total_played_result as $totplay) {
    //                                 $total_questions_played = $totplay->total;
    //                             }
    //                         }
    //                         $_tasksc_results = $this->api_model->fetch_assessment_aitask_completed($company_id,$assessment_id,$this->atomdb);
    //                         if (count((array)$_tasksc_results)>0){
    //                             foreach ($_tasksc_results as $tasksch) {
    //                                 $total_task_completed = $tasksch->total;
    //                             }
    //                         }
    //                         $_manualrate_results = $this->api_model->fetch_assessment_manualrating_completed($assessment_id,$user_id,$this->atomdb);
    //                         if (count((array)$_manualrate_results)>0){
    //                             foreach ($_manualrate_results as $manrate) {
    //                                 $total_manual_rating_completed = $manrate->total;
    //                             }
    //                         }
    //                         $_total_question_results = $this->api_model->fetch_assessment_question_count($assessment_id,$this->atomdb);
    //                         if (count((array)$_total_question_results)>0){
    //                             foreach ($_total_question_results as $totque) {
    //                                 $total_question_mapped = $totque->total;
    //                             }
    //                         }
    //                         $_schdule_running_result = $this->api_model->fetch_assessment_task_schedule($assessment_id,$this->atomdb);
    //                         if (count((array)$_schdule_running_result)>0){
    //                             $is_schdule_running = true;                                
    //                         }
    //                         $show_report_result = $this->api_model->fetch_show_report_status($company_id,$assessment_id,$this->atomdb); 
    //                         if (isset($show_report_result) AND count((array)$show_report_result)>0){
    //                             $show_reports_flag = true;
    //                         }
    //                         $_xls_results          = $this->api_model->fetch_assessment_aixls_imported($company_id,$assessment_id,$user_id,$this->atomdb);
    //                         if (count((array)$_xls_results)>0){
    //                             foreach ($_xls_results as $xlsres) {
    //                                 if ((int)$xlsres->total>0){
    //                                     $_score_imported = true;
    //                                 }
    //                             }                               
    //                         }

    //                         // if (((int)$total_questions_played>0) AND ((int)$total_task_completed>0) AND ((int)$total_questions_played == (int)$total_task_completed)) {
    //                         //     $show_ai_pdf = true;
    //                         // }
    //                         // Ajit-27-01-2023-Start
    //                         if (((int)$total_questions_played >= (int)$total_task_completed) AND ((int)$total_task_completed>0)) {
    //                             $show_ai_pdf = true;
    //                         }
    //                         if (((int)$total_task_completed >= (int)$total_questions_played) AND ((int)$total_questions_played>0)) {
    //                             $show_ai_pdf = true;
    //                         }
    //                         // Ajit-27-01-2023-End

    //                         if ((int)$total_question_mapped == (int)$total_manual_rating_completed){
    //                             $show_manual_pdf = true;
    //                         }

    //                         if (($report_type == 1 OR $report_type == 3) AND $show_reports_flag==true AND $show_ai_pdf==true AND $_score_imported==true){
    //                             $pdf_url        = $domin_url.'/pdf/ai/'.$company_id.'/'.$assessment_id.'/'. base64_encode($user_id);
    //                         }
    //                         if (($report_type == 2 OR $report_type == 3) AND $show_reports_flag== true AND $show_manual_pdf==true){
    //                             $mpdf_url        = $domin_url.'/pdf/manual/'.$company_id.'/'.$assessment_id.'/'. base64_encode($user_id);
    //                         }
    //                         if ($report_type == 3 AND  $show_reports_flag==true AND $show_ai_pdf==true AND  $show_manual_pdf==true AND $_score_imported==true){
    //                             $cpdf_url        = $domin_url.'/pdf/combine/'.$company_id.'/'.$assessment_id.'/'. base64_encode($user_id);
    //                         }
    //                         array_push($data, array(
    //                             'assessment_id'         => $assessment_id,
    //                             'assessment_name'       => $assessment_name,
    //                             'otp'                   => $code,
    //                             'read_more'             => $description,
    //                             'assessment_type'       => $assessment_type,
    //                             'assessment_type_name'  => strtoupper($assessment_type_name),
    //                             'assessment_start_date' => $assessment_start_date,
    //                             'assessment_end_date'   => $assessment_end_date,
    //                             'instruction'           => $instruction,
    //                             'attempts_allowed'      => $number_attempts,
    //                             'total_attempts'        => $assessment_attempts,
    //                             'is_situation'          => $is_situation==1?'Situation Based':'Q&A Based',
    //                             'total_question'        => $total_question,
    //                             'total_time'            => $total_time,
    //                             'otp_verified'          => $otp_verified,
    //                             'is_completed'          => $is_completed,
    //                             'ftpto_vimeo_uploaded'  => $ftpto_vimeo_uploaded,
    //                             'pdf_url'               => $pdf_url,
    //                             'mpdf_url'              => $mpdf_url,
    //                             'cpdf_url'              => $cpdf_url,
    //                         ));
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'              => $data,
    //                     'vimeo_credentials' => $vimeo_credentials,
    //                     'success'           => true,
    //                     'message'           => 'Assessment data loaded successfully.'
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }

    // public function video_assessment_demo_instruction(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }
    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $company_id   = $user_details->company_id;

    //                 $assessment_instructions_result = $this->api_model->fetch_assessment_instructions($this->atomdb);
    //                 if (count((array)$assessment_instructions_result)>0) {
    //                     foreach ($assessment_instructions_result as $ai_row) {
    //                         $instruction  = $ai_row->instruction;
    //                         array_push($data, array(
    //                             'instruction' => $instruction
    //                         ));
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'                           => $data,
    //                     'success'                        => true,
    //                     'message'                        => 'Assessment data loaded successfully.',
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success'                        => false,
    //                     'message'                        => "Invalid Token",
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success'                        => false,
    //                 'message'                        => $e->getMessage(),
    //                 'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                 'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                 'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                 'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                 'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                 'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                 'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                 'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                 'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                 'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                 'PENDING_POPUP_MESSAGE_SUB'      => '',

    //                 'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                 'VIMEO_CALLBACK_TIMER'           => 20000,
    //                 'PBWRAPPER_BG'                   => '#999999',
    //                 'PBFONT_CLR'                     => '#ffffff',
    //                 'PBU_BG'                         => '#349b82',
    //                 'PBC_BG'                         => '#ff7739',
    //                 'PBF_BG'                         => '#f85252',
    //                 'PBS_BG'                         => '#349b82',
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'                        => false,
    //             'message'                        => "Unable to post data either it is empty or not set.",
    //             'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //             'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //             'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //             'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //             'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //             'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //             'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //             'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //             'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //             'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //             'PENDING_POPUP_MESSAGE_SUB'      => '',

    //             'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //             'VIMEO_CALLBACK_TIMER'           => 20000,
    //             'PBWRAPPER_BG'                   => '#999999',
    //             'PBFONT_CLR'                     => '#ffffff',
    //             'PBU_BG'                         => '#349b82',
    //             'PBC_BG'                         => '#ff7739',
    //             'PBF_BG'                         => '#f85252',
    //             'PBS_BG'                         => '#349b82',
    //         );
    //     }
    //     echo json_encode($json);  
    // }

    // public function fetch_assessment(){
    //     $data     = array();
    //     $json     = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id      = $user_details->company_id;
    //                 $assessment_id        = $this->input->post('assessment_id');
    //                 $total_offline_played = isset($_POST['total_offline_played'])?$this->input->post('total_offline_played'):0;

    //                 if ($assessment_id!=''){

    //                     $total_numbersof_attempts = 0 ;
    //                     $attempted_count          = 0 ;

    //                     //NO. OF ATTEMPTS ALLOWED.
    //                     $where_clause = array(
    //                         'id'         => $assessment_id,
    //                         'company_id' => $user_company_id,
    //                         'status'     => 1
    //                     ); 
    //                     $assessment_mst_results = $this->api_model->fetch_record('assessment_mst',$where_clause,$this->atomdb);
    //                     if (count((array)$assessment_mst_results)>0){
    //                         $total_numbersof_attempts = $assessment_mst_results->number_attempts;
    //                     }

    //                     //ATTEMPTED COUNT
    //                     $where_clause = array(
    //                         'user_id'       => $user_id,
    //                         'assessment_id' => $assessment_id
    //                     ); 
    //                     $assessment_attempts_results = $this->api_model->fetch_record('assessment_attempts',$where_clause,$this->atomdb);
    //                     if (count((array)$assessment_attempts_results)>0){
    //                         $attempted_count = $assessment_attempts_results->attempts;
    //                     }

    //                     // $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                     $video_assessment_result = $this->api_model->fetch_assessment_question_new($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                     if (count((array)$video_assessment_result)>0) {
    //                         foreach ($video_assessment_result as $va_row) {
    //                             $trans_id        = $va_row->trans_id;
    //                             $question_id     = $va_row->id;
    //                             $question_formate= $va_row->formate;
    //                             $company_id      = $va_row->company_id;
    //                             $assessment_type = $va_row->assessment_type;
    //                             $weightage       = $va_row->weightage;
    //                             $timer           = $va_row->timer;
    //                             $read_timer      = $va_row->read_timer;
    //                             $response_timer  = $va_row->response_timer;
    //                             $question        = $va_row->question;
    //                             $question_path   = $va_row->question_path;
    //                             $instruction     = $va_row->instruction;
    //                             $poweredby       = $va_row->poweredby;
    //                             $domin_url      = $va_row->domin_url;
    //                             array_push($data, array(
    //                                 'trans_id'        => $trans_id,
    //                                 'question_id'     => $question_id,
    //                                 'question_formate'=> $question_formate,
    //                                 'company_id'      => $company_id,
    //                                 'assessment_type' => $assessment_type,
    //                                 'weightage'       => $weightage,
    //                                 'timer'           => $timer,
    //                                 'read_timer'      => $read_timer,
    //                                 'response_timer'  => $response_timer,
    //                                 'question'        => $question,
    //                                 'question_path'   => $question_path,
    //                                 'domin_url'       => $domin_url,
    //                                 'instruction'     => $instruction,
    //                                 'poweredby'       => $poweredby
    //                             ));
    //                         }

    //                         //ATTEMPT SETTELMENT
    //                         if ((int)$attempted_count >= (int)$total_numbersof_attempts){
    //                         }else{
    //                             //TOTAL OFFLINE = TOTAL QUESTION  THEN DONT UPDATE ATTEMPTS IT IS IN PREVIEW MODE. 
    //                             //i.e. OFFLINE QUESTION PLAYED COUNT IS MATCH WITH TOTAL QUESTIONS IN ASSESSMENT.
    //                             if (($total_offline_played>0 AND count((array)$video_assessment_result)>0) AND ($total_offline_played == count((array)$video_assessment_result))){

    //                             }else{
    //                                 //UPDATE USERS ATTEMPTS
    //                                 $where_clause = array(
    //                                     'user_id'       => $user_id,
    //                                     'assessment_id' => $assessment_id
    //                                 ); 
    //                                 $attempts_found =$this->api_model->record_count('assessment_attempts',$where_clause,$this->atomdb);
    //                                 if ($attempts_found>0){
    //                                     $this->api_model->update_attempts('assessment_attempts',$user_id,$assessment_id,$this->atomdb);
    //                                 }else{
    //                                     $jsonAttempts = array(
    //                                         'user_id'       => $user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         'attempts'      => 1,
    //                                         'addeddate'     => date('Y-m-d H:i:s')
    //                                     );
    //                                     $this->api_model->insert('assessment_attempts',$jsonAttempts,$this->atomdb);
    //                                 }
    //                             }
    //                         }
    //                     }

    //                     $total_attempts            = 0;
    //                     $total_attempts_result = $this->api_model->fetch_assessment_attempts($user_id,$assessment_id,$this->atomdb); 
    //                     if (count((array)$total_attempts_result)>0) {
    //                         foreach ($total_attempts_result as $ta_row) {
    //                             $total_attempts     = $ta_row->attempts;
    //                         }
    //                     }

    //                     $json = array(
    //                         'data'           => $data,
    //                         'total_attempts' => $total_attempts,
    //                         'success'        => true,
    //                         'message'        => 'Assessment questions loaded successfully.'
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' => $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function fetch_assessments(){
    //     $data     = array();
    //     $json     = array();  
    //      $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST      = json_decode($_jsonObj, TRUE);
    //          $_POST = json_decode($_jsonObj, TRUE);
    //          $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $question_id        = $this->input->post('question_id');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");




    //             if ($question_id!=''){

    //                     $JWTVerify = $this->jwt->decode($payload, $server_key);
    //                     $common_user_id = $JWTVerify->user_id;
    //                     $this->atomdb   = $this->api_model->connectDb($common_user_id);
    //                     $where_clause = array(
    //                         'id'         => $question_id,
    //                     ); 
    //                     //echo $question_id;
    //                     $assessment_question_results = $this->api_model->fetch_question_formate('assessment_question',$where_clause,$this->atomdb);
    //                     if (count((array)$assessment_question_results)>0){
    //                         $question_format = $assessment_question_results[0]->question_format;
    //                         $question_path = $assessment_question_results[0]->question_path;
    //                     }

    //                     $fetch_domain_url = $this->api_model->fetch_domain_url('company',$this->atomdb);

    //                     $domain_url=isset($fetch_domain_url[0]->domin_url)?$fetch_domain_url[0]->domin_url:"";


    //                     $json = array(
    //                         'question_format'   => $question_format,
    //                         'question_path'     => $question_path,
    //                         'question_id'       => $question_id ,
    //                         'domin_url'         => $domain_url,
    //                         'success'           => true,
    //                         'message'        => 'Assessment questions loaded successfully.'
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "question id is missing."
    //                     );
    //                 }
    //             } 

    //     echo json_encode($json);  
    // }
    // public function fetch_assessment_demo(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $video_assessment_result = $this->api_model->fetch_assessment_demo_question($this->atomdb);
    //                 if (count((array)$video_assessment_result)>0) {
    //                     foreach ($video_assessment_result as $va_row) {
    //                         $trans_id        = 0;
    //                         $question_id     = $va_row->id;
    //                         $company_id      = $va_row->company_id;
    //                         $assessment_type = $va_row->assessment_type;
    //                         $weightage       = $va_row->weightage;
    //                         $read_timer      = $va_row->read_timer;
    //                         $response_timer  = $va_row->response_timer;
    //                         $question        = $va_row->question;
    //                         $instruction     = $va_row->instruction;
    //                         $poweredby       = $va_row->poweredby;
    //                         array_push($data, array(
    //                             'trans_id'        => $trans_id,
    //                             'question_id'     => $question_id,
    //                             'company_id'      => $company_id,
    //                             'assessment_type' => $assessment_type,
    //                             'weightage'       => $weightage,
    //                             'read_timer'      => $read_timer,
    //                             'response_timer'  => $response_timer,
    //                             'question'        => $question,
    //                             'instruction'     => $instruction,
    //                             'poweredby'       => $poweredby
    //                         ));
    //                     }
    //                 }
    //                 $json = array(
    //                     'data'                           => $data,
    //                     'success'                        => true,
    //                     'message'                        => 'Assessment questions loaded successfully.',
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success'                        => false,
    //                     'message'                        => "Invalid Token",
    //                     'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                     'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                     'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                     'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                     'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                     'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                     'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                     'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                     'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                     'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                     'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                     'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                     'VIMEO_CALLBACK_TIMER'           => 20000,
    //                     'PBWRAPPER_BG'                   => '#999999',
    //                     'PBFONT_CLR'                     => '#ffffff',
    //                     'PBU_BG'                         => '#349b82',
    //                     'PBC_BG'                         => '#ff7739',
    //                     'PBF_BG'                         => '#f85252',
    //                     'PBS_BG'                         => '#349b82',
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success'                        => false,
    //                 'message'                        => $e->getMessage(),
    //                 'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //                 'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //                 'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //                 'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //                 'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //                 'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //                 'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //                 'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //                 'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //                 'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //                 'PENDING_POPUP_MESSAGE_SUB'      => '',
    //                 'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //                 'VIMEO_CALLBACK_TIMER'           => 20000,
    //                 'PBWRAPPER_BG'                   => '#999999',
    //                 'PBFONT_CLR'                     => '#ffffff',
    //                 'PBU_BG'                         => '#349b82',
    //                 'PBC_BG'                         => '#ff7739',
    //                 'PBF_BG'                         => '#f85252',
    //                 'PBS_BG'                         => '#349b82',
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'                        => false,
    //             'message'                        => "Unable to post data either it is empty or not set.",
    //             'LOCK_RECORDING_BUTTON_PERIOD'   => 3,
    //             'NO_RECORD_FOUND'                => 'Practice questions not available.',
    //             'INTERNET_DISCONNECTED'          => 'Internet connection does not appear to be available. To continue please check internet connection and try again.',
    //             'INTERNET_HARDWARE_DISCONNECTED' => 'Your internet connection disconnected Or the user disconnected the video and audio sources. Please check your internet connection and click on ok button to attempt this question again.',
    //             'VIMEO_TRANSCODING_STUCK'        => 'We are almost there. We are unable to upload all your video. Do click on Upload Your Assessment again to upload pending videos.',
    //             'UPLOAD_POPUP_TITLE'             => 'Howdy User!',
    //             'UPLOAD_POPUP_MESSAGE_HEAD'      => 'You have successfully initiated the process of uploading your video. While videos are getting uploaded you can keep Awarathon in background and resume your work. This may take a few minutes.',
    //             'UPLOAD_POPUP_MESSAGE_SUB'       => 'Please do not close Awarathon App!',
    //             'PENDING_POPUP_TITLE'            => 'Howdy User!',
    //             'PENDING_POPUP_MESSAGE_HEAD'     => 'Your assessment video is processing in background once process will compelete your assessment will move to history. During this process if any video failed then this assessment will re-open again.',
    //             'PENDING_POPUP_MESSAGE_SUB'      => '',
    //             'VIMEO_CALLBACK_ATTEMPTS'        => 10,
    //             'VIMEO_CALLBACK_TIMER'           => 20000,
    //             'PBWRAPPER_BG'                   => '#999999',
    //             'PBFONT_CLR'                     => '#ffffff',
    //             'PBU_BG'                         => '#349b82',
    //             'PBC_BG'                         => '#ff7739',
    //             'PBF_BG'                         => '#f85252',
    //             'PBS_BG'                         => '#349b82',
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function submit_assessment_attempts(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $assessment_id   = $this->input->post('assessment_id');
    //                 if ($assessment_id!=''){

    //                     $total_numbersof_attempts = 0 ;
    //                     $attempted_count          = 0 ;

    //                     //NO. OF ATTEMPTS ALLOWED.
    //                     $where_clause = array(
    //                         'id'         => $assessment_id,
    //                         'company_id' => $user_company_id,
    //                         'status'     => 1
    //                     ); 
    //                     $assessment_mst_results = $this->api_model->fetch_record('assessment_mst',$where_clause,$this->atomdb);
    //                     if (count((array)$assessment_mst_results)>0){
    //                         $total_numbersof_attempts = $assessment_mst_results->number_attempts;
    //                     }

    //                     //ATTEMPTED COUNT
    //                     $where_clause = array(
    //                         'user_id'       => $user_id,
    //                         'assessment_id' => $assessment_id
    //                     ); 
    //                     $assessment_attempts_results = $this->api_model->fetch_record('assessment_attempts',$where_clause,$this->atomdb);
    //                     if (count((array)$assessment_attempts_results)>0){
    //                         $attempted_count = $assessment_attempts_results->attempts;
    //                     }

    //                     if ((int)$attempted_count >= (int)$total_numbersof_attempts){

    //                     }else{
    //                         //UPDATE USERS ATTEMPTS
    //                         $where_clause = array(
    //                             'user_id'       => $user_id,
    //                             'assessment_id' => $assessment_id
    //                         ); 
    //                         $attempts_found =$this->api_model->record_count('assessment_attempts',$where_clause,$this->atomdb);
    //                         if ($attempts_found>0){
    //                             $this->api_model->update_attempts('assessment_attempts',$user_id,$assessment_id,$this->atomdb);
    //                         }else{
    //                             $jsonAttempts = array(
    //                                 'user_id'       => $user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'attempts'      => 1,
    //                                 'addeddate'     => date('Y-m-d H:i:s')
    //                             );
    //                             $this->api_model->insert('assessment_attempts',$jsonAttempts,$this->atomdb);
    //                         }
    //                     }

    //                     $json = array(
    //                         'data'              => $data,
    //                         'success'           => true,
    //                         'message'           => 'Assessment attempts updated successfully.'
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json); 
    // }
    // public function assessment_otc(){
    //     $data         = array();
    //     $json         = array(); 
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST         = json_decode($_jsonObj, TRUE);
    //         $payload       = $this->input->post('payload');
    //         $otp           = $this->input->post('otp');
    //         $assessment_id = $this->input->post('assessment_id');

    //         $server_key       = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify      = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 

    //             //IF PAYLOAD VERIFY THEN RETRIVE USERS DETAILS FROM DATABASE
    //             $user_details = $this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //             $user_count =  count((array)$user_details);
    //             if ($user_count > 0) {
    //                 foreach ($user_details as $row) {


    //                 }

    //                 //VERIFY ASSESSMENT OTC IN DATABASE
    //                 $where_clause = array(
    //                     'id'  => $assessment_id,
    //                     'code' => $otp
    //                 ); 
    //                 $otp_found = $this->api_model->record_count('assessment_mst',$where_clause,$this->atomdb);
    //                 if ($otp_found==1){

    //                     //CHECK USER IS REGISTERED FOR ASSESSMENT.
    //                     $where_clause = array(
    //                         'user_id'       => $user_id,
    //                         'assessment_id' => $assessment_id
    //                     ); 
    //                     $assessment_registeration_result = $this->api_model->fetch_record('assessment_attempts',$where_clause,$this->atomdb);
    //                     if (count((array)$assessment_registeration_result)>0){
    //                         $is_otp_verified  = $assessment_registeration_result->otp_verified;
    //                         if ($is_otp_verified==1 OR $is_otp_verified=='1'){
    //                             $json = array(
    //                                 'success' => true,
    //                                 'message' => "Assessment One Time Code(OTC) Already Verified"
    //                             );
    //                         }else{
    //                             $data = array(
    //                                 'otp_verified'        => 1,
    //                                 'otp_registered_dttm' => date('Y-m-d H:i:s'),
    //                                 'attempts'            => 0,
    //                                 'addeddate'           => date('Y-m-d H:i:s'),
    //                                 'is_completed'        => 0,
    //                                 'complete_dttm'       => date('Y-m-d H:i:s')
    //                             );
    //                             $assessment_attempt_txn_id = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                             $json = array(
    //                                 'success' => true,
    //                                 'message' => "Assessment One Time Code(OTC) Verified"
    //                             );
    //                         }
    //                     }else{
    //                         $data = array(
    //                             'user_id'             => $user_id,
    //                             'assessment_id'       => $assessment_id,
    //                             'otp_verified'        => 1,
    //                             'otp_registered_dttm' => date('Y-m-d H:i:s'),
    //                             'attempts'            => 0,
    //                             'addeddate'           => date('Y-m-d H:i:s'),
    //                             'is_completed'        => 0,
    //                             'complete_dttm'       => date('Y-m-d H:i:s')
    //                         );
    //                         $assessment_attempt_txn_id = $this->api_model->insert('assessment_attempts',$data,$this->atomdb);
    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Assessment One Time Code(OTC) Verified"
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' => "Invalid Assessment One Time Code(OTC)"
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }

    // public function submit_assessment_details(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id    = $user_details->company_id;
    //                 $assessment_id      = $this->input->post('assessment_id');
    //                 $trans_id           = $this->input->post('trans_id');
    //                 $question_id        = $this->input->post('question_id');
    //                 $addeddate          = $this->input->post('addeddate');
    //                 $total_question_app = $this->input->post('total_question');
    //                 $session_id         = $this->input->post('session_id');
    //                 $token_id           = $this->input->post('token_id');
    //                 $archive_id         = $this->input->post('archive_id');
    //                 if ($assessment_id!=''){

    //                     $temp_video_id    = '';
    //                     $temp_video_array = array();
    //                     $video_id = '';
    //                     $temp_video_id = $this->input->post('video_id');
    //                     if ($temp_video_id!=''){
    //                         $temp_video_array  = explode("/", $temp_video_id);
    //                         if (isset($temp_video_array[2])){
    //                             $video_id = $temp_video_array[2];
    //                         }
    //                     }
    //                     if ($video_id!=''){

    //                         //FIND VIDEO EXISTS.
    //                         $video_result_found = $this->api_model->fetch_assessment_video_result($user_company_id,$assessment_id,$trans_id,$user_id,$question_id,$this->atomdb); 
    //                         if (count((array)$video_result_found)>0){
    //                             $where_clause = array(
    //                                 'user_id'       => $user_id,
    //                                 'company_id'    => $user_company_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id
    //                             ); 
    //                             $data = array(
    //                                 'company_id'    => $user_company_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id,
    //                                 'user_id'       => $user_id,
    //                                 'video_url'     => $video_id,
    // 								'vimeo_uri'     => $video_id,
    //                                 'user_rating'   => 0,
    //                                 'retake'        => 0,
    //                                 'addeddate'     => date('Y-m-d H:i:s'),
    //                                 'session_id'    => $session_id,
    //                                 'token_id'      => $token_id,
    //                                 'archive_id'    => $archive_id
    //                             );
    //                             $this->api_model->update('assessment_results',$where_clause,$data,$this->atomdb);
    //                         }else{
    //                             $data = array(
    //                                 'company_id'    => $user_company_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id,
    //                                 'user_id'       => $user_id,
    //                                 'video_url'     => $video_id,
    // 								'vimeo_uri'     => $video_id,
    //                                 'user_rating'   => 0,
    //                                 'retake'        => 0,
    //                                 'addeddate'     => date('Y-m-d H:i:s'),
    //                                 'session_id'    => $session_id,
    //                                 'token_id'      => $token_id,
    //                                 'archive_id'    => $archive_id
    //                             );
    //                             $this->api_model->insert('assessment_results',$data,$this->atomdb);
    //                         }

    //                         $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                         $total_question = count((array)$video_assessment_result);

    //                         $total_uploaded =$this->api_model->fetch_assessment_total_uploaded_videos_count($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                         if ($total_question==count((array)$total_uploaded)){
    //                             $where_clause = array(
    //                                 'user_id'       => $user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 // 'otp_verified'  => 1
    //                             ); 
    //                             $data = array(
    //                                 'is_completed'        => 1,
    //                                 'complete_dttm'       => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                         }

    //                         $jsonUpdtLog = array(
    //                             'user_id'       => $user_id,
    //                             'company_id'    => $user_company_id,
    //                             'assessment_id' => $assessment_id,
    //                             'trans_id'      => $trans_id,
    //                             'question_id'   => $question_id,
    //                             'log_dttm'      => date('Y-m-d H:i:s'),
    //                             'log_status'    => 'UPLOADED'
    //                         );
    //                         $this->api_model->insert('assessment_upload_log',$jsonUpdtLog,$this->atomdb);

    //                         // $retake_data = array(
    //                         //     'retake' => 0
    //                         // );
    //                         // $where_clause = array(
    //                         //     'assessment_id' => $assessment_id,
    //                         //     'user_id'       => $user_id,
    //                         //     'retake'        => 1
    //                         // );  
    //                         // $update_status = $this->api_model->update('assessment_retake', $where_clause, $retake_data,$this->atomdb);


    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Assessment video uploaded successfully"
    //                         );
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' =>  "Video id is missing."
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function delete_video_from_vimeo($lib,$asst_row,$video_id){
    //     try{
    //         $uri = "/videos/$video_id";
    //         $lib->request($uri, [], 'DELETE');
    //     }catch(Exception $e){
    //     }
    // }
    // // 16-09-2021 Start
    // public function update_vimeo_uri($company_id){
    //     die($company_id);
    // 	$where_clause = array('company_id'      => $company_id); 
    // 	$this->atomdb = null;
    // 	$this->atomdb = $this->api_model->connectCo($company_id);
    // 	$data_set = $this->api_model->fetch_results('assessment_results',$where_clause,$this->atomdb);
    //     print_r($where_clause);
    //     die();
    // 	if(count((array)$data_set)>0){
    // 		$where_clause = array(
    // 			'status'      => 1
    // 		);

    // 		$vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    // 		$vimeo_client_id     = $vimeo_credentials->client_id;
    // 		$vimeo_client_secret = $vimeo_credentials->client_secret;
    // 		$vimeo_access_token  = $vimeo_credentials->access_token;
    // 		$lib = new Vimeo\Vimeo($vimeo_client_id, $vimeo_client_secret,$vimeo_access_token);
    // 		if (!empty($vimeo_access_token)) {
    // 			$lib->setToken($vimeo_access_token);
    // 		}
    // 		foreach($data_set as $value){
    // 			$vimeo_video_id        = $value->video_url;
    // 			if ($vimeo_video_id!=''){
    // 				$request_url = "/me/videos/$vimeo_video_id";
    // 				$is_uploaded = '';
    // 				$vimeo_vidstat_response = $lib->request($request_url);
    // 				if (count((array)$vimeo_vidstat_response) > 0){
    // 					$vimeo_uri='';
    // 					if(isset($vimeo_vidstat_response['body']['embed']['html'])){
    // 						$tdata = $vimeo_vidstat_response['body']['embed']['html'];
    // 						$htmldata1 = explode('/',$tdata);
    // 						$htmldata2 = (isset($htmldata1[4]) ? explode('&',$htmldata1[4]) : '');
    // 						if(isset($htmldata2[0])){
    // 							$vimeo_uri= $htmldata2[0];
    // 						}
    // 					}
    // 					if($vimeo_uri !=''){
    // 						$where_clause = array('id' => $value->id); 
    // 						$assessment_results_data = array('vimeo_uri'	=>$vimeo_uri);
    // 						$this->api_model->update('assessment_results',$where_clause,$assessment_results_data,$this->atomdb);
    // 					}
    // 				}
    // 			}

    // 		}
    // 	}

    // }
    // // 16-09-2021 End
    // public function cronjob_check_vimeo_status($cronjob_id=""){
    //     $co_common_result=$this->api_model->fetch_company_for_vimeo($cronjob_id); //You can pass comma seperated string for more company
    //     if (count((array)$co_common_result)>0){
    //         foreach ($co_common_result as $co_row) {
    //             $company_id   = $co_row->id;
    //             $company_name = $co_row->company_name;
    //             $this->atomdb = null;
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             $where_clause = array(
    //                 'status'      => 1
    //             ); 
    //             $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //             $vimeo_client_id     = $vimeo_credentials->client_id;
    //             $vimeo_client_secret = $vimeo_credentials->client_secret;
    //             $vimeo_access_token  = $vimeo_credentials->access_token;
    //             $lib = new Vimeo\Vimeo($vimeo_client_id, $vimeo_client_secret,$vimeo_access_token);

    //             if (!empty($vimeo_access_token)) {
    //                 $lib->setToken($vimeo_access_token);
    //             }

    //             $_assessment_results=$this->api_model->fetch_assessment_results_ftpurl($company_id,$this->atomdb);

    //             if (count((array)$_assessment_results)>0){
    //                 foreach ($_assessment_results as $asst_row) {
    //                     $assessment_results_id = $asst_row->id;
    //                     $assessment_company_id = $asst_row->company_id;
    //                     $assessment_id         = $asst_row->assessment_id;
    //                     $trans_id              = $asst_row->trans_id;
    //                     $question_id           = $asst_row->question_id;
    //                     $assessment_user_id    = $asst_row->user_id;
    //                     $vimeo_video_id        = $asst_row->video_url;
    //                     $ios_session_id        = $asst_row->session_id;
    //                     $ios_token_id          = $asst_row->token_id;
    //                     $ios_archive_id        = $asst_row->archive_id;
    //                     $addeddate             = (is_null($asst_row->addeddate) OR $asst_row->addeddate=='0000-00-00 00:00:00' OR $asst_row->addeddate=='')?'':$asst_row->addeddate;
    //                     $assessment_addeddate  = date("Y-m-d", strtotime($addeddate));

    //                     if ($vimeo_video_id!=''){
    //                         $vimeo_status                 = '';
    //                         $vimeo_video_upload_status    = '';
    //                         $vimeo_video_transcode_status = '';
    //                         $_vimeo_created_dttm          = '';
    //                         $vimeo_created_dttm           = '';
    //                         $datetimediff                 = '';
    //                         $days                         = 0;
    //                         $vimeo_video_duration         = 0;

    //                         $request_url = "/me/videos/$vimeo_video_id";
    //                         $is_uploaded = '';
    // 						try{
    // 							$vimeo_uri='';
    // 							$vimeo_vidstat_response = $lib->request($request_url);
    //                             if (count((array)$vimeo_vidstat_response) > 0){
    //                                 if (array_key_exists("status",$vimeo_vidstat_response)){
    //                                     $vimeo_status = $vimeo_vidstat_response['status'];    
    //                                 }
    // 								if(isset($vimeo_vidstat_response['body']['embed']['html'])){
    // 									$tdata = $vimeo_vidstat_response['body']['embed']['html'];
    // 									$htmldata1 = explode('/',$tdata);
    // 									$htmldata2 = (isset($htmldata1[4]) ? explode('&',$htmldata1[4]) : '');
    // 									if(isset($htmldata2[0])){
    // 										$vimeo_uri= $htmldata2[0];
    // 									}
    // 								}
    //                                 if (array_key_exists("body",$vimeo_vidstat_response)){
    //                                     if (array_key_exists("created_time",$vimeo_vidstat_response['body'])){
    //                                         $_vimeo_created_dttm = $vimeo_vidstat_response['body']['created_time'];        
    //                                         $vimeo_created_dttm =  date("Y-m-d", strtotime($_vimeo_created_dttm)) ;
    //                                     }
    //                                     if (array_key_exists("status",$vimeo_vidstat_response['body'])){
    //                                         $vimeo_video_upload_status = $vimeo_vidstat_response['body']['status'];        
    //                                     }
    //                                     if (array_key_exists("transcode",$vimeo_vidstat_response['body'])){
    //                                         if (array_key_exists("status",$vimeo_vidstat_response['body']['transcode'])){
    //                                             $vimeo_video_transcode_status = $vimeo_vidstat_response['body']['transcode']['status'];
    //                                         }
    //                                     }
    //                                     if (array_key_exists("duration",$vimeo_vidstat_response['body'])){
    //                                         $vimeo_video_duration =  $vimeo_vidstat_response['body']['duration']; 
    //                                         if ($vimeo_video_duration>0){
    //                                             $vimeo_video_duration = ((int)$vimeo_video_duration - 1);
    //                                         }
    //                                     }
    //                                 }
    //                                 if ($vimeo_status=='404' OR $vimeo_status==404){
    //                                     $this->delete_video_from_vimeo($lib,$asst_row,$vimeo_video_id);
    //                                     $is_uploaded = 'ERROR';
    //                                 }else if (($vimeo_video_upload_status=='transcoding' OR $vimeo_video_upload_status=='uploading') AND $vimeo_video_transcode_status=='in_progress'){
    //                                     $datetimediff = date_diff(date_create($assessment_addeddate),date_create(date("Y-m-d")));
    //                                     if (count((array)$datetimediff) > 0){
    //                                         $days = $datetimediff->days;
    //                                         if ($days>=1){
    //                                             $this->delete_video_from_vimeo($lib,$asst_row,$vimeo_video_id);
    //                                             $is_uploaded = 'ERROR';
    //                                         }
    //                                     }
    //                                 }else if ($vimeo_video_upload_status=='uploading_error' AND $vimeo_video_transcode_status=='in_progress'){
    //                                     $this->delete_video_from_vimeo($lib,$asst_row,$vimeo_video_id);
    //                                     $is_uploaded = 'ERROR';
    // 								}else if ($vimeo_video_upload_status=='available' AND $vimeo_video_transcode_status=='complete'){
    // 									$is_uploaded = 'Y';
    // 									$where_clause = array(
    // 										'id' => $assessment_results_id
    // 									); 
    // 									//DIVYESH PANCHAL -- KRISHNA -- DELETE VIDEO FROM VONAGE
    //                                     try{
    //                                         if ($ios_session_id !='' AND $ios_token_id != '' AND $ios_archive_id != ''){    //ONLY FOR iOS DEVICES
    //                                             $apiKey          = $this->config->item('tokbox_apikey');
    //                                             $apiSecret       = $this->config->item('tokbox_apisecret');
    //                                             $opentok         = new OpenTok($apiKey, $apiSecret);
    //                                             $opentok->deleteArchive($ios_archive_id);   //DELETE VIDEO FROM VONAGE ONCE UPLOADED SUCCESSFULLY ON VIMEO

    //                                             $assessment_results_data = array(
    //                                                 'ftp_status'    => 1,
    //                                                 'vimeo_uri'	    => $vimeo_uri,
    //                                                 'tokbox_status' => 1    //UPDATE FLAG ONCE VIDEO IS DELETED FROM VONAGE 
    //                                             );
    //                                         }else{
    //                                             $assessment_results_data = array(
    //                                                 'ftp_status'    => 1,
    //                                                 'vimeo_uri'	    => $vimeo_uri
    //                                             );
    //                                         }
    //                                     }catch(Exception $e){
    //                                     }
    // 									$this->api_model->update('assessment_results',$where_clause,$assessment_results_data,$this->atomdb);
    // 								}
    // 							}
    // 						}catch(Exception $e){
    // 						}	
    //                         if ($is_uploaded=='ERROR'){      
    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                             ); 
    //                             $data = array(
    //                                 'is_completed'  => 0,
    //                                 'complete_dttm' => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);

    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                             ); 
    //                             $data = array(
    //                                 'ftpto_vimeo_uploaded' => 0,
    //                                 'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                             $this->api_model->delete_single_assessment_results($assessment_results_id,$this->atomdb);
    //                             $this->send_notification_for_video_failed($assessment_company_id,$assessment_id,$assessment_user_id);
    //                         }else if ($is_uploaded=='Y'){

    //                             //UPDATE VIMEO VIDEO DURATION/LENGTH
    //                             $where_clause = array(
    //                                 'company_id'    => $assessment_company_id,
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id
    //                             );
    //                             $data = array(
    //                                 'video_duration' => $vimeo_video_duration
    //                             );
    //                             $this->api_model->update('assessment_results', $where_clause,$data,$this->atomdb);

    //                             $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                             $total_question = count((array)$video_assessment_result);

    //                             $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                             $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                             if ($total_question==count((array)$total_uploaded)){
    //                                 $where_clause = array(
    //                                     'user_id'       => $assessment_user_id,
    //                                     'assessment_id' => $assessment_id,
    //                                 ); 
    //                                 $data = array(
    //                                     'is_completed'        => 1,
    //                                     'complete_dttm'       => date('Y-m-d H:i:s')
    //                                 );
    //                                 $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                             }
    //                             if ($total_question==count((array)$total_uploaded_ftp)){
    //                                 $where_clause = array(
    //                                     'user_id'       => $assessment_user_id,
    //                                     'assessment_id' => $assessment_id,
    //                                 ); 
    //                                 $data = array(
    //                                     'ftpto_vimeo_uploaded' => 1,
    //                                     'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                                 );
    //                                 $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);

    //                                 //Notification Module - Jagdisha 10.02.2023 : for email sent while submiting 
    //                                 $ReturnArray=array();
    //                                 $ReturnArray1=array();

    //                                 //Send Notification - For Reps
    //                                 //get email template
    //                                 $emailTemplate = $this->api_model->get_value('auto_emails', '*', "status=1 and alert_name='assessment_successfully_submitted-rep_(vimeo)'",$this->atomdb);
    //                                 $pattern[1] = '/\[NAME\]/';
    //                                 $pattern[0] = '/\[SUBJECT\]/';

    //                                 if (count((array)$emailTemplate) > 0) {
    //                                     $subject = $emailTemplate->subject;
    //                                     $UserData = $this->api_model->get_value('device_users', 'company_id,concat(firstname," ",lastname) as trainee_name,email', '  user_id =' . $assessment_user_id,$this->atomdb);
    //                                     $ToName = $UserData->trainee_name;
    //                                     //$ToName = 'Jagdisha';
    //                                     $email_to = $UserData->email;
    //                                     // $email_to = 'jagdisha.patel@awarathon.com';
    //                                     $Company_id = $UserData->company_id;
    //                                     $replacement[1] = $UserData->trainee_name;
    //                                     $replacement[0] = $subject;
    //                                     $message = $emailTemplate->message;
    //                                     $body = preg_replace($pattern, $replacement, $message);
    //                                     $ReturnArray = $this->api_model->sendPhpMailer($Company_id, $ToName, $email_to, $subject, $body,$this->atomdb);
    //                                 }
    //                                 //End Reps

    //                                 //Start - For Manager
    //                                 $emailTemplate = $this->api_model->get_value('auto_emails', '*', "status=1 and alert_name='assessment_successfully_submitted-manager_(vimeo)'",$this->atomdb);

    //                                 $pattern[0] = '/\[SUBJECT\]/';
    //                                 $pattern[1] = '/\[ASSESSMENT_LINK\]/';
    //                                 $pattern[2] = '/\[NAME\]/';
    //                                 $pattern[3] = '/\[EXPIRE_DATE\]/';
    //                                 $pattern[4] = '/\[Trainee_Name\]/';

    //                                 if (count((array)$emailTemplate) > 0) {
    //                                     $where="assessment_id='$assessment_id' AND user_id='$assessment_user_id'";
    //                                     $Tarainee_set = $this->api_model->get_value('assessment_mapping_user', 'trainer_id',$where, $this->atomdb);
    //                                     if (count((array)$Tarainee_set) > 0) {
    //                                         $subject = $emailTemplate->subject;
    //                                         $replacement[0] = $subject;
    //                                         $ManagerSet = $this->api_model->get_value('company_users', 'concat(first_name," ",last_name) as trainer_name,email,company_id', "userid=" . $Tarainee_set->trainer_id, $this->atomdb);
    //                                         $UserData = $this->api_model->get_value('device_users', 'company_id,concat(firstname," ",lastname) as trainee_name,email', '  user_id =' . $assessment_user_id,$this->atomdb);
    //                                         $assessment_set = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $assessment_id,$this->atomdb);
    //                                         $domain_url = $this->api_model->get_value('company', 'domin_url', "id=" . $Company_id,$this->atomdb);
    //                                         $replacement[1] = '<a target="_blank" style="display: inline-block;
    //                                             width: 200px;
    //                                             height: 20px;
    //                                             background: #db1f48;
    //                                             padding: 10px;
    //                                             text-align: center;
    //                                             border-radius: 5px;
    //                                             color: white;
    //                                             border: 1px solid black;
    //                                             text-decoration:none;
    //                                             font-weight: bold;" href="' . $domain_url->domin_url . '/assessment/view/' . base64_encode($assessment_id) . '/2">View Assignment</a>';
    //                                             // font-weight: bold;" href="' . $domain_url->domin_url . '/assessment/view/' . $assessment_id . '/2">View Assignment</a>';
    //                                         $replacement[2] = $ManagerSet->trainer_name;
    //                                         $replacement[3] = date("d-m-Y h:i a", strtotime($assessment_set->assessor_dttm));
    //                                         $replacement[4] = $UserData->trainee_name; //
    //                                         $ToName = $ManagerSet->trainer_name;
    //                                         $email_to = $ManagerSet->email;
    //                                         $Company_id = $ManagerSet->company_id;
    //                                         $message = $emailTemplate->message;
    //                                         $body = preg_replace($pattern, $replacement, $message);

    //                                         $ReturnArray1 = $this->api_model->sendPhpMailer($Company_id, $ToName, $email_to, $subject, $body,$this->atomdb);
    //                                     }
    //                                 }
    //                                 //End Manager

    //                                 //Jagdisha 10.02.2023 : for email sent while submiting Done
    //                             }
    //                         }
    //                     }else{
    //                         //ERROR: VIMEO VIDEO ID NOT FOUND 
    //                     }

    //                 }
    //             }
    //         }
    //     }
    //     //echo "Done";
    // }
    // public function cronjob_check_vimeo_status_ai($cronjob_id=""){
    //     $co_common_result=$this->api_model->fetch_company_for_vimeo($cronjob_id); //You can pass comma seperated string for more company
    //     if (count((array)$co_common_result)>0){
    //         foreach ($co_common_result as $co_row) {
    //             $company_id   = $co_row->id;
    //             $company_name = $co_row->company_name;
    //             $this->atomdb = null;
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             $where_clause = array(
    //                 'status'      => 1
    //             ); 
    //             $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //             $vimeo_client_id     = $vimeo_credentials->client_id;
    //             $vimeo_client_secret = $vimeo_credentials->client_secret;
    //             $vimeo_access_token  = $vimeo_credentials->access_token;
    //             $lib = new Vimeo\Vimeo($vimeo_client_id, $vimeo_client_secret,$vimeo_access_token);

    //             if (!empty($vimeo_access_token)) {
    //                 $lib->setToken($vimeo_access_token);
    //             }

    //             $_assessment_results=$this->api_model->fetch_assessment_results_ftpurl($company_id,$this->atomdb);
    //             if (count((array)$_assessment_results)>0){
    //                 foreach ($_assessment_results as $asst_row) {
    //                     $assessment_results_id = $asst_row->id;
    //                     $assessment_company_id = $asst_row->company_id;
    //                     $assessment_id         = $asst_row->assessment_id;
    //                     $trans_id              = $asst_row->trans_id;
    //                     $question_id           = $asst_row->question_id;
    //                     $assessment_user_id    = $asst_row->user_id;
    //                     $vimeo_video_id        = $asst_row->video_url;
    //                     $ios_session_id        = $asst_row->session_id;
    //                     $ios_token_id          = $asst_row->token_id;
    //                     $ios_archive_id        = $asst_row->archive_id;
    //                     $addeddate             = (is_null($asst_row->addeddate) OR $asst_row->addeddate=='0000-00-00 00:00:00' OR $asst_row->addeddate=='')?'':$asst_row->addeddate;
    //                     $assessment_addeddate  = date("Y-m-d", strtotime($addeddate));
    //                     echo "<br/>".$vimeo_video_id;
    //                     if ($vimeo_video_id!=''){
    //                         $vimeo_status                 = '';
    //                         $vimeo_video_upload_status    = '';
    //                         $vimeo_video_transcode_status = '';
    //                         $_vimeo_created_dttm          = '';
    //                         $vimeo_created_dttm           = '';
    //                         $datetimediff                 = '';
    //                         $days                         = 0;
    //                         $vimeo_video_duration         = 0;

    //                         $request_url = "/me/videos/$vimeo_video_id";
    //                         $is_uploaded = '';
    // 						try{
    // 							$vimeo_uri='';
    // 							$vimeo_vidstat_response = $lib->request($request_url);
    //                             if (count((array)$vimeo_vidstat_response) > 0){
    //                                 if (array_key_exists("status",$vimeo_vidstat_response)){
    //                                     $vimeo_status = $vimeo_vidstat_response['status'];    
    //                                 }
    // 								if(isset($vimeo_vidstat_response['body']['embed']['html'])){
    // 									$tdata = $vimeo_vidstat_response['body']['embed']['html'];
    // 									$htmldata1 = explode('/',$tdata);
    // 									$htmldata2 = (isset($htmldata1[4]) ? explode('&',$htmldata1[4]) : '');
    // 									if(isset($htmldata2[0])){
    // 										$vimeo_uri= $htmldata2[0];
    // 									}
    // 								}
    //                                 if (array_key_exists("body",$vimeo_vidstat_response)){
    //                                     if (array_key_exists("created_time",$vimeo_vidstat_response['body'])){
    //                                         $_vimeo_created_dttm = $vimeo_vidstat_response['body']['created_time'];        
    //                                         $vimeo_created_dttm =  date("Y-m-d", strtotime($_vimeo_created_dttm)) ;
    //                                     }
    //                                     if (array_key_exists("status",$vimeo_vidstat_response['body'])){
    //                                         $vimeo_video_upload_status = $vimeo_vidstat_response['body']['status'];        
    //                                     }
    //                                     if (array_key_exists("transcode",$vimeo_vidstat_response['body'])){
    //                                         if (array_key_exists("status",$vimeo_vidstat_response['body']['transcode'])){
    //                                             $vimeo_video_transcode_status = $vimeo_vidstat_response['body']['transcode']['status'];
    //                                         }
    //                                     }
    //                                     if (array_key_exists("duration",$vimeo_vidstat_response['body'])){
    //                                         $vimeo_video_duration =  $vimeo_vidstat_response['body']['duration']; 
    //                                         if ($vimeo_video_duration>0){
    //                                             $vimeo_video_duration = ((int)$vimeo_video_duration - 1);
    //                                         }
    //                                     }
    //                                 }
    //                                 echo " - ".$vimeo_uri." - ".$vimeo_status." - ".$vimeo_video_upload_status;
    //                                 if ($vimeo_status=='404' OR $vimeo_status==404){
    //                                     $this->delete_video_from_vimeo($lib,$asst_row,$vimeo_video_id);
    //                                     $is_uploaded = 'ERROR';
    //                                 }else if (($vimeo_video_upload_status=='transcoding' OR $vimeo_video_upload_status=='uploading') AND $vimeo_video_transcode_status=='in_progress'){
    //                                     $datetimediff = date_diff(date_create($assessment_addeddate),date_create(date("Y-m-d")));
    //                                     if (count((array)$datetimediff) > 0){
    //                                         $days = $datetimediff->days;
    //                                         if ($days>=1){
    //                                             $this->delete_video_from_vimeo($lib,$asst_row,$vimeo_video_id);
    //                                             $is_uploaded = 'ERROR';
    //                                         }
    //                                     }
    //                                 }else if ($vimeo_video_upload_status=='uploading_error' AND $vimeo_video_transcode_status=='in_progress'){
    //                                     $this->delete_video_from_vimeo($lib,$asst_row,$vimeo_video_id);
    //                                     $is_uploaded = 'ERROR';
    // 								}else if ($vimeo_video_upload_status=='available' AND $vimeo_video_transcode_status=='complete'){
    // 									$is_uploaded = 'Y';
    //                                     $where_clause = array(
    // 										'id' => $assessment_results_id
    // 									); 
    //                                     //DIVYESH PANCHAL -- KRISHNA -- DELETE VIDEO FROM VONAGE
    //                                     try{
    //                                         if ($ios_session_id !='' AND $ios_token_id != '' AND $ios_archive_id != ''){    //ONLY FOR iOS DEVICES
    //                                             $apiKey          = $this->config->item('tokbox_apikey');
    //                                             $apiSecret       = $this->config->item('tokbox_apisecret');
    //                                             $opentok         = new OpenTok($apiKey, $apiSecret);
    //                                             $opentok->deleteArchive($ios_archive_id);   //DELETE VIDEO FROM VONAGE ONCE UPLOADED SUCCESSFULLY ON VIMEO

    //                                             $assessment_results_data = array(
    //                                                 'ftp_status'    => 1,
    //                                                 'vimeo_uri'	    => $vimeo_uri,
    //                                                 'tokbox_status' => 1    //UPDATE FLAG ONCE VIDEO IS DELETED FROM VONAGE 
    //                                             );
    //                                         }else{
    //                                             $assessment_results_data = array(
    //                                                 'ftp_status'    => 1,
    //                                                 'vimeo_uri'	    => $vimeo_uri
    //                                             );
    //                                         }
    //                                     }catch(Exception $e){
    //                                     }
    // 									$this->api_model->update('assessment_results',$where_clause,$assessment_results_data,$this->atomdb);
    // 								}
    // 							}
    // 						}catch(Exception $e){
    // 						}	
    //                         if ($is_uploaded=='ERROR'){      
    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                             ); 
    //                             $data = array(
    //                                 'is_completed'  => 0,
    //                                 'complete_dttm' => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);

    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                             ); 
    //                             $data = array(
    //                                 'ftpto_vimeo_uploaded' => 0,
    //                                 'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                             $this->api_model->delete_single_assessment_results($assessment_results_id,$this->atomdb);
    //                             $this->send_notification_for_video_failed($assessment_company_id,$assessment_id,$assessment_user_id);
    //                         }else if ($is_uploaded=='Y'){

    //                             //UPDATE VIMEO VIDEO DURATION/LENGTH
    //                             $where_clause = array(
    //                                 'company_id'    => $assessment_company_id,
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id
    //                             );
    //                             $data = array(
    //                                 'video_duration' => $vimeo_video_duration
    //                             );
    //                             $this->api_model->update('assessment_results', $where_clause,$data,$this->atomdb);

    //                             $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                             $total_question = count((array)$video_assessment_result);

    //                             $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                             $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                             if ($total_question==count((array)$total_uploaded)){
    //                                 $where_clause = array(
    //                                     'user_id'       => $assessment_user_id,
    //                                     'assessment_id' => $assessment_id,
    //                                 ); 
    //                                 $data = array(
    //                                     'is_completed'        => 1,
    //                                     'complete_dttm'       => date('Y-m-d H:i:s')
    //                                 );
    //                                 $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                             }
    //                             if ($total_question==count((array)$total_uploaded_ftp)){
    //                                 $where_clause = array(
    //                                     'user_id'       => $assessment_user_id,
    //                                     'assessment_id' => $assessment_id,
    //                                 ); 
    //                                 $data = array(
    //                                     'ftpto_vimeo_uploaded' => 1,
    //                                     'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                                 );
    //                                 $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);

    //                                 //Notification Module - Jagdisha 10.02.2023 : for email sent while submiting 
    //                                 $ReturnArray=array();
    //                                 $ReturnArray1=array();

    //                                 //Send Notification - For Reps
    //                                 //get email template
    //                                 $emailTemplate = $this->api_model->get_value('auto_emails', '*', "status=1 and alert_name='assessment_successfully_submitted-rep_(vimeo)'",$this->atomdb);
    //                                 $pattern[1] = '/\[NAME\]/';
    //                                 $pattern[0] = '/\[SUBJECT\]/';

    //                                 if (count((array)$emailTemplate) > 0) {
    //                                     $subject = $emailTemplate->subject;
    //                                     $UserData = $this->api_model->get_value('device_users', 'company_id,concat(firstname," ",lastname) as trainee_name,email', '  user_id =' . $assessment_user_id,$this->atomdb);
    //                                     $ToName = $UserData->trainee_name;
    //                                     //$ToName = 'Jagdisha';
    //                                     $email_to = $UserData->email;
    //                                     // $email_to = 'jagdisha.patel@awarathon.com';
    //                                     $Company_id = $UserData->company_id;
    //                                     $replacement[1] = $UserData->trainee_name;
    //                                     $replacement[0] = $subject;
    //                                     $message = $emailTemplate->message;
    //                                     $body = preg_replace($pattern, $replacement, $message);
    //                                     $ReturnArray = $this->api_model->sendPhpMailer($Company_id, $ToName, $email_to, $subject, $body,$this->atomdb);
    //                                 }
    //                                 //End Reps

    //                                 //Start - For Manager
    //                                 $emailTemplate = $this->api_model->get_value('auto_emails', '*', "status=1 and alert_name='assessment_successfully_submitted-manager_(vimeo)'",$this->atomdb);

    //                                 $pattern[0] = '/\[SUBJECT\]/';
    //                                 $pattern[1] = '/\[ASSESSMENT_LINK\]/';
    //                                 $pattern[2] = '/\[NAME\]/';
    //                                 $pattern[3] = '/\[EXPIRE_DATE\]/';
    //                                 $pattern[4] = '/\[Trainee_Name\]/';

    //                                 if (count((array)$emailTemplate) > 0) {
    //                                     $where="assessment_id='$assessment_id' AND user_id='$assessment_user_id'";
    //                                     $Tarainee_set = $this->api_model->get_value('assessment_mapping_user', 'trainer_id',$where, $this->atomdb);
    //                                     if (count((array)$Tarainee_set) > 0) {
    //                                         $subject = $emailTemplate->subject;
    //                                         $replacement[0] = $subject;
    //                                         $ManagerSet = $this->api_model->get_value('company_users', 'concat(first_name," ",last_name) as trainer_name,email,company_id', "userid=" . $Tarainee_set->trainer_id, $this->atomdb);
    //                                         $UserData = $this->api_model->get_value('device_users', 'company_id,concat(firstname," ",lastname) as trainee_name,email', '  user_id =' . $assessment_user_id,$this->atomdb);
    //                                         $assessment_set = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $assessment_id,$this->atomdb);
    //                                         $domain_url = $this->api_model->get_value('company', 'domin_url', "id=" . $Company_id,$this->atomdb);
    //                                         $replacement[1] = '<a target="_blank" style="display: inline-block;
    //                                             width: 200px;
    //                                             height: 20px;
    //                                             background: #db1f48;
    //                                             padding: 10px;
    //                                             text-align: center;
    //                                             border-radius: 5px;
    //                                             color: white;
    //                                             border: 1px solid black;
    //                                             text-decoration:none;
    //                                             font-weight: bold;" href="' . $domain_url->domin_url . '/assessment/view/' . base64_encode($assessment_id) . '/2">View Assignment</a>';
    //                                             // font-weight: bold;" href="' . $domain_url->domin_url . '/assessment/view/' . $assessment_id . '/2">View Assignment</a>';
    //                                         $replacement[2] = $ManagerSet->trainer_name;
    //                                         $replacement[3] = date("d-m-Y h:i a", strtotime($assessment_set->assessor_dttm));
    //                                         $replacement[4] = $UserData->trainee_name; //
    //                                         $ToName = $ManagerSet->trainer_name;
    //                                         $email_to = $ManagerSet->email;
    //                                         $Company_id = $ManagerSet->company_id;
    //                                         $message = $emailTemplate->message;
    //                                         $body = preg_replace($pattern, $replacement, $message);

    //                                         $ReturnArray1 = $this->api_model->sendPhpMailer($Company_id, $ToName, $email_to, $subject, $body,$this->atomdb);
    //                                     }
    //                                 }
    //                                 //End Manager

    //                                 //Jagdisha 10.02.2023 : for email sent while submiting Done
    //                             }
    //                         }
    //                     }else{
    //                         //ERROR: VIMEO VIDEO ID NOT FOUND 
    //                         echo "<br/>VIMEO VIDEO ID NOT FOUND";
    //                     }

    //                 }
    //             }
    //         }
    //     }
    //     //echo "Done";
    // }
    // public function cronjob_upload_ftp_videoto_vimeo_companywise($cronjob_id=""){
    //     error_reporting(E_ALL);
    //     $co_common_result=$this->api_model->fetch_company_for_vimeo($cronjob_id=""); //You can pass comma seperated string for more company
    //     if (count((array)$co_common_result)>0){
    //         foreach ($co_common_result as $co_row) {
    //             $company_id   = $co_row->id;
    //             $company_name = $co_row->company_name;
    //             $this->atomdb = null;
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             $where_clause = array(
    //                 'status'      => 1
    //             ); 
    //             $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //             $vimeo_client_id     = $vimeo_credentials->client_id;
    //             $vimeo_client_secret = $vimeo_credentials->client_secret;
    //             $vimeo_access_token  = $vimeo_credentials->access_token;
    //             $lib = new Vimeo\Vimeo($vimeo_client_id, $vimeo_client_secret,$vimeo_access_token);

    //             if (!empty($vimeo_access_token)) {
    //                 $lib->setToken($vimeo_access_token);
    //             }

    //             $_assessment_results=$this->api_model->fetch_assessment_results_ftpurl($company_id,$this->atomdb);
    //             if (count((array)$_assessment_results)>0){
    //                 foreach ($_assessment_results as $asst_row) {
    //                     $assessment_results_id = $asst_row->id;
    //                     $assessment_company_id = $asst_row->company_id;
    //                     $assessment_id         = $asst_row->assessment_id;
    //                     $trans_id              = $asst_row->trans_id;
    //                     $question_id           = $asst_row->question_id;
    //                     $assessment_user_id    = $asst_row->user_id;
    //                     $ftp_url               = $asst_row->ftp_url;
    //                     $ftp_video_path        = "../vimeo/$ftp_url";

    //                     if (file_exists($ftp_video_path)) {
    //                         //USER DETAILS
    //                         $where_clause = array(
    //                             'user_id'      => $assessment_user_id,
    //                             'status'       => 1,
    //                             'block'        => 0
    //                         );
    //                         $user_results =$this->api_model->fetch_device_user_details($where_clause,$this->atomdb);
    //                         $total =  count((array)$user_results);
    //                         if ($total > 0) { 
    //                             foreach ($user_results as $userrow) {
    //                                 $firstname = $userrow->firstname;
    //                                 $lastname = $userrow->lastname;
    //                                 $fullname = $firstname.' '.$lastname;
    //                             }
    //                         }

    //                         $video_description = "Video Uploaded By $fullname, Company Name $company_name";
    //                         $vimeo_upload_response = $lib->upload($ftp_video_path, [
    //                             'name'        => $ftp_url,
    //                             'description' => $video_description,
    //                             'privacy'     => [
    //                                 "comments" => "nobody",
    //                                 "download" => "false",
    //                                 "embed"    => "public",
    //                                 "view"     => "unlisted"
    //                             ]
    //                         ]);

    //                         $temp_video_array = array();
    //                         $video_id         = '';
    //                         if ($vimeo_upload_response!=''){
    //                             $temp_video_array  = explode("/", $vimeo_upload_response);
    //                             if (isset($temp_video_array[2])){
    //                                 $video_id = $temp_video_array[2];
    //                             }
    //                         }

    //                         if ($video_id!=''){
    //                             $request_url = "/me/videos/$video_id";

    //                             $is_uploaded = '';
    //                             for($i=0;$i<10;$i++){
    // 								try{
    // 									$vimeo_vidstat_response = $lib->request($request_url);
    // 									$vimeo_uri='';
    // 									if (count((array)$vimeo_vidstat_response) > 0){
    // 										if(isset($vimeo_vidstat_response['body']['embed']['html'])){
    // 											$tdata = $vimeo_vidstat_response['body']['embed']['html'];
    // 											$htmldata1 = explode('/',$tdata);
    // 											$htmldata2 = (isset($htmldata1[4]) ? explode('&',$htmldata1[4]) : '');
    // 											if(isset($htmldata2[0])){
    // 												$vimeo_uri= $htmldata2[0];
    // 											}
    // 										}
    // 										$vimeo_video_upload_status    = $vimeo_vidstat_response['body']['status'];
    // 										$vimeo_video_transcode_status = $vimeo_vidstat_response['body']['transcode']['status'];
    // 										if ($vimeo_video_upload_status=='available' AND $vimeo_video_transcode_status=='complete'){
    // 											$is_uploaded = 'Y';
    // 											$where_clause = array(
    // 												'id' => $assessment_results_id
    // 											); 
    // 											$assessment_results_data = array(
    // 												'ftp_status'    => 1,
    // 												'video_url'     => $video_id,
    // 												'vimeo_uri'     => ($vimeo_uri !='' ? $vimeo_uri :$video_id)
    // 											);
    // 											$this->api_model->update('assessment_results',$where_clause,$assessment_results_data,$this->atomdb);
    // 											if ($ftp_url!=''){
    // 												if (file_exists($ftp_video_path)) {
    // 													unlink($ftp_video_path);
    // 												}
    // 											}
    // 											break;
    // 										}
    // 									}
    // 									sleep(30);
    // 								}catch(Exception $e){
    // 								}	
    //                             }
    //                             if ($is_uploaded=='Y'){
    //                                 $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                                 $total_question = count((array)$video_assessment_result);

    //                                 $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                                 $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                                 if ($total_question==count((array)$total_uploaded)){
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'is_completed'        => 1,
    //                                         'complete_dttm'       => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }else{
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'is_completed'  => 0,
    //                                         'complete_dttm' => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }
    //                                 if ($total_question==count((array)$total_uploaded_ftp)){
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'ftpto_vimeo_uploaded' => 1,
    //                                         'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }else{
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'ftpto_vimeo_uploaded' => 0,
    //                                         'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }
    //                             }
    //                             if ($is_uploaded==''){
    //                                 //VIDEO UPLOAD ERROR.
    //                                 $this->api_model->delete_single_assessment_results($assessment_results_id,$this->atomdb);  

    //                                 $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                                 $total_question = count((array)$video_assessment_result);

    //                                 $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                                 $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                                 if ($total_question==count((array)$total_uploaded)){
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'is_completed'        => 1,
    //                                         'complete_dttm'       => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }else{
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'is_completed'  => 0,
    //                                         'complete_dttm' => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }
    //                                 if ($total_question==count((array)$total_uploaded_ftp)){
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'ftpto_vimeo_uploaded' => 1,
    //                                         'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }else{
    //                                     $where_clause = array(
    //                                         'user_id'       => $assessment_user_id,
    //                                         'assessment_id' => $assessment_id,
    //                                         // 'otp_verified'  => 1
    //                                     ); 
    //                                     $data = array(
    //                                         'ftpto_vimeo_uploaded' => 0,
    //                                         'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                                     );
    //                                     $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                                 }
    //                             }
    //                         }else{
    //                             //ERROR: VIMEO VIDEO ID NOT FOUND 
    //                         }
    //                     }else{
    //                         $this->api_model->delete_single_assessment_results($assessment_results_id,$this->atomdb);

    //                         $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                         $total_question = count((array)$video_assessment_result);

    //                         $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                         $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($assessment_company_id,$assessment_user_id,$assessment_id,$this->atomdb);
    //                         if ($total_question==count((array)$total_uploaded)){
    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 // 'otp_verified'  => 1
    //                             ); 
    //                             $data = array(
    //                                 'is_completed'        => 1,
    //                                 'complete_dttm'       => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                         }else{
    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 // 'otp_verified'  => 1
    //                             ); 
    //                             $data = array(
    //                                 'is_completed'  => 0,
    //                                 'complete_dttm' => date('Y-m-d H:i:s'),
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                         }
    //                         if ($total_question==count((array)$total_uploaded_ftp)){
    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 // 'otp_verified'  => 1
    //                             ); 
    //                             $data = array(
    //                                 'ftpto_vimeo_uploaded' => 1,
    //                                 'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                         }else{
    //                             $where_clause = array(
    //                                 'user_id'       => $assessment_user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 // 'otp_verified'  => 1
    //                             ); 
    //                             $data = array(
    //                                 'ftpto_vimeo_uploaded' => 0,
    //                                 'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     //echo "Done";
    // }

    // public function gdrive_oauth_request_uri(){
    //     set_time_limit(0);
    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(0);

    //     if (isset($_REQUEST)){
    //         $client_id     = "515347157782-29acq1kip5bqrkffl304b9jqlamtpq01.apps.googleusercontent.com";
    // 		$client_secret = "BPCRYzQPwPk9fetC4kPYlekq";
    //         $request_uri   = 'https://api.awarathon.com/api/gdrive_oauth_request_uri';

    //         define('GOOGLECREDENTIALSPATH', './credentials/google-drive.json');
    //         define('GOOGLECLIENTID', $client_id);
    //         define('GOOGLECLIENTSECRET', $client_secret);
    //         define('GOOGLEREQUESTURI', $request_uri);

    //         $credentials = GOOGLECREDENTIALSPATH;

    //         $client = new Google_Client();
    //         $client->setClientId(GOOGLECLIENTID);
    //         $client->setClientSecret(GOOGLECLIENTSECRET);
    //         $client->setRedirectUri(GOOGLEREQUESTURI);
    //         $client->setAccessType("offline");
    // 		$client->setApprovalPrompt('force');
    //         $client->setApplicationName("Atomapp Jarvis AI");
    //         $client->addScope(Google_Service_Drive::DRIVE);
    //         $client->addScope(Google_Service_Drive::DRIVE_APPDATA);
    //         $client->addScope(Google_Service_Drive::DRIVE_FILE);
    //         $client->addScope(Google_Service_Drive::DRIVE_METADATA);
    //         $client->addScope(Google_Service_Drive::DRIVE_SCRIPTS);

    //         $dcp_service = new Google_Service_Drive($client);   

    //         $auth_code = $_REQUEST['code'];
    //         $access_token = $client->authenticate($auth_code);

    //         if (!file_exists(dirname($credentials))) {
    //             mkdir(dirname($credentials));
    //         }
    //         //WRITE PERMISSION
    //         chmod($credentials,0755);

    //         file_put_contents($credentials, json_encode($access_token));

    //         echo "<p style='color:red;'>Do not refresh the page.</p>";
    //         echo "Credentials saved to ".$credentials;
    //         echo "Credentials/Token has been created & renewed. Please close the window. ";
    //     }else{

    //     }
    // }

    // public function cronjob_upload_vimeo_to_googledrive($cronjob_id=""){
    //     set_time_limit(0);
    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 0);
    //     error_reporting(0);

    // 	//CHECK VIDEO EXISTS FOR UPLOAD IN AWARATHON COMPANY DATABASE.

    // 	$co_common_result=$this->api_model->fetch_company_for_vimeo($cronjob_id,1); //You can pass comma seperated string for more company
    // 	$video_exists_in_some_co = false;
    // 	if (count((array)$co_common_result)>0){
    // 		foreach ($co_common_result as $co_row) {
    // 			$company_id   = $co_row->id;
    // 			$company_name = $co_row->portal_name;
    // 			$this->atomdb = null;
    // 			$this->atomdb = $this->api_model->connectCo($company_id);
    // 			$validate_assessment_attempts_results=$this->api_model->fetch_completed_assessment($company_id,$this->atomdb);
    // 			if (count((array)$validate_assessment_attempts_results)>0){
    // 				$video_exists_in_some_co=true;
    // 				break;
    // 			}
    // 		}
    // 	}
    // 	if(!$video_exists_in_some_co){
    // 		exit;
    // 	}
    // 	////

    //     $gdrive_master_folder_id = "17gB5fmGg7bDn1_bY2rAH_n5Vvkj2TJJT"; //Folder Id : Video Assessment AI
    //     $client                  = new Google_Client();
    //     $client_id               = "515347157782-29acq1kip5bqrkffl304b9jqlamtpq01.apps.googleusercontent.com";
    //     $client_secret           = "BPCRYzQPwPk9fetC4kPYlekq";
    //     $request_uri             = 'https://api.awarathon.com/api/gdrive_oauth_request_uri';

    //     define('GOOGLECREDENTIALSPATH', './credentials/google-drive.json');
    //     define('DCPALERT', './dcp_alerts/diskquota.txt');
    //     define('GOOGLECLIENTID', $client_id);
    //     define('GOOGLECLIENTSECRET', $client_secret);
    //     define('GOOGLEREQUESTURI', $request_uri);

    //     $client->setClientId(GOOGLECLIENTID);
    //     $client->setClientSecret(GOOGLECLIENTSECRET);
    //     $client->setRedirectUri(GOOGLEREQUESTURI);
    //     $client->setAccessType("offline");
    // 	$client->setApprovalPrompt('force');
    //     $client->setApplicationName("Atomapp Jarvis AI");
    //     $client->addScope(Google_Service_Drive::DRIVE);
    //     $client->addScope(Google_Service_Drive::DRIVE_APPDATA);
    //     $client->addScope(Google_Service_Drive::DRIVE_FILE);
    //     $client->addScope(Google_Service_Drive::DRIVE_METADATA);
    //     $client->addScope(Google_Service_Drive::DRIVE_SCRIPTS);

    //     $credentials = GOOGLECREDENTIALSPATH;

    //     if (!file_exists($credentials)) {
    //         $auth_url = $client->createAuthUrl();
    //         //=====================================
    //         //EMAIL TO ADMIN
    //         //=====================================
    //         $subject       = "Token Expired : GDrive Upload Video To Jarvis Account Failed !!!";
    //         $body          = 'Awarathon cronjob google drive upload video to jarvis account failed due to in-valid token 
    //         or credentials failed. Please click on below URL and allow permission using jarvis.ai@awarathon.com email account.<br/><br/> <a href="'.$auth_url.'" target="_blank">Click Here To Allow Permission.</a>';

    //         $from_name     = 'Awarathon';
    //         $from_email    = "no-reply@awarathon.com";
    //         $to_array      = "Divyesh Panchal|sameer@mworks.in";
    //         $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');
    // 		// echo "Check Email : divyesh@mworks.in";
    //         // $to_array      = "Rahul Ghatalia|rahul@mworks.in";
    //         // $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');
    //         exit;
    //     }else {
    // 		try{
    // 			$access_token = file_get_contents($credentials);
    // 			$client->setAccessToken($access_token);
    // 			if ($client->isAccessTokenExpired()) {
    // 				$refresh_token = $client->getRefreshToken();
    // 				if($refresh_token !=''){
    // 					$client->refreshToken($refresh_token);
    // 					$client->fetchAccessTokenWithRefreshToken($refresh_token);
    // 					$new_access_token = $client->getAccessToken();
    // 					$new_access_token['refresh_token'] = $refresh_token;
    //                     $client->setAccessToken($refresh_token);

    //                     //WRITE PERMISSION
    //                     chmod($credentials,0755);
    // 					file_put_contents($credentials, json_encode($new_access_token));
    // 				}else{
    //                     $auth_url = $client->createAuthUrl();
    //                     //=====================================
    //                     //EMAIL TO ADMIN
    //                     //=====================================
    //                     $subject       = "Token Expired : GDrive Upload Video To Jarvis Account Failed !!!";
    //                     $body          = 'Awarathon cronjob google drive upload video to jarvis account failed due to in-valid token 
    //                     or credentials failed. Please click on below URL and allow permission using jarvis.ai@awarathon.com email account.<br/><br/> <a href="'.$auth_url.'" target="_blank">Click Here To Allow Permission.</a>';

    //                     $from_name     = 'Awarathon';
    //                     $from_email    = "no-reply@awarathon.com";
    //                     $to_array      = "Divyesh Panchal|sameer@mworks.in";
    //                     $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');
    //                     // echo "Check Email : divyesh@mworks.in";
    //                     // $to_array      = "Rahul Ghatalia|rahul@mworks.in";
    //                     // $issend        = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');
    //                     exit;
    //                 }
    // 			}
    // 		}catch(Exception $e){
    //             // echo "Exception code is: " . $e->getCode();
    // 			// print_r($e);
    // 			//unlink($credentials);
    // 			//redirect(current_url());
    // 		}

    //     }
    //     $dcp_service = new Google_Service_Drive($client);

    //     //=====================================
    //     //FIND SERVICE ACCOUNT FIELDS AND QUOTA.
    //     //=====================================
    //     try {
    //         $parameters = array('fields' => '*');
    //         $about = $dcp_service->about->get($parameters);

    //         $total_space = $about['storageQuota']['limit'].'-';
    //         $usage_space = $about['storageQuota']['usage'];
    //         $space_margin = round(($usage_space * 100)/$total_space);

    //         $disk_quota_path =  DCPALERT;

    //         if (!file_exists($disk_quota_path)) {
    //             if ($space_margin >= 90){
    //                 if (!file_exists(dirname($disk_quota_path))) {
    //                     mkdir(dirname($disk_quota_path));
    //                 }
    //                 //WRITE PERMISSION
    //                 chmod($disk_quota_path,0755);

    //                 file_put_contents($disk_quota_path, "Y");

    //                 //EMAIL TO ADMIN
    //                 $subject        = "Disk Renewal Notice - 10% Disk quota left in gdrive for Jarvis account. !!!";
    //                 $body           = '10% Disk quota remain in google drive for Jarvis account. Please increase disk quota or remove some files.<br/><br/>';
    //                 $body          .= 'Total Space - '.number_format($total_space / 1073741824, 1).' GB';
    //                 $body          .= 'Usage Space - '.number_format($usage_space / 1073741824, 1).' GB';

    //                 $from_name      = 'Awarathon';
    //                 $from_email     = "no-reply@awarathon.com";

    //                 $to_array       = "Divyesh Panchal|divyesh@mworks.in";
    //                 $issend         = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');

    //                 $to_arrayi      = "Rahul Ghatalia|rahul@mworks.in";
    //                 $issendi        = $this->send_email($subject,$body,$from_name,$from_email,$to_arrayi,'','');

    //                 $to_arrayii     = "Sameer Mansuri|sameer@mworks.in";
    //                 $issendii       = $this->send_email($subject,$body,$from_name,$from_email,$to_arrayii,'','');
    //             }else{
    //                 if (!file_exists(dirname($disk_quota_path))) {
    //                     mkdir(dirname($disk_quota_path));
    //                 }
    //                 //WRITE PERMISSION
    //                 chmod($disk_quota_path,0755);

    //                 file_put_contents($disk_quota_path, "N");
    //             }
    //         }else{
    //             $is_disk_quota_alert_sent = file_get_contents($disk_quota_path);
    //             if ($is_disk_quota_alert_sent=="Y"){

    //             }else{
    //                 if ($space_margin >= 90){
    //                     if (!file_exists(dirname($disk_quota_path))) {
    //                         mkdir(dirname($disk_quota_path));
    //                     }
    //                     //WRITE PERMISSION
    //                     chmod($disk_quota_path,0755);

    //                     file_put_contents($disk_quota_path, "Y");

    //                     //EMAIL TO ADMIN
    //                     $subject        = "Disk Renewal Notice - 10% Disk quota left in gdrive for Jarvis account. !!!";
    //                     $body           = '10% Disk quota remain in google drive for Jarvis account. Please increase disk quota or remove some files.<br/><br/>';
    //                     $body          .= 'Total Space - '.number_format($total_space / 1073741824, 1).' GB';
    //                     $body          .= 'Usage Space - '.number_format($usage_space / 1073741824, 1).' GB';

    //                     $from_name      = 'Awarathon';
    //                     $from_email     = "no-reply@awarathon.com";

    //                     $to_array       = "Divyesh Panchal|divyesh@mworks.in";
    //                     $issend         = $this->send_email($subject,$body,$from_name,$from_email,$to_array,'','');

    //                     $to_arrayi      = "Rahul Ghatalia|rahul@mworks.in";
    //                     $issendi        = $this->send_email($subject,$body,$from_name,$from_email,$to_arrayi,'','');

    //                     $to_arrayii     = "Sameer Mansuri|sameer@mworks.in";
    //                     $issendii       = $this->send_email($subject,$body,$from_name,$from_email,$to_arrayii,'','');
    //                 }else{
    //                     if (!file_exists(dirname($disk_quota_path))) {
    //                         mkdir(dirname($disk_quota_path));
    //                     }
    //                     //WRITE PERMISSION
    //                     chmod($disk_quota_path,0755);

    //                     file_put_contents($disk_quota_path, "N");
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //     }

    //     //$co_common_result=$this->api_model->fetch_company_for_vimeo($cronjob_id); //You can pass comma seperated string for more company
    //     if (count((array)$co_common_result)>0){
    //         foreach ($co_common_result as $co_row) {
    //             $company_id   = $co_row->id;
    //             $company_name = $co_row->portal_name;
    //             $this->atomdb = null;
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             if ($company_name!=''){

    //                 //VIMEO CREDENTIALS
    //                 $where_clause = array(
    //                     'status'      => 1
    //                 ); 
    //                 $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //                 $vimeo_client_id     = $vimeo_credentials->client_id;
    //                 $vimeo_client_secret = $vimeo_credentials->client_secret;
    //                 $vimeo_access_token  = $vimeo_credentials->access_token;
    //                 $lib = new Vimeo\Vimeo($vimeo_client_id, $vimeo_client_secret,$vimeo_access_token);

    //                 if (!empty($vimeo_access_token)) {
    //                     $lib->setToken($vimeo_access_token);
    //                 }


    //                 //COMPANY WISE FOLDER CREATION
    //                 $co_folder_id   = '';
    //                 $co_folder_name = $company_name;
    //                 $where_clause   = array(
    //                     'company_id'         => $company_id,
    //                     'assessment_id'      => 999999999,
    //                     'gdrive_folder_name' => $co_folder_name
    //                 );

    //                 $gdrive_cofolder_found =$this->api_model->record_count('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                 if ($gdrive_cofolder_found>0){
    //                     $atom_cofolder_result =  $this->api_model->fetch_record('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                     if (count((array)$atom_cofolder_result)>0){
    //                         $co_folder_id = $atom_cofolder_result->gdrive_folder_id;
    //                     }
    //                 }else{
    //                     //CHECK COMPANY FOLDER IS CREATED OR NOT.
    //                     $cofolder_parameters['q'] = 'parents ="'.$gdrive_master_folder_id.'" AND name="'.$co_folder_name.'" AND mimeType="application/vnd.google-apps.folder" AND trashed=false';
    //                     $cofolder_parameters['fields'] = "files(*)";
    //                     $codrive_folder_results = $dcp_service->files->listFiles($cofolder_parameters);

    //                     if (count((array)$codrive_folder_results) > 0) {
    //                         foreach ($codrive_folder_results as $cofolder_data){
    //                             $co_folder_id = $cofolder_data->id;
    //                         }
    //                     }else{
    //                         //CREATE FOLDER IF NOT FOUND.
    //                         $cofile = new Google_Service_Drive_DriveFile();
    //                         $cofile->setParents(array($gdrive_master_folder_id));
    //                         $cofile->setName($co_folder_name);
    //                         $cofile->setMimeType('application/vnd.google-apps.folder');
    //                         $cofoldr_result = $dcp_service->files->create($cofile);

    //                         if (isset($cofoldr_result->id)){
    //                             $co_folder_id = $cofoldr_result->id;
    //                             try {
    //                                 $where_clause = array(
    //                                     'company_id'         => $company_id,
    //                                     'assessment_id'      => 999999999,
    //                                     'gdrive_folder_name' => $co_folder_name
    //                                 ); 
    //                                 $gdrive_cofolder_found =$this->api_model->record_count('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                                 if ($gdrive_cofolder_found>0){
    //                                 }else{
    //                                     $json_gdrive_cofolder = array(
    //                                         'company_id'         => $company_id,
    //                                         'assessment_id'      => 999999999,
    //                                         'gdrive_folder_id'   => $co_folder_id,
    //                                         'gdrive_folder_name' => $co_folder_name,
    //                                         'addeddate'          => date('Y-m-d H:i:s')
    //                                     );
    //                                     $this->api_model->insert('assessment_gdrive_folders',$json_gdrive_cofolder,$this->atomdb);
    //                                 }
    //                             } catch (Exception $e) {
    //                             }
    //                         }
    //                     }
    //                 }    

    //                 //ONLY COMPLETED ASSESSMENTS.
    //                 $_assessment_attempts_results=$this->api_model->fetch_completed_assessment($company_id,$this->atomdb);
    //                 if (count((array)$_assessment_attempts_results)>0){
    //                     foreach ($_assessment_attempts_results as $asstattmpt_row) {
    //                         $user_id         = $asstattmpt_row->user_id;
    //                         $user_name       = $asstattmpt_row->user_name;
    //                         $email           = $asstattmpt_row->email;
    //                         $assessment_id   = $asstattmpt_row->assessment_id;
    //                         $assessment_name = $asstattmpt_row->assessment;

    //                         $assessment_folder_id = '';
    //                         $user_folder_id       = '';
    //                         $user_folder_name     = '';
    //                         $gdrive_file_id       = '';
    //                         $vimeo_video_url      = '';
    //                         $vimeo_file_status    = '';
    //                         $question_file_id     = '';

    //                         $user_folder_name = $user_name.'-'.$user_id;

    //                         $where_clause = array(
    //                             'company_id'         => $company_id,
    //                             'assessment_id'      => $assessment_id,
    //                             'gdrive_folder_name' => $assessment_name
    //                         ); 
    //                         $gdrive_folder_found =$this->api_model->record_count('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                         if ($gdrive_folder_found>0){
    //                             $atom_folder_result =  $this->api_model->fetch_record('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                             if (count((array)$atom_folder_result)>0){
    //                                 $assessment_folder_id = $atom_folder_result->gdrive_folder_id;
    //                             }
    //                         }else{
    //                             //CHECK ASSESSMENT FOLDER IS CREATED OR NOT.
    //                             $folder_parameters['q'] = 'parents ="'.$co_folder_id.'" AND name="'.$assessment_name.'" AND mimeType="application/vnd.google-apps.folder" AND trashed=false';
    //                             $folder_parameters['fields'] = "files(*)";
    //                             $drive_folder_results = $dcp_service->files->listFiles($folder_parameters);

    //                             if (count((array)$drive_folder_results) > 0) {
    //                                 foreach ($drive_folder_results as $folder_data){
    //                                     $assessment_folder_id = $folder_data->id;
    //                                 }
    //                             }else{
    //                                 //CREATE FOLDER IF NOT FOUND.
    //                                 $assfile = new Google_Service_Drive_DriveFile();
    //                                 // $assfile->setParents(array($gdrive_master_folder_id));
    //                                 $assfile->setParents(array($co_folder_id));
    //                                 $assfile->setName($assessment_name);
    //                                 $assfile->setMimeType('application/vnd.google-apps.folder');
    //                                 $assfoldr_result = $dcp_service->files->create($assfile);

    //                                 if (isset($assfoldr_result->id)){
    //                                     $assessment_folder_id = $assfoldr_result->id;
    //                                     try {
    //                                         $where_clause = array(
    //                                             'company_id'         => $company_id,
    //                                             'assessment_id'      => $assessment_id,
    //                                             'gdrive_folder_name' => $assessment_name
    //                                         ); 
    //                                         $gdrive_assfolder_found =$this->api_model->record_count('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                                         if ($gdrive_assfolder_found>0){
    //                                         }else{
    //                                             $json_gdrive_folder = array(
    //                                                 'company_id'         => $company_id,
    //                                                 'assessment_id'      => $assessment_id,
    //                                                 'gdrive_folder_id'   => $assessment_folder_id,
    //                                                 'gdrive_folder_name' => $assessment_name,
    //                                                 'addeddate'          => date('Y-m-d H:i:s')
    //                                             );
    //                                             $this->api_model->insert('assessment_gdrive_folders',$json_gdrive_folder,$this->atomdb);
    //                                         }
    //                                     } catch (Exception $e) {
    //                                     }
    //                                 }
    //                             }
    //                         }

    //                         //ASSESSMENT FOLDER FOUND
    //                         if ($user_folder_name!='' AND $co_folder_id !='' AND $gdrive_master_folder_id!='' AND $assessment_folder_id!=''){

    //                             $user_folder_id = '';
    //                             $where_clause = array(
    //                                 'company_id'         => $company_id,
    //                                 'assessment_id'      => $assessment_id,
    //                                 'user_id'            => $user_id,
    //                                 'gdrive_folder_name' => $user_folder_name
    //                             ); 
    //                             $gdrive_ufolder_found =$this->api_model->record_count('assessment_gdrive_users_folders',$where_clause,$this->atomdb);
    //                             if ($gdrive_ufolder_found>0){
    //                                 $atom_ufolder_result =  $this->api_model->fetch_record('assessment_gdrive_users_folders',$where_clause,$this->atomdb);
    //                                 if (count((array)$atom_ufolder_result)>0){
    //                                     $user_folder_id = $atom_ufolder_result->gdrive_folder_id;
    //                                 }
    //                             }else{
    //                                 //CHECK ASSESSMENT FOLDER IS CREATED OR NOT.
    //                                 $folder_parameters['q'] = 'parents ="'.$assessment_folder_id.'" AND name="'.$user_folder_name.'" AND mimeType="application/vnd.google-apps.folder" AND trashed=false';
    //                                 $folder_parameters['fields'] = "files(*)";
    //                                 $drive_folder_results = $dcp_service->files->listFiles($folder_parameters);
    //                                 if (count((array)$drive_folder_results) > 0) {
    //                                     foreach ($drive_folder_results as $folder_data){
    //                                         $user_folder_id = $folder_data->id;
    //                                     }
    //                                 }else{
    //                                     //CREATE FOLDER IF NOT FOUND.
    //                                     $ufile = new Google_Service_Drive_DriveFile();
    //                                     $ufile->setParents(array($assessment_folder_id));
    //                                     $ufile->setName($user_folder_name);
    //                                     $ufile->setMimeType('application/vnd.google-apps.folder');
    //                                     $ufolder_result = $dcp_service->files->create($ufile);

    //                                     if (isset($ufolder_result->id)){
    //                                         $user_folder_id = $ufolder_result->id;
    //                                         try {
    //                                             $where_clause = array(
    //                                                 'company_id'         => $company_id,
    //                                                 'assessment_id'      => $assessment_id,
    //                                                 'user_id'            => $user_id,
    //                                                 'gdrive_folder_id'   => $user_folder_id,
    //                                                 'gdrive_folder_name' => $user_folder_name
    //                                             );
    //                                             $gdrive_ufolder_foundi =$this->api_model->record_count('assessment_gdrive_users_folders',$where_clause,$this->atomdb);
    //                                             if ($gdrive_ufolder_foundi>0){
    //                                             }else{
    //                                                 $json_gdrive_ufolder = array(
    //                                                     'company_id'         => $company_id,
    //                                                     'assessment_id'      => $assessment_id,
    //                                                     'user_id'            => $user_id,
    //                                                     'gdrive_folder_id'   => $user_folder_id,
    //                                                     'gdrive_folder_name' => $user_folder_name,
    //                                                     'addeddate'          => date('Y-m-d H:i:s')
    //                                                 );
    //                                                 $this->api_model->insert('assessment_gdrive_users_folders',$json_gdrive_ufolder,$this->atomdb);
    //                                             }
    //                                         } catch (Exception $e) {
    //                                         }
    //                                     }
    //                                 }
    //                             }

    //                             //USER FOLDER FOUND
    //                             if ($user_folder_id!=''){

    //                                 $gdrive_file_id    = '';
    //                                 $vimeo_video_url   = '';
    //                                 $vimeo_file_status = '';

    //                                 //UPLOAD FILES.
    //                                 $_assessment_quiz_results=$this->api_model->fetch_user_assessment_result_for_gdrive($company_id,$user_id,$assessment_id,$this->atomdb);
    //                                 if (count((array)$_assessment_quiz_results)>0){
    //                                     foreach ($_assessment_quiz_results as $asstquiz_row) {
    //                                         $trans_id        = $asstquiz_row->trans_id;
    //                                         $question_id     = $asstquiz_row->question_id;
    //                                         $question_series = $asstquiz_row->question_series;
    //                                         $video_url       = $asstquiz_row->video_url;

    //                                         $gdrive_file_id    = '';
    //                                         $vimeo_video_url   = '';
    //                                         $vimeo_file_status = '';
    //                                         $gdrive_file_id  = (is_null($asstquiz_row->gdrive_file_id) OR $asstquiz_row->gdrive_file_id=='')?'':$asstquiz_row->gdrive_file_id;

    //                                         if ($gdrive_file_id==''){

    //                                             //============================================
    //                                             //START - FETCH VIMEO VIDEO DIRECT VIDEO LINK.
    //                                             //============================================
    //                                             $vimeo_video_url   = '';
    //                                             $vimeo_file_status = '';
    //                                             if ($video_url!=''){
    //                                                 $request_url = "/me/videos/$video_url";
    //                                                 $vimeo_vidstat_response = $lib->request($request_url);

    //                                                 if (count((array)$vimeo_vidstat_response) > 0){
    //                                                     if (array_key_exists("status",$vimeo_vidstat_response)){
    //                                                         $vimeo_file_status = $vimeo_vidstat_response['status'];
    //                                                     }
    //                                                     if ($vimeo_file_status=='404'){
    //                                                         //STORE FILE STATUS IN DATABASE.
    //                                                         $where_clause = array(
    //                                                             'company_id'       => $company_id,
    //                                                             'assessment_id'    => $assessment_id,
    //                                                             'user_id'          => $user_id,
    //                                                             'trans_id'         => $trans_id,
    //                                                             'question_id'      => $question_id,
    //                                                             'video_url'        => $video_url,
    //                                                             'gdrive_file_name' => $question_series
    //                                                         );
    //                                                         $gdrive_file_found =$this->api_model->record_count('assessment_gdrive_files',$where_clause,$this->atomdb);
    //                                                         if ($gdrive_file_found>0){

    //                                                         }else{
    //                                                             $json_gdrive_file = array(
    //                                                                 'company_id'       => $company_id,
    //                                                                 'assessment_id'    => $assessment_id,
    //                                                                 'user_id'          => $user_id,
    //                                                                 'trans_id'         => $trans_id,
    //                                                                 'question_id'      => $question_id,
    //                                                                 'video_url'        => $video_url,
    //                                                                 'gdrive_file_id'   => '404 - File Not Found',
    //                                                                 'gdrive_file_name' => $question_series,
    //                                                                 'addeddate'        => date('Y-m-d H:i:s')
    //                                                             );
    //                                                             $this->api_model->insert('assessment_gdrive_files',$json_gdrive_file,$this->atomdb);
    //                                                         }
    //                                                     }else{
    //                                                         if (array_key_exists("body",$vimeo_vidstat_response)){
    //                                                             if (array_key_exists("download",$vimeo_vidstat_response['body'])){
    //                                                                 $direct_url_result = $vimeo_vidstat_response['body']['download'];    

    //                                                                 foreach($direct_url_result as $key => $direct_link){
    //                                                                     if (array_key_exists("type",$direct_url_result[$key]) AND
    //                                                                         array_key_exists("width",$direct_url_result[$key]) AND 
    //                                                                         array_key_exists("height",$direct_url_result[$key])){
    //                                                                             $type   = $direct_url_result[$key]['type'];
    //                                                                             $width  = $direct_url_result[$key]['width'];
    //                                                                             $height = $direct_url_result[$key]['height'];
    //                                                                             $size   = $direct_url_result[$key]['size'];
    //                                                                             if ($type == "video/mp4" AND $width!="" AND $height != ""){
    //                                                                                 $vimeo_video_url = $direct_url_result[$key]['link'];
    //                                                                                 break;
    //                                                                             }
    //                                                                     }
    //                                                                 }
    //                                                             }
    //                                                         }
    //                                                     }
    //                                                 }
    //                                             }

    //                                             //============================================
    //                                             //END - FETCH VIMEO VIDEO DIRECT VIDEO LINK.
    //                                             //============================================


    //                                             //=====================================
    //                                             //CREATE FILE
    //                                             //=====================================
    //                                             $question_file_id = '';
    //                                             if ($user_folder_id !='' AND $vimeo_video_url!=''){
    //                                                 $vimfile = new Google_Service_Drive_DriveFile($client);
    //                                                 $vimfile->setMimeType('video/mp4');
    //                                                 $vimfile->setName($question_series.'.mp4');
    //                                                 $vimfile->setDescription('Company Id: '.$company_id.' | User Id: '.$user_id.' | Assessment Id:'.$assessment_id.' | Trans Id: '.$trans_id.' | Question Id: '.$question_id);
    //                                                 $vimfile->setParents(array($user_folder_id));
    //                                                 $data = file_get_contents($vimeo_video_url);
    //                                                 $question_file_result = $dcp_service->files->create($vimfile, array('data' => $data,'mimeType' => 'video/mp4', 'uploadType' => 'resumable'));
    //                                                 $question_file_id = $question_file_result->getId();

    //                                                 if ($question_file_id!=''){
    //                                                     try {
    //                                                         $where_clause = array(
    //                                                             'company_id'       => $company_id,
    //                                                             'assessment_id'    => $assessment_id,
    //                                                             'user_id'          => $user_id,
    //                                                             'trans_id'         => $trans_id,
    //                                                             'question_id'      => $question_id,
    //                                                             'video_url'        => $video_url,
    //                                                             'gdrive_file_name' => $question_series
    //                                                         );
    //                                                         $gdrive_file_foundi =$this->api_model->record_count('assessment_gdrive_files',$where_clause,$this->atomdb);
    //                                                         if ($gdrive_file_foundi>0){
    //                                                         }else{
    //                                                             $json_gdrive_file = array(
    //                                                                 'company_id'       => $company_id,
    //                                                                 'assessment_id'    => $assessment_id,
    //                                                                 'user_id'          => $user_id,
    //                                                                 'trans_id'         => $trans_id,
    //                                                                 'question_id'      => $question_id,
    //                                                                 'video_url'        => $video_url,
    //                                                                 'gdrive_file_id'   => $question_file_id,
    //                                                                 'gdrive_file_name' => $question_series,
    //                                                                 'addeddate'        => date('Y-m-d H:i:s')
    //                                                             );
    //                                                             $this->api_model->insert('assessment_gdrive_files',$json_gdrive_file,$this->atomdb);
    //                                                         }
    //                                                     } catch (Exception $e) {
    //                                                     } 
    //                                                 }
    //                                             }
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }

    //             }
    //         }
    //     }
    //     //echo "Done";
    // }
    // public function cronjob_upload_vimeo_to_googledrive_old($cronjob_id=""){
    //     error_reporting(E_ALL);

    //     include_once "base.php";
    //     putenv('GOOGLE_APPLICATION_CREDENTIALS=atomapp-261705-f164116ebf9e.json');
    //     $redirect_uri            = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    //     $gdrive_master_folder_id = "1yNVsCPqjRrqO0mlm5J47UXi3zmxqTaTy";

    //     $client = new Google_Client();

    //     if ($credentials_file = checkServiceAccountCredentialsFile()) {
    //         $client->setAuthConfig($credentials_file);
    //         $client->setRedirectUri($redirect_uri);
    //     } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
    //         $client->useApplicationDefaultCredentials();
    //     } else {
    //         echo missingServiceAccountDetailsWarning();
    //         exit;
    //     }
    //     $client->setApplicationName("Atomapp Jarvis AI");
    //     $client->addScope(Google_Service_Drive::DRIVE);
    //     $client->addScope(Google_Service_Drive::DRIVE_APPDATA);
    //     $client->addScope(Google_Service_Drive::DRIVE_FILE);
    //     $client->addScope(Google_Service_Drive::DRIVE_METADATA);
    //     $client->addScope(Google_Service_Drive::DRIVE_SCRIPTS);
    //     $dcp_service = new Google_Service_Drive($client);

    //     $co_common_result=$this->api_model->fetch_company_for_vimeo($cronjob_id,1); //You can pass comma seperated string for more company
    //     if (count((array)$co_common_result)>0){
    //         foreach ($co_common_result as $co_row) {
    //             $company_id   = $co_row->id;
    //             $company_name = $co_row->company_name;
    //             $this->atomdb = null;
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             $where_clause = array(
    //                 'status'      => 1
    //             ); 
    //             $vimeo_credentials = $this->api_model->fetch_record('api_details',$where_clause,$this->atomdb);
    //             $vimeo_client_id     = $vimeo_credentials->client_id;
    //             $vimeo_client_secret = $vimeo_credentials->client_secret;
    //             $vimeo_access_token  = $vimeo_credentials->access_token;
    //             $lib = new Vimeo\Vimeo($vimeo_client_id, $vimeo_client_secret,$vimeo_access_token);

    //             if (!empty($vimeo_access_token)) {
    //                 $lib->setToken($vimeo_access_token);
    //             }

    //             //ONLY COMPLETED ASSESSMENTS.
    //             $_assessment_attempts_results=$this->api_model->fetch_completed_assessment($company_id,$this->atomdb);
    //             if (count((array)$_assessment_attempts_results)>0){
    //                 foreach ($_assessment_attempts_results as $asstattmpt_row) {
    //                     $user_id         = $asstattmpt_row->user_id;
    //                     $user_name       = $asstattmpt_row->user_name;
    //                     $email           = $asstattmpt_row->email;
    //                     $assessment_id   = $asstattmpt_row->assessment_id;
    //                     $assessment_name = $asstattmpt_row->assessment;

    //                     $assessment_folder_id = '';
    //                     $user_folder_id       = '';
    //                     $user_folder_name     = '';
    //                     $gdrive_file_id       = '';
    //                     $vimeo_video_url      = '';
    //                     $vimeo_file_status    = '';
    //                     $question_file_id     = '';

    //                     $user_folder_name = $user_name.'-'.$user_id;

    //                     $where_clause = array(
    //                         'company_id'         => $company_id,
    //                         'assessment_id'      => $assessment_id,
    //                         'gdrive_folder_name' => $assessment_name
    //                     ); 
    //                     $gdrive_folder_found =$this->api_model->record_count('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                     if ($gdrive_folder_found>0){
    //                         $atom_folder_result =  $this->api_model->fetch_record('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                         if (count((array)$atom_folder_result)>0){
    //                             $assessment_folder_id = $atom_folder_result->gdrive_folder_id;
    //                         }
    //                     }else{
    //                         //CHECK ASSESSMENT FOLDER IS CREATED OR NOT.
    //                         $folder_parameters['q'] = 'parents ="'.$gdrive_master_folder_id.'" AND name="'.$assessment_name.'" AND mimeType="application/vnd.google-apps.folder" AND trashed=false';
    //                         $folder_parameters['fields'] = "files(*)";
    //                         $drive_folder_results = $dcp_service->files->listFiles($folder_parameters);

    //                         if (count((array)$drive_folder_results) > 0) {
    //                             foreach ($drive_folder_results as $folder_data){
    //                                 $assessment_folder_id = $folder_data->id;
    //                             }
    //                         }else{
    //                             //CREATE FOLDER IF NOT FOUND.
    //                             $assfile = new Google_Service_Drive_DriveFile();
    //                             $assfile->setParents(array($gdrive_master_folder_id));
    //                             $assfile->setName($assessment_name);
    //                             $assfile->setMimeType('application/vnd.google-apps.folder');
    //                             $assfoldr_result = $dcp_service->files->create($assfile);

    //                             if (isset($assfoldr_result->id)){
    //                                 $assessment_folder_id = $assfoldr_result->id;
    //                                 try {
    //                                     $where_clause = array(
    //                                         'company_id'         => $company_id,
    //                                         'assessment_id'      => $assessment_id,
    //                                         'gdrive_folder_name' => $assessment_name
    //                                     ); 
    //                                     $gdrive_assfolder_found =$this->api_model->record_count('assessment_gdrive_folders',$where_clause,$this->atomdb);
    //                                     if ($gdrive_assfolder_found>0){
    //                                     }else{
    //                                         $json_gdrive_folder = array(
    //                                             'company_id'         => $company_id,
    //                                             'assessment_id'      => $assessment_id,
    //                                             'gdrive_folder_id'   => $assessment_folder_id,
    //                                             'gdrive_folder_name' => $assessment_name,
    //                                             'addeddate'          => date('Y-m-d H:i:s')
    //                                         );
    //                                         $this->api_model->insert('assessment_gdrive_folders',$json_gdrive_folder,$this->atomdb);
    //                                     }
    //                                 } catch (Exception $e) {
    //                                 }
    //                             }
    //                         }
    //                     }

    //                     //ASSESSMENT FOLDER FOUND
    //                     if ($user_folder_name!='' AND $gdrive_master_folder_id!='' AND $assessment_folder_id!=''){

    //                         $user_folder_id = '';
    //                         $where_clause = array(
    //                             'company_id'         => $company_id,
    //                             'assessment_id'      => $assessment_id,
    //                             'user_id'            => $user_id,
    //                             'gdrive_folder_name' => $user_folder_name
    //                         ); 
    //                         $gdrive_ufolder_found =$this->api_model->record_count('assessment_gdrive_users_folders',$where_clause,$this->atomdb);
    //                         if ($gdrive_ufolder_found>0){
    //                             $atom_ufolder_result =  $this->api_model->fetch_record('assessment_gdrive_users_folders',$where_clause,$this->atomdb);
    //                             if (count((array)$atom_ufolder_result)>0){
    //                                 $user_folder_id = $atom_ufolder_result->gdrive_folder_id;
    //                             }
    //                         }else{
    //                             //CHECK ASSESSMENT FOLDER IS CREATED OR NOT.
    //                             $folder_parameters['q'] = 'parents ="'.$assessment_folder_id.'" AND name="'.$user_folder_name.'" AND mimeType="application/vnd.google-apps.folder" AND trashed=false';
    //                             $folder_parameters['fields'] = "files(*)";
    //                             $drive_folder_results = $dcp_service->files->listFiles($folder_parameters);
    //                             if (count((array)$drive_folder_results) > 0) {
    //                                 foreach ($drive_folder_results as $folder_data){
    //                                     $user_folder_id = $folder_data->id;
    //                                 }
    //                             }else{
    //                                 //CREATE FOLDER IF NOT FOUND.
    //                                 $ufile = new Google_Service_Drive_DriveFile();
    //                                 $ufile->setParents(array($assessment_folder_id));
    //                                 $ufile->setName($user_folder_name);
    //                                 $ufile->setMimeType('application/vnd.google-apps.folder');
    //                                 $ufolder_result = $dcp_service->files->create($ufile);

    //                                 if (isset($ufolder_result->id)){
    //                                     $user_folder_id = $ufolder_result->id;
    //                                     try {
    //                                         $where_clause = array(
    //                                             'company_id'         => $company_id,
    //                                             'assessment_id'      => $assessment_id,
    //                                             'user_id'            => $user_id,
    //                                             'gdrive_folder_id'   => $user_folder_id,
    //                                             'gdrive_folder_name' => $user_folder_name
    //                                         );
    //                                         $gdrive_ufolder_foundi =$this->api_model->record_count('assessment_gdrive_users_folders',$where_clause,$this->atomdb);
    //                                         if ($gdrive_ufolder_foundi>0){
    //                                         }else{
    //                                             $json_gdrive_ufolder = array(
    //                                                 'company_id'         => $company_id,
    //                                                 'assessment_id'      => $assessment_id,
    //                                                 'user_id'            => $user_id,
    //                                                 'gdrive_folder_id'   => $user_folder_id,
    //                                                 'gdrive_folder_name' => $user_folder_name,
    //                                                 'addeddate'          => date('Y-m-d H:i:s')
    //                                             );
    //                                             $this->api_model->insert('assessment_gdrive_users_folders',$json_gdrive_ufolder,$this->atomdb);
    //                                         }
    //                                     } catch (Exception $e) {
    //                                     }
    //                                 }
    //                             }
    //                         }

    //                         //USER FOLDER FOUND
    //                         if ($user_folder_id!=''){

    //                             $gdrive_file_id    = '';
    //                             $vimeo_video_url   = '';
    //                             $vimeo_file_status = '';

    //                             //UPLOAD FILES.
    //                             $_assessment_quiz_results=$this->api_model->fetch_user_assessment_result_for_gdrive($company_id,$user_id,$assessment_id,$this->atomdb);
    //                             if (count((array)$_assessment_quiz_results)>0){
    //                                 foreach ($_assessment_quiz_results as $asstquiz_row) {
    //                                     $trans_id        = $asstquiz_row->trans_id;
    //                                     $question_id     = $asstquiz_row->question_id;
    //                                     $question_series = $asstquiz_row->question_series;
    //                                     $video_url       = $asstquiz_row->video_url;

    //                                     $gdrive_file_id    = '';
    //                                     $vimeo_video_url   = '';
    //                                     $vimeo_file_status = '';
    //                                     $gdrive_file_id  = (is_null($asstquiz_row->gdrive_file_id) OR $asstquiz_row->gdrive_file_id=='')?'':$asstquiz_row->gdrive_file_id;

    //                                     if ($gdrive_file_id==''){

    //                                         //============================================
    //                                         //START - FETCH VIMEO VIDEO DIRECT VIDEO LINK.
    //                                         //============================================
    //                                         $vimeo_video_url   = '';
    //                                         $vimeo_file_status = '';
    //                                         if ($video_url!=''){
    //                                             $request_url = "/me/videos/$video_url";
    //                                             $vimeo_vidstat_response = $lib->request($request_url);

    //                                             if (count((array)$vimeo_vidstat_response) > 0){
    //                                                 if (array_key_exists("status",$vimeo_vidstat_response)){
    //                                                     $vimeo_file_status = $vimeo_vidstat_response['status'];
    //                                                 }
    //                                                 if ($vimeo_file_status=='404'){
    //                                                     //STORE FILE STATUS IN DATABASE.
    //                                                     $where_clause = array(
    //                                                         'company_id'       => $company_id,
    //                                                         'assessment_id'    => $assessment_id,
    //                                                         'user_id'          => $user_id,
    //                                                         'trans_id'         => $trans_id,
    //                                                         'question_id'      => $question_id,
    //                                                         'video_url'        => $video_url,
    //                                                         'gdrive_file_name' => $question_series
    //                                                     );
    //                                                     $gdrive_file_found =$this->api_model->record_count('assessment_gdrive_files',$where_clause,$this->atomdb);
    //                                                     if ($gdrive_file_found>0){

    //                                                     }else{
    //                                                         $json_gdrive_file = array(
    //                                                             'company_id'       => $company_id,
    //                                                             'assessment_id'    => $assessment_id,
    //                                                             'user_id'          => $user_id,
    //                                                             'trans_id'         => $trans_id,
    //                                                             'question_id'      => $question_id,
    //                                                             'video_url'        => $video_url,
    //                                                             'gdrive_file_id'   => '404 - File Not Found',
    //                                                             'gdrive_file_name' => $question_series,
    //                                                             'addeddate'        => date('Y-m-d H:i:s')
    //                                                         );
    //                                                         $this->api_model->insert('assessment_gdrive_files',$json_gdrive_file,$this->atomdb);
    //                                                     }
    //                                                 }else{
    //                                                     if (array_key_exists("body",$vimeo_vidstat_response)){
    //                                                         if (array_key_exists("download",$vimeo_vidstat_response['body'])){
    //                                                             $direct_url_result = $vimeo_vidstat_response['body']['download'];    

    //                                                             foreach($direct_url_result as $key => $direct_link){
    //                                                                 if (array_key_exists("type",$direct_url_result[$key]) AND
    //                                                                     array_key_exists("width",$direct_url_result[$key]) AND 
    //                                                                     array_key_exists("height",$direct_url_result[$key])){
    //                                                                         $type   = $direct_url_result[$key]['type'];
    //                                                                         $width  = $direct_url_result[$key]['width'];
    //                                                                         $height = $direct_url_result[$key]['height'];
    //                                                                         $size   = $direct_url_result[$key]['size'];
    //                                                                         if ($type == "video/mp4" AND $width!="" AND $height != ""){
    //                                                                             $vimeo_video_url = $direct_url_result[$key]['link'];
    //                                                                             break;
    //                                                                         }
    //                                                                 }
    //                                                             }
    //                                                         }
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }

    //                                         //============================================
    //                                         //END - FETCH VIMEO VIDEO DIRECT VIDEO LINK.
    //                                         //============================================


    //                                         //=====================================
    //                                         //CREATE FILE
    //                                         //=====================================
    //                                         $question_file_id = '';
    //                                         if ($user_folder_id !='' AND $vimeo_video_url!=''){
    //                                             $vimfile = new Google_Service_Drive_DriveFile($client);
    //                                             $vimfile->setMimeType('video/mp4');
    //                                             $vimfile->setName($question_series.'.mp4');
    //                                             $vimfile->setDescription('Company Id: '.$company_id.' | User Id: '.$user_id.' | Assessment Id:'.$assessment_id.' | Trans Id: '.$trans_id.' | Question Id: '.$question_id);
    //                                             $vimfile->setParents(array($user_folder_id));
    //                                             $data = file_get_contents($vimeo_video_url);
    //                                             $question_file_result = $dcp_service->files->create($vimfile, array('data' => $data,'mimeType' => 'video/mp4', 'uploadType' => 'resumable'));
    //                                             $question_file_id = $question_file_result->getId();

    //                                             if ($question_file_id!=''){
    //                                                 try {
    //                                                     $where_clause = array(
    //                                                         'company_id'       => $company_id,
    //                                                         'assessment_id'    => $assessment_id,
    //                                                         'user_id'          => $user_id,
    //                                                         'trans_id'         => $trans_id,
    //                                                         'question_id'      => $question_id,
    //                                                         'video_url'        => $video_url,
    //                                                         'gdrive_file_name' => $question_series
    //                                                     );
    //                                                     $gdrive_file_foundi =$this->api_model->record_count('assessment_gdrive_files',$where_clause,$this->atomdb);
    //                                                     if ($gdrive_file_foundi>0){
    //                                                     }else{
    //                                                         $json_gdrive_file = array(
    //                                                             'company_id'       => $company_id,
    //                                                             'assessment_id'    => $assessment_id,
    //                                                             'user_id'          => $user_id,
    //                                                             'trans_id'         => $trans_id,
    //                                                             'question_id'      => $question_id,
    //                                                             'video_url'        => $video_url,
    //                                                             'gdrive_file_id'   => $question_file_id,
    //                                                             'gdrive_file_name' => $question_series,
    //                                                             'addeddate'        => date('Y-m-d H:i:s')
    //                                                         );
    //                                                         $this->api_model->insert('assessment_gdrive_files',$json_gdrive_file,$this->atomdb);
    //                                                     }
    //                                                 } catch (Exception $e) {
    //                                                 } 
    //                                             }
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     //echo "Done";
    // }
    // public function submit_assessment_details_ftp(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);
    //             // $is_email=0;
    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id    = $user_details->company_id;
    //                 $assessment_id      = $this->input->post('assessment_id');
    //                 $trans_id           = $this->input->post('trans_id');
    //                 $question_id        = $this->input->post('question_id');
    //                 $addeddate          = $this->input->post('addeddate');
    //                 $total_question_app = $this->input->post('total_question');
    //                 $session_id         = $this->input->post('session_id');
    //                 $token_id           = $this->input->post('token_id');
    //                 $archive_id         = $this->input->post('archive_id');
    //                 $start_time         = '';
    //                 $end_time           = '';
    //                 $total_seconds      = 0;
    //                 if (isset($_POST['start_time'])){
    //                     $start_time         = $this->input->post('start_time');
    //                 }
    //                 if (isset($_POST['end_time'])){
    //                     $end_time           = $this->input->post('end_time');
    //                 }
    //                 if ($start_time!='' AND $end_time!='') {
    //                     $total_seconds      = strtotime($end_time) - strtotime($start_time);
    //                 }

    //                 if ($assessment_id!=''){

    //                     $temp_video_id    = '';
    //                     $temp_video_array = array();
    //                     $video_id = '';
    //                     $temp_video_id = $this->input->post('video_id');
    //                     $s3flag = $this->input->post('s3flag');
    //                     if($s3flag!='1')
    //                     {
    //                         if ($temp_video_id!=''){
    //                             $temp_video_array  = explode("/", $temp_video_id);
    //                             if (isset($temp_video_array[2])){
    //                                 $video_id = $temp_video_array[2];
    //                             }
    //                         }
    //                     }
    //                     else
    //                     {
    //                         $video_id= $temp_video_id;
    //                     }

    //                     if ($video_id!=''){

    //                         //FIND VIDEO EXISTS.
    //                         $video_result_found = $this->api_model->fetch_assessment_video_result($user_company_id,$assessment_id,$trans_id,$user_id,$question_id,$this->atomdb); 
    //                         if (count((array)$video_result_found)>0){
    //                             $where_clause = array(
    //                                 'user_id'       => $user_id,
    //                                 'company_id'    => $user_company_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id
    //                             ); 
    //                             $data = array(
    //                                 'company_id'    => $user_company_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id,
    //                                 'user_id'       => $user_id,
    //                                 'video_url'     => ($s3flag==1) ? "" : $video_id,
    // 								'vimeo_uri'     => ($s3flag==1) ? "" : $video_id,
    //                                 'bucket_url'    => ($s3flag==1) ? $temp_video_id : "",
    //                                 'bucket_status' => ($s3flag==1) ? 1 : 0,
    //                                 'ftp_url'       => '',
    //                                 'ftp_status'    => 0,
    //                                 'user_rating'   => 0,
    //                                 'retake'        => 0,
    //                                 'addeddate'     => date('Y-m-d H:i:s'),
    //                                 'session_id'    => $session_id,
    //                                 'token_id'      => $token_id,
    //                                 'archive_id'    => $archive_id,
    //                                 'start_time'    => $start_time,
    //                                 'end_time'      => $end_time,
    //                                 'total_seconds' => $total_seconds
    //                             );
    //                             $this->api_model->update('assessment_results',$where_clause,$data,$this->atomdb);
    //                         }else{
    //                             $data = array(
    //                                 'company_id'    => $user_company_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 'trans_id'      => $trans_id,
    //                                 'question_id'   => $question_id,
    //                                 'user_id'       => $user_id,
    //                                 'video_url'     => ($s3flag==1) ? "" : $video_id,
    // 								'vimeo_uri'     => ($s3flag==1) ? "" : $video_id,
    //                                 'bucket_url'    => ($s3flag==1) ? $temp_video_id : "",
    //                                 'bucket_status' => ($s3flag==1) ? 1 : 0,
    //                                 'ftp_url'       => '',
    //                                 'ftp_status'    => 0,
    //                                 'user_rating'   => 0,
    //                                 'retake'        => 0,
    //                                 'addeddate'     => date('Y-m-d H:i:s'),
    //                                 'session_id'    => $session_id,
    //                                 'token_id'      => $token_id,
    //                                 'archive_id'    => $archive_id,
    //                                 'start_time'    => $start_time,
    //                                 'end_time'      => $end_time,
    //                                 'total_seconds' => $total_seconds
    //                             );
    //                             $this->api_model->insert('assessment_results',$data,$this->atomdb);
    //                         }

    //                         $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                         $total_question = count((array)$video_assessment_result);

    //                         $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                         $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                         if ($total_question==count((array)$total_uploaded)){
    //                             $where_clause = array(
    //                                 'user_id'       => $user_id,
    //                                 'assessment_id' => $assessment_id,
    //                             ); 
    //                             $data = array(
    //                                 'is_completed'         => 1,
    //                                 'complete_dttm'        => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                             // $is_email=1;
    //                         }
    //                         if ($total_question==count((array)$total_uploaded_ftp)){
    //                             $where_clause = array(
    //                                 'user_id'       => $user_id,
    //                                 'assessment_id' => $assessment_id,
    //                             ); 
    //                             $data = array(
    //                                 'ftpto_vimeo_uploaded' => 1,
    //                                 'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);

    //                         }
    //                         $jsonUpdtLog = array(
    //                             'user_id'       => $user_id,
    //                             'company_id'    => $user_company_id,
    //                             'assessment_id' => $assessment_id,
    //                             'trans_id'      => $trans_id,
    //                             'question_id'   => $question_id,
    //                             'log_dttm'      => date('Y-m-d H:i:s'),
    //                             'log_status'    => 'UPLOADED'
    //                         );
    //                         $this->api_model->insert('assessment_upload_log',$jsonUpdtLog,$this->atomdb);
    //                         $ReturnArray=array();
    //                         $ReturnArray1=array();
    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Assessment video uploaded successfully",
    //                             'email_rep' => $ReturnArray,
    //                             'email_manager' => $ReturnArray1,
    //                             'video_id' => ($s3flag==1)?$temp_video_id:$video_id
    //                         );
    //                     }else{
    //                         $json = array(
    //                             'success' => false,
    //                             'message' =>  "Video id is missing."
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json); 
    // }
    // public function submit_assessment_status(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $assessment_id   = $this->input->post('assessment_id');
    //                 $total_question  = $this->input->post('total_question');
    //                 $total_uploaded  = $this->input->post('uploaded_question');
    //                 if ($assessment_id!=''){

    //                     $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                     $total_question = count((array)$video_assessment_result);

    //                     $total_uploaded =$this->api_model->fetch_assessment_total_uploaded_videos_count($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                     if ($total_question==count((array)$total_uploaded)){
    //                     //if ($total_question==$total_uploaded){
    //                         $where_clause = array(
    //                             'user_id'       => $user_id,
    //                             'assessment_id' => $assessment_id,
    //                             // 'otp_verified'  => 1
    //                         ); 
    //                         $data = array(
    //                             'is_completed'        => 1,
    //                             'complete_dttm'       => date('Y-m-d H:i:s')
    //                         );
    //                         $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                     }
    //                     //}
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "Assessment video uploaded successfully"
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function submit_assessment_status_ftp(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $assessment_id   = $this->input->post('assessment_id');
    //                 $total_question  = $this->input->post('total_question');
    //                 $total_uploaded  = $this->input->post('uploaded_question');
    //                 if ($assessment_id!=''){

    //                     $video_assessment_result = $this->api_model->fetch_assessment_question($assessment_id,$this->atomdb);
    //                     $total_question = count((array)$video_assessment_result);

    //                     $total_uploaded     = $this->api_model->fetch_assessment_total_uploaded_videos_count($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                     $total_uploaded_ftp = $this->api_model->fetch_assessment_total_uploaded_videos_count_ftp($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                     if ($total_question==count((array)$total_uploaded)){
    //                         $where_clause = array(
    //                             'user_id'       => $user_id,
    //                             'assessment_id' => $assessment_id,
    //                             // 'otp_verified'  => 1
    //                         ); 
    //                         $data = array(
    //                             'is_completed'         => 1,
    //                             'complete_dttm'        => date('Y-m-d H:i:s')
    //                         );
    //                         $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                     }
    //                     if ($total_question==count((array)$total_uploaded_ftp)){
    //                             $where_clause = array(
    //                                 'user_id'       => $user_id,
    //                                 'assessment_id' => $assessment_id,
    //                                 // 'otp_verified'  => 1
    //                             ); 
    //                             $data = array(
    //                                 'ftpto_vimeo_uploaded' => 1,
    //                                 'ftpto_vimeo_dttm'     => date('Y-m-d H:i:s')
    //                             );
    //                             $cmpltid = $this->api_model->update('assessment_attempts', $where_clause,$data,$this->atomdb);
    //                     }
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "Assessment video uploaded successfully"
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function submit_truncate_reshoot(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $assessment_id   = $this->input->post('assessment_id');
    //                 if ($assessment_id!=''){
    //                     $delete_video_assessment_result = $this->api_model->delete_self_assesment_video_result($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                     $total_uploaded =$this->api_model->fetch_assessment_total_uploaded_videos_count($user_company_id,$user_id,$assessment_id,$this->atomdb);
    //                     if (count((array)$total_uploaded)>0){
    //                         $json = array(
    //                             'success' => false,
    //                             'message' => "Failed to clear assessment video result."
    //                         );
    //                     }else{
    //                         $json = array(
    //                             'success' => true,
    //                             'message' => "Assessment video result cleared successfully."
    //                         );
    //                     }
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function submit_truncate_single_reshoot(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $assessment_id   = $this->input->post('assessment_id');
    //                 $trans_id        = $this->input->post('trans_id');
    //                 $question_id     = $this->input->post('question_id');
    //                 if ($assessment_id!='' AND $trans_id!='' AND $question_id!=''){
    //                     $delete_video_assessment_result = $this->api_model->delete_self_assesment_video_single_result($user_company_id,$user_id,$assessment_id,$trans_id,$question_id,$this->atomdb);
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "Assessment single video result cleared successfully."
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id, Transaction Id, Question Id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function submit_assessment_upload_log(){
    //     $data         = array();
    //     $json         = array();  
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);
    //         $token      = $this->input->post('token');
    //         $payload    = $this->input->post('payload');
    //         $server_key = 'awar@thon';
    //         $this->load->library("JWT");

    //         //PAYLOAD VERIFY
    //         try{
    //             $JWTVerify = $this->jwt->decode($payload, $server_key);
    //             $common_user_id = $JWTVerify->user_id;
    //             $this->atomdb   = $this->api_model->connectDb($common_user_id);

    //             $user_id    = 0;
    //             $company_id = 0;
    //             $where_clause = array(
    //                 'id'           => $common_user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $common_user_details = $this->api_model->fetch_record('device_users',$where_clause);
    //             if (count((array)$common_user_details)>0){
    //                 $user_id = $common_user_details->user_id;
    //             }

    //             $where_clause = array(
    //                 'user_id'      => $user_id,
    //                 'status'       => 1,
    //                 'block'        => 0,
    //                 'otp_verified' => 1
    //             ); 
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $user_company_id = $user_details->company_id;
    //                 $assessment_id   = $this->input->post('assessment_id');
    //                 $trans_id        = $this->input->post('trans_id');
    //                 $question_id     = $this->input->post('question_id');
    //                 $log_status      = $this->input->post('log_status');
    //                 if ($assessment_id!='' AND $trans_id!='' AND $question_id!=''){
    //                     $jsonUpdtLog = array(
    //                         'user_id'       => $user_id,
    //                         'company_id'    => $user_company_id,
    //                         'assessment_id' => $assessment_id,
    //                         'trans_id'      => $trans_id,
    //                         'question_id'   => $question_id,
    //                         'log_dttm'      => date('Y-m-d H:i:s'),
    //                         'log_status'    => $log_status
    //                     );
    //                     $this->api_model->insert('assessment_upload_log',$jsonUpdtLog,$this->atomdb);
    //                     $json = array(
    //                         'success' => true,
    //                         'message' => "Assessment video log submitted successfully."
    //                     );
    //                 }else{
    //                     $json = array(
    //                         'success' => false,
    //                         'message' =>  "Assessment id, Transaction Id, Question Id is missing."
    //                     );
    //                 }
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }
    //         }catch(Exception $e){
    //             $json = array(
    //                 'success' => false,
    //                 'message' =>  $e->getMessage()
    //             );
    //         }
    //     }else{
    //         $json = array(
    //             'success'     => false,
    //             'message'     => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);  
    // }
    // public function send_email($subject,$body,$from_name="",$from_email="",$to_array="",$cc_array="",$bcc_array="",$atomdb=null){
    //     date_default_timezone_set('Asia/Calcutta');
    //     $where_clause = array(
    //         'status'  => 1
    //     );
    //     if ($atomdb==null){ 
    //         $SMTP               = $this->api_model->fetch_record('smtp',$where_clause);
    //     }else{
    //         $SMTP               = $this->api_model->fetch_record('smtp',$where_clause,$atomdb);
    //     }
    //     if (count((array)$SMTP)>0){
    //         $smtp_host          = $SMTP->smtp_ipadress;
    //         $smtp_port          = $SMTP->smtp_portno;
    //         $smtp_username      = $SMTP->smtp_username;
    //         $smtp_password      = $SMTP->smtp_password;
    //         $smtp_authenticate  = $SMTP->smtp_authentication;
    //         if ($SMTP->smtp_secure==1){
    //             $smtp_security      = 'ssl';
    //         }
    //         if ($SMTP->smtp_secure==2){
    //             $smtp_security      = 'tls';
    //         }
    //         if ($smtp_host=='' or $smtp_username=='' or $smtp_password==''){
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "Please configure SMTP Settings."
    //             );
    //             return json_encode($json); 
    //             exit;
    //         }

    //         //FETCH SENDER NAME
    //         $_to_name                 = '';
    //         $_to_email                = '';
    //         if ($to_array!=''){
    //             $email_seperated_by_comma = explode(",", $to_array);
    //             foreach($email_seperated_by_comma as $email_details_value){
    //                 $email_seperated_by_piep = explode("|", $email_details_value);
    //                 $_to_name                 = trim($email_seperated_by_piep[0]);
    //                 $_to_email                = trim($email_seperated_by_piep[1]);
    //             }
    //         }
    //         $email_template ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    //         <html xmlns="http://www.w3.org/1999/xhtml">
    //         <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    //                 <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    //                 <title>AWARATHON</title>
    //                 <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    //                 <meta name="description" content="Awarathon" />
    //                 <meta name="keywords" content="css3, css-only, fullscreen, background, slideshow, images, content" />
    //                 <meta name="author" content="" />
    //         </head>
    //         <body style="margin:0; padding:0;">
    //         <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:calibri;">
    //             <tr>
    //                 <td>
    //                     <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color:#f2f2f2; border:1px solid #ddd; font-family:calibri;" align="center">
    //                         <tr>
    //                             <td>
    //                                 <table width="580" border="0" cellspacing="0" cellpadding="0" align="center" style="margin:10px">
    //                                     <tr>
    //                                         <td><img src="https://mwadmin.awarathon.com/assets/images/header.jpg" alt="awarathon" /></td>
    //                                     </tr>
    //                                 </table>
    //                             </td>
    //                         </tr>
    //                         <tr>
    //                             <td>
    //                                 <table width="600" border="0" cellspacing="0" cellpadding="0" style="border-bottom:8px solid #da5918;">
    //                                 <tr>
    //                                     <td style="background-color:#e2763f; padding:20px 0; color:#fff; font-size:24px; font-weight:bold;">
    //                                         <span style="margin-left:10px; cursor:pointer;">'.$subject.'</span>
    //                                     </td>
    //                                 </tr>
    //                                 </table>
    //                             </td>
    //                         </tr>
    //                         <tr>
    //                             <td>
    //                                 <table width="580" border="0" cellspacing="2" cellpadding="2" align="center" style="font-size:16px; color:#828282; line-height:26px; text-align:justify;">
    //                                     <tr>
    //                                         <td>
    //                                             <p style="margin-bottom:20px;"><strong>Dear '.$_to_name.',</strong></p>
    //                                             <p style="margin-bottom:20px;">'.$body.'</p>
    //                                         </td>
    //                                     </tr>
    //                                 </table>
    //                             </td>
    //                         </tr>
    //                         <tr>
    //                             <td height="100px">&nbsp;</td>
    //                         </tr>
    //                         <tr>
    //                             <td>
    //                                 <table width="560" border="0" cellspacing="2" cellpadding="2" align="center" style="border-top:2px solid #dcdbdb; padding:10px 0px;">
    //                                     <tr>
    //                                         <td style="color:#47cfad; font-size:24px; font-weight:bold;">Need help? Get in touch</td>
    //                                         <td width="200px" rowspan="2" align="center"><img src="https://mwadmin.awarathon.com/assets/images/talk.png" alt="awarathon" /></td>
    //                                     </tr>
    //                                     <tr>
    //                                         <td valign="top" style="color:#2c2c2c; font-size:18px; font-weight:bold;">We are happy to help you. For any assistance contact us.</td>
    //                                     </tr>
    //                                 </table>
    //                             </td>
    //                         </tr>          
    //                     </table>
    //                 </td>
    //             </tr>    		
    //             <tr>
    //                 <td style="font-size:16px; color:#282828; text-align:center; padding:10px 0px;">     
    //                     awarathon.com
    //                 </td>
    //             </tr>
    //         </table>
    //         </body>
    //         </html>
    //         ';

    //         //PHP MAILER SETTINGS
    //         $this->load->library('PHPMailer');

    //         $mail = new PHPMailer;
    //         $mail->SMTPDebug =0;
    //         $mail->Debugoutput = 'html';
    //         //$mail->Debugoutput = function($str, $level) { echo $str; };
    //         $mail->CharSet = 'UTF-8';
    //         $mail->isSMTP();                                      
    //         $mail->Host         = $smtp_host;                       
    //         $mail->SMTPAuth     = $smtp_authenticate;                               
    //         $mail->Username     = $smtp_username;                 
    //         $mail->Password     = $smtp_password;  
    //         $mail->SMTPSecure   = $smtp_security;
    //         $mail->Port         = $smtp_port;
    //         $mail->XMailer      = ' ';
    //         $mail->SMTPOptions = array(
    //                 'ssl' => array(
    //                     'verify_peer'       => false,
    //                     'verify_peer_name'  => false,
    //                     'allow_self_signed' => true
    //                 )
    //         );
    //         //FROM
    //         $mail->setFrom($from_email, $from_name);

    //         //TO
    //         if ($to_array!=''){
    //             $email_seperated_by_comma = explode(",", $to_array);
    //             foreach($email_seperated_by_comma as $email_details_value){
    //                 $email_seperated_by_piep = explode("|", $email_details_value);
    //                 $to_name                 = trim($email_seperated_by_piep[0]);
    //                 $to_email                = trim($email_seperated_by_piep[1]);
    //                 if ($to_name!=='' AND $to_email!==''){
    //                     $mail->addAddress($to_email, $to_name);
    //                 }
    //             }
    //         }

    //         //CC
    //         if ($cc_array!=''){
    //             $email_seperated_by_comma = explode(",", $cc_array);
    //             foreach($email_seperated_by_comma as $email_details_value){
    //                 $email_seperated_by_piep = explode("|", $email_details_value);
    //                 $cc_name                 = trim($email_seperated_by_piep[0]);
    //                 $cc_email                = trim($email_seperated_by_piep[1]);
    //                 if ($cc_name!=='' AND $cc_email!==''){
    //                     $mail->addCC($cc_email, $cc_name);
    //                 }

    //             }
    //         }

    //         //BCC
    //         //$mail->addCustomHeader("BCC: mybccaddress@mydomain.com");
    //         if ($bcc_array!=''){
    //             $email_seperated_by_comma = explode(",", $bcc_array);
    //             foreach($email_seperated_by_comma as $email_details_value){
    //                 $email_seperated_by_piep = explode("|", $email_details_value);
    //                 $bcc_name                = trim($email_seperated_by_piep[0]);
    //                 $bcc_email               = trim($email_seperated_by_piep[1]);
    //                 if ($bcc_name!=='' AND $bcc_email!==''){
    //                     $mail->addBCC($bcc_email, $bcc_name);
    //                 }
    //             }
    //         }

    //         $mail->isHTML(true);
    //         $mail->SMTPKeepAlive = true;
    //         $mail->CharSet       = 'UTF-8';
    //         $mail->Subject       = $subject;
    //         $mail->Body          = $email_template;
    //         $mail->AltBody       = $subject;
    //         $mail->Mailer        = "smtp";
    //         $issend              = $mail->send();

    //         if (!$issend){
    //             $json = array(
    //                 'success' => false,
    //                 'message' => $mail->ErrorInfo
    //             );
    //             return json_encode($json); 
    //             exit;
    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => $mail->ErrorInfo
    //             );
    //             return json_encode($json); 
    //             exit;
    //         }
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => 'SMTP details are missing.'
    //         );
    //         return json_encode($json); 
    //         exit;
    //     }
    // }
    // public function cronjob_tokbox($company_id){
    //     $apiKey    = $this->config->item('tokbox_apikey');
    //     $apiSecret = $this->config->item('tokbox_apisecret');
    //     $opentok   = new OpenTok($apiKey, $apiSecret);
    //     $where_clause = array(
    //         'status' => 1,
    //         'id'    => $company_id
    //     );
    //     $co_common_result=$this->api_model->fetch_results('company',$where_clause);
    //     if (count((array)$co_common_result)>0){
    //         foreach ($co_common_result as $co_row) {
    //             $company_id   = $co_row->id;
    //             $this->crondb = $this->api_model->connectCo($company_id);

    //             $where_clause = array(
    //                 'status' => 1,
    //                 'session_id' => 1
    //             );
    //             $assessment_results = $this->api_model->fetch_assessment_results($this->crondb);
    //             if (count((array)$assessment_results) >0) {
    //                 foreach ($assessment_results as $ass_row) {
    //                     echo "<pre>";
    //                     print_r($ass_row);
    //                     //KRISHNA -- REMOVE VIDEO FROM VONAGE
    //                     $assessment_results_id= $ass_row->id;
    //                     $session_id           = $ass_row->session_id;
    //                     $token_id             = $ass_row->token_id;
    //                     $archive_id           = $ass_row->archive_id;
    //                     $vimeo_video_id       = $ass_row->video_url;

    //                     $archive_details      = $opentok->getArchive($archive_id);
    //                     print_r($archive_details);
    //                     $_archive_id          = $archive_details->id;
    //                     $_archive_status      = $archive_details->status;
    //                     $_archive_name        = $archive_details->name;
    //                     $_archive_reason      = $archive_details->reason;
    //                     $_archive_session_id  = $archive_details->sessionId;
    //                     $_archive_project_id  = $archive_details->projectId;
    //                     $_archive_created_at  = $archive_details->createdAt;
    //                     $_archive_size        = $archive_details->size;
    //                     $_archive_duration    = $archive_details->duration;
    //                     $_archive_output_mode = $archive_details->outputMode;
    //                     $_archive_has_audio   = $archive_details->hasAudio;
    //                     $_archive_has_video   = $archive_details->hasVideo;
    //                     $_archive_sha256sum   = $archive_details->sha256sum;
    //                     $_archive_password    = $archive_details->password;
    //                     $_archive_updated_at  = $archive_details->updatedAt;
    //                     $_archive_resolution  = $archive_details->resolution;
    //                     $_archive_partner_id  = $archive_details->partnerId;
    //                     $_archive_event       = $archive_details->event;
    //                     // $_archive_url         = $archive_details->url?json_encode($singleArchive->url):'';

    //                     //CHECK USERS VIDEO RECORDING HAS BEEN COMPLETED TO THE TOKBOX SUCCESSFULLY. 
    //                     if ($_archive_status=='completed'){
    //                         //CHECK VIDEO HAS BEEN UPLOADED TO THE VIMEO SUCCESSFULLY. 

    //                         //ONCE VIDEO HAS BEEN UPLOADED TO THE VIMEO SUCCESSFULLY. DELETE FILE FROM OPENTOK SERVER
    //                         $res = $opentok->deleteArchive($archive_id);
    //                         print_r($res);

    //                         $where_clause = array(
    //                             'id' => $assessment_results_id
    //                         ); 
    //                         $assessment_results_data = array(
    //                             'tokbox_status'    => 1
    //                         );
    //                         $qres = $this->api_model->update('assessment_results',$where_clause,$assessment_results_data,$this->crondb);
    //                         print_r($qres);
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }
    // public function drreddys_auto_login(){
    //     $_jsonObj = file_get_contents('php://input');
    //     if ($_jsonObj){
    //         $_POST = json_decode($_jsonObj, TRUE);

    //         $data         = array();
    //         $json         = array();
    //         $webapp_id = $this->input->post('id');

    //         $where_clause = array(
    //             'status'  => 1,
    //             'block'   => 0,
    //             'emp_id' => $webapp_id
    //         );
    //         $row          = $this->api_model->fetch_record('device_users',$where_clause);

    //         $db_password  = '';
    //         if (count((array)$row) > 0) {
    //             $common_user_id = $row->id;
    //             $user_id        = $row->user_id;
    //             $company_id     = $row->company_id;
    //             $firstname      = $row->firstname;
    //             $lastname       = $row->lastname;
    //             $email          = $row->email;
    //             $mobile         = $row->mobile;
    //             $avatar         = $row->avatar;

    //             //GENERATE TOKEN OF USER FOR SECURITY AND UPDATE
    //             $token = $common_user_id.".".bin2hex(openssl_random_pseudo_bytes(16));

    //             $this->atomdb = $this->api_model->connectDb($common_user_id);

    //             //CHECK COMPANY HAS ENABLED EMPLOYEE VERIFICATION BY SENDING OPT VIA EMAIL.
    //             $co_where_clause = array(
    //                 'id'     => $company_id,
    //                 'status' => 1
    //             );
    //             if ($company_id=='' OR $company_id<=0){
    //                 $otp_verified = 0;
    //             }else{
    //                 $otp_verified = $row->otp_verified;
    //                 $eotp_result = $this->api_model->fetch_record('company', $co_where_clause,$this->atomdb);
    //                 if (count((array)$eotp_result) > 0) {
    //                     if ($eotp_result->eotp_required==0){
    //                         $otp_verified = 1;
    //                     }
    //                 }
    //             }

    //             $token_data = array(
    //                 'token'   => $token
    //             );
    //             //COMMON
    //             $where_clause = array(
    //                 'id' => $common_user_id
    //             ); 
    //             $update_status    = $this->api_model->update('device_users', $where_clause, $token_data);

    //             //DOMAIN
    //             $where_clause = array(
    //                 'user_id' => $user_id
    //             );
    //             $update_status_ii = $this->api_model->update('device_users', $where_clause, $token_data,$this->atomdb);

    //             //CHECK IF DEVICE DETAILS HAS CHANGES THEN INSERT NEW ELSE UPDATE
    //             //COMMON
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $common_user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1,
    //                         'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                         'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                         'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                         'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                         'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                         'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                         'info_dttm'          => date('Y-m-d H:i:s')
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $common_user_id,
    //                         'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                         'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                     ); 
    //                     $device_found =$this->api_model->record_count('device_info',$where_clause);
    //                     if ($device_found>0){
    //                         $this->api_model->update('device_info',$where_clause,$jsonDevice);
    //                     }else{
    //                         $this->api_model->insert('device_info',$jsonDevice);
    //                     }
    //                 }
    //             }

    //             //DOMAIN
    //             if (isset($_POST['device_info'])){
    //                 $device_information = json_decode($this->input->post('device_info'));
    //                 if (count((array)$device_information)>0){
    //                     $jsonDevice = array(
    //                         'user_id'            => $user_id,
    //                         'app_name'           => isset($device_information->app_name)?$device_information->app_name:'',
    //                         'package_name'       => isset($device_information->package_name)?$device_information->package_name:'',
    //                         'version_code'       => isset($device_information->version_code)?$device_information->version_code:'',
    //                         'version_number'     => isset($device_information->version_number)?$device_information->version_number:'',
    //                         'cordova'            => isset($device_information->cordova)?$device_information->cordova:'',
    //                         'model'              => isset($device_information->model)?$device_information->model:'',
    //                         'platform'           => isset($device_information->platform)?$device_information->platform:'',
    //                         'uuid'               => isset($device_information->uuid)?$device_information->uuid:'',
    //                         'imei'               => isset($device_information->imei)?$device_information->imei:'',
    //                         'imsi'               => isset($device_information->imsi)?$device_information->imsi:'',
    //                         'iccid'              => isset($device_information->iccid)?$device_information->iccid:'',
    //                         'mac'                => isset($device_information->mac)?$device_information->mac:'',
    //                         'version'            => isset($device_information->version)?$device_information->version:'',
    //                         'manufacturer'       => isset($device_information->manufacturer)?$device_information->manufacturer:'',
    //                         'is_virtual'         => isset($device_information->is_virtual)?$device_information->is_virtual:'',
    //                         'serial'             => isset($device_information->serial)?$device_information->serial:'',
    //                         'memory'             => isset($device_information->memory)?$device_information->memory:'',
    //                         'cpumhz'             => isset($device_information->cpumhz)?$device_information->cpumhz:'',
    //                         'totalstorage'       => isset($device_information->totalstorage)?$device_information->totalstorage:'',
    //                         'registered_account' => '',
    //                         'user_agent'         => '',
    //                         'status'             => 1,
    //                         'ip_address'         => isset($device_information->ip_address)?$device_information->ip_address:'',
    //                         'latitude'           => isset($device_information->latitude)?$device_information->latitude:'',
    //                         'longitude'          => isset($device_information->longitude)?$device_information->longitude:'',
    //                         'browser_agent'      => isset($device_information->browser_agent)?$device_information->browser_agent:'',
    //                         'time_open'          => isset($device_information->time_open)?$device_information->time_open:'',
    //                         'organisation'       => isset($device_information->organisation)?$device_information->organisation:'',
    //                         'info_dttm'          => date('Y-m-d H:i:s')
    //                     );
    //                     $where_clause = array(
    //                         'user_id' => $user_id,
    //                         'serial'  => isset($device_information->serial)?$device_information->serial:'',
    //                         'uuid'    => isset($device_information->uuid)?$device_information->uuid:'',
    //                     ); 
    //                     $device_found =$this->api_model->record_count('device_info',$where_clause,$this->atomdb);
    //                     if ($device_found>0){
    //                         $this->api_model->update('device_info',$where_clause,$jsonDevice,$this->atomdb);
    //                     }else{
    //                         $this->api_model->insert('device_info',$jsonDevice,$this->atomdb);
    //                     }
    //                 }
    //             }

    //             if ($update_status AND $update_status_ii){
    //                 $payload      = $this->generate_payload($common_user_id,$token);
    //                 $portal_name  = '';
    //                 $domin_url    = '';
    //                 $company_name = '';
    //                 $company_logo = '';
    //                 $whereclause  = array('id' => $company_id);
    //                 $company_data = $this->api_model->fetch_record('company',$whereclause,$this->atomdb);
    //                 if(count((array)$company_data)>0){
    //                     $company_name = $company_data->company_name;
    //                     $company_logo = $company_data->company_logo;
    //                     $portal_name  = $company_data->portal_name;
    //                     $domin_url    = $company_data->domin_url;
    //                 }
    //                 $company_path = '';                      
    //                 if ($company_logo ==''){
    //                     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                 }else{
    //                     // $file_path_check = "../".$portal_name."/assets/uploads/company/".$company_logo;
    //                     $file_path = "/assets/uploads/company/".$company_logo;
    //                     // if (file_exists($file_path_check)){
    //                         $company_path = $domin_url.$file_path;
    //                     // }else{
    //                     //     $company_path = $domin_url."/assets/uploads/company/no-logo.jpg";
    //                     // } 
    //                 }

    //                 $avatar_path = '';                      
    //                 if ($avatar ==''){
    //                     $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                 }else{
    //                     $file_path_check = "../".$portal_name."/assets/uploads/avatar/".$avatar;
    //                     $file_path = "/assets/uploads/avatar/".$avatar;
    //                     if (file_exists($file_path_check)){
    //                         $avatar_path = $domin_url.$file_path;
    //                     }else{
    //                         $avatar_path = $domin_url."/assets/uploads/avatar/no-avatar.jpg";
    //                     } 
    //                 }                               
    //                 $json = array(
    //                     'success'       => true,
    //                     'message'       => "Welcome to the Awarathon",
    //                     'user_id'       => $common_user_id,
    //                     'company_id'    => $company_id,
    //                     'company_name'  => $company_name,
    //                     'firstname'     => $firstname,
    //                     'lastname'      => $lastname,
    //                     'email'         => $email,
    //                     'mobile'        => $mobile,
    //                     'avatar'        => $avatar_path,
    //                     'co_logo'       => $company_path,
    //                     'superaccess'   => false,
    //                     'token_no'      => $token,
    //                     'payload'       => $payload,
    //                     'otp_pending'   => $otp_verified==1?0:1,
    //                     'otc_pending'   => ($company_id!='' AND $company_id>0)?0:1,
    //                     'co_pending'    => ($company_id!='' AND $company_id>0)?0:1,
    //                     'webauto_login' => 1
    //                 );
    //             }else{
    //                 $json = array(
    //                     'success' => false,
    //                     'message' => "Invalid Token"
    //                 );
    //             }

    //         }else{
    //             $json = array(
    //                 'success' => false,
    //                 'message' => "Invalid username/password."
    //             );
    //         }  
    //     }else{
    //         $json = array(
    //             'success' => false,
    //             'message' => "Unable to post data either it is empty or not set."
    //         );
    //     }
    //     echo json_encode($json);
    // }
    // public function update_loginid(){
    // 	// echo "start";
    //     $this->atomdb = $this->api_model->connectCo(67);
    //     $_mwadmin_results = $this->api_model->fetch_drreddy_users();
    //     if (count((array)$_mwadmin_results)>0){
    //         foreach ($_mwadmin_results as $mwdata) {
    //             $user_id = $mwdata->user_id;
    //             $where_clause = array(
    //                 'company_id' => 67,
    //                 'user_id'    => $user_id,
    //             );
    //             $user_details = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_details)>0){
    //                 $emp_id         = $user_details->emp_id;
    //                 $drreddy_data = array(
    //                     'emp_id'   => $emp_id
    //                 );
    //                 $where_clause = array(
    //                     'company_id' => 67,
    //                     'user_id'    => $user_id
    //                 );
    //                 $this->api_model->update('device_users', $where_clause, $drreddy_data);
    //             }            
    //         }                              
    //     }      
    // }
    // public function send_notification_for_video_failed($company_id,$assessment_id,$user_id){
    //     // if ($company_id!='' AND ($company_id==59 OR $company_id=="59")){
    //         $this->atomdb = $this->api_model->connectCo($company_id);
    //         $is_sent         = 0;

    //         //REOPEN
    //         $where_clause = array(
    //             'company_id'    => $company_id,
    //             'assessment_id' => $assessment_id,
    //             'user_id'       => $user_id
    //         );
    //         $assessment_notification_result = $this->api_model->fetch_record('assessment_notification',$where_clause,$this->atomdb);
    //         if (count((array)$assessment_notification_result)>0){
    //             $is_sent   = $assessment_notification_result->is_sent;
    //             $sent_dttm = $assessment_notification_result->sent_dttm;
    //             $_sent_time = strtotime($sent_dttm);
    //             $_current_time = strtotime(date('Y-m-d H:i:s'));
    //             $mins = ($_current_time - $_sent_time) / 60;
    //             if ($mins > 60){
    //                 $is_sent     = 0;
    //                 $system_dttm = date('Y-m-d H:i:s');
    //                 $data = array(
    //                     'is_sent'   => 0,
    //                     'sent_dttm' => $system_dttm
    //                 );
    //                 $where_clause = array(
    //                     'company_id'    => $company_id,
    //                     'assessment_id' => $assessment_id,
    //                     'user_id'       => $user_id
    //                 ); 
    //                 $update_status    = $this->api_model->update('assessment_notification', $where_clause, $data,$this->atomdb);
    //             }
    //         }
    //         if ($is_sent==0){
    //             $company_name             = "";
    //             $username                 = "";
    //             $email_address            = "";
    //             $assessment_name          = "";
    //             $is_completed             = 0;
    //             $ftpto_vimeo_uploaded     = 0;
    //             $total_questions          = 0;
    //             $total_question_attampted = 0;

    //             $where_clause = array(
    //                 'id' => $company_id
    //             );
    //             $company_result = $this->api_model->fetch_record('company',$where_clause,$this->atomdb);
    //             if (count((array)$company_result)>0){
    //                 $company_name = $company_result->company_name;
    //             }

    //             $where_clause = array(
    //                 'user_id' => $user_id
    //             );
    //             $user_result = $this->api_model->fetch_record('device_users',$where_clause,$this->atomdb);
    //             if (count((array)$user_result)>0){
    //                 $username      = $user_result->firstname." ".$user_result->lastname;
    //                 $email_address = $user_result->email;
    //             }

    //             $where_clause = array(
    //                 'id' => $assessment_id
    //             );
    //             $assessment_result = $this->api_model->fetch_record('assessment_mst',$where_clause,$this->atomdb);
    //             if (count((array)$assessment_result)>0){
    //                 $assessment_name = $assessment_result->assessment;
    //             }


    //             $_total_questions_results = $this->api_model->fetch_assessment_question_count($assessment_id,$this->atomdb);
    //             if (count((array)$_total_questions_results)>0){
    //                 foreach ($_total_questions_results as $totque) {
    //                     $total_questions = $totque->total;
    //                 }
    //             }

    //             $where_clause = array(
    //                 'assessment_id' => $assessment_id,
    //                 'user_id'       => $user_id
    //             );
    //             $assessment_attempts_result = $this->api_model->fetch_record('assessment_attempts',$where_clause,$this->atomdb);
    //             if (count((array)$assessment_attempts_result)>0){
    //                 $is_completed         = $assessment_attempts_result->is_completed;
    //                 $ftpto_vimeo_uploaded = $assessment_attempts_result->ftpto_vimeo_uploaded;
    //             }
    //             $where_clause = array(
    //                 'company_id'    => $company_id,
    //                 'assessment_id' => $assessment_id,
    //                 'user_id'       => $user_id
    //             );
    //             $assessment_results = $this->api_model->fetch_record('assessment_results',$where_clause,$this->atomdb);
    //             $total_question_attampted = count((array)$assessment_results);

    //             if ($total_question_attampted < $total_questions){
    //                 //SEND EMAIL
    //                 date_default_timezone_set('Asia/Kolkata');
    //                 $email_html = "<html><body>
    //                 Dear ".$username.",<br/><br/>
    //                 The videos submitted by you for ".$assessment_name." do not qualify our quality check. Requesting you to kindly re-attempt the assessment. Thank you!
    //                 <br/><br/>Best,<br/>
    //                 ".$company_name."</body></html>";


    //                 $subject = "Re-attempt Notification";
    //                 $this->load->library('My_PHPMailer');
    //                 $mail                       =  new PHPMailer;
    //                 $mail->SMTPDebug            =  0;
    //                 $mail->isSMTP();
    //                 $mail->Host                 =  "send.smtp.com";
    //                 $mail->SMTPAuth             =  "1";
    //                 $mail->Username             =  "no-reply@awarathon.com";
    //                 $mail->Password             =  "atom1001";
    //                 $mail->SMTPSecure           =  "tls";
    //                 $mail->Port                 =  "587";
    //                 $mail->XMailer              =  ' ';
    //                 $mail->SMTPOptions          =  array(
    //                     'ssl'                   => array(
    //                         'verify_peer'       => false,
    //                         'verify_peer_name'  => false,
    //                         'allow_self_signed' => true
    //                     )
    //                 );

    //                 $mail->addAddress($email_address, $username);

    //                 // $mail->addBCC("divyesh@mworks.in", "Divyesh Panchal");
    //                 // $mail->addCC($email_address, $username);
    //                 // $mail->addAddress("divyesh@mworks.in", "Divyesh Panchal");

    //                 $mail->isHTML(true);
    //                 $mail->setFrom("no-reply@awarathon.com", "Awarathon");
    //                 $mail->SMTPKeepAlive        =  true;
    //                 $mail->CharSet              =  'UTF-8';
    //                 $mail->Subject              =  $subject;
    //                 $mail->Body                 =  $email_html;
    //                 $mail->AltBody              =  $subject;
    //                 $mail->Mailer               =  "smtp";
    //                 $MailFlag                   =  $mail->send();

    //                 //COMMON
    //                 $where_clause = array(
    //                     'company_id'    => $company_id,
    //                     'assessment_id' => $assessment_id,
    //                     'user_id'       => $user_id
    //                 );
    //                 $system_dttm = date('Y-m-d H:i:s');
    //                 $assessment_notification_result = $this->api_model->fetch_record('assessment_notification',$where_clause,$this->atomdb);
    //                 if (count((array)$assessment_notification_result)>0){
    //                     $data = array(
    //                         'is_sent'   => 1,
    //                         'sent_dttm' => $system_dttm
    //                     );
    //                     $where_clause = array(
    //                         'company_id'    => $company_id,
    //                         'assessment_id' => $assessment_id,
    //                         'user_id'       => $user_id
    //                     ); 
    //                     $update_status    = $this->api_model->update('assessment_notification', $where_clause, $data,$this->atomdb);
    //                 }else{
    //                     $data = array(
    //                         'company_id'    => $company_id,
    //                         'assessment_id' => $assessment_id,
    //                         'user_id'       => $user_id,
    //                         'is_sent'       => 1,
    //                         'sent_dttm'     => $system_dttm
    //                     );
    //                     $this->api_model->insert('assessment_notification',$data,$this->atomdb);
    //                 }


    //             }
    //         }
    //     // }
    // }
    // //To add device user statistics - CRON executes every night
    // public function fetch_user_statistics($company_id){
    //     $Success=1;
    // 	$Message='';
    // 	if (isset($company_id) AND $company_id!==""){
    //         try {

    //             //OPEN CLIENT DATABASE
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             $now                  = date('Y-m-d H:i:s');
    //             $total_users          = 0;
    //             $total_active_users   = 0;
    //             $total_inactive_users = 0;

    //             //TOTAL USERS
    //             $where_clause = array(
    //                 'istester' => 0,
    //             ); 
    //             $results_total_users = $this->api_model->fetch_results('device_users',$where_clause,$this->atomdb);
    //             $total_users = count($results_total_users);

    //             //TOTAL ACTIVE USERS
    //             $where_clause = array(
    //                 'istester' => 0,
    //                 'status'   => 1,
    //             ); 
    //             $results_active_users = $this->api_model->fetch_results('device_users',$where_clause,$this->atomdb);
    //             $total_active_users = count($results_active_users);

    //             //TOTAL IN-ACTIVE USERS
    //             $where_clause = array(
    //                 'istester' => 0,
    //                 'status'   => 0,
    //             ); 
    //             $results_inactive_users = $this->api_model->fetch_results('device_users',$where_clause,$this->atomdb);
    //             $total_inactive_users = count($results_inactive_users);

    //             $json_user_statistics = array(
    //                 'company_id'     => $company_id,
    //                 'total_users'    => $total_users,
    //                 'active_users'   => $total_active_users,
    //                 'inactive_users' => $total_inactive_users,
    //                 'system_dttm'    => $now
    //             );
    //             $user_statistics_id =  $this->api_model->insert('device_users_statistics',$json_user_statistics,$this->atomdb); 

    //             $Message='User statistics updated Successfully';

    //         }catch(Exception $e) {
    //         }  
    //     }else{
    // 		$Success=0;
    // 		$Message='Invalid Company ID';
    // 	}
    // }

    // //BILLING MODULE - Add CMS/DEVICE users statistics - CRON executes every night
    // public function fetch_all_user_statistics($company_id){
    //     $Success=1;
    // 	$Message='';
    // 	if (isset($company_id) AND $company_id!==""){
    //         try {

    //             //OPEN CLIENT DATABASE
    //             $this->atomdb = $this->api_model->connectCo($company_id);

    //             $now                  = date('Y-m-d H:i:s');
    //             $total_users          = 0;
    //             $total_active_users   = 0;
    //             $total_inactive_users = 0;
    //             $result_last_users    = 0;
    //             $total_users_with_attempts = 0;

    //             //TOTAL USERS
    //             $results_total_users = $this->api_model->fetch_total_users_statistics($this->atomdb);
    //             if(!empty($results_total_users)){
    //                 $total_users = $results_total_users[0]->total;
    //                 $total_active_users = $results_total_users[0]->active;
    //                 $total_inactive_users = $results_total_users[0]->inactive;
    //             }

    //             $result_last_added_users = $this->api_model->fetch_last_added_users($this->atomdb,date('Y-m-d', strtotime($now .' -1 day')));
    //             if(!empty($result_last_added_users)){
    //                 $result_last_users = $result_last_added_users[0]->latest_users;
    //             }

    //             $total_attempt_users = $this->api_model->fetch_users_with_attempts($this->atomdb,date('Y-m-d', strtotime($now .' -1 day')));
    //             if(!empty($total_attempt_users)){
    //                 $total_users_with_attempts = $total_attempt_users[0]->user_attempted;
    //             }

    //             $json_user_statistics = array(
    //                 'company_id'     => $company_id,
    //                 'total_users'    => $total_users,
    //                 'active_users'   => $total_active_users,
    //                 'inactive_users' => $total_inactive_users,
    //                 'played_users'   => $total_users_with_attempts,
    //                 'last_added'     => $result_last_users,
    //                 'system_dttm'    => $now
    //             );
    //             $user_statistics_id =  $this->api_model->insert('users_statistics',$json_user_statistics,$this->atomdb); 

    //             $Message='User statistics updated Successfully';

    //         }catch(Exception $e) {
    //         }  
    //     }else{
    // 		$Success=0;
    // 		$Message='Invalid Company ID';
    // 	}
    // }

    // //NOTIFICATION MODULE -- Reminder Email to Reps & Managers 
    // public function send_assessment_reminder($company_id){
    //     if($company_id !=''){
    //         $this->atomdb = $this->api_model->connectCo($company_id);

    //         $day_left =  'One day';
    //         $tomorrow = date( "Y-m-d", strtotime( "+1 days" ) );
    //         $where_clause = array(
    //             'status' => 1,
    //             'date(end_dttm)'=>$tomorrow
    //         );
    //         $assesment_set_1 = $this->api_model->fetch_results('assessment_mst',$where_clause,$this->atomdb);

    //         $day_left =  'Four days';
    //         $where_clause = array(
    //             'status' => 1,
    //             'date(end_dttm)'=>date( "Y-m-d", strtotime( "+4 days" ))
    //         );
    //         $assesment_set_4 = $this->api_model->fetch_results('assessment_mst',$where_clause,$this->atomdb);

    //         $assesment_set = array_merge($assesment_set_1, $assesment_set_4);

    //         //get web url to redirect on assessment
    //         $get_web_url = $this->api_model->fetch_record('company',['id'=>$company_id],'');
    //         $web_url = (!empty($get_web_url) && !empty($get_web_url->web_url)) ? $get_web_url->web_url : 'https://pwa.awarathon.com';

    //         if(count($assesment_set) > 0){
    //             foreach($assesment_set as $value){

    //                 //Pending Users
    //                 $PendingUserList = $this->api_model->get_notPlayedAssUsers($value->id,$this->atomdb);
    //                 if(count($PendingUserList)>0){
    //                     $emailTemplate = $this->api_model->fetch_record('auto_emails',array('status'=>1,'alert_name'=>'reminder_assessment_(for_rep)'),$this->atomdb);
    //                     if(!empty($emailTemplate)){
    //                         $pattern[0] = '/\[SUBJECT\]/';
    //                         $pattern[1] = '/\[ASSESSMENT_NAME\]/';
    //                         $pattern[2] = '/\[ASSESSMENT_LINK\]/';
    //                         $pattern[3] = '/\[NAME\]/';
    //                         $pattern[4] = '/\[DAY\]/';
    //                         //$pattern[4] = '/\[DATE_TIME\]/';
    //                         $replacement[0] = $emailTemplate->subject;
    //                         $replacement[1] = $value->assessment;

    //                         $end_date = new DateTime($value->end_dttm);
    //                         $current_date = new DateTime();
    //                         $diff = $end_date->diff($current_date)->format("%d");

    //                         $from_name= $emailTemplate->fromname;
    //                         $from_email=$emailTemplate->fromemail;
    //                         foreach($PendingUserList as $value2){
    //                             $replacement[2] = '<a target="_blank" style="display: inline-block;
    //                             background: #db1f48;
    //                             padding: .45rem 1rem;
    //                             box-sizing: border-box;
    //                             border: none;
    //                             border-radius: 3px;
    //                             color: #fff;
    //                             text-align: center;
    //                             font-family: Lato,Arial,sans-serif;
    //                             font-weight: 400;
    //                             font-size: 1em;
    //                             text-decoration:none;
    //                             line-height: initial;" href="'.$web_url.'">View Assignment</a>';
    //                             $replacement[3] = $value2->firstname;
    //                             $replacement[4] = ($diff==1) ? 'one day' : 'four days';
    //                             //$replacement[4] = date("d-m-Y h:i a", strtotime($assessment_set->start_dttm));
    //                             $ToName = $value2->firstname;
    //                             $email_to = $value2->email;
    //                             // $email_to = 'krishna.revawala@awarathon.com';
    //                             $message = $emailTemplate->message;
    //                             $subject =$emailTemplate->subject;
    //                             $body = preg_replace($pattern, $replacement, $message);

    //                             $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $ToName, $email_to, $subject, $body,$this->atomdb);
    //                             // echo "<pre>";
    //                             // print_r($ReturnArray1);
    //                             // exit;
    //                             //Add log - reminder email sent to Rep
    //                             $log_data = [
    //                                 'assessment_id' => $value->id,
    //                                 'user_id' => $value2->user_id,
    //                                 'is_manager' => 0,
    //                                 'mail_dttm' => date('Y-m-d H:i:s')
    //                             ];
    //                             $this->api_model->insert('assessment_reminder_logs',$log_data,$this->atomdb);
    //                         }
    //                     }
    //                 }

    //                 //Pending Managers to Rate assessment
    //                 $PendingratingList = $this->api_model->get_notRatingAssTariner($value->id,$this->atomdb);
    //                 if(count($PendingratingList)>0){
    //                     $emailTemplate = $this->api_model->fetch_record('auto_emails',array('status'=>1,'alert_name'=>'reminder_for_ratting_(for_manager)'),$this->atomdb);
    //                     if(!empty($emailTemplate)){
    //                         $pattern[0] = '/\[SUBJECT\]/';
    //                         // $pattern[1] = '/\[ASSESSMENT_NAME\]/';
    //                         $pattern[2] = '/\[ASSESSMENT_LINK\]/';
    //                         $pattern[3] = '/\[NAME\]/';
    //                         $pattern[4] = '/\[EXPIRE_DATE\]/';
    //                         $replacement[0] = $emailTemplate->subject;
    //                         // $replacement[1] = $value->assessment;
    //                         $from_name= $emailTemplate->fromname;
    //                         $from_email=$emailTemplate->fromemail;
    //                         foreach($PendingratingList as $value2){
    //                             $replacement[2] = '<a target="_blank" style="display: inline-block;
    //                             background: #db1f48;
    //                             padding: .45rem 1rem;
    //                             box-sizing: border-box;
    //                             border: none;
    //                             border-radius: 3px;
    //                             color: #fff;
    //                             text-align: center;
    //                             font-family: Lato,Arial,sans-serif;
    //                             font-weight: 400;
    //                             font-size: 1em;
    //                             text-decoration:none;
    //                             line-height: initial;" href="'.$web_url.'">View Assignment</a>';
    //                             $replacement[3] = $value2->trainer_name;
    //                             $replacement[4] = date("d-m-Y h:i a", strtotime($value->end_dttm));

    //                             $ToName = $value2->trainer_name;
    //                             $email_to = $value2->email;
    //                             $message = $emailTemplate->message;
    //                             $subject =$emailTemplate->subject;
    //                             $body = preg_replace($pattern, $replacement, $message);

    //                             // $ToName ="Krishna";
    //                             // $email_to ="krishna.revawala@awarathon.com";
    //                             // $to_array    = $ToName."|".$email_to;
    //                             $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $ToName, $email_to, $subject, $body,$this->atomdb);
    //                             // echo "<pre>";
    //                             // print_r($ReturnArray1);
    //                             // exit;
    //                             //Add log - rating reminder email sent to Manager
    //                             $log_data = [
    //                                 'assessment_id' => $value->id,
    //                                 'user_id' => $value2->trainer_id,
    //                                 'is_manager' => 1,
    //                                 'mail_dttm' => date('Y-m-d H:i:s')
    //                             ];
    //                             $this->api_model->insert('assessment_reminder_logs',$log_data,$this->atomdb);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }   
    // }

    //NOTIFICATION MODULE -- Send scehduled Notifications to Reps & Managers 
    public function send_scheduled_notifications($company_id)
    {
        if ($company_id != '') {
            $this->atomdb = $this->api_model->connectCo($company_id);

            $cron_status = $this->api_model->check_notification_schedule_cron($company_id, $this->atomdb);
           if (!empty($cron_status)) {
                $is_scheduled = $cron_status[0]->schedule_status;
                $is_running = $cron_status[0]->cron_status;
                if ($is_scheduled == 0 && $is_running == 0) {
                    //execute the email schedule code
                    $this->api_model->update('notification_schedule_cron', [], ['cron_status' => 1], $this->atomdb);

                    $users_list = $this->api_model->get_scheduled_notify_Users($company_id, $this->atomdb);
                    if (!empty($users_list)) {
                        //get domain url to redirect on assessment
                        $get_domain_url = $this->api_model->fetch_record('company', ['id' => $company_id], '');
                        $domain_url = (!empty($get_domain_url) && !empty($get_domain_url->domin_url)) ? $get_domain_url->domin_url : '';
                        $web_url = (!empty($get_domain_url) && !empty($get_domain_url->web_url)) ? $get_domain_url->web_url : 'https://pwa.awarathon.com';
                        foreach ($users_list as $user) {
                            if ($user->alert_name == 'assessment_date_extension_mail-rep') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_LINK\]/';
                                $pattern[3] = '/\[NAME\]/';
                                $pattern[4] = '/\[DATE_TIME\]/';
                                $pattern[5] = '/\[Client_mail_id\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $assessment->assessment;
                                    // $replacement[2] = '';
                                    $replacement[2] = '<a target="_blank" style="display: inline-block;
                                            background: #db1f48;
                                            padding: .45rem 1rem;
                                            box-sizing: border-box;
                                            border: none;
                                            border-radius: 3px;
                                            color: #fff;
                                            text-align: center;
                                            font-family: Lato,Arial,sans-serif;
                                            font-weight: 400;
                                            font-size: 1em;
                                            text-decoration:none;
                                            line-height: initial;" href="' . $web_url . '">View Assignment</a>';
                                    $replacement[3] = $user->user_name;
                                    $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->end_dttm));
                                    $replacement[5] = ($company_id == 67) ? 'eNablehelpdesk@drreddys.com' : 'info@awarathon.com';   //customization email for Dr Reddy
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);

                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);
                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'assessment_date_extension_mail-manager') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_LINK\]/';
                                $pattern[3] = '/\[NAME\]/';
                                $pattern[4] = '/\[DATE_TIME\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $assessment->assessment;
                                    $replacement[2] = '<a target="_blank" style="display: inline-block;
                                                width: 200px;
                                                height: 20px;
                                                background: #db1f48;
                                                padding: 10px;
                                                text-align: center;
                                                border-radius: 5px;
                                                color: white;
                                                border: 1px solid black;
                                                text-decoration:none;
                                                font-weight: bold;" href="' . $domain_url . '/assessment/view/' . base64_encode($user->assessment_id) . '/2">View Assignment</a>';
                                    $replacement[3] = $user->user_name;
                                    $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->end_dttm));
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);
                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'assessment_created_rep') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_LINK\]/';
                                $pattern[3] = '/\[NAME\]/';
                                $pattern[4] = '/\[DATE_TIME\]/';
                                $pattern[5] = '/\[Client_mail_id\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $assessment->assessment;
                                    // $replacement[2] = '';
                                    $replacement[2] = '<a target="_blank" style="display: inline-block;
                                                background: #db1f48;
                                                padding: .45rem 1rem;
                                                box-sizing: border-box;
                                                border: none;
                                                border-radius: 3px;
                                                color: #fff;
                                                text-align: center;
                                                font-family: Lato,Arial,sans-serif;
                                                font-weight: 400;
                                                font-size: 1em;
                                                text-decoration:none;
                                                line-height: initial;" href="' . $web_url . '">View Assignment</a>';
                                    $replacement[3] = $user->user_name;
                                    $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->start_dttm));
                                    $replacement[5] = ($company_id == 67) ? 'eNablehelpdesk@drreddys.com' : 'info@awarathon.com';   //customization email for Dr Reddy
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);

                                    $log_udata = [
                                        'send_mail' => $ReturnArray1['sendflag']
                                    ];
                                    $where_uclause = array(
                                        'assessment_id' => $user->assessment_id,
                                        'user_id' => $user->user_id
                                    );
                                    $update_status    = $this->api_model->update('assessment_allow_users', $where_uclause, $log_udata, $this->atomdb);
                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'assessment_created_manger') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_LINK\]/';
                                $pattern[3] = '/\[NAME\]/';
                                $pattern[4] = '/\[DATE_TIME\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $assessment->assessment;
                                    $replacement[2] = '<a target="_blank" style="display: inline-block;
                                                width: 200px;
                                                height: 20px;
                                                background: #db1f48;
                                                padding: 10px;
                                                text-align: center;
                                                border-radius: 5px;
                                                color: white;
                                                border: 1px solid black;
                                                text-decoration:none;
                                                font-weight: bold;" href="' . $domain_url . '/assessment/view/' . base64_encode($user->assessment_id) . '/2">View Assignment</a>';
                                    $replacement[3] = $user->user_name;
                                    $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->assessor_dttm));
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);

                                    $log_udata = [
                                        'send_mail' => $ReturnArray1['sendflag']
                                    ];
                                    $where_uclause = array(
                                        'assessment_id' => $user->assessment_id,
                                        'trainer_id' => $user->user_id
                                    );
                                    $update_status    = $this->api_model->update('assessment_managers', $where_uclause, $log_udata, $this->atomdb);
                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'on_assessment_alert') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_LINK\]/';
                                $pattern[3] = '/\[TRAINER_NAME\]/';
                                $pattern[4] = '/\[EXPIRE_DATE\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $assessment->assessment;
                                    $replacement[2] = '<a target="_blank" style="display: inline-block;
                                                width: 200px;
                                                height: 20px;
                                                background: #db1f48;
                                                padding: 10px;
                                                text-align: center;
                                                border-radius: 5px;
                                                color: white;
                                                border: 1px solid black;
                                                text-decoration:none;
                                                font-weight: bold;" href="' . $domain_url . '/assessment/view/' . base64_encode($user->assessment_id) . '/2">View Assignment</a>';
                                    $replacement[3] = $user->user_name;
                                    $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->assessor_dttm));
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);

                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'on_assessment_trainee_alert') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_LINK\]/';
                                $pattern[3] = '/\[NAME\]/';
                                $pattern[4] = '/\[DATE_TIME\]/';
                                $pattern[5] = '/\[Client_mail_id\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,start_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $assessment->assessment;
                                    // $replacement[2] = '';
                                    $replacement[2] = '<a target="_blank" style="display: inline-block;
                                                background: #db1f48;
                                                padding: .45rem 1rem;
                                                box-sizing: border-box;
                                                border: none;
                                                border-radius: 3px;
                                                color: #fff;
                                                text-align: center;
                                                font-family: Lato,Arial,sans-serif;
                                                font-weight: 400;
                                                font-size: 1em;
                                                text-decoration:none;
                                                line-height: initial;" href="' . $web_url . '">View Assignment</a>';
                                    $replacement[3] = $user->user_name;
                                    $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->start_dttm));
                                    $replacement[5] = ($company_id == 67) ? 'eNablehelpdesk@drreddys.com' : 'info@awarathon.com';   //customization email for Dr Reddy
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);

                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'ai_reports_(rep)') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[3] = '/\[REPORT_LINK\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $user->user_name;
                                    $replacement[2] = $assessment->assessment;

                                    $user_id_enc = base64_encode($user->id);
                                    $report_link = '<table cellpadding="5">';
                                    $report_link .= '<tr><td>AI Report</td>';
                                    $report_link .= '<td>' . base_url() . 'pdf/ai/' . $company_id . '/' . $user->assessment_id . '/' . $user_id_enc . '</td></tr>';
                                    $report_link .= '</table>';
                                    $replacement[3] = $report_link;
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);

                                    sleep(2);
                                }
                            } elseif ($user->alert_name == 'manual_reports_&_combined_reports_(rep)') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[3] = '/\[REPORT_LINK\]/';

                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $user->user_name;
                                    $replacement[2] = $assessment->assessment;

                                    $user_id_enc = base64_encode($user->id);
                                    $report_link = '<table cellpadding="5">';
                                    $report_link .= '<tr><td>Assessor Report</td><td>' . base_url() . 'pdf/manual/' . $company_id . '/' . $user->assessment_id . '/' . $user_id_enc . '</td></tr>';
                                    $report_link .= '<tr><td>Final Report</td><td>' . base_url() . 'pdf/combine/' . $company_id . '/' . $user->assessment_id . '/' . $user_id_enc . '</td></tr>';
                                    $report_link .= '</table>';
                                    $replacement[3] = $report_link;
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status    = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);

                                    sleep(2);
                                }
                            } else if ($user->alert_name == 'on_reset_user') {
                                $pattern[0] = '/\[SUBJECT\]/';
                                $pattern[1] = '/\[NAME\]/';
                                $pattern[2] = '/\[ASSESSMENT_NAME\]/';
                                $pattern[3] = '/\[DATE_TIME\]/';
                                $pattern[4] = '/\[ASSESSMENT_LINK\]/';
                                // $pattern[5] = '/\[NAME\]/';
                                // $pattern[6] = '/\[DATE_TIME\]/';
                                // $pattern[7] = '/\[Client_mail_id\]/';
                                $assessment = $this->api_model->get_value('assessment_mst', 'assessment,assessor_dttm,start_dttm,end_dttm', "id=" . $user->assessment_id, $this->atomdb);
                                if (!empty($assessment)) {
                                    // $user->email = 'bhautik.rana@awarathon.com';
                                    $replacement[0] = $user->subject;
                                    $replacement[1] = $user->user_name;
                                    $replacement[2] = $assessment->assessment;
                                    $replacement[3] = date("d-m-Y h:i a", strtotime($assessment->end_dttm));
                                    // $replacement[2] = '';
                                    $replacement[4] = '<a target="_blank" style="display: inline-block;
                                            background: #db1f48;
                                            padding: .45rem 1rem;
                                            box-sizing: border-box;
                                            border: none;
                                            border-radius: 3px;
                                            color: #fff;
                                            text-align: center;
                                            font-family: Lato,Arial,sans-serif;
                                            font-weight: 400;
                                            font-size: 1em;
                                            text-decoration:none;
                                            line-height: initial;" href="' . $web_url . '">View Assignment</a>';
                                    // $replacement[3] = $user->user_name;
                                    // $replacement[4] = date("d-m-Y h:i a", strtotime($assessment->end_dttm));
                                    // $replacement[5] = ($company_id == 67) ? 'eNablehelpdesk@drreddys.com' : 'info@awarathon.com';   //customization email for Dr Reddy
                                    $message = $user->message;
                                    $body = preg_replace($pattern, $replacement, $message);
                                    $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);
                                    // $ReturnArray1 = $this->api_model->sendPhpMailer($company_id, $user->user_name, $user->email, $user->subject, $body, $this->atomdb);

                                    $log_data = [
                                        'attempt' => ++$user->attempt,
                                        'is_sent' => $ReturnArray1['sendflag'],
                                        'sent_at' => date('Y-m-d H:i:s')
                                    ];
                                    $where_clause = array(
                                        'id' => $user->id
                                    );
                                    $update_status  = $this->api_model->update('assessment_notification_schedule', $where_clause, $log_data, $this->atomdb);
                                    $reset_log_data = [
                                        'assessment_id' => $user->assessment_id,
                                        'user_id' => $user->id,
                                        'addeddate' => date('Y-m-d H:i:s'),
                                        'is_send_user' => 1
                                    ];
                                    $this->api_model->insert('reset_sendflag_log',$reset_log_data,$this->atomdb);
                                    sleep(2);
                                }
                            }
                        }
                    }
                    $this->api_model->update('notification_schedule_cron', [], ['cron_status' => 0], $this->atomdb);
                }
            }
        }
    }
}
