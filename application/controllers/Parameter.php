<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';
use Google\Cloud\Translate\V2\TranslateClient;

class Parameter extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $acces_management = $this->check_rights('parameter');
        if (!$acces_management->allow_access) {
            redirect('dashboard');
        }
        $this->acces_management = $acces_management;
        $this->load->model('parameter_model');
    }        
    public function index() {
        $data['module_id'] = '13.01';
        $data['username'] = $this->mw_session['username'];
        $data['acces_management'] = $this->acces_management;  
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $data['CompnayResultSet'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1');
        } else {
            $data['CompnayResultSet'] = array();
        }
        $data['Company_id'] = $Company_id;
		$data['category_set'] = $this->common_model->get_selected_values('parameter_category', 'id,name', 'status=1');
        $this->load->view('parameter/index', $data);
    }
    public function create() {
        $data['module_id'] = '13.01';
        $data['username'] = $this->mw_session['username'];
        $data['acces_management'] = $this->acces_management;
        if (!$data['acces_management']->allow_add) {
            redirect('parameter');
            return;
        }          
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $data['CompnayResultSet'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1');
        } else {
            $data['CompnayResultSet'] = array();
        }
        $data['Company_id'] = $Company_id;
        $data['assessment_type'] = $this->common_model->get_selected_values('assessment_type', 'id,description', 'status=1');
		$data['category_set'] = $this->common_model->get_selected_values('parameter_category', 'id,name', 'status=1');
        $this->load->view('parameter/create', $data);
    }
    public function edit($id) {
        $parameter_id = base64_decode($id);
        $data['module_id'] = '13.01';
        $data['username'] = $this->mw_session['username'];        
        $data['acces_management'] = $this->acces_management;
        if (!$data['acces_management']->allow_edit) {
            redirect('parameter');
            return;
        }       
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $data['cmp_result'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1');
        } else {
            $data['cmp_result'] = array();
        }
        $data['Company_id'] = $Company_id;
        $data['result'] = $this->common_model->get_value('parameter_mst', '*', 'id='.$parameter_id);
		$data['category_set'] = $this->common_model->get_selected_values('parameter_category', 'id,name', 'status=1');
        $data['assessment_type'] = $this->common_model->get_selected_values('assessment_type', 'id,description', 'status=1');
        $this->load->view('parameter/edit', $data);
    }   
    public function view($id) {
        $parameter_id = base64_decode($id);
        $data['module_id'] = '13.01';
        $data['username'] = $this->mw_session['username'];        
        $data['acces_management'] = $this->acces_management;
        if (!$data['acces_management']->allow_view) {
            redirect('parameter');
            return;
        }       
        $Company_id = $this->mw_session['company_id'];
        if ($Company_id == "") {
            $data['cmp_result'] = $this->common_model->get_selected_values('company', 'id,company_name', 'status=1');
        } else {
            $data['cmp_result'] = array();
        }
        $data['Company_id'] = $Company_id;
        $data['result'] = $this->common_model->get_value('parameter_mst', '*', 'id='.$parameter_id);    
        $data['assessment_type'] = $this->common_model->get_selected_values('assessment_type', 'id,description', 'status=1');
		$data['category_set'] = $this->common_model->get_selected_values('parameter_category', 'id,name', 'status=1');
        $this->load->view('parameter/view', $data);
    }
    public function DatatableRefresh() {
        $dtSearchColumns = array('p.id', 'p.id','c.company_name','at.description','p.description','pc.name','p.status');
        $DTRenderArray = $this->common_libraries->DT_RenderColumns($dtSearchColumns);
        $dtWhere = $DTRenderArray['dtWhere'];
        $dtOrder = $DTRenderArray['dtOrder'];
        $dtLimit = $DTRenderArray['dtLimit'];       

        $status = $this->input->get('filter_status');
        if ($status != "") {
            if ($dtWhere <> '') {
                $dtWhere .= " AND p.status  = " . $status;
            } else {
                $dtWhere .= " WHERE p.status  = " . $status;
            }
        }
		$category_id = $this->input->get('filter_category_id');
        if ($category_id != "") {
            if ($dtWhere <> '') {
                $dtWhere .= " AND p.category_id  = " . $category_id;
            } else {
                $dtWhere .= " WHERE p.category_id  = " . $category_id;
            }
        }
		
        if ($this->mw_session['company_id'] == "") {
            $cmp_id = ($this->input->get('filter_company_id') ? $this->input->get('filter_company_id') : '');
            if ($cmp_id != "") {
                if ($dtWhere <> '') {
                    $dtWhere .= " AND p.company_id  = " . $cmp_id;
                } else {
                    $dtWhere .= " WHERE p.company_id  = " . $cmp_id;
                }
            }
        } else {
            if ($dtWhere <> '') {
                $dtWhere .= " AND p.company_id  = " . $this->mw_session['company_id'];
            } else {
                $dtWhere .= " WHERE p.company_id  = " . $this->mw_session['company_id'];
            }
        }
        $DTRenderArray = $this->parameter_model->LoadDataTable($dtWhere, $dtOrder, $dtLimit);
        $output = array(
            "sEcho" => $this->input->get('sEcho') ? $this->input->get('sEcho') : 0,
            "iTotalRecords" => $DTRenderArray['dtPerPageRecords'],
            "iTotalDisplayRecords" => $DTRenderArray['dtTotalRecords'],
            "aaData" => array()
        );
        $dtDisplayColumns = array('checkbox','id','company_name','assessment_type','description','category_name','status', 'Actions');
        // $dtDisplayColumns = array('id','company_name','assessment_type','description','category_name','status', 'Actions');
        $site_url = base_url();
        $acces_management = $this->acces_management;

        foreach ($DTRenderArray['ResultSet'] as $dtRow) {
            $row = array();
            $title = $dtRow['description'];
            $TotalHeader = count((array)$dtDisplayColumns);
            for ($i = 0; $i < $TotalHeader; $i++) {
                if ($dtDisplayColumns[$i] == "status") {
                    if ($dtRow['status'] == 1) {
                        $status = '<span class="label label-sm label-info status-active" > Active </span>';
                    } else {
                        $status = '<span class="label label-sm label-danger status-inactive" > In Active </span>';
                    }
                    $row[] = $status;
                }else if ($dtDisplayColumns[$i] == "weight_type") {
                    if ($dtRow['weight_type'] == 1) {
                        $weight_type = 'Star Rating';
                    } else {
                        $weight_type = 'Slider';
                    }
                    $row[] = $weight_type;
                }else if ($dtDisplayColumns[$i] == "checkbox") {
                    $disabled = $dtRow['id']>8 ? '' : 'disabled';
                    $row[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes" name="id[]" value="' . $dtRow['id'] . '" '.$disabled.'/>
                                <span></span>
                            </label>';
                } else if ($dtDisplayColumns[$i] == "Actions") {
                    $action = '';
                    if ($acces_management->allow_add OR $acces_management->allow_view OR $acces_management->allow_edit OR $acces_management->allow_delete) {
                        $action = '<div class="btn-group">
                                <button class="btn orange btn-xs btn-outline dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> 
                                    Actions&nbsp;&nbsp;<i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">';
                        if ($acces_management->allow_view) {
                            $action .= '<li>
                                        <a href="' . $site_url . 'parameter/view/' . base64_encode($dtRow['id']) . '">
                                        <i class="fa fa-eye"></i>&nbsp;View
                                        </a>
                                    </li>';
                        }
                        if ($acces_management->allow_edit && $dtRow['id']>8) {
                            $action .= '<li>
                                        <a href="' . $site_url . 'parameter/edit/' . base64_encode($dtRow['id']) . '">
                                        <i class="fa fa-pencil"></i>&nbsp;Edit
                                        </a>
                                    </li>';
                        }
                        if ($acces_management->allow_delete && $dtRow['id']>8) {
                            $action .= '<li class="divider"></li><li>
                                        <a onclick="LoadDeleteDialog(\'' . $title . '\',\'' . base64_encode($dtRow['id']) . '\');" href="javascript:void(0)">
                                        <i class="fa fa-trash-o"></i>&nbsp;Delete
                                        </a>
                                    </li>';
                        }
                        $action .= '</ul>
                            </div>';
                    } else {
                        $action = '<button class="btn orange btn-xs btn-outline dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> 
                                Locked&nbsp;&nbsp;<i class="fa fa-lock"></i>
                            </button>';
                    }
                    $row[] = $action;
                } else if ($dtDisplayColumns[$i] != ' ') {
                    $row[] = $dtRow[$dtDisplayColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }   

public function set_class($class)
{
    $suffix = $this->config->item('controller_suffix');
    if( $suffix && strpos($class, $suffix) === FALSE)
    {
        $class .= $suffix;
    }

    parent::set_class($class);
} 



    public function submit(){
        $SuccessFlag = 1;
        $Message = '';
        $acces_management = $this->acces_management;
        if (!$acces_management->allow_add) {
            $Message .= "You have no rights to Add,Contact Administrator for rights.";
            $SuccessFlag = 0;
        } else {
            $this->load->library('form_validation');
            if ($this->mw_session['company_id'] == "") {
                $this->form_validation->set_rules('company_id', 'Company name', 'required');
                $Company_id = $this->input->post('company_id');
            } else {
                $Company_id = $this->mw_session['company_id'];
            }
            $this->form_validation->set_rules('assessment_type', 'Assessment Type', 'required');
            $this->form_validation->set_rules('parameter', 'Parameter', 'required');
            $this->form_validation->set_rules('category_id', 'category', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == FALSE) {
                $Message .= validation_errors();
                $SuccessFlag = 0;
            }
            $parameter = $this->input->post('parameter');
            if(isset($parameter)){
                /*if (strcmp(substr($parameter,0,18),"Product Knowledge ") !== 0) {
                    $Message .= "Name should start with Product Knowledge..<br/>";
                    $SuccessFlag = 0;
                }*/
                //-- added by shital: 06:03:2024 ----
                $api_key = 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750';
                $translate = new TranslateClient(['key' => 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750']);

                $this->db->select('ml_short');
                $this->db->from('ai_multi_language');
                $language_array = $this->db->get()->result();
                if (count((array)$language_array) > 0) {
                    foreach ($language_array as $lg) {
                        $lang_key[] = $lg->ml_short;
                    }
                }

                if (count((array)$lang_key) > 0) {
                    foreach ($lang_key as $lk) {
                        $parameter = strtolower(str_replace(" ", "", $parameter)); //strtolower($new_text);

                        $result_PK = $translate->translate("Product Knowledge ", ['target' => $lk]);
                        $new_textPK = $result_PK['text'];
                        $final_textPK[] = strtolower(str_replace(" ", "", $new_textPK));
                    }
                } 

                $match_cd=array(); $nomatch_cd=array();

                foreach($final_textPK as $letter){
                    if(strpos($parameter,$letter) === 0) {
                        //echo "------".$letter."===match==================="; echo "<br/>";
                        $match_cd[] = $letter;
                    }else{
                        //echo "------".$parameter."====no match=="; echo "<br/>";
                        $nomatch_cd[] = $letter;
                    }
                }

                if(count((array)$match_cd)==0){
                    $Message .= "Name should start with Product Knowledge..<br/>";
                    $SuccessFlag = 0;
                }
                //-- added by shital: 06:03:2024 ----
            }
            if ($SuccessFlag) {
                //$weight_type = $this->input->post('weight_type');
                $now = date('Y-m-d H:i:s');
                $data = array(
                    'company_id' => $Company_id,
                    'assessment_type' => $this->input->post('assessment_type'),
                    'description' => $this->input->post('parameter'),
                    // 'category_id' => 2,
					'category_id' => $this->input->post('category_id'),
                    //'weight_type' => $weight_type,
                    'weight_value' => 5,  
                    //'weight_range_from' => ($weight_type == 2 ? $this->input->post('weight_range_from') : '0'),
                    //'weight_range_to' => ($weight_type == 2 ? $this->input->post('weight_range_to') : '0'),
                    'status' => $this->input->post('status'),
                    'addeddate' => $now,
                    'addedby' => $this->mw_session['user_id'],
                );
                $insert_id = $this->common_model->insert('parameter_mst', $data);

                //add sub parameter for the Product knowledge parameter
                $subparam_data = array(
                    'parameter_id'          => $insert_id,
                    'description'           => 'Product Knowledge',
                    'has_sentences_keyword' => 1,
                    'status'                => 1,
                    'addeddate'             => $now,
                    'addedby'               => $this->mw_session['user_id'],
                );
                $this->common_model->insert('subparameter_mst', $subparam_data);
                $Message .="Parameter Save Successfully..";
            }
        }
        $Rdata['success'] = $SuccessFlag;
        $Rdata['Msg'] = $Message;
        echo json_encode($Rdata);
    }

    public function mytest()
    {
        $this->load->view('parameter/mytest');
    }

    public function update($Encode_id) {
        $id = base64_decode($Encode_id);
        $SuccessFlag = 1;        
        $Message = '';        
        $acces_management = $this->acces_management;
        if (!$acces_management->allow_edit) {
            $Message .= "You have no rights to Edit,Contact Administrator for rights.";
            $SuccessFlag = 0;
        } else {            
            $this->load->library('form_validation');     
            if ($this->mw_session['company_id'] == "") {
                $this->form_validation->set_rules('company_id', 'Company name', 'required');
                $Company_id = $this->input->post('company_id');
            } else {
                $Company_id = $this->mw_session['company_id'];
            }
            $this->form_validation->set_rules('assessment_type', 'Assessment Type', 'required');            
            $this->form_validation->set_rules('parameter', 'Parameter', 'required');
            // $this->form_validation->set_rules('category_id', 'category', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            $parameter = $this->input->post('parameter');
            if (isset($parameter)) {
                /*if (strcmp(substr($parameter, 0, 18), "Product Knowledge ") !== 0) {
                    $Message .= "Name should start with Product Knowledge..<br/>";
                    $SuccessFlag = 0;
                }*/
                //-- added by shital: 06:03:2024 ----
                $api_key = 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750';
                $translate = new TranslateClient(['key' => 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750']);

                $this->db->select('ml_short');
                $this->db->from('ai_multi_language');
                $language_array = $this->db->get()->result();
                if (count((array)$language_array) > 0) {
                    foreach ($language_array as $lg) {
                        $lang_key[] = $lg->ml_short;
                    }
                }

                if (count((array)$lang_key) > 0) {
                    foreach ($lang_key as $lk) {
                        $parameter = strtolower(str_replace(" ", "", $parameter)); //strtolower($new_text);

                        $result_PK = $translate->translate("Product Knowledge ", ['target' => $lk]);
                        $new_textPK = $result_PK['text'];
                        $final_textPK[] = strtolower(str_replace(" ", "", $new_textPK));
                    }
                } 

                $match_cd=array(); $nomatch_cd=array();

                foreach($final_textPK as $letter){
                    if(strpos($parameter,$letter) === 0) {
                        //echo "---".$letter."===match"; echo "<br/>";
                        $match_cd[] = $letter;
                    }else{
                        //echo "---".$parameter."==no match=="; echo "<br/>";
                        $nomatch_cd[] = $letter;
                    }
                }

                if(count((array)$match_cd)==0){
                    $Message .= "Name should start with Product Knowledge..<br/>";
                    $SuccessFlag = 0;
                }

            }
            if ($this->form_validation->run() == FALSE) {
                $Message .= validation_errors();
                $SuccessFlag = 0;
            }                             
            if ($SuccessFlag) {
                $weight_type = $this->input->post('weight_type');
                $now = date('Y-m-d H:i:s');
                $data = array(
                    'company_id' => $Company_id,
                    'assessment_type' => $this->input->post('assessment_type'),
                    'description' => $this->input->post('parameter'),
                    // 'category_id' => 2,
					// 'category_id' => $this->input->post('category_id'),
                    //'weight_type' => $weight_type,
                    //'weight_value' => ($weight_type == 1 ? $this->input->post('weight_value') : '0'),  
                    //'weight_range_from' => ($weight_type == 2 ? $this->input->post('weight_range_from') : '0'),
                    //'weight_range_to' => ($weight_type == 2 ? $this->input->post('weight_range_to') : '0'),
                    'status' => $this->input->post('status'),
                    'modifieddate' => $now,
                    'modifiedby' => $this->mw_session['user_id'],
                );
                $insert_id = $this->common_model->update('parameter_mst','id',$id,$data);
                $Message .="Parameter Update Successfully..";
            }            
        }
        $Rdata['success'] = $SuccessFlag;
        $Rdata['Msg'] = $Message;
        echo json_encode($Rdata);        
    }
    public function remove() {
        $alert_type = 'success';
        $message = '';
        $title = '';
        $acces_management = $this->acces_management;
        if (!$acces_management->allow_delete) {
            $alert_type = 'error';
            $message = 'You have no rights to delete,Contact Administrator for details.';
        } else {
            $deleted_id = $this->input->post('deleteid');
            $DeleteFlag = 1;
            if ($DeleteFlag) {                
                $this->common_model->delete('parameter_mst', 'id', base64_decode($deleted_id));
                $this->common_model->delete('parameter_label_mst', 'parameter_id', base64_decode($deleted_id));    //remove respective parameter labels
                $this->common_model->delete('subparameter_mst', 'parameter_id', base64_decode($deleted_id));    //remove respective sub-parameter
                $message = "Parameter deleted successfully.";
            } else {
                $alert_type = 'error';
                $message = "FParameter cannot be deleted.";
            }
        }
        echo json_encode(array('message' => $message, 'alert_type' => $alert_type));
        exit;
    }

    public function record_actions($Action) {
        $action_id = $this->input->post('id');
        if (count((array)$action_id) == 0) {
            echo json_encode(array('message' => "Please select record from the list", 'alert_type' => 'error'));
            exit;
        }
        $now = date('Y-m-d H:i:s');
        $alert_type = 'success';
        $message = '';
        $title = '';
        if ($Action == 1) {
            foreach ($action_id as $id) {
                $data = array(
                    'status' => 1,
                    'modifieddate' => $now,
                    'modifiedby' => $this->mw_session['user_id']);
                $this->common_model->update('parameter_mst', 'id', $id, $data);
            }
            $message = 'Status changed to active successfully.';
        } else if ($Action == 2) {
            $SuccessFlag = false;
            foreach ($action_id as $id) {  
                $StatusFlag = 1;
                if ($StatusFlag) {
                    $data = array(
                        'status' => 0,
                        'modifieddate' => $now,
                        'modifiedby' => $this->mw_session['user_id']);
                    $this->common_model->update('parameter_mst', 'id', $id, $data);
                    $SuccessFlag = true;
                } else {
                    $alert_type = 'error';
                    $message = "Status cannot be change. !<br/>";
                }
            }
            if ($SuccessFlag) {
                $message .= 'Status changed to in-active sucessfully.';
            }
        } else if ($Action == 3) {
            $SuccessFlag = false;
            foreach ($action_id as $id) {               
                $DeleteFlag=1;
                if ($DeleteFlag) {                    
                    $this->common_model->delete('parameter_mst', 'id', $id);                    
                    $SuccessFlag = true;
                } else {
                    $alert_type = 'error';
                    $message = "Parameter cannot be deleted.";
                }
            }
            if ($SuccessFlag) {
                $message .= 'Parameter deleted successfully.';
            }
        }
        echo json_encode(array('message' => $message, 'alert_type' => $alert_type));
        exit;
    }
    public function Check_parameter() {
        /*$assessment_type = $this->input->post('assessment_type', true);  
        $parameter       = $this->input->post('parameter', true);
        $parameter_id    = $this->input->post('parameter_id', true);
        if ($assessment_type != '') {
            echo $this->parameter_model->check_parameter_exist($assessment_type,$parameter,$parameter_id);
        }*/

        $assessment_type = $this->input->post('assessment_type', true);  
        $parameter       = $this->input->post('parameter', true);
        $parameter_id    = $this->input->post('parameter_id', true);

        // Changes by Shital Patel - Language module changes-06-03-2024

        $api_key = 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750';
        $translate = new TranslateClient(['key' => 'AIzaSyBFbgPZh0xg8rcH_vkWEXtWufNxwVvU750']);

        $this->db->select('ml_short');
        $this->db->from('ai_multi_language');
        $language_array = $this->db->get()->result();
        if (count((array)$language_array) > 0) {
            foreach ($language_array as $lg) {
                $lang_key[] = $lg->ml_short;
            }
        }
        if (count((array)$lang_key) > 0) {
            foreach ($lang_key as $lk) {
                $result = $translate->translate($parameter, ['target' => $lk]);
                $new_text = $result['text'];
                $final_txt[] = strtolower(str_replace(" ", "", $new_text)); //strtolower($new_text);
            }
        } 

        if (count((array)$final_txt) > 0) {
            $query = "select description from parameter_mst where LOWER(REPLACE(description, ' ', '')) IN ('" . implode("','", $final_txt) . "') ";
            if($assessment_type!=''){
                $query.=" and assessment_type=".$assessment_type;
            }
            if($parameter_id!=''){
                $query.=" and id!=".$parameter_id;
            }
            
            $result = $this->db->query($query);
            $data = $result->row();
            if (count((array)$data) > 0) {
                echo $msg = "Parameter already exists!!!";
            }
        } // Changes by  Shital Patel - Language module changes-06-03-2024
    }
}