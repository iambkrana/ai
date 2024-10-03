<?php
defined('BASEPATH') or exit('No direct script access allowed');
$base_url = base_url();
$asset_url = $this->config->item('assets_url');
// New code for Access Role

$acces_management = $this->session->userdata('awarathon_session');
$SupperAccess = false;
$Company_id = $acces_management['company_id'];
$login_type = $acces_management['login_type'];

if ($acces_management['superaccess']) {
   $SupperAccess = true;
   $roleID = 1;
} else {
   $userID = $acces_management['user_id'];
   $roleID = $acces_management['role'];
   $ReturnSet = CheckSidebarRights($acces_management);
   $SideBarDataSet = $ReturnSet['RightsArray'];
   $GrouprightSet = $ReturnSet['GroupArray'];
}

$masters_module_access = false;
$administrator_module_access = false;
if ($Company_id == "") {
   if (isset($GrouprightSet['Administrator'])) {
      $masters_module_access = true;
   }
   if (isset($SideBarDataSet['roles']) || isset($SideBarDataSet['users'])) {
      $administrator_module_access = true;
   }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <?php $this->load->view('inc/inc_htmlhead'); ?>
   <style>
      .box-style {
         border: 1px solid white;
         text-align: justify;
         /* background: aliceblue; */
         background-color: #e8e8e8;
         /* color: #db1f48; */
         height: 100px;
      }

      .portlet.light {
         padding: 12px 20px 420px;
         background-color: #fff;
      }

      .content-style {
         /* padding: 10px; */
         font-size: 14px;
         padding-left: 23px;
         margin: 0px;
         margin-bottom: 18px;
         padding-right: 22px;
         font-weight: bold;
      }

      .link-style {
         color: #666;
         font-family: Proxima Nova, Open Sans, sans-serif;
      }

      .design-box {
         position: absolute;
         height: 99%;
         width: 10px;
         background: #004369;
         border-bottom-left-radius: 5px !important;
         border-TOP-left-radius: 5px !important;
      }

      .rounded {
         border-radius: 1% !important;
      }

      #head-box {
         /* color: #004369; */
         margin: 10px 0px 6px 23px;
         font-weight: 600;
      }

      .title-style {
         font-size: 16px;
         color: #db1f48;
      }

      .box-space {
         margin-bottom: 15px;
      }

      .box-style:hover {
         /* background-color:#f7f7f7; */
         color: #db1f48;
      }

      /* #head-box :hover {
         background-color: #004369;
         color: white;
      } */
      
   </style>
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
   <div class="page-wrapper">
      <?php $this->load->view('inc/inc_header'); ?>
      <div class="clearfix"> </div>
      <div class="page-container">
         <?php $this->load->view('inc/inc_sidebar'); ?>
         <div class="page-content-wrapper">
            <div class="page-content">
               <div class="page-bar">
                  <ul class="page-breadcrumb">
                     <li>
                        <span>Reports</span>
                        <!-- <i class="fa fa-circle"></i> -->
                     </li>
                  </ul>
               </div>
               <div class="row mt-10">
                  <div class="col-md-12">
                     <div class="portlet light ">
                        <!-- Box1 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['reports_adoption'])) {  ?>
                           <?php if ($roleID == 2) { ?>
                              <a href="<?php echo base_url() . "reports_manager_adoption"; ?>" class="link-style">
                                 <!-- content  -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="icon-bar-chart">&nbsp;</i>  Adoption
                                          </div>
                                       </div>

                                       <p class="content-style">
                                          This is an adoption dashboard, you will get idea of how manager and rep are adopting platform.
                                          You will be able to see detailed analysis on this dashboard.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- content -->
                              </a>
                           <?php } else { ?>
                                 <a href="<?php echo base_url() . "reports_adoption"; ?>" class="link-style">
                                    <!-- content  -->
                                    <div class="col-md-12" style="margin-bottom: 15px;">
                                       <div class="design-box rounded-left"></div>
                                       <div class="box-style rounded">
                                          <div class="portlet-title" id="head-box">
                                             <div class="title-style">
                                             <i class="icon-bar-chart">&nbsp;</i>  Adoption
                                             </div>
                                          </div>

                                          <p class="content-style">
                                             This is an adoption dashboard, you will get idea of how manager and rep are adopting platform.
                                             You will be able to see detailed analysis on this dashboard.
                                          </p>
                                       </div>
                                    </div>
                                    <!-- content -->
                                 </a>
                           <?php } ?>
                        <?php }else{ ?>
                           <a href="#" onClick="showMessage(1)" class="link-style">
                           <!-- content  -->
                              <div class="col-md-12" style="margin-bottom: 15px;">
                                 <div class="design-box rounded-left"></div>
                                 <div class="box-style rounded">
                                    <div class="portlet-title" id="head-box">
                                       <div class="title-style">
                                       <i class="icon-bar-chart">&nbsp;</i>  Adoption
                                       </div>
                                    </div>

                                    <p class="content-style">
                                       This is an adoption dashboard, you will get idea of how manager and rep are adopting platform.
                                       You will be able to see detailed analysis on this dashboard.
                                    </p>
                                 </div>
                              </div>
                              <!-- content -->
                           </a>
                        <?php } ?>
                        <!-- Box1 -->
                        <!-- Box2 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['reports_competency'])) {  ?>
                           <?php if ($roleID == 2) { ?>
                              <a href="<?php echo base_url() . "reports_dashboard"; ?>" class="link-style">
                                 <!-- Content 2 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="icon-pie-chart">&nbsp;</i>  Competency
                                          </div>
                                       </div>
                                       <p class="content-style">
                                          This is an competency dashboard, you will be able to see competency for manager, rep, region, division etc.
                                          You will be able to see the detailed analysis and reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 2 -->
                              </a>
                           <?php } else { ?>
                              <a href="<?php echo base_url() . "reports_competency"; ?>" class="link-style">
                                 <!-- Content 2 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-pie-chart">&nbsp;</i>  Competency
                                          </div>
                                       </div>
                                       <p class="content-style">
                                          This is an competency dashboard, you will be able to see competency for manager, rep, region, division etc.
                                          You will be able to see the detailed analysis and reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 2 -->
                              </a>
                           <?php  } ?>
                        <?php }else{ ?>
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 2 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-pie-chart">&nbsp;</i>  Competency
                                          </div>
                                       </div>
                                       <p class="content-style">
                                          This is an competency dashboard, you will be able to see competency for manager, rep, region, division etc.
                                          You will be able to see the detailed analysis and reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 2 -->
                              </a>
                        <?php } ?>
                        <!-- Box2 -->
                        <!-- Box3 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['reports_ai_process'])) { ?>
                           <?php if ($roleID != 2) { ?>
                           <!-- < ?php if ($roleID == 1 || $roleID == 2) { ?> -->
                              <a href="<?php echo base_url() . "ai_process"; ?>" class="link-style">
                                 <!-- Content 3 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-globe">&nbsp;</i>  AI Reports 
                                          </div>
                                       </div>
                                       <p class="content-style">
                                          This is an AI process dashboard. Here you will be able to see the video processing details and excel reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 3 -->
                              </a>
                           <?php } else { ?> 
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 3 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-globe">&nbsp;</i>  AI Process
                                          </div>
                                       </div>
                                       <p class="content-style">
                                          This is an AI process dashboard. Here you will be able to see the video processing details and excel reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 3 -->
                              </a>
                           <?php } ?>
                        <?php }else{ ?>  
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 3 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-globe">&nbsp;</i>  AI Process
                                          </div>
                                       </div>
                                       <p class="content-style">
                                          This is an AI process dashboard. Here you will be able to see the video processing details and excel reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 3 -->
                              </a>
                        <?php } ?>

                        <!-- Box3 -->
                        <!-- Box7 -->
                          <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['role_play_manager_dashboard'])) {  ?>
                              <a href="<?php echo base_url() . "role_play_manager_dashboard"; ?>" class="link-style">
                                 <!-- Content 5 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="fa fa-bar-chart">&nbsp;</i>Role-Play Manager Dashboard 
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       This is Roleplay-manager dashboard which where you will be able to see individual rep reports<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 7 -->
                              </a>
                           <?php } else { ?>
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 7 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="fa fa-industry">&nbsp;</i>  Role-Play Manager Dashboard </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       This is Roleplay-manager dashboard which where you will be able to see individual rep reports<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 7 -->
                              </a>
                           <?php  } ?>
                        <!-- box7  -->
                        <!-- Box6 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['role_play_dashboard'])) {  ?>
                              <a href="<?php echo base_url() . "role_play_rep_dashboard"; ?>" class="link-style">
                                 <!-- Content 5 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="fa fa-industry">&nbsp;</i> Role-Play Rep dashboard
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       This is Role-play rep dashboard which where you will be able to see individual rep reports<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 6 -->
                              </a>
                           <?php } else { ?>
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 6 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="fa fa-industry">&nbsp;</i>  Role-Play Rep dashboard
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       This is Role-play rep dashboard which where you will be able to see individual rep reports<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 6 -->
                              </a>
                           <?php  } ?>
                           <!-- Box6 -->
                           <!-- Added by Nirmal Gajjar 27-01-2024 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['rep_competency_dashboard'])) {  ?>
                              <a href="<?php echo base_url() . "Rep_competency_dashboard"; ?>" class="link-style">
                                 <!-- Content 5 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="fa fa-industry">&nbsp;</i> Rep Competency Dashboard
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       This is rep competency dashboard which where you will able to see individual performance of reps
                                       <br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 6 -->
                              </a>
                           <?php } else { ?>
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 6 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                             <i class="fa fa-industry">&nbsp;</i>  Rep Competency Dashboard
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       This is rep competency dashboard which where you will able to see individual performance of reps

                                       <br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 6 -->
                              </a>
                           <?php  } ?>
                           <!-- Added by Nirmal Gajjar 27-01-2024 -->
                        
                        <!-- box6 -->
                        <!-- Box4 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['reports_knowledge_assessment'])) {  ?>
                           <?php if ($roleID == 2) { ?>
                              <a href="<?php echo base_url() . "trainer_trainee_dashboard"; ?>" class="link-style">
                                 <!-- Content 4 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-layers">&nbsp;</i>  Knowledge Assessment Dashboard
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                          These are knowledge assessment dashboards. Please click to see the detailed reports.
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 4 -->
                              </a>
                           <?php }else{ ?>
                              <a href="<?php echo base_url() . "knowledge_assessment_dashboard"; ?>" class="link-style">
                                    <!-- Content 4 -->
                                    <div class="col-md-12" style="margin-bottom: 15px;">
                                       <div class="design-box rounded-left"></div>
                                       <div class="box-style rounded">
                                          <div class="portlet-title" id="head-box">
                                             <div class="title-style">
                                             <i class="icon-layers">&nbsp;</i>  Knowledge Assessment Dashboard
                                             </div>
                                          </div>
                                          <p class="content-style" style="margin-bottom:0px">
                                          These are knowledge assessment dashboards. Please click to see the detailed reports.
                                          </p>
                                       </div>
                                    </div>
                                    <!-- Content 4 -->
                                 </a>
                           <?php  } ?>
                        <?php } else { ?>
                           <a href="#" onClick="showMessage()" class="link-style">
                              <!-- Content 4 -->
                              <div class="col-md-12" style="margin-bottom: 15px;">
                                 <div class="design-box rounded-left"></div>
                                 <div class="box-style rounded">
                                    <div class="portlet-title" id="head-box">
                                       <div class="title-style">
                                       <i class="icon-layers">&nbsp;</i>  Knowledge Assessment Dashboard
                                       </div>
                                    </div>
                                    <p class="content-style" style="margin-bottom:0px">
                                    These are knowledge assessment dashboards. Please click to see the detailed reports.
                                    </p>
                                 </div>
                              </div>
                              <!-- Content 4 -->
                           </a>
                        <?php } ?>
                        <!-- box4 -->
                        <!-- box5 -->
                        <?php if ($roleID == 1 || $roleID == 2 || $SupperAccess || isset($SideBarDataSet['knowledge_assessment_reports'])) {  ?>
                           <?php if ($roleID == 2) { ?>
                              <a href="<?php echo base_url() . "trainer_trainee_workshop_reports"; ?>" class="link-style">
                                 <!-- Content 5 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-map">&nbsp;</i>  Knowledge Assessment Reports
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       These are knowledge assessment dashboards. Please click to see the detailed reports.<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 5 -->
                              </a>
                           <?php } else { ?>
                              <a href="<?php echo base_url() . "workshops_reports"; ?>" class="link-style">
                                 <!-- Content 5 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-map">&nbsp;</i>  Knowledge Assessment Reports
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       These are knowledge assessment dashboards. Please click to see the detailed reports.<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 5 -->
                              </a>
                           <?php } ?>
                        <?php }else{ ?>
                              <a href="#" onClick="showMessage()" class="link-style">
                                 <!-- Content 5 -->
                                 <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="design-box rounded-left"></div>
                                    <div class="box-style rounded">
                                       <div class="portlet-title" id="head-box">
                                          <div class="title-style">
                                          <i class="icon-map">&nbsp;</i>  Knowledge Assessment Reports
                                          </div>
                                       </div>
                                       <p class="content-style" style="margin-bottom:0px">
                                       These are knowledge assessment dashboards. Please click to see the detailed reports.<br>
                                       </p>
                                    </div>
                                 </div>
                                 <!-- Content 5 -->
                              </a>
                        <?php } ?>
                        <!-- Box5 -->
                        
                       
                     </div>

                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php //$this->load->view('inc/inc_quick_sidebar'); 
      ?>
   </div>
   <?php //$this->load->view('inc/inc_footer'); 
   ?>
   </div>
   <?php //$this->load->view('inc/inc_quick_nav'); 
   ?>
   <?php $this->load->view('inc/inc_footer_script'); ?>
   <script src="<?php echo $asset_url; ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
   <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
   <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
   <script src="<?php echo $asset_url; ?>assets/global/plugins/datatables/Buttons-1.3.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
   <script>
      function showMessage(id) {
         ShowAlret("You don't have access to see this feature, please contact admin", 'error');
         return false;
      }
   </script>
</body>

</html>