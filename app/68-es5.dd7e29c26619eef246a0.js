function _classCallCheck(n,o){if(!(n instanceof o))throw new TypeError("Cannot call a class as a function")}function _defineProperties(n,o){for(var t=0;t<o.length;t++){var e=o[t];e.enumerable=e.enumerable||!1,e.configurable=!0,"value"in e&&(e.writable=!0),Object.defineProperty(n,e.key,e)}}function _createClass(n,o,t){return o&&_defineProperties(n.prototype,o),t&&_defineProperties(n,t),n}(window.webpackJsonp=window.webpackJsonp||[]).push([[68],{ugvt:function(n,o,t){"use strict";t.r(o),t.d(o,"WorkshopHistoryPageModule",(function(){return I}));var e=t("ofXK"),i=t("3Pt+"),r=t("TEn/"),a=t("tyNb"),c=t("mrSG"),s=t("dt9i"),l=t("kUAt"),p=t("1vjY"),d=t("X7yx"),b=t("5dVO"),g=t("riPR"),m=t("ZJFI"),u=t("fXoL");function h(n,o){if(1&n&&(u.Ob(0,"ion-col",34),u.mc(1),u.Nb()),2&n){var t=u.Yb().$implicit;u.Ab(1),u.pc(" : ",t.pre_start_date_dmy," | ",t.pre_start_time," ")}}function _(n,o){if(1&n&&(u.Ob(0,"ion-col",34),u.mc(1),u.Nb()),2&n){var t=u.Yb().$implicit;u.Ab(1),u.pc(" : ",t.post_start_date_dmy," | ",t.post_start_time," ")}}function f(n,o){if(1&n&&(u.Ob(0,"ion-col",34),u.mc(1),u.Nb()),2&n){var t=u.Yb(2).$implicit;u.Ab(1),u.pc(" : ",t.pre_end_date_dmy," | ",t.pre_end_time," ")}}function O(n,o){if(1&n&&(u.Ob(0,"ion-col",34),u.mc(1),u.Nb()),2&n){var t=u.Yb(2).$implicit;u.Ab(1),u.pc(" : ",t.post_end_date_dmy," | ",t.post_end_time," ")}}function k(n,o){if(1&n&&(u.Ob(0,"ion-row",2),u.Ob(1,"ion-col",15),u.mc(2," END "),u.Nb(),u.lc(3,f,2,2,"ion-col",16),u.lc(4,O,2,2,"ion-col",16),u.Nb()),2&n){var t=u.Yb().$implicit;u.Ab(3),u.bc("ngIf","PRE"==t.workshop_session),u.Ab(1),u.bc("ngIf","POST"==t.workshop_session)}}function w(n,o){if(1&n){var t=u.Pb();u.Ob(0,"ion-button",35),u.Wb("click",(function(){u.gc(t);var n=u.Yb().$implicit;return u.Yb().alert_quiz_notparticipate(n.workshop_name)})),u.mc(1,"NOT PARTICIPATE"),u.Nb()}}function x(n,o){if(1&n){var t=u.Pb();u.Ob(0,"ion-button",36),u.Wb("click",(function(){u.gc(t);var n=u.Yb().$implicit;return u.Yb().alert_quiz_incomplete(n.workshop_name)})),u.mc(1,"INCOMPLETE"),u.Nb()}}function v(n,o){if(1&n){var t=u.Pb();u.Ob(0,"ion-button",37),u.Wb("click",(function(){u.gc(t);var n=u.Yb().$implicit;return u.Yb().alert_quiz_completed(n.workshop_name)})),u.mc(1,"COMPLETE"),u.Nb()}}function C(n,o){if(1&n){var t=u.Pb();u.Ob(0,"ion-card",4),u.Ob(1,"ion-card-content",2),u.Ob(2,"ion-row",2),u.Ob(3,"span",5),u.mc(4,"COMPLETED"),u.Nb(),u.Ob(5,"ion-col",6),u.Ob(6,"div",7),u.Mb(7,"img",8),u.Nb(),u.Nb(),u.Ob(8,"ion-col",9),u.Ob(9,"span",10),u.Wb("click",(function(){u.gc(t);var n=o.$implicit;return u.Yb().presentModal(n)})),u.Ob(10,"a",11),u.Mb(11,"ion-icon",12),u.Nb(),u.Nb(),u.Ob(12,"ion-row",13),u.Ob(13,"ion-col",14),u.Ob(14,"ion-row",2),u.Ob(15,"ion-col",15),u.mc(16," START "),u.Nb(),u.lc(17,h,2,2,"ion-col",16),u.lc(18,_,2,2,"ion-col",16),u.Nb(),u.lc(19,k,5,2,"ion-row",17),u.Ob(20,"ion-row",2),u.Ob(21,"ion-col",18),u.mc(22," SESSION "),u.Nb(),u.Ob(23,"ion-col",19),u.mc(24),u.Nb(),u.Nb(),u.Nb(),u.Nb(),u.Ob(25,"ion-row",20),u.Ob(26,"ion-col",14),u.Ob(27,"p",21),u.mc(28),u.Nb(),u.Nb(),u.Nb(),u.Nb(),u.Nb(),u.Ob(29,"ion-row",2),u.Ob(30,"ion-col",14),u.Ob(31,"ion-row",22),u.Ob(32,"ion-col",23),u.Ob(33,"ion-row",2),u.Ob(34,"ion-col",24),u.mc(35),u.Nb(),u.Nb(),u.Ob(36,"ion-row",2),u.Ob(37,"ion-col",25),u.mc(38," CORRECT "),u.Nb(),u.Nb(),u.Nb(),u.Ob(39,"ion-col",23),u.Ob(40,"ion-row",2),u.Ob(41,"ion-col",26),u.mc(42),u.Nb(),u.Nb(),u.Ob(43,"ion-row",2),u.Ob(44,"ion-col",25),u.mc(45," WRONG "),u.Nb(),u.Nb(),u.Nb(),u.Ob(46,"ion-col",23),u.Ob(47,"ion-row",2),u.Ob(48,"ion-col",27),u.mc(49),u.Nb(),u.Nb(),u.Ob(50,"ion-row",2),u.Ob(51,"ion-col",25),u.mc(52," PREFERENCE "),u.Nb(),u.Nb(),u.Nb(),u.Ob(53,"ion-col",28),u.Wb("click",(function(){u.gc(t);var n=o.$implicit;return u.Yb().graph_topic_subtopic_wise(n)})),u.Ob(54,"ion-row",2),u.Ob(55,"ion-col",29),u.Mb(56,"img",30),u.Nb(),u.Nb(),u.Ob(57,"ion-row",2),u.Ob(58,"ion-col",25),u.mc(59," VIEW REPORTS "),u.Nb(),u.Nb(),u.Nb(),u.Nb(),u.Nb(),u.Nb(),u.Ob(60,"ion-row",2),u.Ob(61,"ion-col",14),u.lc(62,w,2,0,"ion-button",31),u.lc(63,x,2,0,"ion-button",32),u.lc(64,v,2,0,"ion-button",33),u.Nb(),u.Nb(),u.Nb(),u.Nb()}if(2&n){var e=o.$implicit;u.Ab(7),u.cc("src",e.workshop_image,u.ic),u.Ab(10),u.bc("ngIf","PRE"==e.workshop_session),u.Ab(1),u.bc("ngIf","POST"==e.workshop_session),u.Ab(1),u.bc("ngIf",0==e.end_time_display),u.Ab(5),u.oc(" : ",e.workshop_session," "),u.Ab(4),u.oc(" ",e.workshop_name," "),u.Ab(7),u.oc(" ",e.score_correct," "),u.Ab(7),u.oc(" ",e.score_wrong," "),u.Ab(7),u.oc(" ",e.score_preference," "),u.Ab(13),u.bc("ngIf","N"==e.is_registered),u.Ab(1),u.bc("ngIf",!("0"!=e.is_registered&&"1"!=e.is_registered||0!=e.all_questions_fired&&0!=e.all_feedbacks_fired)),u.Ab(1),u.bc("ngIf",("0"==e.is_registered||"1"==e.is_registered)&&1==e.all_questions_fired&&1==e.all_feedbacks_fired)}}function P(n,o){if(1&n&&(u.Ob(0,"ion-row",38),u.Ob(1,"ion-col",39),u.mc(2),u.Nb(),u.Nb()),2&n){var t=u.Yb();u.Ab(1),u.Cb(t.internet_background),u.Ab(1),u.oc(" ",t.internet_text," ")}}var z,y,M,N=function(n){return n[n.Online=0]="Online",n[n.Offline=1]="Offline",n}({}),A=[{path:"",component:(z=function(){function n(o,t,e,i,r,a,c,s,l,p,d){_classCallCheck(this,n),this.platform=o,this.menu=t,this.modalCtrl=e,this.navCtrl=i,this.zone=r,this.routerOutlet=a,this.events=c,this.ionLoader=s,this.ionAlert=l,this.database=p,this.atom=d,this.btn_isclicked=!1,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.workshops_completed_form={action:"workshops_completed",payload:""},this.workshop_no_otc_form={action:"workshop_without_otc",payload:"",workshop_id:"",workshop_session:""},this.json_workshop_completed=[],this.internet_indicator=!1,this.menu.enable(!0),this.initializeApp()}return _createClass(n,[{key:"ngOnInit",value:function(){}},{key:"initializeApp",value:function(){var n=this;this.platform.ready().then((function(o){window.addEventListener("online",(function(){n.internetStatus=N.Online,n.zone.run((function(){n.internet_background="int-green",n.internet_text="YOU ARE ONLINE"})),n.internet_timer=setTimeout((function(){n.zone.run((function(){n.internet_indicator=!1}))}),1e4)})),window.addEventListener("offline",(function(){n.internetStatus=N.Offline,n.zone.run((function(){n.internet_background="int-red",n.internet_text="YOU ARE OFFLINE",n.internet_indicator=!0}))})),n.database.storage_get("device").then((function(o){o.token_no&&o.payload&&(n.json_profile=o,n.workshops_completed_form.payload=o.payload,n.workshop_no_otc_form.payload=o.payload,n.fetch_workshops_history(),n.events.subscribe("refresh_workshops_history",(function(o){n.fetch_workshops_history()})))})).catch((function(n){}))}))}},{key:"ionViewDidLoad",value:function(){try{this.events.unsubscribe("loginnetwork:online"),this.events.unsubscribe("loginnetwork:offline"),clearTimeout(this.internet_timer)}catch(n){}}},{key:"fetch_workshops_history",value:function(){var n=this;""==this.workshops_completed_form.payload?this.atom.presentToast("Offline API Token missing."):this.atom.http_post(this.workshops_completed_form).subscribe((function(o){1==o.success&&(n.json_workshop_completed=o.data)}),(function(n){}),(function(){}))}},{key:"presentModal",value:function(n){return Object(c.a)(this,void 0,void 0,regeneratorRuntime.mark((function o(){var t;return regeneratorRuntime.wrap((function(o){for(;;)switch(o.prev=o.next){case 0:return o.t0=this.modalCtrl,o.t1=s.a,o.t2=n,o.t3=!0,o.next=6,this.modalCtrl.getTop();case 6:return o.t4=o.sent,o.t5={component:o.t1,componentProps:o.t2,swipeToClose:o.t3,presentingElement:o.t4},o.next=10,o.t0.create.call(o.t0,o.t5);case 10:return t=o.sent,o.next=13,t.present();case 13:return o.abrupt("return",o.sent);case 14:case"end":return o.stop()}}),o,this)})))}},{key:"alert_quiz_notparticipate",value:function(n){this.ionAlert.show_alert("Workshop - Not Participate","You have not participated in "+n+" Workshop.").then((function(){})).catch((function(){}))}},{key:"alert_quiz_incomplete",value:function(n){this.ionAlert.show_alert("Workshop - Incomplete","Workshop is incomplete, "+n+" Workshop period is expiered.").then((function(){})).catch((function(){}))}},{key:"alert_quiz_completed",value:function(n){this.ionAlert.show_alert("Workshop - Completed","Workshop is completed, Thank you for participating in the workshop "+n+".").then((function(){})).catch((function(){}))}},{key:"refresh_page",value:function(){}},{key:"graph_topic_subtopic_wise",value:function(n){return Object(c.a)(this,void 0,void 0,regeneratorRuntime.mark((function o(){var t,e;return regeneratorRuntime.wrap((function(o){for(;;)switch(o.prev=o.next){case 0:if(!(n.score_correct>0||n.score_wrong>0||n.score_time_out>0)){o.next=30;break}if(1!=n.all_questions_fired||1!=n.all_feedbacks_fired){o.next=16;break}return o.t0=this.modalCtrl,o.t1=l.a,o.t2={workshop_details:n,graph_type:"completed"},o.t3=!0,o.next=8,this.modalCtrl.getTop();case 8:return o.t4=o.sent,o.t5={component:o.t1,componentProps:o.t2,swipeToClose:o.t3,presentingElement:o.t4},o.next=12,o.t0.create.call(o.t0,o.t5);case 12:return t=o.sent,o.next=15,t.present();case 15:return o.abrupt("return",o.sent);case 16:return o.t6=this.modalCtrl,o.t7=l.a,o.t8={workshop_details:n,graph_type:"live"},o.t9=!0,o.next=22,this.modalCtrl.getTop();case 22:return o.t10=o.sent,o.t11={component:o.t7,componentProps:o.t8,swipeToClose:o.t9,presentingElement:o.t10},o.next=26,o.t6.create.call(o.t6,o.t11);case 26:return e=o.sent,o.next=29,e.present();case 29:return o.abrupt("return",o.sent);case 30:this.ionAlert.show_alert("Oops!","The graph cannot be viewed as workshop not played.");case 31:case"end":return o.stop()}}),o,this)})))}}]),n}(),z.\u0275fac=function(n){return new(n||z)(u.Lb(r.T),u.Lb(r.Q),u.Lb(r.R),u.Lb(r.S),u.Lb(u.z),u.Lb(r.A),u.Lb(g.a),u.Lb(b.a),u.Lb(d.a),u.Lb(m.a),u.Lb(p.a))},z.\u0275cmp=u.Fb({type:z,selectors:[["app-workshop-history"]],decls:6,vars:2,consts:[["size","12","class","workshop-container",4,"ngFor","ngForOf"],["position","bottom",1,"ion-no-margin","ion-no-padding","ion-no-border","ion-no-shadow","internet-bottom-margin"],[1,"ion-no-margin","ion-no-padding"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],["size","12",1,"workshop-container"],[1,"wrk-status"],["size","6","size-xs","6","size-sm","6","size-md","3","size-lg","3","size-xl","3",1,"gallery"],[1,"gallery-prod-box"],["cache","true","alt","",3,"src"],["size","6","size-xs","6","size-sm","6","size-md","9","size-lg","9","size-xl","9",1,"ion-no-margin","ion-no-padding"],[1,"wrkshp-more-container",3,"click"],[1,"pop-a"],["name","ellipsis-vertical-outline"],[1,"ion-no-margin","ion-no-padding","workshop-date-container"],["size","12",1,"ion-no-margin","ion-no-padding"],["size","3","size-xs","3","size-sm","3","size-md","2","size-lg","2","size-xl","2",1,"ion-no-margin","ion-no-padding","workshop-date"],["size","9","size-xs","9","size-sm","9","size-md","10","size-lg","10","size-xl","10","class","ion-no-margin ion-no-padding workshop-date",4,"ngIf"],["class","ion-no-margin ion-no-padding",4,"ngIf"],["size","3","size-xs","3","size-sm","3","size-md","2","size-lg","2","size-xl","2",1,"ion-no-margin","ion-no-padding","workshop-type"],["size","9","size-xs","9","size-sm","9","size-md","10","size-lg","10","size-xl","10",1,"ion-no-margin","ion-no-padding","workshop-type-value"],[1,"ion-no-margin","ion-no-padding","workshop-title-container"],[1,"workshop-title"],[1,"ion-no-margin","ion-no-padding","score-main-wrapper"],["size","3",1,"ion-no-margin","ion-no-padding","correct-label"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont","recent-value","correct-color"],["size","12",1,"ion-no-margin","ion-no-padding","report-label"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont","recent-value","incorrect-color"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont","recent-value","timeout-color"],["size","3",1,"ion-no-margin","ion-no-padding",3,"click"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont"],["src","assets/images/graph.png","alt","",1,"report-icon"],["size","small","fill","solid","expand","block","class","participate-button ion-no-margin",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block","class","playing-button ion-no-margin",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block","class","completed-button ion-no-margin",3,"click",4,"ngIf"],["size","9","size-xs","9","size-sm","9","size-md","10","size-lg","10","size-xl","10",1,"ion-no-margin","ion-no-padding","workshop-date"],["size","small","fill","solid","expand","block",1,"participate-button","ion-no-margin",3,"click"],["size","small","fill","solid","expand","block",1,"playing-button","ion-no-margin",3,"click"],["size","small","fill","solid","expand","block",1,"completed-button","ion-no-margin",3,"click"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(n,o){1&n&&(u.Ob(0,"ion-content"),u.Ob(1,"ion-list"),u.lc(2,C,65,12,"ion-card",0),u.Nb(),u.Nb(),u.Ob(3,"ion-footer",1),u.Ob(4,"ion-grid",2),u.lc(5,P,3,3,"ion-row",3),u.Nb(),u.Nb()),2&n&&(u.Ab(2),u.bc("ngForOf",o.json_workshop_completed),u.Ab(3),u.bc("ngIf",o.internet_indicator))},directives:[r.m,r.w,e.h,r.o,r.p,e.i,r.j,r.k,r.B,r.l,r.r,r.h],styles:["div[_ngcontent-%COMP%]{border:1px solid #e4e4e4;width:100%;height:100px;text-align:center}div[_ngcontent-%COMP%]:last-child, ion-list[_ngcontent-%COMP%]{margin-bottom:calc(100% - 100px)}ion-card[_ngcontent-%COMP%]{position:relative;border-radius:0;-moz-border-radius:0;-o-border-radius:0;-webkit-border-radius:0;box-shadow:0 0 0 1px hsla(0,0%,56.9%,.14)}.wrkshp-more-container[_ngcontent-%COMP%]{position:absolute;background-color:transparent;width:20px;height:20px;overflow:hidden;top:5px;right:4px;text-align:center;z-index:1}.pop-a[_ngcontent-%COMP%]{text-align:center;vertical-align:middle;margin:0;padding:0}.pop-icon[_ngcontent-%COMP%]{position:relative;padding:0;font-size:2rem;color:#353535;margin:5px 0 0}ion-card[_ngcontent-%COMP%]   img[_ngcontent-%COMP%]{opacity:.9}ion-card[_ngcontent-%COMP%]:last-of-type{margin-bottom:3!important}.wrk-status[_ngcontent-%COMP%], .wrk-status-completed[_ngcontent-%COMP%]{position:absolute;background-color:#e6ca0e;top:0;font-size:1.1rem;font-weight:600;color:#222;height:28px;width:80px;margin:5px 0 0 5px;z-index:2;padding:8px;text-align:center}.cover[_ngcontent-%COMP%]{width:100%!important;height:auto!important;z-index:1!important}.card-content[_ngcontent-%COMP%]{padding:0!important;margin:0!important}.workshop-container[_ngcontent-%COMP%]{padding:0!important;margin:10px 0 0!important}.participate-button[_ngcontent-%COMP%]{--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary);--background-activated:var(--ion-color-primary)}.participate-button[_ngcontent-%COMP%], .playing-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.5rem;font-weight:500;text-transform:none;--box-shadow:none;--border-radius:0px;--color:var(--ion-color-primary-contrast);height:35px!important}.playing-button[_ngcontent-%COMP%]{--background:var(--ion-color-playing);--background-focused:var(--ion-color-playing);--background-hover:var(--ion-color-playing);--background-activated:var(--ion-color-playing)}.completed-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.5rem;font-weight:500;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-teal);--background-focused:var(--ion-color-teal);--background-hover:var(--ion-color-teal);--background-activated:var(--ion-color-teal);--color:var(--ion-color-primary-contrast);height:35px!important}.overlay-text[_ngcontent-%COMP%]{top:0;position:absolute;background-size:cover;background-attachment:fixed;background-repeat:no-repeat;background-position:top;transform:translate(0);width:100%;height:200px!important}.workshop-date-container[_ngcontent-%COMP%]{margin:5px 0 0 10px!important;padding:0!important}.workshop-date[_ngcontent-%COMP%], .workshop-type[_ngcontent-%COMP%]{color:#222}.workshop-date[_ngcontent-%COMP%], .workshop-type[_ngcontent-%COMP%], .workshop-type-value[_ngcontent-%COMP%]{padding:0!important;margin:0!important;font-size:1rem;font-weight:700}.workshop-type-value[_ngcontent-%COMP%]{color:#fe773d}.workshop-title-container[_ngcontent-%COMP%]{margin:0 0 0 10px!important;padding:0!important}.workshop-title[_ngcontent-%COMP%]{padding:0!important;margin:10px 0 0!important;font-size:1.5rem;font-weight:700;color:#222}.black-overlay[_ngcontent-%COMP%]{position:relative;background-color:#000;opacity:.7}.recent-main-wrapper[_ngcontent-%COMP%]{background-color:#f7f7f7;border:1px solid #eee;height:150px}.recent-container-top[_ngcontent-%COMP%]{border-bottom:1px solid #e2e0e1}.recent-container-bottom[_ngcontent-%COMP%], .recent-container-top[_ngcontent-%COMP%]{height:75px;border-left:1px solid #e2e0e1}.recent-heading[_ngcontent-%COMP%]{vertical-align:middle;text-align:center;font-size:.8em;margin:0}.recent-title[_ngcontent-%COMP%]{font-size:1.2em}.recent-label[_ngcontent-%COMP%], .recent-title[_ngcontent-%COMP%]{text-align:center;font-weight:700;margin:0}.recent-label[_ngcontent-%COMP%]{vertical-align:middle;font-size:.8em;padding:15px}.recent-value[_ngcontent-%COMP%]{font-size:3rem!important;font-weight:700}.recent-orange[_ngcontent-%COMP%]{color:#fe773d}.recent-green[_ngcontent-%COMP%]{color:#47cead}.score-main-wrapper[_ngcontent-%COMP%]{margin:0;padding:0!important;background-color:#fcfcfe;border-top:1px solid #dbdbdd;height:60px}.button-main-wrapper[_ngcontent-%COMP%]{padding:8px 0 0 5px!important;background-color:#fcfcfe;border:4px solid #fcfcfe;border-top:1px solid #dbdbdd;height:60px}.correct-label[_ngcontent-%COMP%], .incorrect-label[_ngcontent-%COMP%], .total-label[_ngcontent-%COMP%]{margin:0;border-right:1px solid #dbdbdd}.correct-label[_ngcontent-%COMP%], .incorrect-label[_ngcontent-%COMP%], .report-label[_ngcontent-%COMP%], .total-label[_ngcontent-%COMP%]{vertical-align:middle;text-align:center;font-size:1.1rem;font-weight:700;color:#222}.report-label[_ngcontent-%COMP%]{margin:0!important}.graph-cont[_ngcontent-%COMP%]{text-align:center!important;width:100%;height:39px;overflow:hidden;margin:5px 0 0!important}.report-icon[_ngcontent-%COMP%]{background:transparent;width:40px;height:auto;margin:0 auto!important}.timeout-color[_ngcontent-%COMP%]{color:#222}.incorrect-color[_ngcontent-%COMP%]{color:#fe7638}.correct-color[_ngcontent-%COMP%]{color:#45cfad}.box[_ngcontent-%COMP%]{background:#fff}.advt-footer[_ngcontent-%COMP%], .advt-img[_ngcontent-%COMP%]{height:50px}.advt-img[_ngcontent-%COMP%]{width:100%!important;background-size:cover;-o-object-fit:cover!important;object-fit:cover!important;background-attachment:fixed;background-repeat:no-repeat;background-position:top;transform:translate(0);text-align:center}.gallery[_ngcontent-%COMP%]{float:left;height:130px;margin:0!important;padding:0!important;overflow:hidden;background-color:#bebebe}.gallery[_ngcontent-%COMP%]   .gallery-prod-box[_ngcontent-%COMP%]{height:130px;max-height:130px;line-height:145px;text-align:center;overflow:hidden}.gallery[_ngcontent-%COMP%]   .gallery-prod-box[_ngcontent-%COMP%]   img[_ngcontent-%COMP%]{text-align:center;vertical-align:middle;overflow:hidden;display:inline-block}"]}),z)}],E=((M=function n(){_classCallCheck(this,n)}).\u0275mod=u.Jb({type:M}),M.\u0275inj=u.Ib({factory:function(n){return new(n||M)},imports:[[a.i.forChild(A)],a.i]}),M),I=((y=function n(){_classCallCheck(this,n)}).\u0275mod=u.Jb({type:y}),y.\u0275inj=u.Ib({factory:function(n){return new(n||y)},imports:[[e.b,i.f,r.N,E]]}),y)}}]);