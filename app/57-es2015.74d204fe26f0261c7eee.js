(window.webpackJsonp=window.webpackJsonp||[]).push([[57],{cRhG:function(e,o,t){"use strict";t.r(o),t.d(o,"ProfilePageModule",(function(){return y}));var n=t("ofXK"),i=t("3Pt+"),a=t("TEn/"),r=t("tyNb"),s=t("HDdC"),l=t("vkgz"),c=t("JIr8"),d=t("X7yx"),p=t("5dVO"),f=t("riPR"),m=t("ZJFI"),b=t("1vjY"),h=t("fXoL"),u=t("tk/3");function g(e,o){1&e&&(h.Ob(0,"div",26),h.mc(1," PLEASE WAIT\xa0\xa0"),h.Mb(2,"ion-spinner",27),h.Nb())}function _(e,o){1&e&&(h.Ob(0,"div",26),h.mc(1," UPDATE "),h.Nb())}function v(e,o){if(1&e&&(h.Ob(0,"ion-row",28),h.Ob(1,"ion-col",29),h.mc(2),h.Nb(),h.Nb()),2&e){const e=h.Yb();h.Ab(1),h.Cb(e.internet_background),h.Ab(1),h.oc(" ",e.internet_text," ")}}var O=function(e){return e[e.Online=0]="Online",e[e.Offline=1]="Offline",e}({});const k=[{path:"",component:(()=>{class e{constructor(e,o,t,n,i,a,r,s,l,c,d,p){this.platform=e,this.navCtrl=o,this.menu=t,this.zone=n,this.loadingCtrl=i,this.actionSheetCtrl=a,this.http=r,this.events=s,this.ionLoader=l,this.ionAlert=c,this.database=d,this.atom=p,this.btn_isclicked=!1,this.json_profile={user_id:"",company_id:"",company_name:"",first_name:"",last_name:"",fullname:"",email:"",mobile:"",photo:"",co_photo:"",otp_pending:"",otc_pending:"",co_pending:"",token_no:"",payload:"",webauto_login:"",default_dboard_tab:""},this.device_details={app_name:"",package_name:"",version_code:"",version_number:"",cordova:"",model:"",platform:"",uuid:"",imei:"",imsi:"",iccid:"",mac:"",version:"",manufacturer:"",is_virtual:!1,serial:"",memory:0,cpumhz:"",totalstorage:"",registered_account:"",user_agent:""},this.profile_form={action:"update_profile",payload:"",token:"",firstname:"",lastname:"",mobile:"",email:"",password:"",avatar:"",confirmpwd:"",device_info:""},this.avatarThumb="assets/images/camera.png",this.internet_indicator=!1,this.menu.enable(!0),this.initializeApp()}ngOnInit(){}initializeApp(){this.platform.ready().then(e=>{window.addEventListener("online",()=>{this.internetStatus=O.Online,this.zone.run(()=>{this.internet_background="int-green",this.internet_text="YOU ARE ONLINE"}),this.internet_timer=setTimeout(()=>{this.zone.run(()=>{this.internet_indicator=!1})},1e4)}),window.addEventListener("offline",()=>{this.internetStatus=O.Offline,this.zone.run(()=>{this.internet_background="int-red",this.internet_text="YOU ARE OFFLINE",this.internet_indicator=!0})})}),this.platform.ready().then(e=>{this.database.storage_get("device").then(e=>{e.token_no&&e.payload&&(this.json_profile=e,this.profile_form.payload=e.payload,this.profile_form.token=e.token_no,this.profile_form.firstname=e.first_name,this.profile_form.lastname=e.last_name,this.profile_form.email=e.email,this.profile_form.mobile=e.mobile,this.profile_form.avatar=e.photo)}).catch(e=>{})})}ionViewWillLeave(){1==this.action_sheet_status&&this.action_sheet.dismiss()}ionViewDidLoad(){try{this.events.unsubscribe("loginnetwork:online"),this.events.unsubscribe("loginnetwork:offline"),clearTimeout(this.internet_timer)}catch(e){}}pathForDefaultImage(e){return null===e||""===e||void 0===e?this.atom.getImagePath()+"assets/uploads/avatar/no-avatar.jpg":this.atom.getImagePath()+"assets/uploads/avatar/"+e}pathForImage(e){return null===e||""===e?"":cordova.file.dataDirectory+e}create_file_name(e){return(new Date).getTime()+e.substr(e.lastIndexOf("."))}change_listener(e){this.ionLoader.showLoader().then(()=>{try{if(e.target.files&&e.target.files[0]){let o=new FileReader;o.onload=e=>{this.avatarThumb=e.target.result,this.ionLoader.hideLoader()},o.readAsDataURL(e.target.files[0])}this.file=e.target.files[0];let t=new FormData,n=this.create_file_name(this.file.name);t.append("image",this.file),t.append("file_name",this.create_file_name(n)),t.append("payload",this.profile_form.payload),this.profile_form.avatar=n;var o=this.atom.getapi()+"upload_web";this.http.post(o,t,{}).pipe(Object(l.a)(e=>{this.ionLoader.hideLoader()}),Object(c.a)(this.handleError))}catch(t){}}).catch(e=>{})}handleError(e){return this.ionLoader.hideLoader(),e.error instanceof ErrorEvent?console.error("A server-side or network error occurred ",e.error.message):console.error(`Backend returned code ${e.status}, `+`body was: ${e.error}`),s.a.create(e=>{e.next({success:!1,message:"Server connection failed, Please try again later."}),e.complete()})}update_profile(e){!1===this.btn_isclicked&&(""==this.profile_form.firstname?this.atom.presentToast("First Name Is Required"):""==this.profile_form.lastname?this.atom.presentToast("Last Name Is Required"):""==this.profile_form.mobile?this.atom.presentToast("Mobile Is Required"):10!==this.profile_form.mobile.length?this.atom.presentToast("Mobile number required atleast 10 digit"):""==this.profile_form.email?this.atom.presentToast("Email Address Is Required"):(this.btn_isclicked=!0,e.valid?this.atom.http_post(this.profile_form).subscribe(e=>{1==e.success?(this.ionLoader.hideLoader(),this.btn_isclicked=!1,this.json_profile.user_id=e.user_id,this.json_profile.company_id=e.company_id,this.json_profile.company_name=e.company_name,this.json_profile.first_name=e.firstname,this.json_profile.last_name=e.lastname,this.json_profile.fullname=e.firstname+" "+e.lastname,this.json_profile.email=e.email,this.json_profile.mobile=e.mobile,this.json_profile.photo=e.avatar,this.json_profile.co_photo=e.co_logo,this.json_profile.otp_pending=e.otp_pending,this.json_profile.otc_pending=e.otc_pending,this.json_profile.co_pending=e.co_pending,this.json_profile.token_no=e.token_no,this.json_profile.payload=e.payload,this.json_profile.webauto_login=e.webauto_login,this.atom.setProfile(this.json_profile),this.events.publish("GLOBAL_VAR",this.json_profile),this.atom.presentToast(e.message),this.navCtrl.navigateRoot("/dashboard")):(this.ionLoader.hideLoader(),this.btn_isclicked=!1,this.atom.presentToast(e.message))},e=>{this.ionLoader.hideLoader(),this.btn_isclicked=!1,this.atom.presentToast(e)}):(this.btn_isclicked=!1,this.atom.presentToast("Application Error - Form Validate Failed"))))}}return e.\u0275fac=function(o){return new(o||e)(h.Lb(a.T),h.Lb(a.S),h.Lb(a.Q),h.Lb(h.z),h.Lb(a.P),h.Lb(a.a),h.Lb(u.a),h.Lb(f.a),h.Lb(p.a),h.Lb(d.a),h.Lb(m.a),h.Lb(b.a))},e.\u0275cmp=h.Fb({type:e,selectors:[["app-profile"]],decls:42,vars:9,consts:[[1,"ion-no-border","ion-no-shadow"],["slot","start"],["autoHide","false"],["padding",""],[1,"profile-section"],["method","post"],["ProfileForm","ngForm"],[1,"avatar-container"],["type","file","accept","image/*","id","file_input_avatar",2,"opacity","0",3,"change"],["alt","",1,"avatar-img",3,"src"],[1,"ion-no-padding","ion-no-margin"],["position","stacked",1,"label"],["type","text","placeholder","","name","firstname","spellcheck","false","autocomplete","off","autocorrect","off","autocomplete","off","autocorrect","off","autocapitalize","off","required","",1,"form-input",3,"ngModel","ngModelChange"],["firstname","ngModel"],["type","text","placeholder","","name","lastname","spellcheck","false","autocomplete","off","autocorrect","off","autocapitalize","off","required","",1,"form-input",3,"ngModel","ngModelChange"],["lastname","ngModel"],["type","tel","placeholder","","name","mobile","spellcheck","false","autocomplete","off","autocorrect","off","autocapitalize","off","minlength","10","maxlength","10","pattern","[0-9]*","required","",1,"form-input",3,"ngModel","ngModelChange"],["mobile","ngModel"],["type","email","placeholder","","name","email","spellcheck","false","autocomplete","off","autocorrect","off","autocapitalize","off","required","",1,"form-input",3,"ngModel","ngModelChange"],["email","ngModel"],[1,"ion-no-padding","continue-padding"],["size","large","fill","solid","expand","block",1,"profile-button",3,"disabled","click"],["class","center-vertical-horizontal",4,"ngIf"],["position","bottom",1,"ion-no-margin","ion-no-padding","ion-no-border","ion-no-shadow"],[1,"ion-no-margin","ion-no-padding"],["class","ion-no-margin ion-no-padding","internet-container","",4,"ngIf"],[1,"center-vertical-horizontal"],[1,"button-spinner"],["internet-container","",1,"ion-no-margin","ion-no-padding"],["size","12","no-padding","","no-margin","","internet-container",""]],template:function(e,o){if(1&e){const e=h.Pb();h.Ob(0,"ion-header",0),h.Ob(1,"ion-toolbar"),h.Ob(2,"ion-buttons",1),h.Mb(3,"ion-back-button",2),h.Nb(),h.Ob(4,"ion-title"),h.mc(5,"Update Profile"),h.Nb(),h.Nb(),h.Nb(),h.Ob(6,"ion-content",3),h.Ob(7,"div",4),h.Ob(8,"form",5,6),h.Ob(10,"ion-list"),h.Ob(11,"div",7),h.Ob(12,"input",8),h.Wb("change",(function(e){return o.change_listener(e)})),h.Nb(),h.Ob(13,"ion-avatar"),h.Mb(14,"img",9),h.Nb(),h.Nb(),h.Ob(15,"ion-item",10),h.Ob(16,"ion-label",11),h.mc(17,"First Name"),h.Nb(),h.Ob(18,"ion-input",12,13),h.Wb("ngModelChange",(function(e){return o.profile_form.firstname=e})),h.Nb(),h.Nb(),h.Ob(20,"ion-item",10),h.Ob(21,"ion-label",11),h.mc(22,"Last Name"),h.Nb(),h.Ob(23,"ion-input",14,15),h.Wb("ngModelChange",(function(e){return o.profile_form.lastname=e})),h.Nb(),h.Nb(),h.Ob(25,"ion-item",10),h.Ob(26,"ion-label",11),h.mc(27,"Mobile No."),h.Nb(),h.Ob(28,"ion-input",16,17),h.Wb("ngModelChange",(function(e){return o.profile_form.mobile=e})),h.Nb(),h.Nb(),h.Ob(30,"ion-item",10),h.Ob(31,"ion-label",11),h.mc(32,"Email Address"),h.Nb(),h.Ob(33,"ion-input",18,19),h.Wb("ngModelChange",(function(e){return o.profile_form.email=e})),h.Nb(),h.Nb(),h.Nb(),h.Ob(35,"div",20),h.Ob(36,"ion-button",21),h.Wb("click",(function(){h.gc(e);const t=h.fc(9);return o.update_profile(t)})),h.lc(37,g,3,0,"div",22),h.lc(38,_,2,0,"div",22),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Nb(),h.Ob(39,"ion-footer",23),h.Ob(40,"ion-grid",24),h.lc(41,v,3,3,"ion-row",25),h.Nb(),h.Nb()}2&e&&(h.Ab(14),h.bc("src",o.avatarThumb,h.ic),h.Ab(4),h.bc("ngModel",o.profile_form.firstname),h.Ab(5),h.bc("ngModel",o.profile_form.lastname),h.Ab(5),h.bc("ngModel",o.profile_form.mobile),h.Ab(5),h.bc("ngModel",o.profile_form.email),h.Ab(3),h.bc("disabled",o.btn_isclicked),h.Ab(1),h.bc("ngIf",o.btn_isclicked),h.Ab(1),h.bc("ngIf",!o.btn_isclicked),h.Ab(3),h.bc("ngIf",o.internet_indicator))},directives:[a.q,a.M,a.i,a.e,a.f,a.L,a.m,i.r,i.k,i.l,a.w,a.d,a.u,a.v,a.t,a.W,i.p,i.j,i.m,i.h,i.g,i.n,a.h,n.i,a.o,a.p,a.F,a.B,a.l],styles:["ion-menu-button[_ngcontent-%COMP%]{--color:var(--ion-color-primary-contrast)!important}ion-back-button[_ngcontent-%COMP%]{--color:var(--ion-color-primary-contrast)}.custom-back-button[_ngcontent-%COMP%]{color:#fff;display:inline}.profile-section[_ngcontent-%COMP%]{padding:10px}.continue-padding[_ngcontent-%COMP%]{margin:20px 0 0}.profile-button[_ngcontent-%COMP%]{font-size:1.4rem;text-transform:none;--box-shadow:none;--border-radius:0px;--background:var(--ion-color-primary);--background-focused:var(--ion-color-primary);--background-hover:var(--ion-color-primary);--color:var(--ion-color-primary-contrast)}.form-input[_ngcontent-%COMP%], .profile-button[_ngcontent-%COMP%]{font-family:Roboto,sans-serif;font-weight:400}.form-input[_ngcontent-%COMP%]{width:100%;height:auto;overflow:hidden;background-color:#f2f4f6;border:none!important;margin:13px 0 0;border-radius:0;-moz-border-radius:0;-o-border-radius:0;-webkit-border-radius:0;font-size:14px;padding:0 5px!important;color:#3e4d55}ion-item[_ngcontent-%COMP%]{padding:0 0 2px;--ion-safe-area-right:0;--background-activated:var(--ion-text-color);--background-focused:var(--ion-text-color);--background-hover:var(--ion-text-color);--highlight-color-focused:none!important}ion-item[_ngcontent-%COMP%], ion-item[_ngcontent-%COMP%]:hover{--color:var(--ion-color-dark-black)}ion-item[_ngcontent-%COMP%]:hover{--border-color:var(--ion-color-input-background);--highlight-color-focused:var(--ion-color-input-background);--background-focused:var(--ion-color-input-background);--background-hover:var(--ion-color-input-background)}ion-label[_ngcontent-%COMP%]{font-family:Roboto,sans-serif!important;font-size:16px!important;font-weight:400!important;line-height:35px!important;--color:var(--ion-color-dark-black)!important}.item-has-focus[_ngcontent-%COMP%]   .label-stacked[_ngcontent-%COMP%]{color:var(--ion-color-dark-black)!important}.avatar-container[_ngcontent-%COMP%]{display:flex;padding-top:13px;text-align:center;border:none!important;height:125px}.avatar-img[_ngcontent-%COMP%], ion-avatar[_ngcontent-%COMP%]{width:100px;height:100px;margin:0 auto}ion-avatar[_ngcontent-%COMP%]{background-color:#f2f4f6}#file_input_avatar[_ngcontent-%COMP%]{opacity:0;position:absolute;top:0;width:100%;height:125px;left:0;z-index:999}.button-spinner[_ngcontent-%COMP%]{display:flex}"]}),e})()}];let M=(()=>{class e{}return e.\u0275mod=h.Jb({type:e}),e.\u0275inj=h.Ib({factory:function(o){return new(o||e)},imports:[[r.i.forChild(k)],r.i]}),e})(),y=(()=>{class e{}return e.\u0275mod=h.Jb({type:e}),e.\u0275inj=h.Ib({factory:function(o){return new(o||e)},imports:[[n.b,i.f,a.N,M]]}),e})()}}]);