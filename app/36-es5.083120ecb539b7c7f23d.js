function _classCallCheck(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function _defineProperties(t,e){for(var n=0;n<e.length;n++){var a=e[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}function _createClass(t,e,n){return e&&_defineProperties(t.prototype,e),n&&_defineProperties(t,n),t}(window.webpackJsonp=window.webpackJsonp||[]).push([[36],{zbXe:function(t,e,n){"use strict";n.r(e),n.d(e,"AssessmentLivePageModule",(function(){return A}));var a=n("ofXK"),o=n("3Pt+"),s=n("TEn/"),i=n("tyNb"),r=n("mrSG"),c=n("cupU"),l=n("etx3"),d=n("HDmY"),m=n("1vjY"),u=n("X7yx"),p=n("5dVO"),_=n("riPR"),f=n("ZJFI"),b=n("UgkZ"),g=n("fXoL");function h(t,e){if(1&t){var n=g.Pb();g.Ob(0,"ion-button",29),g.Wb("click",(function(){g.gc(n);var t=g.Yb().$implicit;return g.Yb().participate(t)})),g.mc(1,"PARTICIPATE"),g.Nb()}}function v(t,e){if(1&t){var n=g.Pb();g.Ob(0,"ion-button",30),g.Wb("click",(function(){g.gc(n);var t=g.Yb().$implicit;return g.Yb().play_attemptted(t)})),g.mc(1,"PREVIEW"),g.Nb()}}function O(t,e){if(1&t){var n=g.Pb();g.Ob(0,"ion-button",31),g.Wb("click",(function(){return g.gc(n),g.Yb(2).pending_message()})),g.mc(1,"IN PROGRESS"),g.Nb()}}function P(t,e){if(1&t){var n=g.Pb();g.Ob(0,"ion-card",4),g.Ob(1,"ion-row",5),g.Ob(2,"ion-col",6),g.Ob(3,"ion-badge",7),g.mc(4),g.Nb(),g.Nb(),g.Nb(),g.Ob(5,"ion-row",5),g.Ob(6,"ion-col",8),g.Ob(7,"p",9),g.mc(8),g.Nb(),g.Nb(),g.Nb(),g.Ob(9,"ion-row",0),g.Ob(10,"ion-col",10),g.Ob(11,"p",11),g.mc(12),g.Nb(),g.Nb(),g.Nb(),g.Ob(13,"ion-row",12),g.Ob(14,"ion-col",13),g.mc(15," Total Time "),g.Nb(),g.Ob(16,"ion-col",14),g.mc(17," Total Questions "),g.Nb(),g.Ob(18,"ion-col",15),g.mc(19," No. of Attempts "),g.Nb(),g.Ob(20,"ion-col",16),g.Ob(21,"span",17),g.mc(22),g.Nb(),g.Nb(),g.Ob(23,"ion-col",18),g.Ob(24,"span",19),g.mc(25),g.Nb(),g.Nb(),g.Ob(26,"ion-col",20),g.Ob(27,"span",21),g.mc(28),g.Nb(),g.Nb(),g.Nb(),g.Ob(29,"ion-row",22),g.Ob(30,"ion-col",23),g.Ob(31,"ion-button",24),g.Wb("click",(function(){g.gc(n);var t=e.$implicit;return g.Yb().read_more(null==t?null:t.read_more)})),g.mc(32,"READ DETAILS"),g.Nb(),g.Nb(),g.Ob(33,"ion-col",25),g.lc(34,h,2,0,"ion-button",26),g.lc(35,v,2,0,"ion-button",27),g.lc(36,O,2,0,"ion-button",28),g.Nb(),g.Nb(),g.Nb()}if(2&t){var a=e.$implicit;g.Ab(4),g.nc(null==a?null:a.is_situation),g.Ab(4),g.nc(null==a?null:a.assessment_name),g.Ab(4),g.oc("Submission Date: ",null==a?null:a.assessment_end_date,""),g.Ab(10),g.oc("\xa0",null==a?null:a.total_time,""),g.Ab(3),g.oc("\xa0",null==a?null:a.total_question,""),g.Ab(3),g.pc("\xa0",null==a?null:a.total_attempts," / ",null==a?null:a.attempts_allowed,""),g.Ab(6),g.bc("ngIf",""==a.assessment_status),g.Ab(1),g.bc("ngIf","ATTEMPTED"==a.assessment_status),g.Ab(1),g.bc("ngIf","PENDING"==a.assessment_status)}}function w(t,e){if(1&t&&(g.Ob(0,"ion-row",32),g.Ob(1,"ion-col",33),g.mc(2),g.Nb(),g.Nb()),2&t){var n=g.Yb();g.Ab(1),g.Cb(n.internet_background),g.Ab(1),g.oc(" ",n.internet_text," ")}}var x,E,C,k=function(t){return t[t.Online=0]="Online",t[t.Offline=1]="Offline",t}({}),T=[{path:"",component:(x=function(){function t(e,n,a,o,s,i,r,c,l,d,m){_classCallCheck(this,t),this.platform=e,this.menu=n,this.modalCtrl=a,this.navCtrl=o,this.zone=s,this.routerOutlet=i,this.events=r,this.ionLoader=c,this.ionAlert=l,this.database=d,this.atom=m,this.btn_isclicked=!1,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.json_assessment={action:"video_assessment_list_ftp",token_no:"",payload:""},this.json_assessment_details=[],this.vimeo_credentials=[],this.messages=[],this.internet_indicator=!1,this.platform_used="",this.menu.enable(!0),this.initializeApp()}return _createClass(t,[{key:"ngOnInit",value:function(){}},{key:"initializeApp",value:function(){var t=this;this.platform.ready().then((function(e){window.addEventListener("online",(function(){t.internetStatus=k.Online,t.zone.run((function(){t.internet_background="int-green",t.internet_text="YOU ARE ONLINE"})),t.internet_timer=setTimeout((function(){t.zone.run((function(){t.internet_indicator=!1}))}),1e4)})),window.addEventListener("offline",(function(){t.internetStatus=k.Offline,t.zone.run((function(){t.internet_background="int-red",t.internet_text="YOU ARE OFFLINE",t.internet_indicator=!0}))})),t.database.storage_get("device").then((function(e){e.token_no&&e.payload&&(t.json_profile=e,t.json_assessment.payload=e.payload,t.json_assessment.token_no=e.token_no,t.fetch_assessment_list(),t.events.subscribe("refresh_assessment_live",(function(e){t.fetch_assessment_list()})))})).catch((function(t){})),t.database.storage_get("useragent").then((function(e){t.platform_used=e})).catch((function(t){}))}))}},{key:"ionViewDidLoad",value:function(){try{this.events.unsubscribe("loginnetwork:online"),this.events.unsubscribe("loginnetwork:offline"),clearTimeout(this.internet_timer)}catch(t){}}},{key:"execute_assement",value:function(t,e){var n=this,a=0,o=0,s=0,i=[];i.total_uploaded=0,i.total_attempted=0,i.assessment_question_count=0;var r=Promise.resolve();return Object.keys(t).forEach((function(c){r=r.then((function(){return new Promise((function(r){if(parseFloat(t[c].user_id)==parseFloat(n.json_profile.user_id)){var l=t[c].is_uploaded;t[c].assessment_id===e&&("1"==l&&(a++,i.total_uploaded=a),"0"==l&&(o++,i.total_attempted=o),s++,i.assessment_question_count=s,r(i))}}))}))})),Promise.all([r]).then((function(t){return t[0]}))}},{key:"fetch_assessment_list",value:function(){return Object(r.a)(this,void 0,void 0,regeneratorRuntime.mark((function t(){var e,n=this;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(e=this,""!=this.json_assessment.token_no&&""!=this.json_assessment.payload){t.next=5;break}this.atom.presentToast("Offline API Token missing."),t.next=7;break;case 5:return t.next=7,this.atom.http_post(this.json_assessment).subscribe((function(t){return Object(r.a)(n,void 0,void 0,regeneratorRuntime.mark((function n(){var a,o,s,i=this;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if(this.messages.LOCK_RECORDING_BUTTON_PERIOD=t.LOCK_RECORDING_BUTTON_PERIOD,this.messages.NO_RECORD_FOUND=t.NO_RECORD_FOUND,this.messages.INTERNET_DISCONNECTED=t.INTERNET_DISCONNECTED,this.messages.INTERNET_HARDWARE_DISCONNECTED=t.INTERNET_HARDWARE_DISCONNECTED,this.messages.VIMEO_TRANSCODING_STUCK=t.VIMEO_TRANSCODING_STUCK,this.messages.VIMEO_CALLBACK_ATTEMPTS=t.VIMEO_CALLBACK_ATTEMPTS,this.messages.VIMEO_CALLBACK_TIMER=t.VIMEO_CALLBACK_TIMER,this.messages.PBWRAPPER_BG=t.PBWRAPPER_BG,this.messages.PBFONT_CLR=t.PBFONT_CLR,this.messages.PBU_BG=t.PBU_BG,this.messages.PBC_BG=t.PBC_BG,this.messages.PBF_BG=t.PBF_BG,this.messages.PBS_BG=t.PBS_BG,this.messages.UPLOAD_POPUP_TITLE=t.UPLOAD_POPUP_TITLE,this.messages.UPLOAD_POPUP_MESSAGE_HEAD=t.UPLOAD_POPUP_MESSAGE_HEAD,this.messages.UPLOAD_POPUP_MESSAGE_SUB=t.UPLOAD_POPUP_MESSAGE_SUB,this.messages.PENDING_POPUP_TITLE=t.PENDING_POPUP_TITLE,this.messages.PENDING_POPUP_MESSAGE_HEAD=t.PENDING_POPUP_MESSAGE_HEAD,this.messages.PENDING_POPUP_MESSAGE_SUB=t.PENDING_POPUP_MESSAGE_SUB,this.atom.set_assessment_message(this.messages),1==t.success){for(a=Promise.resolve(),o=function(e){a=a.then((function(){return new Promise((function(n,a){var o=t.data[e].assessment_id;if(t.data[e].assessment_status="","1"!=t.data[e].is_completed&&1!=t.data[e].is_completed||"0"!=t.data[e].ftpto_vimeo_uploaded&&0!=t.data[e].ftpto_vimeo_uploaded)if(parseFloat(t.data[e].attempts_allowed)==parseFloat(t.data[e].total_attempts))t.data[e].assessment_status="ATTEMPTED",n();else{var s=0,c=0;i.database.storage_get("assessment").then((function(n){return Object(r.a)(i,void 0,void 0,regeneratorRuntime.mark((function a(){var i;return regeneratorRuntime.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:if(!(n&&Object.keys(n).length>0)){a.next=7;break}return a.next=3,this.execute_assement(n,o);case 3:i=a.sent,s=i.assessment_question_count,c=i.total_uploaded,i.total_attempted,s>0?t.data[e].assessment_status="ATTEMPTED":(parseFloat(t.data[e].video_uploaded_count)==parseFloat(t.data[e].total_question)&&parseFloat(t.data[e].video_uploaded_count_ftp)==parseFloat(t.data[e].total_question)&&("0"!=t.data[e].is_completed&&0!=t.data[e].is_completed||"0"!=t.data[e].ftpto_vimeo_uploaded&&0!=t.data[e].ftpto_vimeo_uploaded||(t.data[e].assessment_status="ATTEMPTED")),parseFloat(t.data[e].video_uploaded_count)>0&&("0"!=t.data[e].is_completed&&0!=t.data[e].is_completed||"0"!=t.data[e].ftpto_vimeo_uploaded&&0!=t.data[e].ftpto_vimeo_uploaded||(t.data[e].assessment_status="ATTEMPTED"))),a.next=8;break;case 7:parseFloat(t.data[e].video_uploaded_count)==parseFloat(t.data[e].total_question)&&parseFloat(t.data[e].video_uploaded_count_ftp)==parseFloat(t.data[e].total_question)&&("0"!=t.data[e].is_completed&&0!=t.data[e].is_completed||"0"!=t.data[e].ftpto_vimeo_uploaded&&0!=t.data[e].ftpto_vimeo_uploaded||(t.data[e].assessment_status="ATTEMPTED")),parseFloat(t.data[e].video_uploaded_count)>0&&("0"!=t.data[e].is_completed&&0!=t.data[e].is_completed||"0"!=t.data[e].ftpto_vimeo_uploaded&&0!=t.data[e].ftpto_vimeo_uploaded||(t.data[e].assessment_status="ATTEMPTED"));case 8:case"end":return a.stop()}}),a,this)})))})).catch((function(t){})),c>0?(t.data[e].video_uploaded_count=c,n()):n()}else t.data[e].assessment_status="PENDING",n()}))}))},s=0;s<Object.keys(t.data).length;s++)o(s);Promise.all([a]).then((function(n){e.json_assessment_details=t.data,e.vimeo_credentials=t.vimeo_credentials}))}case 1:case"end":return n.stop()}}),n,this)})))}),(function(t){}),(function(){}));case 7:case"end":return t.stop()}}),t,this)})))}},{key:"open_demo_instruction",value:function(){return Object(r.a)(this,void 0,void 0,regeneratorRuntime.mark((function t(){var e,n,a;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.t0=this.modalCtrl,t.t1=b.a,t.t2={},t.t3=!0,t.next=6,this.modalCtrl.getTop();case 6:return t.t4=t.sent,t.t5={component:t.t1,componentProps:t.t2,swipeToClose:t.t3,presentingElement:t.t4},t.next=10,t.t0.create.call(t.t0,t.t5);case 10:return e=t.sent,t.next=13,e.present();case 13:return t.next=15,e.onWillDismiss();case 15:n=t.sent,(a=n.data)&&"Y"===a.assessment_indicator&&this.navCtrl.navigateRoot("/assessment-demo-quiz");case 18:case"end":return t.stop()}}),t,this)})))}},{key:"participate",value:function(t){return Object(r.a)(this,void 0,void 0,regeneratorRuntime.mark((function e(){var n,a,o,s,i=this;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if("1"!==t.otp_verified&&1!==t.otp_verified){e.next=29;break}if(0!==t.is_completed&&"0"!==t.is_completed){e.next=26;break}if(!(parseInt(t.total_attempts)<parseInt(t.attempts_allowed))){e.next=23;break}return e.t0=this.modalCtrl,e.t1=l.a,e.t2={assessment_data:t.instruction},e.t3=!0,e.next=9,this.modalCtrl.getTop();case 9:return e.t4=e.sent,e.t5={component:e.t1,componentProps:e.t2,swipeToClose:e.t3,presentingElement:e.t4},e.next=13,e.t0.create.call(e.t0,e.t5);case 13:return n=e.sent,e.next=16,n.present();case 16:return e.next=18,n.onWillDismiss();case 18:a=e.sent,(o=a.data)&&("Y"===(s=o.assessment_indicator)?this.atom.navigation_param_set({assessment_detail:t,vimeo_credentials:this.vimeo_credentials}).subscribe((function(t){i.navCtrl.navigateRoot("ios"==i.platform_used?"/assessment-quiz":"/assessment-diagnostics")}),(function(t){i.atom.presentToast("Application error, parameter failed to set.")})):"DEMO"===s&&this.open_demo_instruction()),e.next=24;break;case 23:this.atom.presentToast("Awarathon, Your maximum attempt limit completed.");case 24:e.next=27;break;case 26:this.atom.presentToast("Awarathon, Assessment completed.");case 27:e.next=30;break;case 29:this.atom.navigation_param_set(t).subscribe((function(t){i.navCtrl.navigateRoot("/assessment-otc")}),(function(t){i.atom.presentToast("Application error, parameter failed to set.")}));case 30:case"end":return e.stop()}}),e,this)})))}},{key:"read_more",value:function(t){return Object(r.a)(this,void 0,void 0,regeneratorRuntime.mark((function e(){var n;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.t0=this.modalCtrl,e.t1=c.a,e.t2={assessment_data:t},e.t3=!0,e.next=6,this.modalCtrl.getTop();case 6:return e.t4=e.sent,e.t5={component:e.t1,componentProps:e.t2,swipeToClose:e.t3,presentingElement:e.t4},e.next=10,e.t0.create.call(e.t0,e.t5);case 10:return n=e.sent,e.next=13,n.present();case 13:return e.abrupt("return",e.sent);case 14:case"end":return e.stop()}}),e,this)})))}},{key:"play_attemptted",value:function(t){return Object(r.a)(this,void 0,void 0,regeneratorRuntime.mark((function e(){var n,a=this;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:"1"===t.otp_verified||1===t.otp_verified?0===t.is_completed||"0"===t.is_completed?parseInt(t.total_attempts)<parseInt(t.attempts_allowed)?this.atom.navigation_param_set({assessment_detail:t,vimeo_credentials:this.vimeo_credentials}).subscribe((function(t){a.navCtrl.navigateRoot("ios"==a.platform_used?"/assessment-quiz":"/assessment-diagnostics")}),(function(t){a.atom.presentToast("Application error, parameter failed to set.")})):(n=0,this.database.storage_get("assessment").then((function(e){return Object(r.a)(a,void 0,void 0,regeneratorRuntime.mark((function a(){var o=this;return regeneratorRuntime.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:if(!(e&&Object.keys(e).length>0)){a.next=6;break}return a.next=3,Object.keys(e).forEach((function(a){return Object(r.a)(o,void 0,void 0,regeneratorRuntime.mark((function o(){return regeneratorRuntime.wrap((function(o){for(;;)switch(o.prev=o.next){case 0:if(o.t0=parseFloat(e[a].user_id)==parseFloat(this.json_profile.user_id),!o.t0){o.next=7;break}return o.next=4,e[a].assessment_id;case 4:o.t1=o.sent,o.t2=t.assessment_id,o.t0=o.t1===o.t2;case 7:if(o.t3=o.t0,!o.t3){o.next=10;break}n++;case 10:case"end":return o.stop()}}),o,this)})))}));case 3:parseInt(t.total_question)===n?this.atom.navigation_param_set({param_assessment_detail:t,param_vimeo_credentials:this.vimeo_credentials,param_total_attempts:t.total_attempts,param_attempts_allowed:t.attempts_allowed}).subscribe((function(t){o.navCtrl.navigateRoot("/assessment-preview")}),(function(t){o.atom.presentToast("Application error, parameter failed to set.")})):this.ionAlert.show_alert("Awarathon","You have made "+t.total_attempts+" successful attempts for assessment. The maximum attempts allowed for this assessment are "+t.attempts_allowed),a.next=7;break;case 6:this.ionAlert.show_alert("Awarathon","You have made "+t.total_attempts+" successful attempts for assessment. OR You are trying to play assessment on another device that has not stored your previous attempts. The maximum attempts allowed for this assessment are "+t.attempts_allowed);case 7:case"end":return a.stop()}}),a,this)})))})).catch((function(e){a.ionAlert.show_alert("Awarathon","You have made "+t.total_attempts+" successful attempts for assessment. OR You are trying to play assessment on another device that has not stored your previous attempts. The maximum attempts allowed for this assessment are "+t.attempts_allowed)}))):this.atom.presentToast("Awarathon, Assessment completed."):this.atom.navigation_param_set(t).subscribe((function(t){a.navCtrl.navigateRoot("/assessment-otc")}),(function(t){a.atom.presentToast("Application error, parameter failed to set.")}));case 1:case"end":return e.stop()}}),e,this)})))}},{key:"pending_message",value:function(){return Object(r.a)(this,void 0,void 0,regeneratorRuntime.mark((function t(){var e;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.t0=this.modalCtrl,t.t1=d.a,t.t2={popup_type:"PENDING"},t.t3=!0,t.next=6,this.modalCtrl.getTop();case 6:return t.t4=t.sent,t.t5={component:t.t1,componentProps:t.t2,swipeToClose:t.t3,presentingElement:t.t4},t.next=10,t.t0.create.call(t.t0,t.t5);case 10:return e=t.sent,t.next=13,e.present();case 13:return t.next=15,e.onWillDismiss();case 15:case"end":return t.stop()}}),t,this)})))}}]),t}(),x.\u0275fac=function(t){return new(t||x)(g.Lb(s.T),g.Lb(s.Q),g.Lb(s.R),g.Lb(s.S),g.Lb(g.z),g.Lb(s.A),g.Lb(_.a),g.Lb(p.a),g.Lb(u.a),g.Lb(f.a),g.Lb(m.a))},x.\u0275cmp=g.Fb({type:x,selectors:[["app-assessment-live"]],decls:6,vars:2,consts:[[1,"ion-no-margin","ion-no-padding"],["size","12","class","assessment-container",4,"ngFor","ngForOf"],["position","bottom",1,"ion-no-margin","ion-no-padding","ion-no-border","ion-no-shadow","internet-bottom-margin"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],["size","12",1,"assessment-container"],[1,"ion-no-margin","ion-no-padding","container-row"],["size","12",1,"badge-container"],["color","secondary",1,"assessment-badge-purple"],["size","12",1,"ion-no-margin","ion-no-padding"],[1,"assessment-title"],["size","12",1,"ion-no-margin","ion-no-padding","container-row"],[1,"assessment-subtitle"],[1,"ion-no-margin","ion-no-padding","top-mar-8","stat-white-box"],["size","4",1,"ion-no-margin","ion-no-padding","left-title"],["size","4",1,"ion-no-margin","ion-no-padding","middle-title"],["size","4",1,"ion-no-margin","ion-no-padding","right-title"],["size","4",1,"ion-no-margin","left-value"],[1,"left-value-span"],["size","4",1,"ion-no-margin","middle-value"],[1,"middle-value-span"],["size","4",1,"ion-no-margin","right-value"],[1,"right-value-span"],[1,"ion-no-margin","ion-no-padding","bottom-panel"],["size","4",1,"ion-no-margin","ion-no-padding"],["size","small","fill","solid","expand","block",1,"read-details-button",3,"click"],["size","8",1,"ion-no-margin","ion-no-padding"],["size","small","fill","solid","expand","block","class","participate-button",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block","class","preview-button",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block","class","inprogress-button",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block",1,"participate-button",3,"click"],["size","small","fill","solid","expand","block",1,"preview-button",3,"click"],["size","small","fill","solid","expand","block",1,"inprogress-button",3,"click"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(t,e){1&t&&(g.Ob(0,"ion-content"),g.Ob(1,"ion-list",0),g.lc(2,P,37,10,"ion-card",1),g.Nb(),g.Nb(),g.Ob(3,"ion-footer",2),g.Ob(4,"ion-grid",0),g.lc(5,w,3,3,"ion-row",3),g.Nb(),g.Nb()),2&t&&(g.Ab(2),g.bc("ngForOf",e.json_assessment_details),g.Ab(3),g.bc("ngIf",e.internet_indicator))},directives:[s.m,s.w,a.h,s.o,s.p,a.i,s.j,s.B,s.l,s.g,s.h],styles:["ion-card[_ngcontent-%COMP%]:last-child{margin-bottom:260px!important}.assessment-container[_ngcontent-%COMP%]{padding:0!important;margin:5px 0 0!important;height:260px;background-color:#fff;box-shadow:none;border-radius:0;border-width:0}.container-row[_ngcontent-%COMP%]{padding:5px 0 0 10px!important;width:99%}.assessment-badge-red[_ngcontent-%COMP%]{padding:4px 10px 3px!important;background-color:#ff866e;font-size:1.1rem;font-weight:600}.assessment-badge-green[_ngcontent-%COMP%]{background-color:#28c0a7}.assessment-badge-green[_ngcontent-%COMP%], .assessment-badge-purple[_ngcontent-%COMP%]{padding:4px 10px 3px!important;font-size:1rem;font-weight:400}.assessment-badge-purple[_ngcontent-%COMP%]{background-color:#54419a}.badge-container[_ngcontent-%COMP%]{padding:0!important;margin:0!important;text-align:left}.assessment-title[_ngcontent-%COMP%]{text-align:left;font-size:1.8rem;font-weight:600;color:#2f2f2f;line-height:2rem}.assessment-subtitle[_ngcontent-%COMP%]{text-align:left;font-size:1.2rem;font-weight:400;color:#686868}.stat-white-box[_ngcontent-%COMP%]{padding:0!important;background-color:#f8f8f8;height:80px;border-radius:0;box-shadow:none}.left-title[_ngcontent-%COMP%], .middle-title[_ngcontent-%COMP%], .right-title[_ngcontent-%COMP%]{text-align:left;font-size:1.4rem;font-weight:600;color:#909090;padding:10px 0 0 10px!important}.left-value[_ngcontent-%COMP%]{color:#24c3a7}.left-value[_ngcontent-%COMP%], .middle-value[_ngcontent-%COMP%]{text-align:left;font-size:3.5rem;font-weight:600;padding:0 0 0 10px!important}.middle-value[_ngcontent-%COMP%]{color:#de4f41}.right-value[_ngcontent-%COMP%]{text-align:left;font-size:3.5rem;font-weight:600;color:#1e1a55;padding:0 0 0 10px!important}.left-value-span[_ngcontent-%COMP%]{color:#54419a}.left-value-span[_ngcontent-%COMP%], .middle-value-span[_ngcontent-%COMP%]{text-align:left;font-size:2rem;font-weight:600}.middle-value-span[_ngcontent-%COMP%]{color:#3c4d6b}.right-value-span[_ngcontent-%COMP%]{text-align:left;font-size:2rem;font-weight:600;color:#1e1a55}.bottom-panel[_ngcontent-%COMP%]{padding:0!important;height:35px;background-color:#eeeced}.read-more[_ngcontent-%COMP%]{text-align:left;font-size:1.4rem;font-weight:600}.read-more-div[_ngcontent-%COMP%]{width:300px}.read-more-span[_ngcontent-%COMP%]{font-size:1.4rem;font-weight:600;padding-bottom:2px;border-bottom:1px solid #787878;line-height:40px;height:40px}.participate-button[_ngcontent-%COMP%]{--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary)}.participate-button[_ngcontent-%COMP%], .read-details-button[_ngcontent-%COMP%]{width:100%;height:35px;font-family:Roboto,sans-serif;font-size:1.4rem;font-weight:400;text-transform:none;--box-shadow:none;--border-radius:0px;--color:var(--ion-color-primary-contrast);margin:0}.read-details-button[_ngcontent-%COMP%]{--background:var(--ion-color-medium);--background-focused:var(--ion-color-medium);--background-hover:var(--ion-color-medium)}.inprogress-button[_ngcontent-%COMP%], .preview-button[_ngcontent-%COMP%]{width:100%;height:35px;font-family:Roboto,sans-serif;font-size:1.4rem;font-weight:400;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-yellow);--background-focused:var(--ion-color-yellow);--background-hover:var(--ion-color-yellow);--color:var(--ion-color-yellow-contrast);margin:0}"]}),x)}],N=((C=function t(){_classCallCheck(this,t)}).\u0275mod=g.Jb({type:C}),C.\u0275inj=g.Ib({factory:function(t){return new(t||C)},imports:[[i.i.forChild(T)],i.i]}),C),A=((E=function t(){_classCallCheck(this,t)}).\u0275mod=g.Jb({type:E}),E.\u0275inj=g.Ib({factory:function(t){return new(t||E)},imports:[[a.b,o.f,s.N,N]]}),E)}}]);