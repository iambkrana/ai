function _classCallCheck(n,o){if(!(n instanceof o))throw new TypeError("Cannot call a class as a function")}function _defineProperties(n,o){for(var t=0;t<o.length;t++){var i=o[t];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(n,i.key,i)}}function _createClass(n,o,t){return o&&_defineProperties(n.prototype,o),t&&_defineProperties(n,t),n}(window.webpackJsonp=window.webpackJsonp||[]).push([[48],{kgyS:function(n,o,t){"use strict";t.r(o),t.d(o,"ForgotPageModule",(function(){return N}));var i=t("ofXK"),e=t("3Pt+"),r=t("TEn/"),a=t("tyNb"),c=t("X7yx"),s=t("5dVO"),l=t("riPR"),d=t("ZJFI"),b=t("1vjY"),f=t("fXoL"),g=t("tk/3");function u(n,o){1&n&&(f.Ob(0,"div",25),f.mc(1," PLEASE WAIT\xa0\xa0"),f.Mb(2,"ion-spinner",26),f.Nb())}function p(n,o){1&n&&(f.Ob(0,"div",25),f.mc(1," RESET PASSWORD "),f.Nb())}function m(n,o){if(1&n&&(f.Ob(0,"ion-row",27),f.Ob(1,"ion-col",28),f.mc(2),f.Nb(),f.Nb()),2&n){var t=f.Yb();f.Ab(1),f.Cb(t.internet_background),f.Ab(1),f.oc(" ",t.internet_text," ")}}var h,_,O,k=function(n){return n[n.Online=0]="Online",n[n.Offline=1]="Offline",n}({}),v=[{path:"",component:(h=function(){function n(o,t,i,e,r,a,c,s,l,d,b){_classCallCheck(this,n),this.platform=o,this.navCtrl=t,this.menu=i,this.loadingCtrl=e,this.zone=r,this.http=a,this.events=c,this.ionLoader=s,this.ionAlert=l,this.database=d,this.atom=b,this.btn_isclicked=!1,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.forgot_form={action:"forgot",email:""},this.internet_indicator=!1,this.menu.enable(!1),this.initializeApp()}return _createClass(n,[{key:"ngOnInit",value:function(){}},{key:"initializeApp",value:function(){var n=this;this.platform.ready().then((function(o){window.addEventListener("online",(function(){n.internetStatus=k.Online,n.zone.run((function(){n.internet_background="int-green",n.internet_text="YOU ARE ONLINE"})),n.internet_timer=setTimeout((function(){n.zone.run((function(){n.internet_indicator=!1}))}),1e4)})),window.addEventListener("offline",(function(){n.internetStatus=k.Offline,n.zone.run((function(){n.internet_background="int-red",n.internet_text="YOU ARE OFFLINE",n.internet_indicator=!0}))}))}))}},{key:"forgot",value:function(n){var o=this;!1===this.btn_isclicked&&(""==this.forgot_form.email?this.atom.presentToast("Email Address Is Required"):(this.btn_isclicked=!0,n.valid&&this.ionLoader.showLoader().then((function(){o.atom.http_post(o.forgot_form).subscribe((function(n){1==n.success?(o.ionLoader.hideLoader(),o.btn_isclicked=!1,o.ionAlert.show_alert("Password Reset","An email containing information on how to reset your password has been sent to "+o.forgot_form.email+".")):(o.ionLoader.hideLoader(),o.btn_isclicked=!1,o.atom.presentToast(n.message))}),(function(n){o.ionLoader.hideLoader(),o.btn_isclicked=!1;var t=JSON.stringify(n);o.atom.presentToast(t)}),(function(){o.ionLoader.hideLoader(),o.btn_isclicked=!1}))})).catch((function(){o.btn_isclicked=!1}))))}}]),n}(),h.\u0275fac=function(n){return new(n||h)(f.Lb(r.T),f.Lb(r.S),f.Lb(r.Q),f.Lb(r.P),f.Lb(f.z),f.Lb(g.a),f.Lb(l.a),f.Lb(s.a),f.Lb(c.a),f.Lb(d.a),f.Lb(b.a))},h.\u0275cmp=f.Fb({type:h,selectors:[["app-forgot"]],decls:37,vars:5,consts:[[1,"ion-no-border","ion-no-shadow"],["slot","start"],["defaultHref","login",1,"custom-back-button"],["padding",""],[1,"forgot-section"],["size","12"],[1,"image-container"],["size","12","align-self-center",""],["src","/assets/images/forgot.png","alt","",1,"qcpop-img","slideInDownSmall","animate-show"],[1,"no-padmar"],["size","12",1,"title"],["size","12",1,"sub-title"],["method","post"],["ForgotForm","ngForm"],["no-lines",""],[1,"ion-no-padding","ion-no-margin"],["position","stacked",1,"label"],["type","email","placeholder","","name","email","spellcheck","false","autocapitalize","off","autocorrect","false","clearInput","true","required","",1,"form-input",3,"ngModel","ngModelChange"],["email","ngModel"],[1,"ion-no-padding","continue-padding"],["size","large","fill","solid","expand","block",1,"reset-button",3,"disabled","click"],["class","center-vertical-horizontal",4,"ngIf"],["position","bottom",1,"ion-no-margin","ion-no-padding","ion-no-border","ion-no-shadow"],[1,"ion-no-margin","ion-no-padding"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],[1,"center-vertical-horizontal"],[1,"button-spinner"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(n,o){if(1&n){var t=f.Pb();f.Ob(0,"ion-header",0),f.Ob(1,"ion-toolbar"),f.Ob(2,"ion-buttons",1),f.Mb(3,"ion-back-button",2),f.Nb(),f.Ob(4,"ion-title"),f.mc(5,"Forgot Password"),f.Nb(),f.Nb(),f.Nb(),f.Ob(6,"ion-content",3),f.Ob(7,"div",4),f.Ob(8,"ion-grid"),f.Ob(9,"ion-row"),f.Ob(10,"ion-col",5),f.Ob(11,"ion-row",6),f.Ob(12,"ion-col",7),f.Mb(13,"img",8),f.Nb(),f.Nb(),f.Ob(14,"ion-row",9),f.Ob(15,"ion-col",10),f.mc(16," Forgot Password? "),f.Nb(),f.Nb(),f.Ob(17,"ion-row",9),f.Ob(18,"ion-col",11),f.mc(19," We just need your registered Email Id to send you password reset instruction. "),f.Nb(),f.Nb(),f.Ob(20,"ion-row"),f.Ob(21,"ion-col",5),f.Ob(22,"form",12,13),f.Ob(24,"ion-list",14),f.Ob(25,"ion-item",15),f.Ob(26,"ion-label",16),f.mc(27,"Email Address"),f.Nb(),f.Ob(28,"ion-input",17,18),f.Wb("ngModelChange",(function(n){return o.forgot_form.email=n})),f.Nb(),f.Nb(),f.Nb(),f.Ob(30,"div",19),f.Ob(31,"ion-button",20),f.Wb("click",(function(){f.gc(t);var n=f.fc(23);return o.forgot(n)})),f.lc(32,u,3,0,"div",21),f.lc(33,p,2,0,"div",21),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Nb(),f.Ob(34,"ion-footer",22),f.Ob(35,"ion-grid",23),f.lc(36,m,3,3,"ion-row",24),f.Nb(),f.Nb()}2&n&&(f.Ab(28),f.bc("ngModel",o.forgot_form.email),f.Ab(3),f.bc("disabled",o.btn_isclicked),f.Ab(1),f.bc("ngIf",o.btn_isclicked),f.Ab(1),f.bc("ngIf",!o.btn_isclicked),f.Ab(3),f.bc("ngIf",o.internet_indicator))},directives:[r.q,r.M,r.i,r.e,r.f,r.L,r.m,r.p,r.B,r.l,e.r,e.k,e.l,r.w,r.u,r.v,r.t,r.W,e.p,e.j,e.m,r.h,i.i,r.o,r.F],styles:[".custom-back-button[_ngcontent-%COMP%]{color:#fff;display:inline}.forgot-section[_ngcontent-%COMP%]{padding:10px}.continue-padding[_ngcontent-%COMP%]{margin:20px 0 0}.reset-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.4rem;font-weight:400;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary);--color:var(--ion-color-primary-contrast)}.form-input[_ngcontent-%COMP%]{width:100%;height:auto;overflow:hidden;background-color:#f2f4f6;border:none!important;margin:13px 0 0;border-radius:0;-moz-border-radius:0;-o-border-radius:0;-webkit-border-radius:0;font-size:16px;padding:0 5px!important;color:#3e4d55;font-weight:400}ion-item[_ngcontent-%COMP%]{padding:0 0 2px;--ion-safe-area-right:0;--background-activated:var(--ion-text-color);--background-focused:var(--ion-text-color);--background-hover:var(--ion-text-color);--highlight-color-focused:none!important}ion-item[_ngcontent-%COMP%], ion-item[_ngcontent-%COMP%]:hover{--color:var(--ion-color-dark-black)}ion-item[_ngcontent-%COMP%]:hover{--border-color:var(--ion-color-input-background);--highlight-color-focused:var(--ion-color-input-background);--background-focused:var(--ion-color-input-background);--background-hover:var(--ion-color-input-background)}ion-label[_ngcontent-%COMP%]{font-family:Roboto,sans-serif!important;font-size:16px!important;font-weight:400!important;line-height:35px!important;--color:var(--ion-color-dark-black)!important}.item-has-focus[_ngcontent-%COMP%]   .label-stacked[_ngcontent-%COMP%]{color:var(--ion-color-dark-black)!important}.title[_ngcontent-%COMP%]{font-size:3.5rem;font-weight:600;text-align:center;vertical-align:middle;color:var(--ion-color-primary)}.sub-title[_ngcontent-%COMP%]{margin:10px 0 0!important;font-size:1.6rem;line-height:22px;color:var(--ion-color-light-gray);text-align:center;font-weight:700}.image-container[_ngcontent-%COMP%]{text-align:center;height:250px;padding-top:25px;padding-bottom:5px}.qcpop-img[_ngcontent-%COMP%]{-o-object-fit:resize;object-fit:resize;height:200px;width:auto!important;overflow:hidden;margin:0 auto;border:0;padding:0}"]}),h)}],w=((O=function n(){_classCallCheck(this,n)}).\u0275mod=f.Jb({type:O}),O.\u0275inj=f.Ib({factory:function(n){return new(n||O)},imports:[[a.i.forChild(v)],a.i]}),O),N=((_=function n(){_classCallCheck(this,n)}).\u0275mod=f.Jb({type:_}),_.\u0275inj=f.Ib({factory:function(n){return new(n||_)},imports:[[i.b,e.f,r.N,w]]}),_)}}]);