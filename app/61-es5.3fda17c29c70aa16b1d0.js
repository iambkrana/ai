function _classCallCheck(n,i){if(!(n instanceof i))throw new TypeError("Cannot call a class as a function")}function _defineProperties(n,i){for(var t=0;t<i.length;t++){var o=i[t];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(n,o.key,o)}}function _createClass(n,i,t){return i&&_defineProperties(n.prototype,i),t&&_defineProperties(n,t),n}(window.webpackJsonp=window.webpackJsonp||[]).push([[61],{kY5f:function(n,i,t){"use strict";t.r(i),t.d(i,"QuizWrongPageModule",(function(){return v}));var o=t("ofXK"),e=t("3Pt+"),r=t("TEn/"),a=t("tyNb"),s=t("X7yx"),c=t("5dVO"),l=t("riPR"),b=t("ZJFI"),u=t("1vjY"),d=t("fXoL");function p(n,i){if(1&n&&(d.Ob(0,"ion-col",4),d.Ob(1,"ion-row",1),d.Ob(2,"ion-col",13),d.mc(3),d.Nb(),d.Ob(4,"ion-col",13),d.mc(5),d.Nb(),d.Nb(),d.Nb()),2&n){var t=d.Yb();d.Ab(3),d.oc(" Correct answer is ",t.coAns," "),d.Ab(2),d.oc(" ",null==t.question?null:t.question.tip," ")}}function g(n,i){if(1&n&&(d.Ob(0,"ion-row",14),d.Ob(1,"ion-col",15),d.mc(2),d.Nb(),d.Nb()),2&n){var t=d.Yb();d.Ab(1),d.Cb(t.internet_background),d.Ab(1),d.oc(" ",t.internet_text," ")}}var f,h,m,_=function(n){return n[n.Online=0]="Online",n[n.Offline=1]="Offline",n}({}),w=[{path:"",component:(f=function(){function n(i,t,o,e,r,a,s,c,l){_classCallCheck(this,n),this.platform=i,this.menu=t,this.zone=o,this.navCtrl=e,this.events=r,this.ionLoader=a,this.ionAlert=s,this.database=c,this.atom=l,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.internet_indicator=!1,this.menu.enable(!1),this.initializeApp(),this.json_navparam=this.atom.navigation_param_get(),this.question=this.json_navparam.question,this.workshop_details=this.json_navparam.workshop_details,this.coAns="","a"==this.question.correct_answer&&(this.coAns=this.question.option_a),"b"==this.question.correct_answer&&(this.coAns=this.question.option_b),"c"==this.question.correct_answer&&(this.coAns=this.question.option_c),"d"==this.question.correct_answer&&(this.coAns=this.question.option_d)}return _createClass(n,[{key:"initializeApp",value:function(){var n=this;this.platform.ready().then((function(i){window.addEventListener("online",(function(){n.internetStatus=_.Online,n.zone.run((function(){n.internet_background="int-green",n.internet_text="YOU ARE ONLINE"})),n.internet_timer=setTimeout((function(){n.zone.run((function(){n.internet_indicator=!1}))}),1e4)})),window.addEventListener("offline",(function(){n.internetStatus=_.Offline,n.zone.run((function(){n.internet_background="int-red",n.internet_text="YOU ARE OFFLINE",n.internet_indicator=!0}))}))}))}},{key:"ionViewDidLoad",value:function(){try{this.events.unsubscribe("loginnetwork:online"),this.events.unsubscribe("loginnetwork:offline"),clearTimeout(this.internet_timer)}catch(n){}}},{key:"ngOnInit",value:function(){}},{key:"close",value:function(){var n=this;this.atom.navigation_param_set(this.workshop_details).subscribe((function(i){n.navCtrl.navigateRoot("/quiz")}),(function(i){n.atom.presentToast("Application error, parameter failed to set.")}))}}]),n}(),f.\u0275fac=function(n){return new(n||f)(d.Lb(r.T),d.Lb(r.Q),d.Lb(d.z),d.Lb(r.S),d.Lb(l.a),d.Lb(c.a),d.Lb(s.a),d.Lb(b.a),d.Lb(u.a))},f.\u0275cmp=d.Fb({type:f,selectors:[["app-quiz-wrong"]],decls:18,vars:2,consts:[["padding","",1,"body-wrapper"],[1,"ion-no-padding","ion-no-margin"],["size","12",1,"image-container"],["src","assets/images/wrngans.png","alt","",1,"qcpop-img","slideInDownSmall","animate-show"],["size","12"],[1,"heading"],[1,"sub-title"],["size","12",4,"ngIf"],[1,"ion-no-padding","ion-no-margin","ion-no-border","ion-no-shadow"],[1,"ion-no-margin","ion-no-padding"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],["size","12",1,"ion-no-padding","ion-no-margin"],["size","large","fill","solid","expand","block",1,"custom-button",3,"click"],["size","12",1,"sub-heading"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(n,i){1&n&&(d.Ob(0,"ion-content",0),d.Ob(1,"ion-grid",1),d.Ob(2,"ion-row"),d.Ob(3,"ion-col",2),d.Mb(4,"img",3),d.Nb(),d.Ob(5,"ion-col",4),d.Ob(6,"p",5),d.mc(7,"Oops!"),d.Nb(),d.Ob(8,"p",6),d.mc(9,"That is a wrong answer."),d.Nb(),d.Nb(),d.lc(10,p,6,2,"ion-col",7),d.Nb(),d.Nb(),d.Nb(),d.Ob(11,"ion-footer",8),d.Ob(12,"ion-grid",9),d.lc(13,g,3,3,"ion-row",10),d.Nb(),d.Ob(14,"ion-row",1),d.Ob(15,"ion-col",11),d.Ob(16,"ion-button",12),d.Wb("click",(function(){return i.close()})),d.mc(17," NEXT QUESTION"),d.Nb(),d.Nb(),d.Nb(),d.Nb()),2&n&&(d.Ab(10),d.bc("ngIf",0==(null==i.question?null:i.question.hide_answer)),d.Ab(3),d.bc("ngIf",i.internet_indicator))},directives:[r.m,r.p,r.B,r.l,o.i,r.o,r.h],styles:[".body-wrapper[_ngcontent-%COMP%]{background-color:#fbfbfb}.custom-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.4rem;font-weight:400;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary);--color:var(--ion-color-primary-contrast);margin:0}.image-container[_ngcontent-%COMP%]{text-align:center;height:250px;padding-top:25px;padding-bottom:5px}.qcpop-img[_ngcontent-%COMP%]{-o-object-fit:resize;object-fit:resize;height:200px;width:auto!important;overflow:hidden;margin:0 auto;border:0;padding:0}.heading[_ngcontent-%COMP%]{font-size:4rem;vertical-align:middle}.heading[_ngcontent-%COMP%], .sub-title[_ngcontent-%COMP%]{font-weight:700;text-align:center;color:#47cfad}.sub-title[_ngcontent-%COMP%]{font-size:2rem;line-height:22px}.sub-heading-i[_ngcontent-%COMP%]{font-weight:500;margin:10px 0 0}.sub-heading[_ngcontent-%COMP%], .sub-heading-i[_ngcontent-%COMP%]{font-size:1.4rem;line-height:22px;color:#3e4d55;text-align:justify;-moz-text-align-last:center;text-align-last:center}.sub-heading[_ngcontent-%COMP%]{vertical-align:top;padding:10px}"]}),f)}],O=((m=function n(){_classCallCheck(this,n)}).\u0275mod=d.Jb({type:m}),m.\u0275inj=d.Ib({factory:function(n){return new(n||m)},imports:[[a.i.forChild(w)],a.i]}),m),v=((h=function n(){_classCallCheck(this,n)}).\u0275mod=d.Jb({type:h}),h.\u0275inj=d.Ib({factory:function(n){return new(n||h)},imports:[[o.b,e.f,r.N,O]]}),h)}}]);