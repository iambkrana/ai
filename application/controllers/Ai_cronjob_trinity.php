<?php

require 'vendor/autoload.php'; // Include Guzzle HTTP client library


use GuzzleHttp\Client;

defined('BASEPATH') OR exit('No direct script access allowed');
class Ai_cronjob_trinity extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('ai_cronjob_model_trinity');
    }
    public function index(){
        echo "Welcome to the Awarathon";
        exit;
    }
    //Function Updated Date:- 21-02-24
    public function cronjob(){
        //error_reporting(0);
        //GET ALL ASSESSMENTS
        $all_assessment_result = $this->ai_cronjob_model_trinity->get_assessment();
        if (isset($all_assessment_result) AND count((array)$all_assessment_result)>0){
            foreach($all_assessment_result as $allassdata){
                $temp_assessment_id = $allassdata->id;
                $temp_company_id    = $allassdata->company_id;
                $temp_assessment_type    = $allassdata->assessment_type;

                //GET ASSESSMENT WISE USERS PLAYED
                $ass_users_result = $this->ai_cronjob_model_trinity->get_assessment_users($temp_company_id,$temp_assessment_id,$temp_assessment_type);
                if (isset($ass_users_result) AND count((array)$ass_users_result)>0){
                    foreach($ass_users_result as $userdata){
                        $temp_company_id      = $userdata->company_id;
                        $temp_assessment_id   = $userdata->assessment_id;
                        $temp_user_id         = $userdata->user_id;
                        $temp_trans_id        = $userdata->trans_id;
                        $temp_question_id     = $userdata->question_id;
                        $temp_question_series = $userdata->question_series;

                        $temp_task_id = $this->common_model->get_value('ai_schedule', 'task_id,task_status', 'company_id="'.$temp_company_id.'" AND assessment_id="'.$temp_assessment_id.'" AND user_id="'.$temp_user_id.'" AND trans_id="'.$temp_trans_id.'" AND question_id="'.$temp_question_id.'"');
                        if (isset($temp_task_id) AND count((array)$temp_task_id)>0){
                        }else{
                            //INSERT BLANK RECORD.
                            $now = date('Y-m-d H:i:s');
                            $post_data = array(
                                'company_id'        => $temp_company_id,
                                'assessment_id'     => $temp_assessment_id,
                                'user_id'           => $temp_user_id,
                                'trans_id'          => $temp_trans_id,
                                'question_id'       => $temp_question_id,
                                'question_series'   => $temp_question_series,
                                'py_parameter'      => "",
                                'task_id'           => '22222222-2222-2222-2222-22222222222',
                                'task_status'       => 1,
                                'task_status_dttm'  => date('Y-m-d H:i:s'),
                                'xls_generated'     => 0,
                                'xls_filename'      => '',
                                'xls_imported'      => 0,
                                'pdf_generated'     => 0,
                                'pdf_filename'      => '',
                                'mpdf_generated'    => 0,
                                'mpdf_filename'     => '',
                                'cpdf_generated'    => 0,
                                'cpdf_filename'     => '',
                                'schedule_by'       => 0,
                                'schedule_datetime' => $now,
                                'assessment_type'   =>$temp_assessment_type
                            );
                            $ai_schedule_id = $this->common_model->insert('ai_schedule', $post_data);
                        }
                    }
                }
            }
        }

        $assessment_result = $this->ai_cronjob_model_trinity->get_schedule();
        if (isset($assessment_result) AND count((array)$assessment_result)>0){
            foreach($assessment_result as $adata){
                $_process_started = (int)$adata->process_started;
                if ($_process_started==0 OR $_process_started=="0"){
                    //UPDATE CRONJOB STATUS = ACTIVE
                    // $cronjob_status = $this->ai_cronjob_model_trinity->cronjob_status(1);

                    $task_result = $this->ai_cronjob_model_trinity->get_task();
                    if (isset($task_result) AND count((array)$task_result)>0){
                        foreach($task_result as $tdata){
                            $company_id      = $tdata->company_id;
                            $assessment_id   = $tdata->assessment_id;
                            $portal_name     = $tdata->portal_name;
                            $assessment_name = $tdata->assessment;
                            $user_id         = $tdata->user_id;
                            $user_name       = $tdata->user_name;
                            $user_name_id    = $tdata->user_name_id;
                            $trans_id        = $tdata->trans_id;
                            $question_id     = $tdata->question_id;
                            $question_series = $tdata->question_series;
                            $db_task_id      = $tdata->task_id;
                            $task_status     = (int)$tdata->task_status;
                            $xls_generated   = (int)$tdata->xls_generated;
                            $xls_filename    = $tdata->xls_filename;
                            $xls_imported    = (int)$tdata->xls_imported;
                            $assessment_type = (int)$tdata->assessment_type;
                        
                        
                            $is_new = 0; // For old product
                            if ($task_status==1 AND $xls_generated!==1){
                              
                                // //TO get video from vimeo
                                $get_video = $this->ai_cronjob_model_trinity->get_your_video($company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                // // Specify file save path
                              
                                $fileUrl=$get_video->video_url;
                                
                                //Code for video download in EC2
                                // Initialize Guzzle HTTP client
                                $client = new Client();

                                // Vimeo API endpoint for fetching video information
                                $fileName='ai_'.$company_id.'_'.$user_id.'_'.$assessment_id.'_'.$question_id.'.mp4';
                                $savePath = 'vimeo_video/' . $fileName;
                                
                                $accessToken = '23b4928b062eac384534817f4e4c61cd'; // Access token obtained after OAuth authentication
                                $apiEndpoint = "https://api.vimeo.com/videos/".$fileUrl;
                               
                                try {
                                    // Make API request to Vimeo to get video information
                                    $response = $client->request('GET', $apiEndpoint, [
                                        'headers' => [
                                            'Authorization' => "Bearer $accessToken",
                                            'Accept' => 'application/vnd.vimeo.*+json;version=3.4',
                                        ],
                                    ]);
                                    // // Check if request was successful
                                    if ($response->getStatusCode() == 200) {
                                        $videoInfo = json_decode($response->getBody(), true);
                                     
                                            // Check if the video has a download link
                                            if (isset($videoInfo['download'])) {
                                                $downloadUrl = isset($videoInfo['download'][0]['link'])?$videoInfo['download'][0]['link']:'';
                                                if($downloadUrl!='')
                                                {
                                                    // Download the video
                                                    $downloadUrl = preg_replace("/ /", "%20", $downloadUrl);
                                                    $videoContent = file_get_contents($downloadUrl);

                                                    // Save the video to a file
                                                    // echo $savePath;
                                                    if(file_put_contents($savePath, $videoContent))
                                                    {
                                                        $json   = '{"portal_name":"'.$portal_name.'","assessment_name":"'.$assessment_name.'","person_name":"'.$user_name_id.'","question_number":"'.$question_series.'"}';
                                                    
                                                        if ($company_id!="" AND $assessment_id!="" AND $user_id!="" AND $trans_id!="" AND $question_id!=""){
                                                            try {
                                                                if($assessment_type==3)
                                                                {
                                                                    $path = "/var/www/html/awarathon.com/ai/trinity_excel/";
                                                                    $video_path = "/var/www/html/awarathon.com/ai/vimeo_video/".$fileName;
                                                                    $output = shell_exec(sprintf("source /var/www/html/awarathon.com/salesai/trinity/bin/activate"));
                                                                    $output = shell_exec(sprintf("/var/www/html/awarathon.com/salesai/trinity/bin/python /var/www/html/awarathon.com/salesai/rasa/main_trinity_pdf.py --data='".$json."' --path='".$path."' --video_path='".$video_path."' --is_new='".$is_new."' 2>&1"));

                                                                    $output = json_decode($output);
                                                                    
                                                                    if (isset($output) AND isset($output->status) AND $output->status=="True" AND isset($output->file_name)){

                                                                        $update_status = $this->ai_cronjob_model_trinity->update_xls_status(1,$company_id,$assessment_id,$user_id,$trans_id,$question_id,$output->file_name);
                                                                        unlink($video_path);
                                                                    }else{

                                                                        $e_msg=isset($output->message)?$output->message:'py error';
                                                                        $update_status = $this->ai_cronjob_model_trinity->update_xls_status(2,$company_id,$assessment_id,$user_id,$trans_id,$question_id,"");
                                                                        $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage($e_msg,$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                                        
                                                                    }
                                                                }
                                                            }catch(Exception $e) {
                                                                $update_status = $this->ai_cronjob_model_trinity->update_xls_status(2,$company_id,$assessment_id,$user_id,$trans_id,$question_id,"");
                                                                $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage('try catch error',$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                                        
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "video Not Downloded.";
                                                        $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage('Video Not Downloded',$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                                        
                                                    }
                                                }
                                                else
                                                {
                                                    echo "Video Not Downloded From Vimeo.";
                                                    $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage('Video Not Downloded From Vimeo',$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                                        
                                                }
                                                
                                            } else {
                                                echo "Download link not found for the video.";
                                                $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage('Download link not found for the video',$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                                      
                                            }                                            
                                      
                                    } else {
                                        echo "Failed to fetch video information from Vimeo.";
                                        $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage('Failed to fetch video information from Vimeo',$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                                 
                                    }
                                } catch (Exception $e) {
                                    echo "Error: " . $e->getMessage();
                                    $msg="Error-" . $e->getMessage();
                                    $update_massage = $this->ai_cronjob_model_trinity->update_failed_massage($msg,$company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                         
                                }      
                        
                            }
                            
                            if ($xls_generated==1 AND $xls_imported!==1){
                                if ($company_id!="" AND $assessment_id!="" AND $user_id!="" AND $trans_id!="" AND $question_id!=""){
                                    $file_name          = $xls_filename;
                                    $absolute_file_path = '/var/www/html/awarathon.com/ai/trinity_excel/'.$file_name;
                                    $temp_file_path     =  $file_name;
                                  
                                    if (file_exists($absolute_file_path)==TRUE){
                                         
                                        $this->load->library('PHPExcel_CI');
                                        $objPHPExcel           = PHPExcel_IOFactory::load($absolute_file_path);
                                        $objPHPExcel->setActiveSheetIndex(0);
                                        $worksheet             = $objPHPExcel->getActiveSheet();
                                        $worksheet_max_row     = $worksheet->getHighestRow();
                                        $worksheet_max_col     = $worksheet->getHighestColumn();
                                        $worksheet_max_col_idx = PHPExcel_Cell::columnIndexFromString($worksheet_max_col);
                                        
                                        if ($worksheet_max_row <= 1) {
                                        }else if ($worksheet_max_col_idx < 6) {
                                        }else{
                                            for ($row = 2; $row <= $worksheet_max_row; $row++) {
                                                $participant_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                                                $question_no      = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                                $parameter_type   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                                $parameter        = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                                                $parameter        = trim(str_replace("\t", '', $parameter));// remove tabs
                                                $parameter        = trim(str_replace("\n", '', $parameter));// remove new lines
                                                $parameter        = trim(str_replace("\r", '', $parameter));// remove carriage returns
                                                $parameter_label  = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                                                $parameter_label  = trim(str_replace("\t", '', $parameter_label));// remove tabs
                                                $parameter_label  = trim(str_replace("\n", '', $parameter_label));// remove new lines
                                                $parameter_label  = trim(str_replace("\r", '', $parameter_label));// remove carriage returns
                                                $score            = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                                                $rating           = number_format(($score*5)/100,2);
                    
                                                $parameter_weight = 0;
                                                $parameter_id       = 0;
                                                $sub_parameter_id   = 0;
                                                $parameter_label_id = 0;
                                                if ($parameter_type=="parameter"){
                                                    $parameter_result = $this->common_model->get_value('parameter_mst', '*', 'status=1 AND company_id="'.$company_id.'" AND description="'.$parameter.'"');
                                                    if (isset($parameter_result) AND count((array)$parameter_result)>0){
                                                        $parameter_id = (float)$parameter_result->id;
                                                        $sub_parameter_id = 0;
                                                    }
                                                    if ($parameter_id!=0){
                                                        $parameter_label_result = $this->common_model->get_value('parameter_label_mst', '*', 'status=1 AND parameter_id="'.$parameter_id.'" AND description="'.$parameter_label.'"');
                                                        if (isset($parameter_label_result) AND count((array)$parameter_label_result)>0){
                                                            $parameter_label_id = (float)$parameter_label_result->id;
                                                        }
                                                    }

                                                    $parameter_weight_result = $this->common_model->get_value('assessment_trans_sparam', 'SUM(parameter_weight) as parameter_weight', 'assessment_id="'.$assessment_id.'" AND question_id="'.$question_id.'" AND parameter_label_id="'.$parameter_id.'"');

                                                    if (isset($parameter_weight_result) AND count((array)$parameter_weight_result)>0){
                                                        $parameter_weight = (float)$parameter_weight_result->parameter_weight;
                                                    }
                                                }
                                                if ($parameter_type=="subparameter"){
                                                    $parameter_result = $this->common_model->get_value('parameter_mst', '*', 'status=1 AND company_id="'.$company_id.'" AND description="'.$parameter.'"');
                                                    if (isset($parameter_result) AND count((array)$parameter_result)>0){
                                                        $parameter_id = (float)$parameter_result->id;
                                                    }
                    
                                                    $sub_parameter_result = $this->common_model->get_value('subparameter_mst', '*', 'status=1 AND parameter_id="'.$parameter_id.'" AND description="'.$parameter_label.'"');
                                                    if (isset($sub_parameter_result) AND count((array)$sub_parameter_result)>0){
                                                        $parameter_id     = (float)$sub_parameter_result->parameter_id;
                                                        $sub_parameter_id = (float)$sub_parameter_result->id;
                                                    }

                                                    $parameter_weight_result = $this->common_model->get_value('assessment_trans_sparam', 'parameter_weight', 'assessment_id="'.$assessment_id.'" AND question_id="'.$question_id.'" AND parameter_label_id="'.$parameter_id.'" AND sub_parameter_id="'.$sub_parameter_id.'"');
                                                    if (isset($parameter_weight_result) AND count((array)$parameter_weight_result)>0){
                                                        $parameter_weight = (float)$parameter_weight_result->parameter_weight;
                                                    }
                                                }
                                                $weighted_score = number_format($score*($parameter_weight/100),2);

                                                $now = date('Y-m-d H:i:s');
                                                $score_result = $this->common_model->get_value('ai_subparameter_score', 'id', 'company_id="'.$company_id.'" AND assessment_id="'.$assessment_id.'" AND user_id="'.$user_id.'" AND trans_id="'.$trans_id.'" AND question_id="'.$question_id.'" AND parameter_type="'.$parameter_type.'" AND parameter_label_id="'.$parameter_id.'" AND sub_parameter_id="'.$sub_parameter_id.'"');
                                                if (isset($score_result) AND count((array)$score_result)>0){
                                                    $ai_subparameter_score_id = (float)$score_result->id;
                                                    $update_data = array(
                                                        'score'           => $score,
                                                        'weighted_score'  => $weighted_score,
                                                        'rating'          => $rating,
                                                        'import_dttm'     => $now
                                                    );
                                                    $this->common_model->update('ai_subparameter_score', 'id', $ai_subparameter_score_id, $update_data);
                                                }else{
                                                    $import_data = array(
                                                        'company_id'         => $company_id,
                                                        'assessment_id'      => $assessment_id,
                                                        'user_id'            => $user_id,
                                                        'trans_id'           => $trans_id,
                                                        'question_id'        => $question_id,
                                                        'question_series'    => $question_series,
                                                        'parameter_type'     => $parameter_type,
                                                        'parameter_id'       => $parameter_id,
                                                        'sub_parameter_id'   => $sub_parameter_id,
                                                        'parameter_label_id' => $parameter_label_id,
                                                        'score'              => $score,
                                                        'weighted_score'     => $weighted_score,
                                                        'rating'             => $rating,
                                                        'import_dttm'        => $now
                                                    );
                                                    $ai_subparameter_score_id = $this->common_model->insert('ai_subparameter_score', $import_data);
                                                }
                                            }
                                            //IMPORT SENTANCE/KEYWORD SCORE
                                           
                                            $xls_import_status = $this->ai_cronjob_model_trinity->update_xls_import_status($company_id,$assessment_id,$user_id,$trans_id,$question_id);
                                            if ($xls_import_status){
                                                unset($objPHPExcel);
                                                if (copy($absolute_file_path,'output_excel/'.$temp_file_path)){
                                                 
                                                    unlink($absolute_file_path);
                                                }
                                            }
                                           
                                            //Jagdisha Patel: For mail : 16-02-2023
                                            $send_notfication = 0;
                                            if($assessment_type == 1 || $assessment_type == 2){
                                                $total_question_added = 0;
                                                $total_xlsi_completed = 0;
                                                $_question_results     = $this->common_model->get_value('assessment_trans', 'count(*) as total', "assessment_id=$assessment_id");
                                                if (isset($_question_results) AND count((array)$_question_results)>0){
                                                    $total_question_added = $_question_results->total;
                                                }
                                                $_xlsi_results     = $this->common_model->get_value('ai_schedule', 'count(*) as total', 'assessment_id='.$assessment_id.' AND user_id='.$user_id.' AND task_status="1" AND xls_generated="1" AND xls_filename!="" AND xls_imported="1"');
                                                if (isset($_xlsi_results) AND count((array)$_xlsi_results)>0){
                                                    $total_xlsi_completed = $_xlsi_results->total;
                                                }

                                                if($total_question_added !=0 AND $total_xlsi_completed != 0 AND $total_question_added == $total_xlsi_completed){
                                                    $send_notfication = 1;
                                                }
                                            }elseif($assessment_type == 3){
                                                $_xlsi_results     = $this->common_model->get_value('ai_schedule', '*', 'assessment_id='.$assessment_id.' AND user_id='.$user_id.' AND task_status="1" AND xls_generated="1" AND xls_filename!="" AND xls_imported="1"');
                                                if (isset($_xlsi_results) AND count((array)$_xlsi_results)>0){
                                                    $send_notfication = 1;
                                                }
                                            }

                                            if($send_notfication){
                                                // get email template
                                                $emailTemplate = $this->common_model->get_value('auto_emails', '*', "status=1 and alert_name='ai_reports_(rep)'");
                                            
                                                if (count((array)$emailTemplate) > 0) {
                                                    $pattern[0] = '/\[SUBJECT\]/';
                                                    $pattern[1] = '/\[NAME\]/';
                                                    $pattern[2] = '/\[ASSESSMENT_NAME\]/';
                                                    $pattern[3] = '/\[REPORT_LINK\]/';

                                                    $subject = $emailTemplate->subject;
                                                    $replacement[0] = $subject;
                                                    $u_id[] = $user_id;
                                                    $UserData = $this->common_model->get_value('device_users', 'company_id,concat(firstname," ",lastname) as trainee_name,email', '  user_id =' . $user_id);
                                                    $ToName = $UserData->trainee_name;
                                                    $email_to = $UserData->email;
                                                    // $email_to = 'krishna.revawala@awarathon.com';
                                                    $Company_id = $UserData->company_id;
                                                    $replacement[1] = $UserData->trainee_name;

                                                    $assessment_data = $this->common_model->get_value('assessment_mst', 'assessment', 'id = '.$assessment_id);
                                                    $replacement[2] = $assessment_data->assessment;

                                                    $user_id_enc = base64_encode($user_id);
                                                    $report_link = '<table cellpadding="5">';
                                                    $report_link .= '<tr><td>AI Report</td>';
                                                    $report_link .= '<td>'.base_url().'pdf/ai/'.$Company_id.'/'.$assessment_id.'/'.$user_id_enc.'</td></tr>';
                                                    $report_link .= '</table>';
                                                    $replacement[3] = $report_link;

                                                    $message = $emailTemplate->message;
                                                    $body = preg_replace($pattern, $replacement, $message);
                                                    $ReturnArray = $this->common_model->sendPhpMailer($Company_id, $ToName, $email_to, $subject, $body);
                                                    $log_data = [
                                                        'company_id' => $Company_id,
                                                        'assessment_id' => $assessment_id,
                                                        'email_alert_id' => $emailTemplate->alert_id, //ai_reports_(rep)
                                                        'user_id' => $user_id,
                                                        'role_id' => 3,
                                                        'user_name' => $ToName,
                                                        'email' => $email_to,
                                                        'attempt' => 1,
                                                        'scheduled_at' => date('Y-m-d H:i:s'),
                                                        'is_sent' => $ReturnArray['sendflag'],
                                                        'sent_at' => date('Y-m-d H:i:s')
                                                    ];
                                                    $this->common_model->insert('assessment_notification_schedule', $log_data); //Add Reps notification log - AI report link send
                                                }
                                            }
                                            //Jagdisha Patel: For mail End
                                        }
                                    }
                                } 
                            }   
                            
                        }                        

                        //CHECK ALL TASK COMPLETED?
                        $total_task           = 0;
                        $total_task_completed = 0;
                        $total_task_failed    = 0;
                        $total_xls_completed  = 0;
                        $total_xlsi_completed = 0;

                        $_tasks_results     = $this->common_model->get_value('ai_schedule', 'count(*) as total', '1=1');
                        if (isset($_tasks_results) AND count((array)$_tasks_results)>0){
                            $total_task = $_tasks_results->total;
                        }

                        $_taskfailed_results     = $this->common_model->get_value('ai_schedule', 'count(*) as total', 'task_status="4"');
                        if (isset($_taskfailed_results) AND count((array)$_taskfailed_results)>0){
                            $total_task_failed = $_taskfailed_results->total;
                        }
                        
                        $_tasksc_results     = $this->common_model->get_value('ai_schedule', 'count(*) as total', 'task_status="1"');
                        if (isset($_tasksc_results) AND count((array)$_tasksc_results)>0){
                            $total_task_completed = $_tasksc_results->total;
                        }
                        
                        $_xls_results     = $this->common_model->get_value('ai_schedule', 'count(*) as total', 'task_status="1" AND xls_generated="1" AND xls_filename!=""');
                        if (isset($_xls_results) AND count((array)$_xls_results)>0){
                            $total_xls_completed = $_xls_results->total;
                        }

                        $_xlsi_results     = $this->common_model->get_value('ai_schedule', 'count(*) as total', 'task_status="1" AND xls_generated="1" AND xls_filename!="" AND xls_imported="1"');
                        if (isset($_xlsi_results) AND count((array)$_xlsi_results)>0){
                            $total_xlsi_completed = $_xlsi_results->total;
                        }

                        if ((int)$total_task==(int)$total_task_failed){
                            // $whereclause = "1=1";
                            // $this->common_model->delete_whereclause('ai_cronjob', $whereclause);
                            $cronjob_status = $this->ai_cronjob_model_trinity->cronjob_status(0);
                        }else if (((int)$total_task==(int)$total_task_completed) AND
                            ((int)$total_task==(int)$total_xls_completed) AND
                            ((int)$total_task==(int)$total_xlsi_completed)){
                            // $whereclause = "1=1";
                            // $this->common_model->delete_whereclause('ai_cronjob', $whereclause);
                            $cronjob_status = $this->ai_cronjob_model_trinity->cronjob_status(0);
                        }else{
                            //UPDATE CRONJOB STATUS = RE-RUN BECOZ IT IS NOT COMPLETED.
                            $cronjob_status = $this->ai_cronjob_model_trinity->cronjob_status(0);
                        }
                    }else{
                        $cronjob_status = $this->ai_cronjob_model_trinity->cronjob_status(0);
                    }
                }
            }
        }
    }
    
}