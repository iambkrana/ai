function _classCallCheck(n,t){if(!(n instanceof t))throw new TypeError("Cannot call a class as a function")}function _defineProperties(n,t){for(var i=0;i<t.length;i++){var e=t[i];e.enumerable=e.enumerable||!1,e.configurable=!0,"value"in e&&(e.writable=!0),Object.defineProperty(n,e.key,e)}}function _createClass(n,t,i){return t&&_defineProperties(n.prototype,t),i&&_defineProperties(n,i),n}(window.webpackJsonp=window.webpackJsonp||[]).push([[60],{TUdV:function(n,t,i){"use strict";i.r(t),i.d(t,"QuizTimeoutPageModule",(function(){return w}));var e=i("ofXK"),o=i("3Pt+"),a=i("TEn/"),r=i("tyNb"),s=i("X7yx"),c=i("5dVO"),l=i("riPR"),d=i("ZJFI"),u=i("1vjY"),b=i("fXoL");function p(n,t){if(1&n&&(b.Ob(0,"ion-row",12),b.Ob(1,"ion-col",13),b.mc(2),b.Nb(),b.Nb()),2&n){var i=b.Yb();b.Ab(1),b.Cb(i.internet_background),b.Ab(1),b.oc(" ",i.internet_text," ")}}var g,f,m,h=function(n){return n[n.Online=0]="Online",n[n.Offline=1]="Offline",n}({}),_=[{path:"",component:(g=function(){function n(t,i,e,o,a,r,s,c,l){_classCallCheck(this,n),this.platform=t,this.menu=i,this.navCtrl=e,this.zone=o,this.events=a,this.ionLoader=r,this.ionAlert=s,this.database=c,this.atom=l,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.internet_indicator=!1,this.menu.enable(!1),this.initializeApp(),this.json_navparam=this.atom.navigation_param_get(),this.question=this.json_navparam.question,this.workshop_details=this.json_navparam.workshop_details}return _createClass(n,[{key:"initializeApp",value:function(){var n=this;this.platform.ready().then((function(t){window.addEventListener("online",(function(){n.internetStatus=h.Online,n.zone.run((function(){n.internet_background="int-green",n.internet_text="YOU ARE ONLINE"})),n.internet_timer=setTimeout((function(){n.zone.run((function(){n.internet_indicator=!1}))}),1e4)})),window.addEventListener("offline",(function(){n.internetStatus=h.Offline,n.zone.run((function(){n.internet_background="int-red",n.internet_text="YOU ARE OFFLINE",n.internet_indicator=!0}))}))}))}},{key:"ionViewDidLoad",value:function(){try{this.events.unsubscribe("loginnetwork:online"),this.events.unsubscribe("loginnetwork:offline"),clearTimeout(this.internet_timer)}catch(n){}}},{key:"ngOnInit",value:function(){}},{key:"close",value:function(){var n=this;this.atom.navigation_param_set(this.workshop_details).subscribe((function(t){n.navCtrl.navigateRoot("/quiz")}),(function(t){n.atom.presentToast("Application error, parameter failed to set.")}))}}]),n}(),g.\u0275fac=function(n){return new(n||g)(b.Lb(a.T),b.Lb(a.Q),b.Lb(a.S),b.Lb(b.z),b.Lb(l.a),b.Lb(c.a),b.Lb(s.a),b.Lb(d.a),b.Lb(u.a))},g.\u0275cmp=b.Fb({type:g,selectors:[["app-quiz-timeout"]],decls:17,vars:1,consts:[["padding","",1,"body-wrapper"],[1,"ion-no-padding","ion-no-margin"],["size","12",1,"image-container"],["src","assets/images/timeout.png","alt","",1,"qcpop-img","slideInDownSmall","animate-show"],["size","12"],[1,"heading"],[1,"sub-title"],[1,"ion-no-padding","ion-no-margin","ion-no-border","ion-no-shadow"],[1,"ion-no-margin","ion-no-padding"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],["col-12","",1,"ion-no-padding","ion-no-margin"],["size","large","fill","solid","expand","block",1,"custom-button",3,"click"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(n,t){1&n&&(b.Ob(0,"ion-content",0),b.Ob(1,"ion-grid",1),b.Ob(2,"ion-row"),b.Ob(3,"ion-col",2),b.Mb(4,"img",3),b.Nb(),b.Ob(5,"ion-col",4),b.Ob(6,"p",5),b.mc(7,"Speed-up Miss(ter)!"),b.Nb(),b.Ob(8,"p",6),b.mc(9,"Sorry you have run out of time, try later"),b.Nb(),b.Nb(),b.Nb(),b.Nb(),b.Nb(),b.Ob(10,"ion-footer",7),b.Ob(11,"ion-grid",8),b.lc(12,p,3,3,"ion-row",9),b.Nb(),b.Ob(13,"ion-row",1),b.Ob(14,"ion-col",10),b.Ob(15,"ion-button",11),b.Wb("click",(function(){return t.close()})),b.mc(16," NEXT QUESTION"),b.Nb(),b.Nb(),b.Nb(),b.Nb()),2&n&&(b.Ab(12),b.bc("ngIf",t.internet_indicator))},directives:[a.m,a.p,a.B,a.l,a.o,e.i,a.h],styles:[".body-wrapper[_ngcontent-%COMP%]{background-color:#fbfbfb}.custom-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-size:1.4rem;font-weight:400;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary);--color:var(--ion-color-primary-contrast);margin:0}.image-container[_ngcontent-%COMP%]{text-align:center;height:250px;padding-top:25px;padding-bottom:5px}.qcpop-img[_ngcontent-%COMP%]{-o-object-fit:resize;object-fit:resize;height:200px;width:auto!important;overflow:hidden;margin:0 auto;border:0;padding:0}.heading[_ngcontent-%COMP%]{font-size:4rem;vertical-align:middle}.heading[_ngcontent-%COMP%], .sub-title[_ngcontent-%COMP%]{font-weight:700;text-align:center;color:#47cfad}.sub-title[_ngcontent-%COMP%]{font-size:2rem;line-height:22px}.sub-heading-i[_ngcontent-%COMP%]{font-weight:500;margin:10px 0 0}.sub-heading[_ngcontent-%COMP%], .sub-heading-i[_ngcontent-%COMP%]{font-size:1.4rem;line-height:22px;color:#3e4d55;text-align:justify;-moz-text-align-last:center;text-align-last:center}.sub-heading[_ngcontent-%COMP%]{vertical-align:top;padding:10px}"]}),g)}],O=((m=function n(){_classCallCheck(this,n)}).\u0275mod=b.Jb({type:m}),m.\u0275inj=b.Ib({factory:function(n){return new(n||m)},imports:[[r.i.forChild(_)],r.i]}),m),w=((f=function n(){_classCallCheck(this,n)}).\u0275mod=b.Jb({type:f}),f.\u0275inj=b.Ib({factory:function(n){return new(n||f)},imports:[[e.b,o.f,a.N,O]]}),f)}}]);