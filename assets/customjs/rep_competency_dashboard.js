//get_manager_data function created by Patel Rudra
function get_manager_rep_info() {
    var division_id = $('#division_id').val();


    if (Company_id == "") {
        return false;
    }
    $.ajax({
        type: "POST",
        data: {
            company_id: Company_id,
            division_id: division_id
        },
        url: base_url + "rep_competency_dashboard/get_manager_rep_data",

        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);
                // console.log(Oresult)
                $('#manager_id').empty();
                $('#manager_id').append(Oresult['manager_set']);
                $('#trainee_id').empty();
                $('#trainee_id').append(Oresult['rep_set']);
                // console.log(Oresult['rep_set']);

            }
            customunBlockUI();
        }
    });
}
//get_manager_data function ended by Patel Rudra

//get_manager_data function created by Patel Rudra
function get_rep_info(manager_id) {
    var manager_id = $('#manager_id').val();


    if (Company_id == "") {
        return false;
    }
    $.ajax({
        type: "POST",
        data: {
            company_id: Company_id,
            manager_id: manager_id
        },
        url: base_url + "rep_competency_dashboard/get_rep_data",

        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);
                $('#trainee_id').empty();
                $('#trainee_id').append(Oresult['trainee_set']);
            }
            customunBlockUI();
        }
    });
}
//get_manager_data function ended by Patel Rudra

//get_assessment_data function created by Patel Rudra
function get_assessment_info(trainee_id) {
    var trainee_id = $('#trainee_id').val();


    if (Company_id == "") {
        return false;
    }
    $.ajax({
        type: "POST",
        data: {
            company_id: Company_id,
            trainee_id: trainee_id
        },
        url: base_url + "rep_competency_dashboard/get_assessment_data",

        beforeSend: function () {
            customBlockUI();
        },
        success: function (msg) {
            if (msg != '') {
                var Oresult = jQuery.parseJSON(msg);
                $('#assessment_id').empty();
                $('#assessment_id').append(Oresult['assessment_set']);
                $('#assessment_id2').empty();
                $('#assessment_id2').append(Oresult['assessment_set']);
                $('#assessment_id3').empty();
                $('#assessment_id3').append(Oresult['assessment_set']);
                $('#assessment_id4').empty();
                $('#assessment_id4').append(Oresult['assessment_set']);
                $('#assessment_id5').empty();
                $('#assessment_id5').append(Oresult['assessment_set']);
                $('#assessment_id6').empty();
                $('#assessment_id6').append(Oresult['assessment_set']);

            }
            customunBlockUI();
        }
    });
}
//get_assessment_data function ended by Patel Rudra

//leader_board_understanding datatable created by Rudra Patel 20/11/2023
function leader_board_understanding(type, IsCustom = '') {

    var assessment_id = $("#assessment_id").val();
    var user_id = $("#trainee_id").val();
    var table = $('#leader_board_table');
    if (type == 2) {

        if ($('#trainee_id').val() == '') {
            ShowAlret("Please select TraineeID.!!", 'error');
            return false;
        }
    }
    table.dataTable({
        destroy: true,
        "language": {
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending",
            },
            "emptyTable": "No data available in table",
            "info": "Showing _START_ to _END_ of _TOTAL_ records",
            "infoEmpty": "No records found",
            "infoFiltered": "(filtered1 from _MAX_ total records)",
            "lengthMenu": "Show _MENU_",
            "search": "Search:",
            "zeroRecords": "No matching records found",
            "paginate": {
                "previous": "Prev",
                "next": "Next",
                "last": "Last",
                "first": "First"
            }
        },
        "bStateSave": true,
        "lengthMenu": [
            [5, 10, 15, 20, -1],
            [5, 10, 15, 20, "All"]
        ],
        "pageLength": 5,
        "paging": true,
        "pagingType": "bootstrap_full_number",
        "columnDefs": [{
            'className': 'dt-head-left dt-body-left',
            'width': '50px',
            'orderable': false,
            'searchable': true,
            'targets': [0]
        },
        {
            'className': 'dt-head-left dt-body-left',
            'width': '100px',
            'orderable': false,
            'searchable': true,
            'targets': [1]
        },
        {
            'className': 'dt-head-left dt-body-left',
            'width': '100px',
            'orderable': false,
            'searchable': true,
            'targets': [2]
        },
        {
            'className': 'dt-head-left dt-body-left',
            'width': '100px',
            'orderable': false,
            'searchable': true,
            'targets': [3]
        },
        {
            'className': 'dt-head-left dt-body-left',
            'width': '80px',
            'orderable': false,
            'searchable': true,
            'targets': [4]
        },
        ],
        "order": [
            [0, "desc"]
        ],
        "processing": true,
        // "serverSide": true,
        "serverSide": false,
        "sAjaxSource": base_url + "Rep_competency_dashboard/leader_board_understanding",
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                name: 'assessment_id',
                value: $('#assessment_id').val(),
            });
            aoData.push({
                name: 'trainee_id',
                value: $('#trainee_id').val(),
            });
            aoData.push({
                name: 'StartDate',
                value: StartDate,
            });
            aoData.push({
                name: 'EndDate',
                value: EndDate,
            });
            aoData.push({
                name: 'IsCustom',
                value: $('#iscustom').val()
            });
            $.getJSON(sSource, aoData, function (json) {
                fnCallback(json);

                if (json.trainee_id != '') {
                    $('#trainee_id').val(json.trainee_id);
                    $('#trainee_id').trigger('change');
                }
                if ($('#assessment_id').val() != "") {
                    $('#leaderboard_modal').modal('hide');
                }

            });
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            return nRow;
        },
        "fnFooterCallback": function (nRow, aData) { },
        "initComplete": function (settings, json) {
            $('thead > tr> th:nth-child(1)').css({
                'min-width': '100px',
                'max-width': '100px'
            });
        }
    });
}
//leader_board_understanding datatable ended by Rudra Patel 20/11/2023

