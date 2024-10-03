<?php

// use PhpOffice\PhpSpreadsheet\Reader\IReader;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rep_competency_dashboard_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //Get All Assessment  start from here
    public function get_all_assessment()
    {
        $query = " SELECT distinct am.id as assessment_id,am.report_type as report_type,
                 CONCAT('[', am.id,'] ', am.assessment, ' - [', art.description, ']') as assessment, 
                 if(DATE_FORMAT(am.end_dttm,'%Y-%m-%d %H:%i')>=CURDATE(),'Live','Expired') AS status
                 FROM assessment_mst am 
                 LEFT JOIN assessment_report_type as art on art.id=am.report_type ";
        $query .= " WHERE am.status = 1 ";
        $query .= " GROUP BY am.id ";
        $query .= " ORDER BY am.id DESC ";
        $result = $this->db->query($query);
        return $result->result();
    }
    //get_all_assessment end here

    // get all division for division filter start from here 
    public function get_all_division()
    {
        $query = "SELECT id,division_name FROM division_mst ";

        $result = $this->db->query($query);
        return $result->result();
    }
    // get all division for division filter ended here 

    // get_manager_info for getting manager filter started here 
    public function get_manager_info($division_id)
    {
        $query = " SELECT cu.username as manager_name, cu.userid as manager_id
        FROM company_users cu
        LEFT JOIN division_mst AS dmt ON dmt.id=cu.division_id ";
        if ($division_id != '') {
            $query .= " WHERE dmt.id = '" . $division_id . "' ";
        }
        $query .= "ORDER BY dmt.id";

        $result = $this->db->query($query);
        return $result->result();
    }
    // get_manager_info for getting manager filter ended here 

    // get_div_rep_info for getting division and rep filter started here 
    public function get_div_rep_info($division_id)
    {
        // $query = "SELECT DISTINCT division_name,
        // dmt.id AS division_id,
        // amu.user_id AS trainee_id,
        // IFNULL(concat(du.firstname, ' ', du.lastname, ' (', du.email, ')'), '') AS traineename
        // FROM company_users cu
        // LEFT JOIN division_mst dmt ON dmt.id = cu.division_id
        // LEFT JOIN assessment_mapping_user AS amu ON dmt.division_name = amu.division_id
        // LEFT JOIN device_users du ON du.user_id = amu.user_id ";
        // if($division_id != ''){
        //     $query .= " WHERE dmt.id = '" . $division_id . "' ";
        // }
        // $query .= "  AND du.user_id IS NOT NULL 
        // GROUP BY cu.username,
        // amu.user_id ";

        $query = "SELECT dm.division_name,du.user_id,IFNULL(concat(du.firstname, ' ', du.lastname, ' (', du.email, ')'), '') AS traineename  FROM assessment_mapping_user as amu 
        LEFT JOIN device_users as du on du.user_id = amu.user_id
        LEFT JOIN division_mst as dm on dm.id = amu.division_id
        WHERE dm.division_name is NOT null ";
        if($division_id != ''){
            $query .= " AND dm.id = '" . $division_id . "' ";
        }
        $query .= " GROUP by dm.division_name;";

        // echo  $query;die;
        $result = $this->db->query($query);
        return $result->result();
    }

    public function get_rep_info($manager_id)
    {
        $query = " SELECT DISTINCT if(du.area!= '',du.area, '' ) as  region, du.emp_id as emp_code, au.user_id as userid, 
        IFNULL(concat(du.firstname, ' ', du.lastname, ' (', du.email, ')'), '') AS traineename
        FROM assessment_mapping_user au 
        left join device_users as du on du.user_id = au.user_id where au.trainer_id IS NOT NULL
    AND du.firstname IS NOT NULL AND du.lastname IS NOT NULL  ";
        if ($manager_id != '') {
            $query .= "AND  au.trainer_id = '" . $manager_id . "' ";
        }
        $query .= " order by traineename ";
        // echo $query;
        // die;
        $result = $this->db->query($query);
        return $result->result();
    }

    public function get_assessment_info($trainee_id)
    {
        $query = " SELECT DISTINCT aau.assessment_id AS assessment_id,
        CONCAT('[', amt.id, '] ', amt.assessment, ' - [', art.description, ']') AS assessment,
        if(DATE_FORMAT(amt.end_dttm, '%Y-%m-%d %H:%i')>=CURDATE(), 'Live', 'Expired') AS status
        FROM assessment_allow_users aau
        LEFT JOIN device_users du ON aau.user_id=du.user_id
        LEFT JOIN assessment_mst AS amt ON amt.id = aau.assessment_id
        LEFT JOIN assessment_report_type AS art ON art.id=amt.report_type ";

        if ($trainee_id != '') {
            $query .= " WHERE aau.user_id = '" . $trainee_id . "' ";
        }
        $result = $this->db->query($query);
        return $result->result();
    }
    // get_assessment_info for getting assessment filter ended here 

    // get_all_managers for all managers starts from here
    public function get_all_managers()
    {
        $query = "SELECT ROW_NUMBER() OVER (ORDER BY username) AS id, username FROM  `company_users`";
        $result = $this->db->query($query);
        return $result->result();

    }
    // get_all_managers for all managers ended here


    //get_Trainee_score created by Rudra Patel 24/11/2023
    public function get_Trainee_score($trainee_id, $assessment_id, $StartDate, $EndDate, $type)
    {
        $cond = '';
        if ($StartDate != '' &&  $EndDate != '') {
            $cond .= " AND (date(am.start_dttm) BETWEEN '" . $StartDate . "' AND '" . $EndDate . "'";
            $cond .= " OR date(am.end_dttm) BETWEEN '" . $StartDate . "' AND '" . $EndDate . "'";
            $cond .= " OR '" . $StartDate . "' BETWEEN date(am.start_dttm) AND date(am.end_dttm)";
            $cond .= " OR '" . $EndDate . "' BETWEEN date(am.start_dttm) AND date(am.end_dttm))";
        }
        if($assessment_id != '') {
            $cond .= " AND am.id in (".$assessment_id.")";
        }

        if($type != 1 and $type != 2){
            if ($trainee_id != '') {
                $cond .= " AND a.user_id in (" . $trainee_id . ")";
            }
        }

        if($type == 1){
            $query = " SELECT MIN(your_score) as bottom_performer,MAX(your_score) as top_performer FROM ( 
                       SELECT IF(SUM(cnt2) > 1, round(AVG(overall_score),2),  SUM(overall_score)) as your_score ,emp_id,users_id,user_name,department,cnt2,assessment_id FROM ( ";
        }else if($type == 2){
            $query = " SELECT emp_id,users_id,user_name,department,cnt2,assessment_id,your_score, (@row := ifnull(@row, 0) + 1) AS ranking FROM (
                        SELECT IF(SUM(cnt2) > 1, round(AVG(overall_score),2),  SUM(overall_score)) as your_score ,@row :=0 ,emp_id,users_id,user_name,department,cnt2,assessment_id FROM ( ";
        } else{
            $query = " SELECT IF(SUM(cnt2) > 1, round(AVG(overall_score),2),  SUM(overall_score)) as your_score ,emp_id,users_id,user_name,department,cnt2,assessment_id FROM ( ";
        }
        
        $query .= "  (SELECT  IF(FORMAT(IFNULL(AVG(overall_score),0),2) > 0,1,0) AS cnt2,overall_score,emp_id,users_id,user_name,if(department != '', department,'-')as department,users,assessment_id FROM (
            SELECT IF(SUM(cnt) > 1, round(AVG(overall_score),2),  SUM(overall_score)) as overall_score,emp_id,users_id,user_name,if(department != '', department,'-')as department,users,assessment_id FROM (
            SELECT overall_score,emp_id,users_id,user_name,if(department != '', department,'-')as department ,users,
                              IF(FORMAT(IFNULL(AVG(overall_score),0),2) > 0,1,0) AS cnt,assessment_id FROM ( 
                              SELECT ps.assessment_id as assessment_id,ROUND( 
                              ROUND( IF(SUM(ps.weighted_score)=0, SUM(ps.score)/count(ps.question_id), 
                              SUM(ps.weighted_score)/COUNT( ps.question_id) ) ,2),2) AS overall_score, ps.user_id as users, 
                              c.emp_id as emp_id, c.user_id as users_id, CONCAT(c.firstname,' ',c.lastname) as user_name , 
                              c.department as department 
                              FROM ai_subparameter_score ps
                              LEFT JOIN assessment_mapping_user as a 
                              ON 
                              ps.assessment_id=a.assessment_id AND ps.user_id=a.user_id 
                              LEFT JOIN device_users as c on ps.user_id = c.user_id 
                              LEFT JOIN assessment_mst as am ON ps.assessment_id =am.id 
                              AND ps.parameter_type = 'parameter' where 1=1 $cond
                              GROUP BY assessment_id,users_id) as main GROUP BY assessment_id,users_id) as main2 GROUP BY users_id) as  main3 GROUP BY users_id)
            UNION ALL
            (SELECT  IF(FORMAT(IFNULL(AVG(overall_score),0),2) > 0,1,0) AS cnt2,overall_score,emp_id,users_id,user_name,if(department != '', department,'-')as department,users,assessment_id FROM (
            SELECT IF(SUM(cnt) > 1, round(AVG(overall_score),2), SUM(overall_score)) as overall_score,emp_id,users_id,user_name,if(department != '', department,'-')as department,users,assessment_id FROM (
            SELECT overall_score,emp_id,users_id,user_name,if(department != '', department,'-')as department,users,
                             IF(FORMAT(IFNULL(AVG(overall_score),0),2) > 0,1,0) AS cnt,assessment_id FROM 
                             (SELECT ps.assessment_id as assessment_id,
                             IF(ats.parameter_weight=0, round(IFNULL(sum(ps.score)/count(*),0),2), round(IFNULL(sum(ps.score)/SUM(ats.parameter_weight),0),2)) as overall_score,
                             ps.user_id as users, c.emp_id as emp_id, c.user_id as users_id,
                             CONCAT(c.firstname,' ',c.lastname) as user_name, c.department as department
                             FROM assessment_results_trans ps 
                             LEFT JOIN assessment_trans_sparam ats ON ps.parameter_id=ats.parameter_id AND ps.assessment_id=ats.assessment_id 
                             AND ps.question_id=ats.question_id
                             LEFT JOIN assessment_mapping_user as a ON ps.assessment_id=a.assessment_id AND ps.user_id=a.user_id 
                             LEFT JOIN device_users as c on ps.user_id = c.user_id 
                             LEFT JOIN assessment_mst as am ON ps.assessment_id =am.id where 1=1 $cond
                             GROUP BY assessment_id,users_id) as main GROUP BY assessment_id,users_id) as main2 GROUP BY main2.users_id) as  main3 GROUP BY users_id)) as final GROUP BY users_id  ";
        if($type == 1){
            $query .= " ) as main5";
        }
        if($type == 2){
            $query .= " ) as main6 ORDER BY your_score desc ";
            
        }

        $result = $this->db->query($query);
        if($type == 1){
            $data =  $result->row();
        }
        else{
            $data =  $result->result();    
        }
        return $data;
    }
    //get_Trainee_score ended by Rudra Patel 24/11/2023

    //get_respected_assessment created by Rudra Patel 
    public function get_respected_assessment($trainee_id, $StartDate, $EndDate)
    {
        $cond = '';
        if ($StartDate != '' && $EndDate != '') {
            $cond .= " AND (date(am.start_dttm) BETWEEN '" . $StartDate . "' AND '" . $EndDate . "'";
            $cond .= " OR date(am.end_dttm) BETWEEN '" . $StartDate . "' AND '" . $EndDate . "'";
            $cond .= " OR '" . $StartDate . "' BETWEEN date(am.start_dttm) AND date(am.end_dttm)";
            $cond .= " OR '" . $EndDate . "' BETWEEN date(am.start_dttm) AND date(am.end_dttm))";
        }
        
        if ($trainee_id != '') {
             $cond .= " and amu.user_id = '".$trainee_id."' ";

        }
        $result = "SELECT amu.assessment_id FROM 
        assessment_mst as am
        LEFT JOIN assessment_allow_users as amu on am.id = amu.assessment_id WHERE 1=1 $cond";
        $query = $this->db->query($result);
        return $query->result_array();
    }
    //get_respected_assessment ended by Rudra Patel 

    //last_assessment_id created by Rudra Patel
    public function last_trainer_id()
    {
        $result ="SELECT assessment_id,user_id  FROM assessment_allow_users ORDER BY id DESC LIMIT 1";

        $data = $this->db->query($result);
        return $data->row();
    }

    //last_assessment_id ended by Rudra Patel 

    //get_trainee_respected_assessment for trainee wise assessment created by Patel Rudra 
    //  public function get_rep_trainee_assessment($Company_id, $trainee_id)
    //  {    
    //     // $query = "SELECT DISTINCT aau.user_id AS user_id,
    //     // CONCAT(du.firstname, ' ', du.lastname) AS user_name,
    //     // du.email
    //     // FROM assessment_mapping_user aau
    //     // INNER JOIN device_users du ON du.user_id=aau.user_id";
    //     // if(!empty($assessment_id)){
    //     // $query .= " WHERE aau.assessment_id IN (" . implode(',', $assessment_id) . ") ";
    //     // }
    //     // $query = "SELECT DISTINCT aau.user_id AS emp_code,
    //     // CONCAT(du.firstname, ' ', du.lastname) AS user_name,
    //     // du.email
    //     // FROM assessment_mapping_user aau
    //     // INNER JOIN device_users du ON du.user_id=aau.user_id";
    //     // if (!empty($assessment_id)) {
    //     //     $query .= " WHERE aau.assessment_id IN (" . implode(',', $assessment_id) . ") ";
    //     // }
    //     $query = " SELECT DISTINCT aau.assessment_id AS assessment_id,
    //      CONCAT('[', amt.id, '] ', amt.assessment, ' - [', art.description, ']') AS assessment,
    //      if(DATE_FORMAT(amt.end_dttm, '%Y-%m-%d %H:%i')>=CURDATE(), 'Live', 'Expired') AS status
    //      FROM assessment_allow_users aau
    //      LEFT JOIN device_users du ON aau.user_id=du.user_id
    //      LEFT JOIN assessment_mst AS amt ON amt.id = aau.assessment_id
    //      LEFT JOIN assessment_report_type AS art ON art.id=amt.report_type ";
    //     if (!empty($trainee_id)) {
    //         $query .= " WHERE aau.user_id = '" . $trainee_id . "' ";
    //     }
    //     $result = $this->db->query($query);
    //     return $result->result();
    // }
    public function get_Trainee_data($Company_id)
    {
        $query = "SELECT DISTINCT if(du.area!= '',du.area, '' ) as  region,
         du.emp_id as emp_code, au.user_id as userid, 
        if(cms.username !='',cms.username, '') as manager_name, 
        if(dmt.id!= '',dmt.id, '') as designation, 
        concat(du.firstname,' ',du.lastname,' (',du.email,' )') as traineename 
        FROM assessment_mapping_user au LEFT JOIN assessment_mst am ON am.id = au.assessment_id 
        INNER JOIN device_users du ON du.user_id=au.user_id
        LEFT JOIN company_users as cms ON cms.userid = du.trainer_id
        LEFT JOIN division_mst as dmt ON dmt.id = du.designation WHERE am.company_id = $Company_id 
        ORDER BY traineename";
        $result = $this->db->query($query);
        return $result->result();
    }

    //  //Trainee score for trainee graph created by Patel Rudra
    public function get_assessment_scores($trainee_id, $assessment_id, $startdate, $enddate, $report_by)
    {
        if ($report_by == "0") {
            $query = "SELECT assessment_id,assessment_name,ROUND(AVG(overall_score), 2) AS assessment_average,u_name,c_id,e_id
         FROM
            (SELECT assessment_id,assessment_name,
            users,
            u_name,
            c_id,
            e_id,
            IF(SUM(cnt) > 1, ROUND(AVG(overall_score), 2), SUM(overall_score)) AS overall_score
            FROM (
            (SELECT assessment_id,assessment_name,
                   IFNULL(overall_score, 0) AS overall_score,
                   users,
                   u_name,
                   c_id,
                   e_id,
                   IF(FORMAT(IFNULL(AVG(overall_score), 0), 2) > 0, 1, 0) AS cnt
            FROM
              (SELECT ROUND(IF(SUM(ps.weighted_score) = 0, SUM(ps.score) / COUNT(ps.question_id), SUM(ps.weighted_score) / COUNT(DISTINCT ps.question_id)), 2) AS overall_score,
                      IFNULL(amu.user_id, '') AS users,
                    CONCAT(du.firstname, '', du.lastname) AS u_name,
                    du.company_id as c_id,
                    du.emp_id as e_id,
                    am.id AS assessment_id,am.assessment as assessment_name
                      
                 FROM assessment_mst AS am
                 LEFT JOIN assessment_mapping_user AS amu ON amu.assessment_id = am.id
                 LEFT JOIN ai_subparameter_score AS ps ON am.id = ps.assessment_id
                 AND amu.user_id = ps.user_id
                 LEFT JOIN device_users AS du ON du.user_id = amu.user_id
                 LEFT JOIN assessment_attempts AS aa ON aa.user_id = amu.user_id
                 AND aa.assessment_id = amu.assessment_id
                 WHERE ps.parameter_type = 'parameter'
                 AND aa.is_completed = 1
                 AND (date(am.start_dttm) BETWEEN '" . $startdate . "' AND '" . $enddate . "' )
                 AND amu.user_id = '" . $trainee_id . "' ";
            if(!empty($assessment_id[0])){
                $query .=" AND amu.assessment_id IN (" . implode(',', $assessment_id) . ") ";
            }
            $query .= " GROUP BY am.id,
                        amu.user_id) AS main
            GROUP BY main.assessment_id,
                     main.users)
            UNION ALL
            (SELECT assessment_id,assessment_name,
                   IFNULL(overall_score, 0) AS overall_score,
                   users,
                   u_name,
                   c_id,
                   e_id,
                   IF(FORMAT(IFNULL(AVG(overall_score), 0), 2) > 0, 1, 0) AS cnt
            FROM
              (SELECT ROUND(IF(ps.weighted_percentage = 0, SUM(ps.percentage) / COUNT(ps.question_id), SUM(ps.weighted_percentage) / COUNT(DISTINCT ps.question_id)), 2) AS overall_score,
                      IFNULL(amu.user_id, '') AS users,
                      CONCAT(du.firstname, '', du.lastname) AS u_name,
                      du.company_id as c_id,
                      du.emp_id as e_id,
                      am.id AS assessment_id,am.assessment as assessment_name
               FROM assessment_mst AS am
               LEFT JOIN assessment_mapping_user AS amu ON am.id = amu.assessment_id
               LEFT JOIN assessment_results_trans AS ps ON am.id = ps.assessment_id
               AND amu.user_id = ps.user_id
               LEFT JOIN device_users AS du ON du.user_id = amu.user_id
               LEFT JOIN assessment_attempts AS aa ON aa.user_id = amu.user_id
               AND aa.assessment_id = amu.assessment_id
               AND (date(am.start_dttm) BETWEEN '" . $startdate . "' AND '" . $enddate . "' )
               WHERE 1 = 1
                 AND aa.is_completed = 1
                 AND amu.user_id = '" . $trainee_id . "' ";
            if(!empty($assessment_id[0])){
                $query .=" AND amu.assessment_id IN (" . implode(',', $assessment_id) . ") ";
            }
            $query .= " GROUP BY am.id,
                        amu.user_id) AS main
            GROUP BY main.assessment_id,
                     main.users)) AS m2
            GROUP BY m2.assessment_id,
            m2.users) AS subquery GROUP BY assessment_id ORDER by assessment_average desc LIMIT 8";
        } else {
            $query = " SELECT ifnull(IF(SUM(a.cnt) > 1, round(AVG(a.result), 2), sum(RESULT)), 0) AS parameter_avg,
           a.assessment_id,
           a.user_id,
           a.u_name as u_name,
           if(a.p_id != '', a.p_id,'-') as p_id,
           c_id,
           e_id,
           if(a.parameter_name != '', a.parameter_name,'-') as parameter_name
    FROM (
            (SELECT main.user_id,
                    main.assessment_id,
                    main.u_name,
                    main.p_id,
                    main.parameter_name,
                    main.c_id,
                    main.e_id,
                    main.result,
                    IF(FORMAT(IFNULL(AVG(main.result), 0), 2) > 0, 1, 0) AS cnt
             FROM
               (SELECT am.id AS assessment_id,
                       aau.user_id,
                       concat(du.firstname, ' ', du.lastname) AS u_name,
                       IF(ats.parameter_weight=0, round(IFNULL(sum(art.score)/count(*), 0), 2), round(IFNULL(sum(art.score*(ats.parameter_weight))/SUM(ats.parameter_weight), 0), 2)) AS RESULT,
                       ifnull(pm.id, gm.id) AS p_id,
                       ifnull(pm.description,gm.description) AS parameter_name,
                       du.company_id as c_id,
                       du.emp_id as e_id,
                       art.question_id
                FROM assessment_mst AS am
                LEFT JOIN assessment_allow_users AS aau ON aau.assessment_id = am.id
                LEFT JOIN ai_subparameter_score AS art ON art.user_id = aau.user_id
                LEFT JOIN assessment_trans_sparam ats ON art.parameter_id=ats.parameter_id
                AND art.assessment_id=ats.assessment_id
                AND art.question_id=ats.question_id
                LEFT JOIN parameter_mst AS pm ON art.parameter_id = pm.id
                LEFT JOIN goal_mst as gm on gm.id = art.parameter_id
                LEFT JOIN device_users AS du ON du.user_id = aau.user_id
                WHERE art.parameter_type = 'parameter'
                  AND aau.user_id = '" . $trainee_id . "' 
                 AND (date(am.start_dttm) BETWEEN '" . $startdate . "' AND '" . $enddate . "' )
                  ";
            if(!empty($assessment_id[0])){
            $query .=" AND am.id IN (" . implode(',', $assessment_id) . ") ";
            }

            $query .= "  GROUP BY art.question_id,
                         art.parameter_id) AS main
             GROUP BY main.question_id,
                      main.p_id)
          UNION ALL
            (SELECT main.user_id,
                    main.id,
                    main.u_name,
                    main.p_id,
                    main.parameter_name,
                    main.result,
                    main.c_id,
                    main.e_id,
                    IF(FORMAT(IFNULL(AVG(main.result), 0), 2) > 0, 1, 0) AS cnt
             FROM
               (SELECT am.id,
                       concat(u.firstname, ' ', u.lastname)AS u_name,
                       amu.user_id,
                       ROUND(IF(art.weighted_percentage=0, SUM(art.percentage)/count(art.question_id), SUM(art.weighted_percentage)/count(DISTINCT art.question_id)), 2) AS RESULT,
                       ifnull(pm.id, gm.id) AS p_id,
                       ifnull(pm.description,gm.description) AS parameter_name,
                       u.company_id as c_id,
                       u.emp_id as e_id,
                       art.question_id
                FROM assessment_mst AS am
                LEFT JOIN assessment_allow_users AS amu ON amu.assessment_id = am.id
                LEFT JOIN device_users AS u ON u.user_id = amu.user_id
                LEFT JOIN assessment_results_trans AS art ON art.assessment_id = amu.assessment_id
                AND amu.user_id = art.user_id
                LEFT JOIN parameter_mst AS pm ON pm.id =art.parameter_id
                LEFT JOIN goal_mst as gm on gm.id = art.parameter_id
                WHERE amu.user_id = '" . $trainee_id . "' 
                AND (date(am.start_dttm) BETWEEN '" . $startdate . "' AND '" . $enddate . "' )
                ";

            if(!empty($assessment_id[0])){
                $query .=" AND am.id IN (" . implode(',', $assessment_id) . ") ";
            }

            $query .= " AND amu.user_id IS NOT NULL
                GROUP BY art.question_id,
                         art.parameter_id) AS main
             GROUP BY main.question_id,
                      main.p_id)) AS a
        GROUP BY a.p_id
        ORDER BY parameter_avg desc LIMIT 8";

    }
        $result = $this->db->query($query);
         return $result->result_array();
 
     }
     //Assessment and parameter score for rep spider chart of trainee ended by Patel Rudra

    //Last assessment id for assessment comparision graph started by Patel Rudra
    public function last_ass_id()
    {
        $query = " SELECT id FROM assessment_mst ORDER BY id DESC LIMIT 1 ";
        $data = $this->db->query($query);
        return $data->row();
    }
    //Last assessment id for assessment comparision graph ended by Patel Rudra

    //Assessment comparison of assessment trainee graph created by Patel Rudra
    public function get_threshold_score($trainee_id, $assessment_id, $above_range, $second_range_from, $second_range_to, $third_range_from, $third_range_to, $forth_range_from, $forth_range_to, $fifth_range_from, $fifth_range_to, $less_range)
    {
        $query = "SELECT assessment_id,assessment_name,ROUND(AVG(overall_score), 2) AS assessment_average,
        sum(above_" . $above_range . ") as Rockstars, 
            sum(score_" . $fifth_range_from . "_" . $fifth_range_to . ") as Expert, 
            sum(score_" . $forth_range_from . "_" . $forth_range_to . ") as Advance, 
            sum(score_" . $third_range_from . "_" . $third_range_to . ") as Intermediate, 
            sum(score_" . $second_range_from . "_" . $second_range_to . ") as Beginner, 
            sum(less_" . $less_range . ") as At_Risk,
            u_name as trainee_name,
            users as trainee_id,
            c_id,
            e_id
        FROM
           (SELECT assessment_id,
       assessment_name,
       users,
       u_name,
       c_id,
       e_id,
       IF(SUM(cnt) > 1, ROUND(AVG(overall_score_2), 2), SUM(overall_score_2)) AS overall_score,
       CASE
           WHEN overall_score_2 >= '" . $above_range . "' THEN count(overall_score_2)
           ELSE ''
       END AS above_" . $above_range . ",
       CASE
           WHEN overall_score_2 >= '" . $fifth_range_from . "'
                AND overall_score_2 <= '" . $fifth_range_to . '.99' . "' THEN count(overall_score_2)
           ELSE ''
       END AS score_" . $fifth_range_from . "_" . $fifth_range_to . ",
       CASE
           WHEN overall_score_2 >= '" . $forth_range_from . "'
                AND overall_score_2 <= '" . $forth_range_to . '.99' . "' THEN count(overall_score_2)
           ELSE ''
       END AS score_" . $forth_range_from . "_" . $forth_range_to . ",
       CASE
           WHEN overall_score_2 >= '" . $third_range_from . "'
                AND overall_score_2 <= '" . $third_range_to . '.99' . "' THEN count(overall_score_2)
           ELSE ''
       END AS score_" . $third_range_from . "_" . $third_range_to . ",
       CASE
           WHEN overall_score_2 >= '" . $second_range_from . "'
                AND overall_score_2 <= '" . $second_range_to . '.99' . "' THEN count(overall_score_2)
           ELSE ''
       END AS score_" . $second_range_from . "_" . $second_range_to . ",
       CASE
           WHEN overall_score_2 <= '" . $less_range . '.99' . "' THEN count(overall_score_2)
           ELSE ''
       END AS less_" . $less_range . "
FROM
  (SELECT IF(SUM(cnt) > 1, round(AVG(overall_score), 2), SUM(overall_score)) AS overall_score_2,
          main2.*
   FROM (
           (SELECT assessment_id,
                   assessment_name,
                   IFNULL(overall_score, 0) AS overall_score,
                   users,
                   u_name,
                   c_id,
                   e_id,
                   IF(FORMAT(IFNULL(AVG(overall_score), 0), 2) > 0, 1, 0) AS cnt
            FROM
              (SELECT ROUND(IF(SUM(ps.weighted_score) = 0, SUM(ps.score) / COUNT(ps.question_id), SUM(ps.weighted_score) / COUNT(DISTINCT ps.question_id)), 2) AS overall_score,
                      IFNULL(amu.user_id, '') AS users,
                      CONCAT(du.firstname, '', du.lastname) AS u_name,
                      am.id AS assessment_id,
                      du.company_id as c_id,
                      du.emp_id as e_id,
                      am.assessment AS assessment_name
               FROM assessment_mst AS am
               LEFT JOIN assessment_mapping_user AS amu ON amu.assessment_id = am.id
               LEFT JOIN ai_subparameter_score AS ps ON am.id = ps.assessment_id
               AND amu.user_id = ps.user_id
               LEFT JOIN device_users AS du ON du.user_id = amu.user_id
               WHERE ps.parameter_type = 'parameter'
                 AND amu.user_id ='" . $trainee_id . "' ";
        if ($assessment_id != '') {
            $query .= " AND amu.assessment_id IN (" . implode(',', $assessment_id) . ") ";
        }
        $query .= " GROUP BY am.id,
                        amu.user_id) AS main
            GROUP BY main.assessment_id,
                     main.users
            ORDER BY main.users)
         UNION ALL
           (SELECT assessment_id,
                   assessment_name,
                   IFNULL(overall_score, 0) AS overall_score,
                   users,
                   u_name,
                   c_id,
                   e_id,
                   IF(FORMAT(IFNULL(AVG(overall_score), 0), 2) > 0, 1, 0) AS cnt
            FROM
              (SELECT ROUND(IF(ps.weighted_percentage = 0, SUM(ps.percentage) / COUNT(ps.question_id), SUM(ps.weighted_percentage) / COUNT(DISTINCT ps.question_id)), 2) AS overall_score,
                      IFNULL(amu.user_id, '') AS users,
                      CONCAT(du.firstname, '', du.lastname) AS u_name,
                      am.id AS assessment_id,
                      du.company_id as c_id,
                      du.emp_id as e_id,
                      am.assessment AS assessment_name
               FROM assessment_mst AS am
               LEFT JOIN assessment_mapping_user AS amu ON am.id = amu.assessment_id
               LEFT JOIN assessment_results_trans AS ps ON am.id = ps.assessment_id
               AND amu.user_id = ps.user_id
               LEFT JOIN device_users AS du ON du.user_id = amu.user_id
               WHERE 1 = 1
                 AND amu.user_id ='" . $trainee_id . "' ";
        if ($assessment_id != '') {
            $query .= " AND amu.assessment_id IN (" . implode(',', $assessment_id) . ") ";
        }
        $query .= " GROUP BY am.id,
                        amu.user_id) AS main
            GROUP BY main.assessment_id,
                     main.users
            ORDER BY main.users)) AS main2
   GROUP BY main2.assessment_id,
            main2.users) AS m2
GROUP BY m2.assessment_id,
         m2.users) AS subquery GROUP BY assessment_id ORDER by assessment_average DESC LIMIT 5";
        $result = $this->db->query($query);
        return $result->result_array();
    }
    //Assessment comparison of assessment trainee graph end by Patel Rudra

    // //get_assessment_trainee_assessment for trainee wise assessment created by Patel Rudra 
    // public function get_assessment_trainee_assessment($Company_id, $trainee_id)
    // {
    //     $query = " SELECT DISTINCT aau.assessment_id AS assessment_id,
    //      CONCAT('[', amt.id, '] ', amt.assessment, ' - [', art.description, ']') AS assessment,
    //      if(DATE_FORMAT(amt.end_dttm, '%Y-%m-%d %H:%i')>=CURDATE(), 'Live', 'Expired') AS status
    //      FROM assessment_allow_users aau
    //      LEFT JOIN device_users du ON aau.user_id=du.user_id
    //      LEFT JOIN assessment_mst AS amt ON amt.id = aau.assessment_id
    //      LEFT JOIN assessment_report_type AS art ON art.id=amt.report_type ";
    //     if (!empty($trainee_id)) {
    //         $query .= " WHERE aau.user_id = '" . $trainee_id . "' ";
    //     }
    //     $result = $this->db->query($query);
    //     return $result->result();
    // }
    // //get_assessment_trainee_assessment for trainee wise assessment ended by Patel Rudra 
    //Assessment attempt of assessment trainee graph created by Patel Rudra
    public function get_assessment_attempts_count($trainee_id, $assessment_id, $startdate, $enddate)
    {
        $query = "SELECT IFNULL(amu.user_id, '') AS users,
        CONCAT(du.firstname, '', du.lastname) AS u_name,
        am.id AS assessment_id,
        du.company_id as c_id,
        du.emp_id as e_id,
        am.assessment AS assessment_name,if(aa.attempts!= '',aa.attempts, '0') as assessment_attempts
        FROM assessment_mst AS am
        LEFT JOIN assessment_mapping_user AS amu ON amu.assessment_id = am.id
        LEFT JOIN assessment_attempts AS aa ON aa.user_id = amu.user_id 
        AND aa.assessment_id = amu.assessment_id
        LEFT JOIN device_users AS du ON du.user_id = amu.user_id
        WHERE (date(am.start_dttm) BETWEEN '" . $startdate . "' AND '" . $enddate . "' ) 
        AND amu.user_id = '" . $trainee_id . "' ";
        if ($assessment_id != "") {
            $query .= " AND amu.assessment_id IN (" . implode(',', $assessment_id) . ")";
        }
        $query .= " ORDER BY `assessment_attempts` DESC LIMIT 5 ";
        $result = $this->db->query($query);
        return $result->result_array();
    }
    //Assessment attempt of assessment trainee graph end by Patel Rudra

}