function _classCallCheck(t,n){if(!(t instanceof n))throw new TypeError("Cannot call a class as a function")}function _defineProperties(t,n){for(var e=0;e<n.length;e++){var o=n[e];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}function _createClass(t,n,e){return n&&_defineProperties(t.prototype,n),e&&_defineProperties(t,e),t}(window.webpackJsonp=window.webpackJsonp||[]).push([[49],{JYB0:function(t,n,e){"use strict";e.r(n),e.d(n,"GoLivePageModule",(function(){return _}));var o,i,a,r=e("TEn/"),s=e("ofXK"),c=e("3Pt+"),l=e("tyNb"),b=e("1vjY"),p=e("X7yx"),d=e("5dVO"),h=e("riPR"),m=e("ZJFI"),f=e("fXoL"),g=[{path:"go-live",component:(o=function(){function t(n,e,o,i,a,r,s,c){_classCallCheck(this,t),this.platform=n,this.menu=e,this.navCtrl=o,this.events=i,this.ionLoader=a,this.ionAlert=r,this.database=s,this.atom=c,this.json_profile={user_id:"",compan_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.workshop_tab="bottom-tab-inactive",this.golive_tab="bottom-tab-active",this.initializeApp()}return _createClass(t,[{key:"ionViewWillEnter",value:function(){this.selected_tab=this.tabs.getSelected()}},{key:"initializeApp",value:function(){var t=this;this.platform.ready().then((function(n){t.database.storage_get("device").then((function(n){n.token_no&&n.payload&&(t.json_profile=n)})).catch((function(t){}))}))}},{key:"refresh_page",value:function(){this.selected_tab=this.tabs.getSelected(),"assessment-live"==this.selected_tab&&this.events.publish("refresh_assessment_live",{}),"assessment-history"==this.selected_tab&&this.events.publish("refresh_assessment_history",{})}},{key:"open_browser",value:function(t){if(""!==t)return window.open(t,"_system","location=yes"),!1}},{key:"open_dashboard",value:function(){this.navCtrl.navigateRoot("/dashboard/workshop-live")}},{key:"open_golive",value:function(){this.navCtrl.navigateRoot("/go-live/assessment-live")}}]),t}(),o.\u0275fac=function(t){return new(t||o)(f.Lb(r.T),f.Lb(r.Q),f.Lb(r.S),f.Lb(h.a),f.Lb(d.a),f.Lb(p.a),f.Lb(m.a),f.Lb(b.a))},o.\u0275cmp=f.Fb({type:o,selectors:[["app-go-live"]],viewQuery:function(t,n){var e;1&t&&(f.qc(r.J,!0),f.qc(r.H,!0)),2&t&&(f.ec(e=f.Xb())&&(n.tabs=e.first),f.ec(e=f.Xb())&&(n.tabBar=e.first))},decls:26,vars:1,consts:[[1,"ion-no-border","ion-no-shadow"],["slot","start"],["autoHide","false"],["slot","end",2,"cursor","pointer",3,"click"],["slot","icon-only",1,"fa","fa-refresh","lnr-font-size"],[1,"ion-no-margin","ion-no-padding"],[1,"dbavatar_background"],["size","12",1,"dbavatar-container"],["src","assets/images/drreddy-pitchperfect-logo.png","alt",""],["size","12",1,"dbprofile-name-container"],[1,"dbprofile-name"],["slot","top"],["tab","assessment-live"],["tab","assessment-history"],[1,"bottom-tab-container"],["size","12",1,"ion-no-margin","ion-no-padding",2,"text-align","center !important"],["alt","","src","assets/images/footer_logo.png",1,"footer-img",2,"text-align","center !important"]],template:function(t,n){1&t&&(f.Ob(0,"ion-header",0),f.Ob(1,"ion-toolbar"),f.Ob(2,"ion-buttons",1),f.Mb(3,"ion-menu-button",2),f.Nb(),f.Ob(4,"ion-buttons",3),f.Wb("click",(function(){return n.refresh_page()})),f.Mb(5,"ion-icon",4),f.Nb(),f.Nb(),f.Ob(6,"ion-grid",5),f.Ob(7,"ion-row",6),f.Ob(8,"ion-col",7),f.Mb(9,"img",8),f.Nb(),f.Ob(10,"ion-col",9),f.Ob(11,"span",10),f.mc(12),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Ob(13,"ion-tabs"),f.Ob(14,"ion-tab-bar",11),f.Ob(15,"ion-tab-button",12),f.Ob(16,"ion-label"),f.mc(17,"Live"),f.Nb(),f.Nb(),f.Ob(18,"ion-tab-button",13),f.Ob(19,"ion-label"),f.mc(20,"Reports"),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Ob(21,"div",14),f.Ob(22,"ion-grid",5),f.Ob(23,"ion-row",5),f.Ob(24,"ion-col",15),f.Mb(25,"ion-img",16),f.Nb(),f.Nb(),f.Nb(),f.Nb()),2&t&&(f.Ab(12),f.nc(n.json_profile.fullname))},directives:[r.q,r.M,r.i,r.y,r.r,r.p,r.B,r.l,r.J,r.H,r.I,r.v,r.s],styles:["ion-tabs[_ngcontent-%COMP%]{top:195px}ion-tab-bar[_ngcontent-%COMP%]{height:45px}ion-toolbar[_ngcontent-%COMP%]{--background:var(--ion-color-primary);--color:var(--ion-color-primary-contrast)}ion-tab-button[_ngcontent-%COMP%]{border-right:1px solid #eaeaea}ion-tab-button[_ngcontent-%COMP%]:last-child{border-right:0}.footer-img[_ngcontent-%COMP%]{width:auto!important;height:22px!important;display:inline-block!important}.lnr-font-size[_ngcontent-%COMP%]{font-size:1.8rem!important;margin-right:15px!important;line-height:1!important}.dbavatar_background[_ngcontent-%COMP%]{background-size:cover;height:150px;z-index:1!important;background-repeat:no-repeat;background-position:top;transform:translate(0);text-align:center;background:#92949c}.dbavatar-container[_ngcontent-%COMP%]{width:145px;height:auto;overflow:hidden;margin:0 auto;font-size:0}.dbavatar-container[_ngcontent-%COMP%]   img[_ngcontent-%COMP%]{width:100%;height:150px}.dbprofile-name-container[_ngcontent-%COMP%]{margin-top:5px 0}.dbcompany-name-container[_ngcontent-%COMP%], .dbprofile-name-container[_ngcontent-%COMP%]{padding:0!important;display:flex!important;justify-content:center}.dbcompany-name-container[_ngcontent-%COMP%]{margin-top:-5px}.dbprofile-name[_ngcontent-%COMP%]{height:20px;font-size:2rem;font-weight:700;color:#fff;padding:0!important;margin:-50px!important}.dbcompany-name[_ngcontent-%COMP%]{height:14px;font-size:1.4rem;font-weight:400;color:#fff;padding:0!important;margin:-20px!important}.bottom-tab-container[_ngcontent-%COMP%]{position:absolute;display:flex;width:100%;height:30px;background-color:var(--ion-color-primary-contrast);bottom:0}"]}),o),children:[{path:"assessment-live",children:[{path:"",loadChildren:function(){return Promise.all([e.e(0),e.e(36)]).then(e.bind(null,"zbXe")).then((function(t){return t.AssessmentLivePageModule}))}}]},{path:"assessment-history",children:[{path:"",loadChildren:function(){return Promise.all([e.e(0),e.e(34)]).then(e.bind(null,"8FUN")).then((function(t){return t.AssessmentHistoryPageModule}))}}]},{path:"",redirectTo:"/go-live/assessment-live",pathMatch:"full"}]},{path:"",redirectTo:"/go-live/assessment-live",pathMatch:"full"}],u=((a=function t(){_classCallCheck(this,t)}).\u0275mod=f.Jb({type:a}),a.\u0275inj=f.Ib({factory:function(t){return new(t||a)},imports:[[l.i.forChild(g)],l.i]}),a),_=((i=function t(){_classCallCheck(this,t)}).\u0275mod=f.Jb({type:i}),i.\u0275inj=f.Ib({factory:function(t){return new(t||i)},imports:[[r.N,s.b,c.f,u]]}),i)}}]);