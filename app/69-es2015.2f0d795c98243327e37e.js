(window.webpackJsonp=window.webpackJsonp||[]).push([[69],{fNLi:function(o,n,t){"use strict";t.r(n),t.d(n,"WorkshopLivePageModule",(function(){return N}));var i=t("ofXK"),e=t("3Pt+"),r=t("TEn/"),a=t("tyNb"),c=t("mrSG"),s=t("dt9i"),l=t("kUAt"),p=t("1vjY"),d=t("X7yx"),b=t("5dVO"),g=t("riPR"),m=t("ZJFI"),h=t("fXoL");function _(o,n){if(1&o&&(h.Ob(0,"ion-col",34),h.mc(1),h.Nb()),2&o){const o=h.Yb().$implicit;h.Ab(1),h.pc(" : ",o.pre_start_date_dmy," | ",o.pre_start_time," ")}}function f(o,n){if(1&o&&(h.Ob(0,"ion-col",34),h.mc(1),h.Nb()),2&o){const o=h.Yb().$implicit;h.Ab(1),h.pc(" : ",o.post_start_date_dmy," | ",o.post_start_time," ")}}function u(o,n){if(1&o&&(h.Ob(0,"ion-col",34),h.mc(1),h.Nb()),2&o){const o=h.Yb(2).$implicit;h.Ab(1),h.pc(" : ",o.pre_end_date_dmy," | ",o.pre_end_time," ")}}function O(o,n){if(1&o&&(h.Ob(0,"ion-col",34),h.mc(1),h.Nb()),2&o){const o=h.Yb(2).$implicit;h.Ab(1),h.pc(" : ",o.post_end_date_dmy," | ",o.post_end_time," ")}}function k(o,n){if(1&o&&(h.Ob(0,"ion-row",2),h.Ob(1,"ion-col",15),h.mc(2," END "),h.Nb(),h.lc(3,u,2,2,"ion-col",16),h.lc(4,O,2,2,"ion-col",16),h.Nb()),2&o){const o=h.Yb().$implicit;h.Ab(3),h.bc("ngIf","PRE"==o.workshop_session),h.Ab(1),h.bc("ngIf","POST"==o.workshop_session)}}function w(o,n){if(1&o){const o=h.Pb();h.Ob(0,"ion-button",35),h.Wb("click",(function(){h.gc(o);const n=h.Yb().$implicit;return h.Yb().participate(n)})),h.mc(1,"PARTICIPATE"),h.Nb()}}function v(o,n){if(1&o){const o=h.Pb();h.Ob(0,"ion-button",36),h.Wb("click",(function(){h.gc(o);const n=h.Yb().$implicit;return h.Yb().play_quiz(n)})),h.mc(1,"PLAYING"),h.Nb()}}function x(o,n){if(1&o){const o=h.Pb();h.Ob(0,"ion-button",37),h.Wb("click",(function(){h.gc(o);const n=h.Yb().$implicit;return h.Yb().alert_quiz_completed(n.workshop_name)})),h.mc(1,"COMPLETED"),h.Nb()}}function C(o,n){if(1&o){const o=h.Pb();h.Ob(0,"ion-card",4),h.Ob(1,"ion-card-content",2),h.Ob(2,"ion-row",2),h.Ob(3,"span",5),h.mc(4,"LIVE NOW"),h.Nb(),h.Ob(5,"ion-col",6),h.Ob(6,"div",7),h.Mb(7,"img",8),h.Nb(),h.Nb(),h.Ob(8,"ion-col",9),h.Ob(9,"span",10),h.Wb("click",(function(){h.gc(o);const t=n.$implicit;return h.Yb().presentModal(t)})),h.Ob(10,"a",11),h.Mb(11,"ion-icon",12),h.Nb(),h.Nb(),h.Ob(12,"ion-row",13),h.Ob(13,"ion-col",14),h.Ob(14,"ion-row",2),h.Ob(15,"ion-col",15),h.mc(16," START "),h.Nb(),h.lc(17,_,2,2,"ion-col",16),h.lc(18,f,2,2,"ion-col",16),h.Nb(),h.lc(19,k,5,2,"ion-row",17),h.Ob(20,"ion-row",2),h.Ob(21,"ion-col",18),h.mc(22," SESSION "),h.Nb(),h.Ob(23,"ion-col",19),h.mc(24),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Ob(25,"ion-row",20),h.Ob(26,"ion-col",14),h.Ob(27,"p",21),h.mc(28),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Ob(29,"ion-row",2),h.Ob(30,"ion-col",14),h.Ob(31,"ion-row",22),h.Ob(32,"ion-col",23),h.Ob(33,"ion-row",2),h.Ob(34,"ion-col",24),h.mc(35),h.Nb(),h.Nb(),h.Ob(36,"ion-row",2),h.Ob(37,"ion-col",25),h.mc(38," CORRECT "),h.Nb(),h.Nb(),h.Nb(),h.Ob(39,"ion-col",23),h.Ob(40,"ion-row",2),h.Ob(41,"ion-col",26),h.mc(42),h.Nb(),h.Nb(),h.Ob(43,"ion-row",2),h.Ob(44,"ion-col",25),h.mc(45," WRONG "),h.Nb(),h.Nb(),h.Nb(),h.Ob(46,"ion-col",23),h.Ob(47,"ion-row",2),h.Ob(48,"ion-col",27),h.mc(49),h.Nb(),h.Nb(),h.Ob(50,"ion-row",2),h.Ob(51,"ion-col",25),h.mc(52," PREFERENCE "),h.Nb(),h.Nb(),h.Nb(),h.Ob(53,"ion-col",28),h.Wb("click",(function(){h.gc(o);const t=n.$implicit;return h.Yb().graph_topic_subtopic_wise(t)})),h.Ob(54,"ion-row",2),h.Ob(55,"ion-col",29),h.Mb(56,"img",30),h.Nb(),h.Nb(),h.Ob(57,"ion-row",2),h.Ob(58,"ion-col",25),h.mc(59," VIEW REPORTS "),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Ob(60,"ion-row",2),h.Ob(61,"ion-col",14),h.lc(62,w,2,0,"ion-button",31),h.lc(63,v,2,0,"ion-button",32),h.lc(64,x,2,0,"ion-button",33),h.Nb(),h.Nb(),h.Nb(),h.Nb()}if(2&o){const o=n.$implicit;h.Ab(7),h.cc("src",o.workshop_image,h.ic),h.Ab(10),h.bc("ngIf","PRE"==o.workshop_session),h.Ab(1),h.bc("ngIf","POST"==o.workshop_session),h.Ab(1),h.bc("ngIf",0==o.end_time_display),h.Ab(5),h.oc(" : ",o.workshop_session," "),h.Ab(4),h.oc(" ",o.workshop_name," "),h.Ab(7),h.oc(" ",o.score_correct," "),h.Ab(7),h.oc(" ",o.score_wrong," "),h.Ab(7),h.oc(" ",o.score_preference," "),h.Ab(13),h.bc("ngIf","N"==o.is_registered),h.Ab(1),h.bc("ngIf",!("0"!=o.is_registered&&"1"!=o.is_registered||0!=o.all_questions_fired&&0!=o.all_feedbacks_fired)),h.Ab(1),h.bc("ngIf",("0"==o.is_registered||"1"==o.is_registered)&&1==o.all_questions_fired&&1==o.all_feedbacks_fired)}}function P(o,n){if(1&o&&(h.Ob(0,"ion-row",38),h.Ob(1,"ion-col",39),h.mc(2),h.Nb(),h.Nb()),2&o){const o=h.Yb();h.Ab(1),h.Cb(o.internet_background),h.Ab(1),h.oc(" ",o.internet_text," ")}}var z=function(o){return o[o.Online=0]="Online",o[o.Offline=1]="Offline",o}({});const y=[{path:"",component:(()=>{class o{constructor(o,n,t,i,e,r,a,c,s,l,p){this.platform=o,this.menu=n,this.modalCtrl=t,this.navCtrl=i,this.zone=e,this.routerOutlet=r,this.events=a,this.ionLoader=c,this.ionAlert=s,this.database=l,this.atom=p,this.btn_isclicked=!1,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.workshops_live_form={action:"workshop_live",payload:""},this.workshop_no_otc_form={action:"workshop_without_otc",payload:"",workshop_id:"",workshop_session:""},this.json_workshop_ongoing=[],this.internet_indicator=!1,this.menu.enable(!0),this.initializeApp()}ngOnInit(){}initializeApp(){this.platform.ready().then(o=>{window.addEventListener("online",()=>{this.internetStatus=z.Online,this.zone.run(()=>{this.internet_background="int-green",this.internet_text="YOU ARE ONLINE"}),this.internet_timer=setTimeout(()=>{this.zone.run(()=>{this.internet_indicator=!1})},1e4)}),window.addEventListener("offline",()=>{this.internetStatus=z.Offline,this.zone.run(()=>{this.internet_background="int-red",this.internet_text="YOU ARE OFFLINE",this.internet_indicator=!0})}),this.database.storage_get("device").then(o=>{o.token_no&&o.payload&&(this.json_profile=o,this.workshops_live_form.payload=o.payload,this.workshop_no_otc_form.payload=o.payload,this.fetch_workshops_live(),this.events.subscribe("refresh_workshops_live",o=>{this.fetch_workshops_live()}))}).catch(o=>{})})}ionViewDidLoad(){try{this.events.unsubscribe("loginnetwork:online"),this.events.unsubscribe("loginnetwork:offline"),clearTimeout(this.internet_timer)}catch(o){}}fetch_workshops_live(){""==this.workshops_live_form.payload?this.atom.presentToast("Offline API Token missing."):this.atom.http_post(this.workshops_live_form).subscribe(o=>{1==o.success&&(this.json_workshop_ongoing=o.data)},o=>{},()=>{})}presentModal(o){return Object(c.a)(this,void 0,void 0,(function*(){const n=yield this.modalCtrl.create({component:s.a,componentProps:o,swipeToClose:!0,presentingElement:yield this.modalCtrl.getTop()});return yield n.present()}))}participate(o){return Object(c.a)(this,void 0,void 0,(function*(){!1===this.btn_isclicked&&(this.btn_isclicked=!0,1==o.otp_required?(this.btn_isclicked=!1,this.atom.navigation_param_set(o).subscribe(o=>{this.navCtrl.navigateRoot("/workshop-otc")},o=>{this.atom.presentToast("Application error, parameter failed to set.")})):o.information_form_id>0&&"N"==o.information_form_filled?(this.btn_isclicked=!1,this.atom.navigation_param_set(o).subscribe(o=>{this.navCtrl.navigateRoot("/information-form")},o=>{this.atom.presentToast("Application error, parameter failed to set.")})):""!=o.id?(this.btn_isclicked=!1,this.workshop_no_otc_form.workshop_id=o.id,this.workshop_no_otc_form.workshop_session=o.workshop_session,this.ionLoader.showLoader().then(()=>{this.atom.http_post(this.workshop_no_otc_form).subscribe(n=>{1==n.success?(this.ionLoader.hideLoader(),this.atom.navigation_param_set(o).subscribe(o=>{this.navCtrl.navigateRoot("/quiz")},o=>{this.atom.presentToast("Application error, parameter failed to set.")})):(this.ionLoader.hideLoader(),this.atom.presentToast(n.message))},o=>{this.ionLoader.hideLoader(),this.btn_isclicked=!1},()=>{this.ionLoader.hideLoader(),this.btn_isclicked=!1})}).catch(()=>{this.btn_isclicked=!1})):(this.btn_isclicked=!1,this.atom.presentToast("Workshop id misssing, Please try again")))}))}play_quiz(o){o.information_form_id>0&&"N"==o.information_form_filled?this.atom.navigation_param_set(o).subscribe(o=>{this.navCtrl.navigateRoot("/information-form")},o=>{this.atom.presentToast("Application error, parameter failed to set.")}):this.atom.navigation_param_set(o).subscribe(o=>{this.navCtrl.navigateRoot("/quiz")},o=>{this.atom.presentToast("Application error, parameter failed to set.")})}alert_quiz_completed(o){this.ionAlert.show_alert("Workshop - Completed","Workshop is completed, Thank you for participating in the workshop "+o+".").then(()=>{}).catch(()=>{})}refresh_page(){}graph_topic_subtopic_wise(o){return Object(c.a)(this,void 0,void 0,(function*(){if(o.score_correct>0||o.score_wrong>0||o.score_time_out>0){if(1==o.all_questions_fired&&1==o.all_feedbacks_fired){const n=yield this.modalCtrl.create({component:l.a,componentProps:{workshop_details:o,graph_type:"completed"},swipeToClose:!0,presentingElement:yield this.modalCtrl.getTop()});return yield n.present()}{const n=yield this.modalCtrl.create({component:l.a,componentProps:{workshop_details:o,graph_type:"live"},swipeToClose:!0,presentingElement:yield this.modalCtrl.getTop()});return yield n.present()}}this.ionAlert.show_alert("Oops!","The graph cannot be viewed as workshop not played.")}))}}return o.\u0275fac=function(n){return new(n||o)(h.Lb(r.T),h.Lb(r.Q),h.Lb(r.R),h.Lb(r.S),h.Lb(h.z),h.Lb(r.A),h.Lb(g.a),h.Lb(b.a),h.Lb(d.a),h.Lb(m.a),h.Lb(p.a))},o.\u0275cmp=h.Fb({type:o,selectors:[["app-workshop-live"]],decls:6,vars:2,consts:[["size","12","class","workshop-container",4,"ngFor","ngForOf"],["position","bottom",1,"ion-no-margin","ion-no-padding","ion-no-border","ion-no-shadow","internet-bottom-margin"],[1,"ion-no-margin","ion-no-padding"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],["size","12",1,"workshop-container"],[1,"wrk-status"],["size","6","size-xs","6","size-sm","6","size-md","3","size-lg","3","size-xl","3",1,"gallery"],[1,"gallery-prod-box"],["cache","true","alt","",3,"src"],["size","6","size-xs","6","size-sm","6","size-md","9","size-lg","9","size-xl","9",1,"ion-no-margin","ion-no-padding"],[1,"wrkshp-more-container",3,"click"],[1,"pop-a"],["name","ellipsis-vertical-outline"],[1,"ion-no-margin","ion-no-padding","workshop-date-container"],["size","12",1,"ion-no-margin","ion-no-padding"],["size","3","size-xs","3","size-sm","3","size-md","2","size-lg","2","size-xl","2",1,"ion-no-margin","ion-no-padding","workshop-date"],["size","9","size-xs","9","size-sm","9","size-md","10","size-lg","10","size-xl","10","class","ion-no-margin ion-no-padding workshop-date",4,"ngIf"],["class","ion-no-margin ion-no-padding",4,"ngIf"],["size","3","size-xs","3","size-sm","3","size-md","2","size-lg","2","size-xl","2",1,"ion-no-margin","ion-no-padding","workshop-type"],["size","9","size-xs","9","size-sm","9","size-md","10","size-lg","10","size-xl","10",1,"ion-no-margin","ion-no-padding","workshop-type-value"],[1,"ion-no-margin","ion-no-padding","workshop-title-container"],[1,"workshop-title"],[1,"ion-no-margin","ion-no-padding","score-main-wrapper"],["size","3",1,"ion-no-margin","ion-no-padding","correct-label"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont","recent-value","correct-color"],["size","12",1,"ion-no-margin","ion-no-padding","report-label"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont","recent-value","incorrect-color"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont","recent-value","timeout-color"],["size","3",1,"ion-no-margin","ion-no-padding",3,"click"],["size","12",1,"ion-no-margin","ion-no-padding","graph-cont"],["src","assets/images/graph.png","alt","",1,"report-icon"],["size","small","fill","solid","expand","block","class","participate-button ion-no-margin",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block","class","playing-button ion-no-margin",3,"click",4,"ngIf"],["size","small","fill","solid","expand","block","class","completed-button ion-no-margin",3,"click",4,"ngIf"],["size","9","size-xs","9","size-sm","9","size-md","10","size-lg","10","size-xl","10",1,"ion-no-margin","ion-no-padding","workshop-date"],["size","small","fill","solid","expand","block",1,"participate-button","ion-no-margin",3,"click"],["size","small","fill","solid","expand","block",1,"playing-button","ion-no-margin",3,"click"],["size","small","fill","solid","expand","block",1,"completed-button","ion-no-margin",3,"click"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(o,n){1&o&&(h.Ob(0,"ion-content"),h.Ob(1,"ion-list"),h.lc(2,C,65,12,"ion-card",0),h.Nb(),h.Nb(),h.Ob(3,"ion-footer",1),h.Ob(4,"ion-grid",2),h.lc(5,P,3,3,"ion-row",3),h.Nb(),h.Nb()),2&o&&(h.Ab(2),h.bc("ngForOf",n.json_workshop_ongoing),h.Ab(3),h.bc("ngIf",n.internet_indicator))},directives:[r.m,r.w,i.h,r.o,r.p,i.i,r.j,r.k,r.B,r.l,r.r,r.h],styles:["div[_ngcontent-%COMP%]{border:1px solid #e4e4e4;width:100%;height:100px;text-align:center}div[_ngcontent-%COMP%]:last-child, ion-list[_ngcontent-%COMP%]{margin-bottom:calc(100% - 100px)}ion-card[_ngcontent-%COMP%]{position:relative;border-radius:0;-moz-border-radius:0;-o-border-radius:0;-webkit-border-radius:0;box-shadow:0 0 0 1px hsla(0,0%,56.9%,.14)}.wrkshp-more-container[_ngcontent-%COMP%]{position:absolute;background-color:transparent;width:20px;height:20px;overflow:hidden;top:5px;right:4px;text-align:center;z-index:1}.pop-a[_ngcontent-%COMP%]{text-align:center;vertical-align:middle;margin:0;padding:0}.pop-icon[_ngcontent-%COMP%]{position:relative;padding:0;font-size:2rem;color:#353535;margin:5px 0 0}ion-card[_ngcontent-%COMP%]   img[_ngcontent-%COMP%]{opacity:.9}ion-card[_ngcontent-%COMP%]:last-of-type{margin-bottom:3!important}.wrk-status[_ngcontent-%COMP%], .wrk-status-completed[_ngcontent-%COMP%]{position:absolute;background-color:#e6ca0e;top:0;font-size:1.1rem;font-weight:600;color:#222;height:28px;width:80px;margin:5px 0 0 5px;z-index:2;padding:8px;text-align:center}.cover[_ngcontent-%COMP%]{width:100%!important;height:auto!important;z-index:1!important}.card-content[_ngcontent-%COMP%]{padding:0!important;margin:0!important}.workshop-container[_ngcontent-%COMP%]{padding:0!important;margin:10px 0 0!important}.participate-button[_ngcontent-%COMP%]{--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary);--background-activated:var(--ion-color-primary)}.participate-button[_ngcontent-%COMP%], .playing-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.5rem;font-weight:500;text-transform:none;--box-shadow:none;--border-radius:0px;--color:var(--ion-color-primary-contrast);height:35px!important}.playing-button[_ngcontent-%COMP%]{--background:var(--ion-color-playing);--background-focused:var(--ion-color-playing);--background-hover:var(--ion-color-playing);--background-activated:var(--ion-color-playing)}.completed-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.5rem;font-weight:500;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-teal);--background-focused:var(--ion-color-teal);--background-hover:var(--ion-color-teal);--background-activated:var(--ion-color-teal);--color:var(--ion-color-primary-contrast);height:35px!important}.overlay-text[_ngcontent-%COMP%]{top:0;position:absolute;background-size:cover;background-attachment:fixed;background-repeat:no-repeat;background-position:top;transform:translate(0);width:100%;height:200px!important}.workshop-date-container[_ngcontent-%COMP%]{margin:5px 0 0 10px!important;padding:0!important}.workshop-date[_ngcontent-%COMP%], .workshop-type[_ngcontent-%COMP%]{color:#222}.workshop-date[_ngcontent-%COMP%], .workshop-type[_ngcontent-%COMP%], .workshop-type-value[_ngcontent-%COMP%]{padding:0!important;margin:0!important;font-size:1rem;font-weight:700}.workshop-type-value[_ngcontent-%COMP%]{color:#fe773d}.workshop-title-container[_ngcontent-%COMP%]{margin:0 0 0 10px!important;padding:0!important}.workshop-title[_ngcontent-%COMP%]{padding:0!important;margin:10px 0 0!important;font-size:1.5rem;font-weight:700;color:#222}.black-overlay[_ngcontent-%COMP%]{position:relative;background-color:#000;opacity:.7}.recent-main-wrapper[_ngcontent-%COMP%]{background-color:#f7f7f7;border:1px solid #eee;height:150px}.recent-container-top[_ngcontent-%COMP%]{border-bottom:1px solid #e2e0e1}.recent-container-bottom[_ngcontent-%COMP%], .recent-container-top[_ngcontent-%COMP%]{height:75px;border-left:1px solid #e2e0e1}.recent-heading[_ngcontent-%COMP%]{vertical-align:middle;text-align:center;font-size:.8em;margin:0}.recent-title[_ngcontent-%COMP%]{font-size:1.2em}.recent-label[_ngcontent-%COMP%], .recent-title[_ngcontent-%COMP%]{text-align:center;font-weight:700;margin:0}.recent-label[_ngcontent-%COMP%]{vertical-align:middle;font-size:.8em;padding:15px}.recent-value[_ngcontent-%COMP%]{font-size:3rem!important;font-weight:700}.recent-orange[_ngcontent-%COMP%]{color:#fe773d}.recent-green[_ngcontent-%COMP%]{color:#47cead}.score-main-wrapper[_ngcontent-%COMP%]{margin:0;padding:0!important;background-color:#fcfcfe;border-top:1px solid #dbdbdd;height:60px}.button-main-wrapper[_ngcontent-%COMP%]{padding:8px 0 0 5px!important;background-color:#fcfcfe;border:4px solid #fcfcfe;border-top:1px solid #dbdbdd;height:60px}.correct-label[_ngcontent-%COMP%], .incorrect-label[_ngcontent-%COMP%], .total-label[_ngcontent-%COMP%]{margin:0;border-right:1px solid #dbdbdd}.correct-label[_ngcontent-%COMP%], .incorrect-label[_ngcontent-%COMP%], .report-label[_ngcontent-%COMP%], .total-label[_ngcontent-%COMP%]{vertical-align:middle;text-align:center;font-size:1.1rem;font-weight:700;color:#222}.report-label[_ngcontent-%COMP%]{margin:0!important}.graph-cont[_ngcontent-%COMP%]{text-align:center!important;width:100%;height:39px;overflow:hidden;margin:5px 0 0!important}.report-icon[_ngcontent-%COMP%]{background:transparent;width:40px;height:auto;margin:0 auto!important}.timeout-color[_ngcontent-%COMP%]{color:#222}.incorrect-color[_ngcontent-%COMP%]{color:#fe7638}.correct-color[_ngcontent-%COMP%]{color:#45cfad}.box[_ngcontent-%COMP%]{background:#fff}.advt-footer[_ngcontent-%COMP%], .advt-img[_ngcontent-%COMP%]{height:50px}.advt-img[_ngcontent-%COMP%]{width:100%!important;background-size:cover;-o-object-fit:cover!important;object-fit:cover!important;background-attachment:fixed;background-repeat:no-repeat;background-position:top;transform:translate(0);text-align:center}.gallery[_ngcontent-%COMP%]{float:left;height:130px;margin:0!important;padding:0!important;overflow:hidden;background-color:#bebebe}.gallery[_ngcontent-%COMP%]   .gallery-prod-box[_ngcontent-%COMP%]{height:130px;max-height:130px;line-height:145px;text-align:center;overflow:hidden}.gallery[_ngcontent-%COMP%]   .gallery-prod-box[_ngcontent-%COMP%]   img[_ngcontent-%COMP%]{text-align:center;vertical-align:middle;overflow:hidden;display:inline-block}"]}),o})()}];let M=(()=>{class o{}return o.\u0275mod=h.Jb({type:o}),o.\u0275inj=h.Ib({factory:function(n){return new(n||o)},imports:[[a.i.forChild(y)],a.i]}),o})(),N=(()=>{class o{}return o.\u0275mod=h.Jb({type:o}),o.\u0275inj=h.Ib({factory:function(n){return new(n||o)},imports:[[i.b,e.f,r.N,M]]}),o})()}}]);