// Rep spider chart of trainee function start by Patel Rudra
function rep_spider_chart(type, IsCustom) {
    var trainee_id = $('#trainee_id').val();
    var assessment_id = $('#assessment_id2').val();
    if (type == 2) {
        if ($('#trainee_id').val() == '') {
            ShowAlret("Please select TraineeID.!!", 'error');
            return false;
        }
        if ($('#assessment_id2').val() != null) {
            if ($('#assessment_id2').val().length > 8) {
                ShowAlret("Please select Only 8 modules.!", 'error');
                return false;
            }
        }
    }

    $.ajax({
        type: "POST",
        data: {
            trainee_id: trainee_id,
            assessment_id: assessment_id,
            StartDate: StartDate,
            EndDate: EndDate,
            IsCustom: $('#custom_date').val(),
            report_by: $('#report_by').val()
        },
        url: base_url + "rep_competency_dashboard/rep_spider_chart",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);
        
            }
            $("#rep_spider_chart").html(json['get_trainee_score_overall']);

            customunBlockUI();
            $('#rep_spider_modal').modal('hide');
        }
    });
}
// Rep spider chart of trainee function end by Patel Rudra

// Assessment comparison function start by Patel Rudra
function assessment_comparison(type, IsCustom) {

    var trainee_id = $("#trainee_id").val();
    var assessment_id = $("#assessment_id3").val()
    
    if (type == 2) {
        if ($('#trainee_id').val() == '') {
            ShowAlret("Please select TraineeID.!!", 'error');
            return false;
        }
    }
    $.ajax({
        type: "POST",
        data: {
            trainee_id: trainee_id,
            assessment_id: assessment_id,
            StartDate: StartDate,
            EndDate: EndDate,
            IsCustom: $('#custom_date').val()
        },
        url: base_url + "rep_competency_dashboard/assessment_comparison",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);


            }
            $("#assessment_comparison").html(json['assessment_comparison_graph']);
            customunBlockUI();
            $('#comparision_modal').modal('hide');
        }
    });
}
// Assessment comparison function end by Patel Rudra

// Assessment attempt function start by Patel Rudra
function assessment_attempt(type, IsCustom) {
    if (type == 2) {
        if ($('#trainee_id').val() == '') {
            ShowAlret("Please select TraineeID.!!", 'error');
            return false;
        }
        if ($('#assessment_id4').val() != null) {
            if ($('#assessment_id4').val().length > 5) {
                ShowAlret("Please select Only 5 modules.!", 'error');
                return false;
            }
        }
    }
    var trainee_id = $("#trainee_id").val();
    var assessment_id = $("#assessment_id4").val();

    $.ajax({
        type: "POST",
        data: {
            trainee_id: trainee_id,
            assessment_id: assessment_id,
            StartDate: StartDate,
            EndDate: EndDate,
            IsCustom: $('#custom_date').val()
        },
        url: base_url + "rep_competency_dashboard/assessment_attempt",
        beforeSend: function () {
            customBlockUI();
        },
        success: function (response) {
            if (response != '') {
                var json = jQuery.parseJSON(response);
            }
            $("#assessment_attempt").html(json['assessment_attempt_chart']);
            customunBlockUI();
            $('#attempts_modal').modal('hide');
        }
    });
}
// Assessment attempts  function end by Patel Rudra