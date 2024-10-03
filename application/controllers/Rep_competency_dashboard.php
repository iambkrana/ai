<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Rep_competency_dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $acces_management = $this->check_rights('rep_competency_dashboard');
        if ((isset($acces_management->allow_access) && !$acces_management->allow_access) && $this->mw_session['role'] != 1) {
            redirect('reports');
        }
        $this->acces_management = $acces_management;
        $this->load->model('Rep_competency_dashboard_model');
    }

    public function index()
    {
        // $data['module_id'] = '90';
        $data['module_id'] = '121';
        $data['acces_management'] = $this->acces_management;
        $data['company_id'] = $this->mw_session['company_id'];
        $data['role'] = $this->mw_session['role'];
        if ($data['company_id'] == "") {
            $data['CompanyData'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1', 'company_name');
        } else {
            $data['CompanyData'] = array();
        }

        $data['ThresholdData'] = $this->common_model->get_selected_values('company_result_range', 'id,range_from,range_to,range_color', 'company_id=' . $data['company_id']);
        $Threshold_array = array();
        if (count((array)$data['ThresholdData']) > 0) {
            foreach ($data['ThresholdData'] as $value) {
                $Threshold_array[$value->id] = array(
                    'range_from' => $value->range_from,
                    'range_to' => $value->range_to, 'range_color' => $value->range_color,
                );
            }
        }
        $this->session->set_userdata('Assessment_threshold_session', $Threshold_array);

        $data['ResultData'] = $this->common_model->get_selected_values('company_threshold_result', 'result_from as range_from,result_to as range_to,result_color as range_color,assessment_status', 'company_id=' . $data['company_id']);
        $result_array = array();
        if (count((array)$data['ResultData']) > 0) {
            foreach ($data['ResultData'] as $value) {
                $result_array[$value->assessment_status] = array(
                    'range_from' => $value->range_from,
                    'range_to' => $value->range_to, 'range_color' => $value->range_color,
                );
            }
        }
        $this->session->set_userdata('Assessment_result_session', $result_array);

        // $data['region_data'] = $this->adoption_model->get_trainee_region($data['company_id']);
        $data['start_date'] = date('d-M-Y', strtotime('-6 days'));
        $data['end_date'] = date("d-m-Y");
        $start_date = date('Y-m-d', strtotime('-6 days'));
        $end_date = date("Y-m-d");
        $data['company_id'] = $this->mw_session['company_id'];
        $company_id = $data['company_id'];
        $data['TrainerResult'] = $this->common_model->get_selected_values('company_users', 'userid,CONCAT(first_name, " " ,last_name) as fullname', 'status="1" AND login_type="2" AND company_id="' . $company_id . '"');
        //$assessment_list= $this->adoption_model->get_assessment_list($company_id, $trainer_id, $start_date, $end_date);
        $data['report_type'] = $this->common_model->get_selected_values('assessment_report_type', 'id,description,default_selected', 'status=1');
        //----------------
        // $data['assessment_data'] = $this->adoption_model->get_assessment($data['company_id'], '', $start_date, $end_date);
        // $data['parameter_data'] = $this->adoption_model->get_parameter();
        $data['trainee_data'] = $this->Rep_competency_dashboard_model->get_Trainee_data($data['company_id']);
        $data['assessment'] = $this->Rep_competency_dashboard_model->get_all_assessment();
        $data['division_id'] = $this->Rep_competency_dashboard_model->get_all_division();
        $data['managers'] = $this->Rep_competency_dashboard_model->get_all_managers();
        // echo "<pre>";   
        // print_r($data['managers']);
        // die;


        $this->load->view('rep_competency_dashboard/index', $data);
    }

    public function get_manager_rep_data()
    {
        $manager_set = '';
        $rep_set = '';
        $Company_id =  $this->input->post('company_id', TRUE);
        $division_id = $this->input->post('division_id', TRUE) != '' ? $this->input->post('division_id', TRUE) : '';
        $manager_list = $this->Rep_competency_dashboard_model->get_manager_info($division_id);
        $manager_set .= '<option value="">Please select </option>';
        if (count((array)$manager_list) > 0) {
            
            foreach ($manager_list as $tl) {
                $manager_set .= '<option value="' . $tl->manager_id . '">' . $tl->manager_name  . ' </option>';
            }
        }
        $rep_list = $this->Rep_competency_dashboard_model->get_div_rep_info($division_id);
        $rep_set .= '<option value="">Please select </option>';

        if (count((array)$rep_list) > 0) {

            foreach ($rep_list as $rl) {
                $rep_set .= '<option value="' . $rl->user_id . '">' . $rl->traineename  . ' </option>';
            }
        }
        $data['manager_set'] = $manager_set;
        $data['rep_set'] = $rep_set;
        echo json_encode($data);
    }

    public function get_rep_data()
    {
        $trainee_set = '';
        $Company_id =  $this->input->post('company_id', TRUE);
        $manager_id = $this->input->post('manager_id', TRUE) != '' ? $this->input->post('manager_id', TRUE) : '';
        $trainee_list = $this->Rep_competency_dashboard_model->get_rep_info($manager_id);
        // print_r($trainee_list);
        // die;
        $trainee_set .= '<option value="">Please select </option>';

        if (count((array)$trainee_list) > 0) {

            foreach ($trainee_list as $tl) {
                $trainee_set .= '<option value="' . $tl->userid . '"> ' . $tl->traineename . '</option >';
            }
        }
        $data['trainee_set'] = $trainee_set;
        echo json_encode($data);
    }

    public function get_assessment_data()
    {
        $assessment_set = '';
        $Company_id =  $this->input->post('company_id', TRUE);
        $trainee_id = $this->input->post('trainee_id', TRUE) != '' ? $this->input->post('trainee_id', TRUE) : '';
        $assessment_list = $this->Rep_competency_dashboard_model->get_assessment_info($trainee_id);
        if (count((array)$assessment_list) > 0) {

            foreach ($assessment_list as $al) {
                $assessment_set .= '<option value="' . $al->assessment_id . '">[' . $al->assessment . '] - ' . $al->status . '</option>';
            }
        }
        $data['assessment_set'] = $assessment_set;
        echo json_encode($data);
    }
    //Leader board created by Rudra patel
    public function leader_board_understanding()
    {
        $dtSearchColumns = array('your_score', 'top_performer', 'bottom_performer', 'ranks', 'badge');
        $DTRenderArray = $this->common_libraries->DT_RenderColumns($dtSearchColumns);

        $company_id = $this->mw_session['company_id'];
        $assessment_id = $this->input->get('assessment_id', true);
        $trainee_id = $this->input->get('trainee_id', true);
        $start_date = $this->input->get('StartDate', true);
        $end_date =   $this->input->get('EndDate', true);
        $startdate = date("Y-m-d", strtotime($start_date));
        $enddate = date("Y-m-d", strtotime($end_date));
        $is_custom =  $this->input->get('IsCustom', true);

        //for ranking purpose 
        $this->db->select('company_id,range_from,range_to,title,rating');
        $this->db->from('industry_threshold_range');
        $this->db->order_by('rating', 'asc');
        $data['color_range'] = $this->db->get()->result();

        $user_list = [];
        $x = 0;

        if (!empty($trainee_id) && !empty($assessment_id)) {
            //Time filter
            if ($is_custom == "") {
                $startdate = date(("Y-m-d"), strtotime("-30 days"));
                $enddate = date("Y-m-d", strtotime($end_date));

                $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, '');
                $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 1);
                $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 2);
            } else if ($is_custom == "Current Year") {
                $startdate = date('Y-01-01');
                $CurrentDate = date("Y-m-d");

                $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, '');
                $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 1);
                $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 2);
            } else {
                $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, '');
                $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 1);
                $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 2);
            }
        } else if (!empty($trainee_id)) {
            
            if ($is_custom == "") {
                $startdate = date('Y-m-d', strtotime("-30 days"));
                $enddate = date("Y-m-d");
                $get_respected_assessment = $this->Rep_competency_dashboard_model->get_respected_assessment($trainee_id, $startdate, $enddate);
                if (!empty($get_respected_assessment)) {
                    foreach ($get_respected_assessment as $gra) {
                        $lastAssessmentId[] = $gra['assessment_id'] != '' ? $gra['assessment_id'] : '';
                    } 
                    if (!empty($lastAssessmentId)) {
                        $assessment_id = implode(',', $lastAssessmentId);
                        $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, '', $startdate, $enddate, '');
                        $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 1);
                        $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 2);
                    } else {
                        $trainee_score = '';
                        $min_max_score = '';
                        $ranking = '';
                        $trainee_id = '';
                        $assessment_id = '';
                    }
                } else {
                    $trainee_score = '';
                    $min_max_score = '';
                    $ranking = '';
                    $trainee_id = '';
                    $assessment_id = '';
                }
            } else if ($is_custom == "Current Year") {
                $startdate = date('Y-01-01');
                $CurrentDate = date("Y-m-d");
                $get_respected_assessment = $this->Rep_competency_dashboard_model->get_respected_assessment($trainee_id, $startdate, $CurrentDate);
                if (!empty($get_respected_assessment)) {
                    foreach ($get_respected_assessment as $gra) {
                        $lastAssessmentId[] = $gra['assessment_id'] != '' ? $gra['assessment_id'] : '';
                    }
                    if (!empty($lastAssessmentId)) {
                        $assessment_id = implode(',', $lastAssessmentId);
                        $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, '', $startdate, $CurrentDate, '');
                        $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 1);
                        $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 2);
                    } else {
                        $trainee_score = '';
                        $min_max_score = '';
                        $ranking = '';
                        $trainee_id = '';
                        $assessment_id = '';
                    }
                } else {
                    $trainee_score = '';
                    $min_max_score = '';
                    $ranking = '';
                    $trainee_id = '';
                    $assessment_id = '';
                }
            } else {
                $get_respected_assessment = $this->Rep_competency_dashboard_model->get_respected_assessment($trainee_id, $startdate, $enddate);
                if (!empty($get_respected_assessment)) {
                    foreach ($get_respected_assessment as $gra) {
                        $lastAssessmentId[] = $gra['assessment_id'] != '' ? $gra['assessment_id'] : '';
                    }
                    if (!empty($lastAssessmentId)) {
                        $assessment_id = implode(',', $lastAssessmentId);
                        $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, '', $startdate, $enddate, '');
                        $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 1);
                        $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 2);
                    } else {
                        $trainee_score = '';
                        $min_max_score = '';
                        $ranking = '';
                        $trainee_id = '';
                        $assessment_id = '';
                    }
                } else {
                    $trainee_score = '';
                    $min_max_score = '';
                    $ranking = '';
                    $trainee_id = '';
                    $assessment_id = '';
                }
            }
        } else {
            $last_assessment_id = $this->Rep_competency_dashboard_model->last_trainer_id();
            if (!empty($last_assessment_id)) {
                $assessment_id = $last_assessment_id->assessment_id;
                $trainee_id = $last_assessment_id->user_id;
            } else {
                $assessment_id = '';
                $trainee_id = '';
            }


            if (!empty($assessment_id) && !empty($trainee_id)) {
                if ($is_custom == "") {
                    $start_date = date('Y-m-d', strtotime("-30 days"));
                    $current_date =  date("Y-m-d h:i:s");

                    $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, '');
                    $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 1);
                    $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 2);
                } else if ($is_custom == "Current Year") {
                    $startdate = date('Y-01-01');
                    $CurrentDate = date("Y-m-d");

                    $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, '');
                    $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 1);
                    $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 2);
                    // print_r($trainee_score);
                } else {
                    $trainee_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, '');
                    $min_max_score = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 1);
                    $ranking = $this->Rep_competency_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $enddate, 2);
                }
            } else {
                $trainee_score = '';
                $min_max_score = '';
                $ranking = '';
                $trainee_id = '';
                $assessment_id = '';
            }
        }
        $rank = array();
        if (!empty($ranking)) {
            foreach ($ranking as $rd) {
                $rank[$rd->users_id] = $rd->ranking;
            }
        }
        if (!empty($trainee_score)) {
            foreach ($trainee_score as $ts) {
                $user_list[$x]['your_score'] = $ts->your_score;
                $user_list[$x]['top_performer'] = $min_max_score->top_performer;
                $user_list[$x]['bottom_performer'] = $min_max_score->bottom_performer;
                $user_list[$x]['ranks'] = (!empty($rank[$trainee_id]) ? $rank[$trainee_id] : '-');

                $badge = '';
                if ((float) $ts->your_score < $data['color_range'][0]->range_to . '.99' and (float) $ts->your_score >= $data['color_range'][0]->range_from) {
                    //$badge = $data['color_range'][0]->rating;  
                    $badge =  'Rockstar' ;
                } else if ((float) $ts->your_score < $data['color_range'][1]->range_to . '.99' and (float) $ts->your_score >= $data['color_range'][1]->range_from) {
                    //$badge = $data['color_range'][1]->rating;
                    $badge = 'Expert';
                } else if ((float) $ts->your_score < $data['color_range'][2]->range_to . '.99' and (float) $ts->your_score >= $data['color_range'][2]->range_from) {
                    //$badge = $data['color_range'][2]->rating;
                    $badge = 'Advance';
                } else if ((float) $ts->your_score < $data['color_range'][3]->range_to . '.99' and (float) $ts->your_score >= $data['color_range'][3]->range_from) {
                    //$badge = $data['color_range'][3]->rating;
                    $badge = 'Intermediate';
                } else if ((float) $ts->your_score < $data['color_range'][4]->range_to . '.99' and (float) $ts->your_score >= $data['color_range'][4]->range_from) {
                    //$badge = $data['color_range'][4]->rating;
                    $badge = 'Beginner';
                } else if ((float) $ts->your_score < $data['color_range'][5]->range_to . '.99' and (float) $ts->your_score >= $data['color_range'][5]->range_from) {
                    //$badge = $data['color_range'][5]->rating;
                    $badge = 'At Risk';
                } else {
                    $badge = '-';
                }
                $user_list[$x]['badge'] = $badge;
                $x++;
            }
        } else {
            $user_list[] = '';
        }

        $DTRenderArray = $user_list;

        $output = array(
            "sEcho" => $this->input->get('sEcho') ? $this->input->get('sEcho') : 0,
            // "iTotalRecords" => $DTRenderArray['dtPerPageRecords'],
            "iTotalRecords" => count((array)$trainee_score),
            "iTotalDisplayRecords" => count((array)$trainee_score),
            "aaData" => array()
        );

        $output['title'] = 'leader board';
        $output['assessment_id'] = $assessment_id;
        $output['trainee_id'] = $trainee_id;
        $dtDisplayColumns = array('your_score', 'top_performer', 'bottom_performer', 'ranks', 'badge');

        if (!empty($DTRenderArray[0]) && isset($DTRenderArray[0])) {
            foreach ($DTRenderArray as $dtRow) {
                $row = array();
                $TotalHeader = count((array)$dtDisplayColumns);
                for ($i = 0; $i < $TotalHeader; $i++) {
                    if ($dtDisplayColumns[$i] != ' ' and isset($dtDisplayColumns)) {
                        $row[] = $dtRow[$dtDisplayColumns[$i]];
                    }
                }
                $output['aaData'][] = $row;
            }
        }
        echo json_encode($output);
    }
    //Leader board ended by Rudra patel  

    //Rep spider chart graph controller starts from here 
    public function rep_spider_chart()
    {
        $data = array();
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $Company_id = $this->input->post('company_id', TRUE);
        }
        $trainee_id = $this->input->post('trainee_id', TRUE);
        $assessment_id = ($this->input->post('assessment_id', TRUE) ? $this->input->post('assessment_id', TRUE) : '');
        $StartDate = $this->input->post('StartDate', true);
        $EndDate = $this->input->post('EndDate', true);
        $startdate = date("Y-m-d", strtotime($StartDate));
        $enddate = date("Y-m-d", strtotime($EndDate));
        $IsCustom = $this->input->post('IsCustom', true) != '' ? $this->input->post('IsCustom', true) : '';

        $report_by = $this->input->post('report_by');
        $ass_names = array();

        $assessment_average = array();
        if ($report_by == 0) {

            if (!empty($trainee_id) and empty($assessment_id)) {
                if ($IsCustom == "") {
                    $startdate = date('Y-m-d', strtotime("-30 days"));
                    $enddate = date("Y-m-d");
                    $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $enddate, $report_by);
                } else if ($IsCustom == "Current Year") {
                    $StartDate = date('Y-01-01');
                    $CurrentDate =  date("Y-m-d h:i:s");
                    $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $CurrentDate, $report_by);
                } else {
                    $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $enddate, $report_by);
                }

            } elseif(!empty($trainee_id) && !empty($assessment_id)) {           
                if ($IsCustom == "") {
                    $startdate = date('Y-m-d', strtotime("-30 days"));
                    $enddate = date("Y-m-d");
                    $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $enddate, $report_by);
                    
                } else if ($IsCustom == "Current Year") {
                    $StartDate = date('Y-01-01');
                    $CurrentDate =  date("Y-m-d h:i:s");
                    $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $CurrentDate, $report_by);
                    
                } else {
                    $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $enddate, $report_by);
                }
            } else {
                $LAssessmentDetails = $this->Rep_competency_dashboard_model->last_trainer_id();
                if (!empty($LAssessmentDetails)) {
                    $trainee_id = isset($LAssessmentDetails->user_id) ? $LAssessmentDetails->user_id : '';
                    if ($IsCustom == "") {
                        $startdate = date('Y-m-d', strtotime("-30 days"));
                        $enddate = date("Y-m-d");
                        $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, '', $startdate, $enddate, $report_by);
                    } else if ($IsCustom == "Current Year") {
                        $StartDate = date('Y-01-01');
                        $CurrentDate =  date("Y-m-d h:i:s");
                        $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, '', $startdate, $CurrentDate, $report_by);
                    } else {
                        $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, '', $startdate, $enddate, $report_by);
                    }
                }
            }
            if(!empty($trainee_score)){
                foreach($trainee_score as $ts){
                    $trainer_name =  isset($ts['u_name']) ? $ts['u_name'] : '0';
                    $ass_names[] =  isset($ts['assessment_name']) ? $ts['assessment_name'] : '0';
                    $assessment_average[] =  isset($ts['assessment_average']) ? json_encode($ts['assessment_average'], JSON_NUMERIC_CHECK) : '0';
                    $Company_id =  isset($ts['c_id']) ? json_encode($ts['c_id'], JSON_NUMERIC_CHECK) : '0';
                    $e_id =  isset($ts['e_id']) ? json_encode($ts['e_id'], JSON_NUMERIC_CHECK) : '0';

                }
            } else{
                $trainer_name ='';
                $ass_names[]='';
                $assessment_average[]='';
                $Company_id = '';
                $e_id ='';

            }
        }
        else {
            if ($IsCustom == "") {
                $startdate = date('Y-m-d', strtotime("-30 days"));
                $enddate = date("Y-m-d");
                $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $EndDate, $report_by);
                
            } else if ($IsCustom == "Current Year") {
                $StartDate = date('Y-01-01');
                $CurrentDate =  date("Y-m-d h:i:s");
                $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $CurrentDate, $report_by);
            } else {
                $trainee_score = $this->Rep_competency_dashboard_model->get_assessment_scores($trainee_id, $assessment_id, $startdate, $EndDate, $report_by);
                

            }
            if(!empty($trainee_score)){
                foreach($trainee_score as $ts){
                    $trainer_name =  isset($ts['u_name']) ? $ts['u_name'] : '0';
                    $ass_names[] =  isset($ts['parameter_name']) ? $ts['parameter_name'] : '0';
                    $assessment_average[] =  isset($ts['parameter_avg']) ? json_encode($ts['parameter_avg'], JSON_NUMERIC_CHECK) : '0';
                    $Company_id =  isset($ts['c_id']) ? json_encode($ts['c_id'], JSON_NUMERIC_CHECK) : '0';
                    $e_id =  isset($ts['e_id']) ? json_encode($ts['e_id'], JSON_NUMERIC_CHECK) : '0';
                } 
            } else{
                $trainer_name ='';
                $ass_names[]='';
                $assessment_average[]='';
                $Company_id ='';
                $e_id ='';
            }
        }
        $Rdata = array();
        $Rdata['trainer_name'] = json_encode($trainer_name);
        $Rdata['ass_names'] = json_encode($ass_names);
        $Rdata['assessment_average'] = json_encode($assessment_average, JSON_NUMERIC_CHECK);
        $Rdata['Company_id'] = json_encode($Company_id, JSON_NUMERIC_CHECK);
        $Rdata['e_id'] = json_encode($e_id, JSON_NUMERIC_CHECK);

        $get_trainee_score = $this->load->view('rep_competency_dashboard/Rep_spider_chart', $Rdata, true);
        $data['get_trainee_score_overall'] = $get_trainee_score;
        echo json_encode($data);
    }
    //Rep spider chart graph controller ends from here 

    public function assessment_comparison()
    {
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $Company_id = $this->input->post('company_id', TRUE);
        }
        $trainee_id = ($this->input->post('trainee_id', TRUE) ? $this->input->post('trainee_id', TRUE) : '');
        $assessment_id = ($this->input->post('assessment_id', TRUE) ? $this->input->post('assessment_id', TRUE) : '');
        $StartDate = $this->input->post('StartDate', true);
        $EndDate = $this->input->post('EndDate', true);
        $startdate = date("Y-m-d", strtotime($StartDate));
        $enddate = date("Y-m-d", strtotime($EndDate));

        $IsCustom = $this->input->post('IsCustom', true) != '' ? $this->input->post('IsCustom', true) : '';

        $assessment_names = array();
        $less_than_range = array();
        $second_range = array();
        $third_range = array();
        $forth_range = array();
        $fifth_range = array();
        $above_range_final = array();

        // static range for customization
        $less_range = 25;
        $second_range_from = 26;
        $second_range_to = 54;
        $third_range_from = 55;
        $third_range_to = 64;
        $forth_range_from = 65;
        $forth_range_to = 74;
        $fifth_range_from = 75;
        $fifth_range_to = 84;
        $above_range = 85;
        // static range for customization

        if (!empty($trainee_id) and empty($assessment_id)) {
            if ($IsCustom == "") {
                $startdate = date('Y-m-d', strtotime("-30 days"));
                $enddate = date("Y-m-d");
                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            } else if ($IsCustom == "Current Year") {
                $StartDate = date('Y-m-d', strtotime("-30 days"));
                $CurrentDate =  date("Y-m-d h:i:s");
                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            } else {

                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            }
        } else if (!empty($trainee_id) && !empty($assessment_id)) {
            if ($IsCustom == "") {
                $StartDate = date('Y-m-d', strtotime("-30 days"));
                $CurrentDate =  date("Y-m-d h:i:s");
                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            } else if ($IsCustom == "Current Year") {
                $StartDate = date('Y-m-d', strtotime("-30 days"));
                $CurrentDate =  date("Y-m-d h:i:s");
                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            } else {
                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            }
        } else {

            $LastTrainerId = $this->Rep_competency_dashboard_model->last_trainer_id();

            if (!empty($LastTrainerId)) {
                $trainee_id = isset($LastTrainerId->user_id) ? $LastTrainerId->user_id : '';
                if ($IsCustom == "") {
                    $StartDate = date('Y-m-d', strtotime("-30 days"));
                    $CurrentDate =  date("Y-m-d h:i:s");
                    $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
                } else if ($IsCustom == "Current Year") {
                    $StartDate = date('Y-m-d', strtotime("-30 days"));
                    $CurrentDate =  date("Y-m-d h:i:s");
                    $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
                }
            } else {
                $assessment_score  = $this->Rep_competency_dashboard_model->get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range);
            }
        }
        if (!empty($assessment_score)) {
            foreach ($assessment_score  as $assc) {
                $trainer_name =  isset($assc['trainee_name']) ? $assc['trainee_name'] : '0';
                $assessment_names[] =  isset($assc['assessment_name']) ? $assc['assessment_name'] : '-';
                $less_than_range[] =  isset($assc['At_Risk']) ? $assc['At_Risk'] : '0';
                $second_range[] =  isset($assc['Beginner']) ? $assc['Beginner'] : '0';
                $third_range[] =  isset($assc['Intermediate']) ? $assc['Intermediate'] : '0';
                $forth_range[] =  isset($assc['Advance']) ? $assc['Advance'] : '0';
                $fifth_range[] =  isset($assc['Expert']) ? $assc['Expert'] : '0';
                $above_range_final[] =  isset($assc['Rockstars']) ? $assc['Rockstars'] : '0';
                $Company_id =  isset($assc['c_id']) ? json_encode($assc['c_id'], JSON_NUMERIC_CHECK) : '0';
                $e_id =  isset($assc['e_id']) ? json_encode($assc['e_id'], JSON_NUMERIC_CHECK) : '0';
            }
            $N_count = count($assessment_names);
        } else {
            $trainer_name = '';
            $assessment_names[] = '-';
            $less_than_range[] = '';
            $second_range[] = '';
            $third_range[] = '';
            $forth_range[] = '';
            $fifth_range[] = '';
            $above_range_final[] = '';
            $N_count = '';
            $Company_id ='';
            $e_id ='';
        }
        $Rdata = array();
        $range_list = array('less than ' . $less_range, $second_range_to . ' to ' . $second_range_from, $third_range_to . ' to ' . $third_range_from, $forth_range_to . ' to ' . $forth_range_from, $fifth_range_to . ' to ' . $fifth_range_from, 'above ' . $above_range . '');
        $Rdata['range_list'] = json_encode($range_list);
        $Rdata['assessment_names'] = json_encode($assessment_names);
        $Rdata['Ncount'] = $N_count;
        $Rdata['trainee_name'] = json_encode($trainer_name);
        $Rdata['less_than_range'] = json_encode($less_than_range, JSON_NUMERIC_CHECK);
        $Rdata['second_range'] = json_encode($second_range, JSON_NUMERIC_CHECK);
        $Rdata['third_range'] = json_encode($third_range, JSON_NUMERIC_CHECK);
        $Rdata['forth_range'] = json_encode($forth_range, JSON_NUMERIC_CHECK);
        $Rdata['fifth_range'] = json_encode($fifth_range, JSON_NUMERIC_CHECK);
        $Rdata['above_range_final'] = json_encode($above_range_final, JSON_NUMERIC_CHECK);
        $Rdata['Company_id'] = json_encode($Company_id, JSON_NUMERIC_CHECK);
        $Rdata['e_id'] = json_encode($e_id, JSON_NUMERIC_CHECK);
        // print_r($Rdata);
        // die;
        $assessment_comparison = $this->load->view('rep_competency_dashboard/assessment_comparison_chart', $Rdata, true);
        $data['assessment_comparison_graph'] = $assessment_comparison;
        echo json_encode($data);
    }

    //assessment attempt controller starts from here 
    public function assessment_attempt()
    {
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $Company_id = $this->input->post('company_id', TRUE);
        }
        $trainee_id = ($this->input->post('trainee_id', TRUE) ? $this->input->post('trainee_id', TRUE) : '');
        $assessment_id = ($this->input->post('assessment_id', TRUE) ? $this->input->post('assessment_id', TRUE) : '');
        $StartDate = $this->input->post('StartDate', true);
        $EndDate = $this->input->post('EndDate', true);
        $startdate = date("Y-m-d", strtotime($StartDate));
        $enddate = date("Y-m-d", strtotime($EndDate));
        $IsCustom = $this->input->post('IsCustom', true) != '' ? $this->input->post('IsCustom', true) : '';
        $assessment_names = array();
        $assessment_attempts = array();
        $assessment_ids = array();

        if (!empty($trainee_id) and empty($assessment_id)) {
            if ($IsCustom == "") {
                $startdate = date('Y-m-d', strtotime("-30 days"));
                $enddate = date("Y-m-d");
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id,$startdate, $enddate);
            } else if ($IsCustom == "Current Year") {
                $StartDate = date('Y-01-01');
                $CurrentDate =  date("Y-m-d h:i:s");
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $CurrentDate);
            } else {
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $enddate);
            }
        } else if (!empty($trainee_id) && !empty($assessment_id)) {
            if ($IsCustom == "") {
                $startdate = date('Y-m-d', strtotime("-30 days"));
                $enddate = date("Y-m-d");
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $enddate);
            } else if ($IsCustom == "Current Year") {
                $StartDate = date('Y-01-01');
                $CurrentDate =  date("Y-m-d h:i:s");
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $CurrentDate);
            } else {
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $enddate);
            }
        } else {

            $LastTrainerId = $this->Rep_competency_dashboard_model->last_trainer_id();
            if (!empty($LastTrainerId)) {
                $trainee_id = isset($LastTrainerId->user_id) ? $LastTrainerId->user_id : '';
                if ($IsCustom == "") {
                    $startdate = date('Y-m-d', strtotime("-30 days"));
                    $enddate = date("Y-m-d");
                    $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $enddate);
                } else if ($IsCustom == "Current Year") {
                    $StartDate = date('Y-01-01');
                    $CurrentDate =  date("Y-m-d h:i:s");
                    $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $CurrentDate);
                }
            } else {
                $assessment_attempts_total = $this->Rep_competency_dashboard_model->get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $enddate);
            }
        }
        if (!empty($assessment_attempts_total)) {
            foreach ($assessment_attempts_total as $assc) {
                $trainer_name =  isset($assc['u_name']) ? $assc['u_name'] : '0';
                $assessment_names[] =  isset($assc['assessment_name']) ? $assc['assessment_name'] : '-';
                $assessment_attempts[] = isset($assc['assessment_attempts']) ? $assc['assessment_attempts'] : '-';
                $assessment_ids[] = isset($assc['assessment_id']) ? $assc['assessment_id'] : '-';
                $Company_id =  isset($assc['c_id']) ? json_encode($assc['c_id'], JSON_NUMERIC_CHECK) : '0';
                $e_id =  isset($assc['e_id']) ? json_encode($assc['e_id'], JSON_NUMERIC_CHECK) : '0';
                // print_r($e_id);
            }
            // die;
        } else {
            $trainer_name = '';
            $assessment_names[] = '-';
            $assessment_attempts[] = '-';
            $assessment_ids[] = '-';
            $Company_id ='';
            $e_id ='';
        }
        $Rdata = array();
        $Rdata['assessment_names'] = json_encode($assessment_names);
        $Rdata['trainee_name'] = json_encode($trainer_name);
        $Rdata['assessment_ids'] = json_encode($assessment_ids);
        $Rdata['assessment_attempts'] = json_encode($assessment_attempts);
        $Rdata['Company_id'] = json_encode($Company_id, JSON_NUMERIC_CHECK);
        $Rdata['e_id'] = json_encode($e_id, JSON_NUMERIC_CHECK);
        // print_r($Rdata);
        // die;
        $assessment_attempt = $this->load->view('rep_competency_dashboard/assessment_attempt_graph', $Rdata, true);
        $data['assessment_attempt_chart'] = $assessment_attempt;
        echo json_encode($data);
    }
    //Assessment attempt controller ends by Patel Rudra
}
?>