webpackJsonp([25],{DRdg:function(t,e,s){t.exports=s.p+"static/img/submit.89183bb.png"},"pz+t":function(t,e){},"wM+7":function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s("mvHQ"),r=s.n(a),o=s("Au9i"),i={data:function(){return{username:"",password:""}},methods:{submit:function(){var t=this;this.check.isPoneAvailable(this.username)&&this.check.isPassWordAvailable(this.password)?(o.Indicator.open(),this.post("home/Login/login",{username:this.username,password:this.password}).then(function(e){if(o.Indicator.close(),1e4===e.data.code){Object(o.Toast)({message:e.data.msg,duration:1e3});var s={name:t.username,word:t.password};localStorage.setItem("token",e.data.token);var a=Date.now()+1e3*e.data.time;localStorage.setItem("expires",a),localStorage.setItem("user",r()(s)),t.$router.push("/index")}else Object(o.Toast)(e.data.msg)})):Object(o.Toast)("手机号或密码格式错误")},login:function(){this.$router.push("/login")},forget:function(){this.$router.push("/forget")}},mounted:function(){var t=JSON.parse(localStorage.getItem("user"));t&&(this.username=t.name,this.password=t.word)}},n={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"submit"},[t._m(0),t._v(" "),a("div",{staticClass:"login_main"},[a("label",{staticClass:"form"},[a("img",{attrs:{src:s("5v+i"),alt:""}}),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model.trim",value:t.username,expression:"username",modifiers:{trim:!0}}],staticClass:"input",attrs:{type:"text",placeholder:"请输入手机号",autocomplete:"username"},domProps:{value:t.username},on:{input:function(e){e.target.composing||(t.username=e.target.value.trim())},blur:function(e){t.$forceUpdate()}}})]),t._v(" "),a("label",{staticClass:"form"},[a("img",{attrs:{src:s("Oedy"),alt:""}}),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model.trim",value:t.password,expression:"password",modifiers:{trim:!0}}],staticClass:"input",attrs:{type:"password",placeholder:"请输入密码",autocomplete:"password"},domProps:{value:t.password},on:{input:function(e){e.target.composing||(t.password=e.target.value.trim())},blur:function(e){t.$forceUpdate()}}})]),t._v(" "),a("button",{staticClass:"login_btn",on:{click:t.submit}},[t._v("登录")]),a("br"),t._v(" "),a("p",{staticClass:"login_r"},[a("a",{staticClass:"login_forget",attrs:{href:"javascript:void(0)"},on:{click:t.login}},[t._v("还没有账号？"),a("span",{staticStyle:{color:"#ff6f00","font-size":"0.3rem"}},[t._v("注册")])]),t._v(" "),a("a",{staticClass:"login_forget",attrs:{href:"javascript:void(0)"},on:{click:t.forget}},[t._v("忘记密码?")])])])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"login_header"},[e("img",{attrs:{src:s("DRdg"),alt:""}})])}]};var c=s("VU/8")(i,n,!1,function(t){s("pz+t")},"data-v-215f2f52",null);e.default=c.exports}});
//# sourceMappingURL=25.1cb2133569a235b5aa90.js.map