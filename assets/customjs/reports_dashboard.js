function dashboard_refresh() {
    if ($('#company_id').val() == "") {
        ShowAlret("Please select Company.!!", 'error');
        return false;
    }
    if ($('#report_by').val() == 1) {
        $(".th-desc").text("Parameter");
    } else {
        $(".th-desc").text("Assessment");
    }
	//console.log( $('#FilterFrm').serialize());
    $.ajax({
        type: "POST",
		//data: $('#FilterFrm').serialize(),
        data: {company_id: $('#company_id').val(), report_by: $('#report_by').val(), region_id: $('#region_id').val(),
           store_id: $('#store_id').val(),StartDate: StartDate, EndDate: EndDate, supervisor_id: $('#supervisor_id').val(), 
			assessment_id1: $('#assessment_id1').val(), report_type: $('#report_type').val()},
        //async: false,
        url: base_url + "reports_dashboard/getdashboardData",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (data) {
            if (data != '') {
                var json = jQuery.parseJSON(data);
                $('#total_assessment').attr('data-value', json['Total_Assessment']);
                $('#total_assessment').counterUp();
                $('#candidate_count').attr('data-value', json['Candidate_Count']);
                $('#candidate_count').counterUp();
                $('#average_accuracy').attr('data-value', json['Avg_Accuracy']+'%');
                $('#average_accuracy').counterUp();
                $('#highest_accuracy').attr('data-value', json['high_Accuracy']+'%');
                $('#highest_accuracy').counterUp();
                $('#lowest_accuracy').attr('data-value', json['low_Accuracy']+'%');
                $('#lowest_accuracy').counterUp();
                $('#question_answer').attr('data-value', json['question_answer']);
                $('#question_answer').counterUp();
                
                if (json['para_top_five_html'] != '') {
                    $('#asmnt-top-five tbody').empty();
                    $('#asmnt-top-five tbody').append(json['para_top_five_html']);
                }
                if (json['para_bottom_five_table'] != '') {
                    $('#asmnt-bottom-five tbody').empty();
                    $('#asmnt-bottom-five tbody').append(json['para_bottom_five_table']);
                }
                
                $('#participants_html').html(json['participants_html']);
                $('#region_performance').html(json['overall_graph']);
                $('#region_data').html(json['region_graph']);
                $('#region_table').html(json['regiontable_graph']);
                $('#assessment_id').empty();
                $('#assessment_id').append(json['Assessmentchtml']);
                if(json['region_total'] > 3){
                    $('#btn-prev').show();
                    $('#btn-naxt').show();
                }else{
                    $('#btn-prev').hide();
                    $('#btn-naxt').hide();
                }
                assessment_index_refresh();
//                 $('#region_id').empty();
//                $('#region_id').append(json['Regionchtml']);
                customunBlockUI();
            }
        }
    });
}
function get_parameterdata(parameter_id, region_id, report_type, supervisor_id) {

    $.ajax({
        url: base_url + "reports_dashboard/parameter_scoredata",
        type: 'POST',
        data: {company_id: Company_id, report_type: report_type ,parameter_id: parameter_id, region_id: region_id,StartDate: StartDate, EndDate: EndDate, report_type: report_type, supervisor_id: supervisor_id},
        beforeSend: function () {
            customBlockUI();
        },
        success: function (html) {
            $('#modal-body').html(html);
            $('#LoadModalFilter').modal();
            customunBlockUI();
        }
    });
}
function get_regionwisedata(assessment_id, region_id, report_type) {

    $.ajax({
        url: base_url + "reports_dashboard/region_scoredata",
        type: 'POST',
        data: {assessment_id: assessment_id, region_id: region_id, report_type: report_type},
        beforeSend: function () {
            customBlockUI();
        },
        success: function (html) {
            $('#modal-body').html(html);
            $('#LoadModalFilter').modal();
            customunBlockUI();
        }
    });
}
function get_assessmentwisedata(assessment_id, report_type) {
    if (assessment_id == '') {
        return false;
    }
    $.ajax({
        url: base_url + "reports_dashboard/assessment_parameter_scoredata",
        type: 'POST',
        data: {assessment_id: assessment_id, report_type:report_type},
        beforeSend: function () {
            customBlockUI();
        },
        success: function (html) {
            $('#modal-body').html(html);
            $('#LoadModalFilter').modal();
            customunBlockUI();
        }
    });
}
function get_regionlevel_graph(region_id) {
    $.ajax({
        url: base_url + "reports_dashboard/region_level_scoredata",
        type: 'POST',
        data: {region_id: region_id,StartDate: StartDate, EndDate: EndDate, report_type: $('#report_type').val(), supervisor_id: $('#supervisor_id').val()},
        beforeSend: function () {
            customBlockUI();
        },
        success: function (html) {
            $('#modal-body').html(html);
            $('#LoadModalFilter').modal();
            customunBlockUI();
        }
    });
}
function getDatewiseAssessment() {
    $.ajax({
        type: "POST",
        data: {StartDate: StartDate, EndDate: EndDate},
        //async: false,
        url: base_url + "reports_dashboard/getDatewiseAssessment",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);
                $('#assessment_id').empty();
                $('#assessment_id').append(Oresult['Assessmentchtml']);
            }
            customunBlockUI();
        }
    });
}
function getDatewiseRegion() {
    $.ajax({
        type: "POST",
        data: {StartDate: StartDate, EndDate: EndDate},
        //async: false,
        url: base_url + "reports_dashboard/getDatewiseRegion",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);
                $('#region_id').empty();
                $('#region_id').append(Oresult['Regionchtml']);
            }
            customunBlockUI();
        }
    });
}
function dashboard_region_refresh() {
    $.ajax({
        type: "POST",
        //data: $('#frmModalForm').serialize(),
        data: $('#frmModalForm').serialize() + "&StartDate=" + StartDate + "&EndDate=" + EndDate +
         "&supervisor_id=" + $('#supervisor_id').val()+
        "&report_by=" + $('#report_by').val() + "&region_id=" + $('#region_id').val() + "&store_id=" + $('#store_id').val()+"&report_type=" + $('#report_type').val(),
        url: base_url + "reports_dashboard/getFilterDashboardData",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (data) {
            if (data != '') {
                var json = jQuery.parseJSON(data);
                $('#filter-modal').modal('toggle');
                $('#region_data').html(json['region_graph']);
                $('#region_table').html(json['regiontable_graph']);

                customunBlockUI();
            }
        }
    });
}
function dashboard_region_change(move_val) {
    if(move_val==1 && step > 3){
            step=step-3;
            $('#btn-prev').show();
        }else if(move_val==2){
            step=step+3;
            $('#btn-prev').show();
        }else{
            step=0;
            $('#btn-prev').hide();
        }
    $.ajax({
        type: "POST",
        data: $('#frmModalForm').serialize() + "&StartDate=" + StartDate + "&EndDate=" + EndDate +
                "&report_by=" + $('#report_by').val() + "&step=" + step + "&region_id=" + $('#region_id').val() + "&store_id=" + $('#store_id').val()
                + "&report_type=" + $('#report_type').val(),
        //async: false,
        url: base_url + "reports_dashboard/getRegionChart",
     
        success: function (data) {
            if (data != '') {
                var json = jQuery.parseJSON(data);
                if(step < json['region_total']){
                        $('#btn-naxt').show();
                        $('#region_data').html(json['region_graph']);
                    }else{
                        step=json['region_total']-3;
                        $('#btn-naxt').hide();
                    }      
            }
        }
    });
}
function SubmitData() {
    $.ajax({
        url: base_url + "reports_dashboard/update_range",
        type: 'POST',
        data: $("#rangeform").serialize(),
        beforeSend: function () {
            customBlockUI();
        },
        success: function (Odata) {
            var Data = $.parseJSON(Odata);
            if (Data['Success']) {
                ShowAlret(Data['Message'], 'success');
                dashboard_refresh();
            } else {
                ShowAlret(Data['Message'], 'error');
            }
            customunBlockUI();
        }
    });
}
function getWeek(){
    $.ajax({
        type: "POST",
        data: {year: $('#year').val(),month: $('#month').val()},
        async: false,
        url: base_url +"reports_dashboard/ajax_getWeeks",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {                        
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);                                                        
                var WStartEndDate = Oresult['WStartEnd'];                            
                var week_option = '<option value="">All Week</option>';                            
                    for (var i = 0; i < WStartEndDate.length; i++) {
                        week_option += '<option value="' + WStartEndDate[i] + '">' +'Week-'+ (i+1) + '</option>';
                    }                             
                $('#week').empty();
                $('#week').append(week_option);
            }
        customunBlockUI();    
        }
    });                               
}
function assessment_index_refresh(){
    if ($('#company_id').val() == "") {
        ShowAlret("Please select Company.!!", 'error');
        return false;
    } 
    $.ajax({
        type: "POST",
         data: {report_by: $('#report_by').val(), rpt_period:$('#rpt_period').val(),parameter_id:$('#parameter_id').val(), supervisor_id:$('#supervisor_id').val(), report_type: $('#report_type').val(), assessment_id1: $('#assessment_id1').val(),
             month:$('#month').val(),year:$('#year').val(),week:$('#week').val(),StartDate: StartDate, EndDate: EndDate, region_id: $('#region_id').val(),store_id: $('#store_id').val()},
        //async: false,
        url: base_url +"reports_dashboard/load_assessment_index",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);
                $("#assessment_index").html(json['index_graph']);
				if($('#parameter_id').val() !=''){
					$("#parameter_index").html(json['index_paragraph']);
				}
            }
            customunBlockUI();
        }
    });
}
function parameter_index_refresh(){
    $.ajax({
        type: "POST",
         data: {report_by: $('#report_by').val(), rpt_period:$('#rpt_period').val(),parameter_id:$('#parameter_id').val(),
             month:$('#month').val(),year:$('#year').val(),week:$('#week').val(),StartDate: StartDate, EndDate: EndDate, user_id: $('#user_id').val()},
        //async: false,
        url: base_url +"reports_dashboard/load_parameter_index",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);
                $("#parameter_index").html(json['index_paragraph']);
            }
            customunBlockUI();
        }
    });
}
function load_questions(company_id,assessment_id,user_id){
    var form_data = new FormData();
    form_data.append('company_id', company_id);
    form_data.append('assessment_id', assessment_id);
    form_data.append('user_id', user_id);
    $.ajax({
        cache      : false,
        contentType: false,
        processData: false,
        type       : 'POST',
        url        : base_url+"/reports_dashboard/fetch_questions/",
        data       : form_data,
        beforeSend: function () {
            customBlockUI();
        },
        success: function (Odata) {
            var json = $.parseJSON(Odata); 
            if (json.success=="true"){
                $('#mdl_questions').html(json['html_1']);
                $('#responsive-modal1').modal('show');   
            }
            customunBlockUI();
        },
        error: function(e){
            customunBlockUI();
        }
    });
}
function play_video(response){
    var vimeo_url = "https://player.vimeo.com/video/"+response+"&autoplay=1";
    document.getElementById('dp-video').src = vimeo_url;
    $('#responsive-video-modal').modal('show');   
}
function stop_video(){
    document.getElementById('dp-video').src ="";
}
// ======================================================================================================================================>\







