<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class role_play_rep_dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $acces_management = $this->check_rights('role_play_dashboard');
        $rightrole = ($this->mw_session['role'] == 1 || $this->mw_session['role'] == 2) ? 1 : 0;
        if ((isset($acces_management->allow_access) && !$acces_management->allow_access) && !$rightrole) {
            redirect('reports');
        }
        $this->acces_management = $acces_management;
        $this->load->model('role_play_rep_dashboard_model');
    }

    public function index()
    {
        // $data['module_id'] = '104';
        $data['module_id'] = '88';
        $data['acces_management'] = $this->acces_management;
        $data['company_id'] = $this->mw_session['company_id'];
        $data['role'] = $this->mw_session['role'];
        if ($data['company_id'] == "") {
            $data['CompanyData'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1', 'company_name');
        } else {
            $data['CompanyData'] = array();
        }

        $data['trainee'] = $this->role_play_rep_dashboard_model->get_user($data['company_id']);
        $data['report_type'] = $this->common_model->get_selected_values('assessment_report_type', 'id,description,default_selected', 'status=1');
        $data['start_date'] = date('d-M-Y', strtotime('-6 days'));
        $data['end_date'] = date("d-m-Y");
        $start_date = date('Y-m-d', strtotime('-6 days'));
        $end_date = date("Y-m-d");

        $user_set = $this->session->userdata('awarathon_session');
        $manager_id = $user_set['login_type'] == 2 ? $user_set['user_id'] : '';
        $data['trainee_data'] = $this->role_play_rep_dashboard_model->get_Trainee_data($data['company_id'], $manager_id);
        // $data['trainee_data'] = $this->role_play_rep_dashboard_model->get_Trainee($data['company_id'],$start_date,$end_date);
        //$data['parameter_data'] = $this->role_play_rep_dashboard_model->get_parameter($data['company_id']);
        $data['parameter'] = $this->common_model->get_selected_values('parameter_mst', 'id as parameter_id,description as parameter', 'status=1', 'description');
        $data['assessment'] = $this->role_play_rep_dashboard_model->get_all_assessment();
        $this->load->view('role_play_rep_dashboard/index', $data);
    }

    public function getdashboardData()
    {
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $Company_id = $this->input->post('company_id', TRUE);
        }
        $question_type = $this->input->post('question_type', true);
        $report_by = $this->input->post('report_by', true);
        $user_id = $this->input->post('user_id', true);
        $report_type = $this->input->post('report_type', true);
        $start_date = $this->input->post('StartDate', true);
        $end_date = $this->input->post('EndDate', true);
        $SDate = date('Y-m-d', strtotime($start_date));
        $EDate = date('Y-m-d', strtotime($end_date));
        $data = $this->load_assessment_index(1);
        $data['Total_Assessment'] = $this->role_play_rep_dashboard_model->get_Total_Assessment($Company_id, $SDate, $EDate, $user_id, $report_type);
        $data['question_answer'] = $this->role_play_rep_dashboard_model->get_Total_Questions_Time($Company_id, $SDate, $EDate, $user_id, $report_type);
        //$data['Total_Questions'] = $Questions_Time['TotalQ'];
        //$data['Total_Time'] = $Questions_Time['TotalT'];
        $time = $this->role_play_rep_dashboard_model->get_time($Company_id, $SDate, $EDate, $user_id);
        //print_r($time);
        $data['Total_Time'] = $time->total_time;
        $data['Avg_Accuracy'] = $this->role_play_rep_dashboard_model->get_Average_Accuracy($Company_id, $report_by, $SDate, $EDate, $user_id, $report_type);
        //        
        $TopFiveParameter = $this->role_play_rep_dashboard_model->get_top_five_parameter($Company_id, $report_by, $SDate, $EDate, $user_id, $report_type);

        //--- High and Low Accuracy--//
        $data['high_Accuracy'] = 0;
        $data['low_Accuracy'] = 0;
        if (count((array) $TopFiveParameter) > 0) {
            $data['high_Accuracy'] = $TopFiveParameter[0]->result;
            $existid = ($report_by == 1 ? $TopFiveParameter[0]->parameter_id : $TopFiveParameter[0]->assessment_id);
            $minSet = $this->role_play_rep_dashboard_model->get_bottom_five_parameter($Company_id, $report_by, $existid, $SDate, $EDate, $user_id, $report_type);
            if (count((array) $minSet) > 0) {
                $data['low_Accuracy'] = $minSet[0]->result;
            }
        }
        //----- End -----//
        $top_five_para_id = "0,";
        $para_top_five_html = '';
        if (count((array) $TopFiveParameter) > 0) {
            foreach ($TopFiveParameter as $para_top) {
                if ($report_by == 1) {
                    $top_five_para_id .= $para_top->parameter_id . ",";
                } else {
                    $top_five_para_id .= $para_top->assessment_id . ",";
                }
                $para_top_five_html .= '<tr class="tr-background">';
                if ($report_by == 1) {
                    $para_top_five_html .= '<td class="wksh-td">' . $para_top->parameter . '</td>';
                } else {
                    $para_top_five_html .= '<td class="wksh-td">' . $para_top->assessment . '</td>';
                }
                $para_top_five_html .= '<td class="wksh-td">
                                                <span class="bold theme-font">' . $para_top->result . '%</span>
                                            </td>
                                        </tr>';
            }
        } else {
            $para_top_five_html .= '<tr class="tr-background">
                                        <td colspan="2" class="wksh-td">No Records Found</td>
                                    </tr>';
        }
        if ($top_five_para_id != '') {
            $top_five_para_id = substr($top_five_para_id, 0, strlen($top_five_para_id) - 1);
        }
        $data['para_top_five_html'] = $para_top_five_html;

        $BottomFiveParameter = $this->role_play_rep_dashboard_model->get_bottom_five_parameter($Company_id, $report_by, $top_five_para_id, $SDate, $EDate, $user_id, $report_type);

        $para_bottom_five_html = '';
        if (count((array) $BottomFiveParameter) > 0) {
            foreach ($BottomFiveParameter as $para_bottom) {
                $para_bottom_five_html .= '<tr class="tr-background">';
                if ($report_by == 1) {
                    $para_bottom_five_html .= '<td class="wksh-td">' . $para_bottom->parameter . '</td>';
                } else {
                    $para_bottom_five_html .= '<td class="wksh-td">' . $para_bottom->assessment . '</td>';
                }
                $para_bottom_five_html .= '<td class="wksh-td">
                                                <span class="bold theme-font">' . $para_bottom->result . '%</span>
                                            </td>
                                        </tr>';
            }
        } else {
            $para_bottom_five_html .= '<tr class="tr-background">
                                        <td colspan="2" class="wksh-td">No Records Found</td>
                                    </tr>';
        }
        $data['para_bottom_five_table'] = $para_bottom_five_html;

        //$parameter_data = $this->role_play_rep_dashboard_model->get_parameter($Company_id,$user_id);
        $parameter_data = $this->role_play_rep_dashboard_model->get_assessment($user_id, $SDate, $EDate, $report_type);
        $parahtml = '<option value="">All</option>';
        if (count((array) $parameter_data) > 0) {
            foreach ($parameter_data as $value) {
                $parahtml .= '<option value="' . $value->assessment_id . '">' . $value->assessment . '</option>';
            }
        }
        $data['parahtml'] = $parahtml;
        echo json_encode($data);
    }


    public function ajax_getWeeks()
    {
        $year = $this->input->post('year', true);
        $month = $this->input->post('month', true);
        $data['WStartEnd'] = $this->common_model->getMonthWeek($year, $month);
        echo json_encode($data);
    }

    public function load_assessment_index($returnflag = 0)
    {
        $data = array();
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $Company_id = $this->input->post('company_id', TRUE);
        }
        $report_by = $this->input->post('report_by', true);
        $user_id = $this->input->post('user_id', true);
        $report_type = $this->input->post('report_type', true);
        $start_date = $this->input->post('StartDate', true);
        $end_date = $this->input->post('EndDate', true);
        $SDate = date('Y-m-d', strtotime($start_date));
        $EDate = date('Y-m-d', strtotime($end_date));

        $rpt_period = $this->input->post('rpt_period', true);
        $current_month = date('m');
        $current_date = date('Y-m-d');
        $report_data = array();
        $index_dataset = [];
        $index_label = [];
        $report_title = '';
        $report_xaxis_title = '';
        $Month = $this->input->post('month', true);
        $Year = $this->input->post('year', true);
        $Week = $this->input->post('week', true);
        $WeekStartDate = '';
        $WeekEndDate = '';
        if ($Week != '' && $Month != '' && $Year != '') {
            $WeekDate = explode('-', $Week);
            $WeekStartDay = $WeekDate[0];
            $WeekEndDay = $WeekDate[1];
            $WeekStartDate = date('Y-m-d', strtotime("$Year-$Month-$WeekStartDay"));
            $WeekEndDate = date('Y-m-d', strtotime("$Year-$Month-$WeekEndDay"));
        }
        if ($rpt_period == "weekly") {
            if ($WeekStartDate != '' && $WeekEndDate != '') {
                $AccuracySet = $this->role_play_rep_dashboard_model->assessment_index_weekly_monthly($report_by, $WeekStartDate, $WeekEndDate, $parameter_id, $user_id, $report_type);
                for ($i = $WeekStartDay; $i <= $WeekEndDay; $i++) {
                    $day = str_pad($i, 2, "0", STR_PAD_LEFT);
                    if ($Year != '' && $Month != '') {
                        $TempDate = $Year . '-' . $Month . '-' . $i;
                    } else {
                        $TempDate = Date('Y-m-' . $i);
                    }
                    if (isset($AccuracySet['period'][$day])) {
                        $index_dataset[] = json_encode($AccuracySet['period'][$day], JSON_NUMERIC_CHECK);
                    } else {
                        $index_dataset[] = 0;
                    }
                    $index_label[] = date("l", strtotime($TempDate));
                }
            } else {
                $WeekStartDate = date('Y-m-d', strtotime("-6 days"));
                $WeekEndDate = $current_date;
                $StartStrDt = date('d-m-Y', strtotime("-6 days"));
                $EndStrDt = date('d-m-Y');
                $StartWeek = date('d', strtotime("-6 days"));
                $EndWeek = date('d');
                $AccuracySet = $this->role_play_rep_dashboard_model->assessment_index_weekly_monthly($report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);
                for ($i = $StartWeek; $i <= $EndWeek; $i++) {
                    $day = str_pad($i, 2, "0", STR_PAD_LEFT);
                    $TempDate = Date('Y-m-' . $i);
                    if (isset($AccuracySet['period'][$day])) {
                        $index_dataset[] = json_encode($AccuracySet['period'][$day], JSON_NUMERIC_CHECK);
                    } else {
                        $index_dataset[] = 0;
                    }
                    $index_label[] = date("l", strtotime($TempDate));
                }
            }
            $report_xaxis_title = 'Weekly';
        } elseif ($rpt_period == "monthly") {
            if ($Year != '' && $Month != '' && $Month != $current_month) {
                $StartDate = $Year . '-' . $Month . '-01';
                $WeekStartDate = $StartDate;
                $StartStrDt = '01-' . $Month . '-' . $Year;
                $noofdays = date('t', strtotime($StartDate));
                $EndDate = $Year . '-' . $Month . '-' . $noofdays;
                $WeekEndDate = $EndDate;
                $EndStrDt = $noofdays . '-' . $Month . '-' . $Year;
            } else {
                $WeekStartDate = Date('Y-m-1');
                $WeekEndDate = $current_date;
                $noofdays = Date('d');
            }

            $report_xaxis_title = 'Monthly';
            $AccuracySet = $this->role_play_rep_dashboard_model->assessment_index_weekly_monthly($report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);
            $WeekNo = 1;
            $Divider = 0;
            for ($i = 1; $i <= $noofdays; $i++) {
                $day = str_pad($i, 2, "0", STR_PAD_LEFT);
                $TempDate = $Year . '-' . $Month . '-' . $day;
                if (isset($AccuracySet['period'][$day])) {
                    $index_dataset[] = json_encode($AccuracySet['period'][$day], JSON_NUMERIC_CHECK);
                } else {
                    $index_dataset[] = 0;
                }
                $index_label[] = date("d-M", strtotime($TempDate));
            }
        } elseif ($rpt_period == "yearly") {
            $WeekStartDate = $Year . '-01-01';
            $WeekEndDate = $Year . '-12-31';

            $report_xaxis_title = 'Yearly';
            $AccuracySet = $this->role_play_rep_dashboard_model->assessment_index_yearly($report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);
            for ($i = 1; $i <= 12; $i++) {
                $day = str_pad($i, 2, "0", STR_PAD_LEFT);
                $TempDate = Date('Y-' . $day . '-01');
                if (isset($AccuracySet['period'][$i])) {
                    $index_dataset[] = json_encode($AccuracySet['period'][$i], JSON_NUMERIC_CHECK);
                } else {
                    $index_dataset[] = 0;
                }
                $index_label[] = date("M", strtotime($TempDate));
            }
        }
        $report_title = 'Assessment Index - (Period From ' . date('d-m-Y', strtotime($WeekStartDate)) . ' To ' . date('d-m-Y', strtotime($WeekEndDate)) . ')';
        $parameter_id = $this->input->post('parameter_id', true);
        if ($parameter_id != '') {
            $data = $this->load_parameter_index(1);
        }

        $data['report'] = $report_data;
        $Rdata['report_period'] = $report_xaxis_title;
        $Rdata['report_title'] = json_encode($report_title);
        $Rdata['index_dataset'] = json_encode($index_dataset, JSON_NUMERIC_CHECK);
        $Rdata['index_label'] = json_encode($index_label);
        $indexGraph = $this->load->view('role_play_rep_dashboard/assessment_index_report', $Rdata, true);
        $data['index_graph'] = $indexGraph;

        if ($returnflag) {
            return $data;
        } else {
            echo json_encode($data);
        }
    }
    public function load_parameter_index($returnflag = 0)
    {
        $data = array();
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $Company_id = $this->input->post('company_id', TRUE);
        }
        $report_by = $this->input->post('report_by', true);
        $user_id = $this->input->post('user_id', true);
        $report_type = $this->input->post('report_type', true);
        $start_date = $this->input->post('StartDate', true);
        $end_date = $this->input->post('EndDate', true);
        $SDate = date('Y-m-d', strtotime($start_date));
        $EDate = date('Y-m-d', strtotime($end_date));

        $rpt_period = $this->input->post('rpt_period', true);
        $current_month = date('m');
        $current_date = date('Y-m-d');
        $report_data = array();


        $index_paradataset = [];
        $index_paralabel = [];
        $report_paratitle = '';
        $report_xaxis_paratitle = '';

        $parameter_id = $this->input->post('parameter_id', true);
        //print_r($parameter_id);
        //exit;
        $Month = $this->input->post('month', true);
        $Year = $this->input->post('year', true);
        $Week = $this->input->post('week', true);
        $WeekStartDate = '';
        $WeekEndDate = '';
        if ($Week != '' && $Month != '' && $Year != '') {
            $WeekDate = explode('-', $Week);
            $WeekStartDay = $WeekDate[0];
            $WeekEndDay = $WeekDate[1];
            $WeekStartDate = date('Y-m-d', strtotime("$Year-$Month-$WeekStartDay"));
            $WeekEndDate = date('Y-m-d', strtotime("$Year-$Month-$WeekEndDay"));
        }
        if ($rpt_period == "weekly") {
            if ($WeekStartDate != '' && $WeekEndDate != '') {
                $AccuracySet = $this->role_play_rep_dashboard_model->parameter_index_charts_new($parameter_id, $report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);
                for ($i = $WeekStartDay; $i <= $WeekEndDay; $i++) {
                    $day = str_pad($i, 2, "0", STR_PAD_LEFT);
                    if ($Year != '' && $Month != '') {
                        $TempDate = $Year . '-' . $Month . '-' . $i;
                    } else {
                        $TempDate = Date('Y-m-' . $i);
                    }
                    if (isset($AccuracySet['period'][$day])) {
                        $index_dataset[] = json_encode($AccuracySet['period'][$day], JSON_NUMERIC_CHECK);
                    } else {
                        $index_dataset[] = 0;
                    }
                    $index_label[] = date("l", strtotime($TempDate));
                    $index_paralabel[] = $assess->assessment;
                }
            } else {
                $WeekStartDate = date('Y-m-d', strtotime("-6 days"));
                $WeekEndDate = $current_date;
                $StartStrDt = date('d-m-Y', strtotime("-6 days"));
                $EndStrDt = date('d-m-Y');
                $StartWeek = date('d', strtotime("-6 days"));
                $EndWeek = date('d');
                $AccuracySet = $this->role_play_rep_dashboard_model->parameter_index_charts($parameter_id, $report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);
            }
            $report_xaxis_title = 'Weekly';
        } elseif ($rpt_period == "monthly") {
            if ($Year != '' && $Month != '' && $Month != $current_month) {
                $StartDate = $Year . '-' . $Month . '-01';
                $WeekStartDate = $StartDate;
                $StartStrDt = '01-' . $Month . '-' . $Year;
                $noofdays = date('t', strtotime($StartDate));
                $EndDate = $Year . '-' . $Month . '-' . $noofdays;
                $WeekEndDate = $EndDate;
                $EndStrDt = $noofdays . '-' . $Month . '-' . $Year;
            } else {
                $WeekStartDate = Date('Y-m-1');
                $WeekEndDate = $current_date;
                $noofdays = Date('d');
            }
            $report_xaxis_title = 'Monthly';
            $AccuracySet = $this->role_play_rep_dashboard_model->parameter_index_charts($parameter_id, $report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);
        } elseif ($rpt_period == "yearly") {
            $WeekStartDate = $Year . '-01-01';
            $WeekEndDate = $Year . '-12-31';

            $report_xaxis_title = 'Yearly';
            $AccuracySet = $this->role_play_rep_dashboard_model->parameter_index_charts_new($parameter_id, $report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);

        }
        $AccuracySet = $this->role_play_rep_dashboard_model->parameter_index_charts_new($parameter_id, $report_by, $WeekStartDate, $WeekEndDate, $user_id, $report_type);

        if (count((array) $AccuracySet) > 0) {
            foreach ($AccuracySet as $assess) {
                $index_paradataset[] = json_encode($assess['result'], JSON_NUMERIC_CHECK);
                $index_paralabel[] = $assess['parameter_name'];
            }
        }
        //$report_paratitle = 'Parameter Index - (Period From ' . date('d-m-Y', strtotime($WeekStartDate)) . ' To ' . date('d-m-Y', strtotime($WeekEndDate));
        if ($parameter_id != '') {
            //            $parameter_data = $this->common_model->get_value('parameter_mst', 'id,description', 'status=1 and id='.$parameter_id);
            $parameter_data = $this->common_model->get_value('assessment_mst', 'id,assessment', 'id=' . $parameter_id);
            $report_paratitle = ' <strong>' . $parameter_data->assessment . '</strong>';
        } else {
            $report_paratitle .= ' <strong>All Parameters</strong>)';
        }

        $PRdata['report_paraperiod'] = $report_xaxis_title;
        $PRdata['report_paratitle'] = $report_paratitle;
        $PRdata['index_paradataset'] = json_encode($index_paradataset, JSON_NUMERIC_CHECK);
        $PRdata['index_paralabel'] = json_encode($index_paralabel);
        $indexAssessGraph = $this->load->view('role_play_rep_dashboard/parameter_index_report', $PRdata, true);
        $data['index_paragraph'] = $indexAssessGraph;

        if ($returnflag) {
            return $data;
        } else {
            echo json_encode($data);
        }
    }
    //User score created by Rudra patel
    public function user_wise_understanding()
    {
        $dtSearchColumns = array('your_score', 'top_performer', 'bottom_performer', 'ranks', 'badge');
        $DTRenderArray = $this->common_libraries->DT_RenderColumns($dtSearchColumns);

        $company_id = $this->mw_session['company_id'];
        $assessment_id = $this->input->get('assessment_id', true);
        $trainee_id = $this->input->get('trainee_id', true);
        $start_date = $this->input->get('StartDate', true);
        $end_date =   $this->input->get('EndDate', true);
        $is_custom =  $this->input->get('IsCustom', true);
        $company_id = $this->mw_session['company_id'];
        $SDate = date('Y-m-d', strtotime($start_date));
        $EDate = date('Y-m-d', strtotime($end_date));

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
                $start_date = date('Y-m-d', strtotime("-30 days"));
                $current_date =  date("Y-m-d h:i:s");

                $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, '');
                $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, 1);
                $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, 2);
            } else if ($is_custom == "Current Year") {
                $startdate = date('Y-01-01');
                $CurrentDate = date("Y-m-d");

                $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, '');
                $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 1);
                $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 2);

            } else {
                $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, '');
                $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, 1);
                $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, 2);
            }
        } else if (!empty($trainee_id)) {
            //Time filter
            // echo "didj";
            
            if ($is_custom == "") {
                $start_date = date('Y-m-d', strtotime("-30 days"));
                $current_date =  date("Y-m-d h:i:s");
                $get_respected_assessment = $this->role_play_rep_dashboard_model->get_respected_assessment($trainee_id, $start_date, $current_date);
                if (!empty($get_respected_assessment)) {
                    foreach ($get_respected_assessment as $gra) {
                        $lastAssessmentId[] = $gra['assessment_id'] != '' ? $gra['assessment_id'] : '';
                    } 
                    if (!empty($lastAssessmentId)) {
                        $assessment_id = implode(',', $lastAssessmentId);
                        $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, '', $start_date, $current_date, '');
                        $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, 1);
                        $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, 2);
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
                $get_respected_assessment = $this->role_play_rep_dashboard_model->get_respected_assessment($trainee_id, $startdate, $CurrentDate);
                if (!empty($get_respected_assessment)) {
                    foreach ($get_respected_assessment as $gra) {
                        $lastAssessmentId[] = $gra['assessment_id'] != '' ? $gra['assessment_id'] : '';
                    }
                    if (!empty($lastAssessmentId)) {
                        $assessment_id = implode(',', $lastAssessmentId);
                        $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, '', $startdate, $CurrentDate, '');
                        $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 1);
                        $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 2);
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
                $get_respected_assessment = $this->role_play_rep_dashboard_model->get_respected_assessment($trainee_id, $SDate, $EDate);

                if (!empty($get_respected_assessment)) {
                    foreach ($get_respected_assessment as $gra) {
                        $lastAssessmentId[] = $gra['assessment_id'] != '' ? $gra['assessment_id'] : '';
                    }
                    if (!empty($lastAssessmentId)) {
                        $assessment_id = implode(',', $lastAssessmentId);
                        $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, '', $SDate, $EDate, '');
                        $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, 1);
                        $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, 2);
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
            $last_assessment_id = $this->role_play_rep_dashboard_model->last_assessment_id();
            $assessment_id = $last_assessment_id->assessment_id;
            $trainee_id = $last_assessment_id->user_id;
           
            if (!empty($assessment_id) && !empty($trainee_id)) {
                if ($is_custom == "") {
                    $start_date = date('Y-m-d', strtotime("-30 days"));
                    $current_date =  date("Y-m-d h:i:s");

                    $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, '');
                    $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, 1);
                    $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $start_date, $current_date, 2);
                } else if ($is_custom == "Current Year") {
                    $startdate = date('Y-01-01');
                    $CurrentDate = date("Y-m-d");

                    $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, '');
                    $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 1);
                    $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $startdate, $CurrentDate, 2);
                    // print_r($trainee_score);
                } else {
                    $trainee_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, '');
                    $min_max_score = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, 1);
                    $ranking = $this->role_play_rep_dashboard_model->get_Trainee_score($trainee_id, $assessment_id, $SDate, $EDate, 2);
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
                    $badge =  'Rockstar';
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

        $output['title'] = '';
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
    //User score ended by Rudra patel  

    //get_all_assessment function created by Rudra Patel 
    public function get_all_trainee(){
        //$trainee_set = '';
        $Company_id =  $this->input->post('company_id', TRUE);
        $trainee_id = $this->input->post('trainee_id', TRUE) != '' ? $this->input->post('trainee_id', TRUE) : '';
        $assessmentid = $this->input->post('assessmentid', TRUE) != '' ? $this->input->post('assessmentid', TRUE) : '';
        $trainee_list = $this->role_play_rep_dashboard_model->get_all_trainee($Company_id,$assessmentid);
        $trainee_set = '<option value="">Please select</option>';
        if (count((array)$trainee_list) > 0) {
            
            foreach ($trainee_list as $mvalue) {
                $trainee_set .= '<option value="' . $mvalue->user_id . '">[' . $mvalue->user_id . '] - ' . $mvalue->user_name . '</option>';
            }
        }
       
        $data['trainee_set'] = $trainee_set;
        echo json_encode($data);
    }
    //get_all_assessment function ended by Rudra Patel 
}