// tranee Dashboard js start here
function dashboard_refresh_trainee() {
    if ($('#company_id_trainee').val() == "") {
        ShowAlret("Please select Company.!!", 'error');
        return false;
    }
    if ($('#report_by_trainee').val() == 1) {
        $(".th-desc").text("Parameter");
    } else {
        $(".th-desc").text("Assessment");
    }
    if($('#user_id_trainee').val()=="")
    {
        ShowAlret("Please select Trainee Name.!!", 'error');
        return false;
    }
    $.ajax({
        type: "POST",
        data: {company_id: $('#company_id_trainee').val(), report_by: $('#report_by_trainee').val(),report_type: $('#report_type_trainee').val(),rpt_period:$('#rpt_period_trainee').val(),parameter_id:$('#parameter_id_trainee').val(),
            month:$('#month').val(),year:$('#year').val(),week:$('#week').val(),StartDate: StartDate, EndDate: EndDate, user_id: $('#user_id_trainee').val()},
        //async: false,
        url: base_url + "reports_dashboard/getdashboardDataTrainee",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (data) {
            if (data != '') {
                var json = jQuery.parseJSON(data);
                $('#total_assessment_trainee').attr('data-value', json['Total_Assessment']);
                $('#total_assessment_trainee').counterUp();
                $('#question_answer_trainee').attr('data-value', json['question_answer']);
                $('#question_answer_trainee').counterUp();
                $('#total_time_trainee').attr('data-value', json['Total_Time']);
                $('#total_time_trainee').counterUp();
                $('#average_accuracy_trainee').attr('data-value', json['Avg_Accuracy']+'%');
                $('#average_accuracy_trainee').counterUp();
                $('#highest_accuracy_trainee').attr('data-value', json['high_Accuracy']+'%');
                $('#highest_accuracy_trainee').counterUp();
                $('#lowest_accuracy_trainee').attr('data-value', json['low_Accuracy']+'%');
                $('#lowest_accuracy_trainee').counterUp();
                
                if (json['para_top_five_html'] != '') {
                    $('#asmnt-top-five_trainee tbody').empty();
                    $('#asmnt-top-five_trainee tbody').append(json['para_top_five_html']);
                }
                if (json['para_bottom_five_table'] != '') {
                    $('#asmnt-bottom-five_trainee tbody').empty();
                    $('#asmnt-bottom-five_trainee tbody').append(json['para_bottom_five_table']);
                }
                $('#parameter_id_trainee').empty();
                $('#parameter_id_trainee').append(json['parahtml']);
                
                $("#assessment_index_trainee").html(json['index_graph_trainee']);
                $("#parameter_index_trainee").html(json['index_paragraph_trainee']);
                customunBlockUI();
            }
        }
    });
}
function getWeekTrainee(){
    $.ajax({
        type: "POST",
        data: {year: $('#year').val(),month: $('#month').val()},
        async: false,
        url: base_url +"reports_dashboard/ajax_getWeeks_trainee",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {                        
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);                                                        
                var WStartEndDate = Oresult['WStartEnd'];                            
                var week_option = '<option value="">All Week</option>';                            
                    for (var i = 0; i < WStartEndDate.length; i++) {
                        week_option += '<option value="' + WStartEndDate[i] + '">' +'Week-'+ (i+1) + '</option>';
                    }                             
                $('#week').empty();
                $('#week').append(week_option);
            }
        customunBlockUI();    
        }
    });                               
}
function assessment_index_refresh_trainee(){
    if ($('#company_id_trainee').val() == "") {
        ShowAlret("Please select Company.!!", 'error');
        return false;
    } 
    $.ajax({
        type: "POST",
         data: {company_id: $('#company_id_trainee').val(), report_by: $('#report_by_trainee').val(),report_type: $('#report_type_trainee').val(), rpt_period:$('#rpt_period_trainee').val(),parameter_id:$('#parameter_id_trainee').val(),
			month:$('#month').val(),year:$('#year').val(),week:$('#week').val(),StartDate: StartDate, EndDate: EndDate, user_id: $('#user_id_trainee').val()},
        //async: false,
        url: base_url +"reports_dashboard/load_assessment_index_trainee",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);
                console.log(json);
                $("#assessment_index_trainee").html(json['index_graph_trainee']);
				if($('#parameter_id_trainee').val() !=''){
					$("#parameter_index_trainee").html(json['index_paragraph_trainee']);
				}
            }
            customunBlockUI();
        }
    });
}
function parameter_index_refresh_trainee(){
    $.ajax({
        type: "POST",
         data: {report_by: $('#report_by_trainee').val(), rpt_period:$('#rpt_period_trainee').val(),parameter_id:$('#parameter_id_trainee').val(), report_type: $('#report_type_trainee').val(),
             month:$('#month').val(),year:$('#year').val(),week:$('#week').val(),StartDate: StartDate, EndDate: EndDate, user_id: $('#user_id_trainee').val()},
        //async: false,
        url: base_url +"reports_dashboard/load_parameter_index_trainee",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);
                $("#parameter_index_trainee").html(json['index_paragraph']);
            }
            customunBlockUI();
        }
    });
}
       