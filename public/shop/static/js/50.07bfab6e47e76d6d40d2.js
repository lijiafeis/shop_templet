webpackJsonp([50],{"4mW3":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a={data:function(){return{}},methods:{getUrlParams:function(e){var t=new RegExp("(^|&)"+e+"=([^&]*)(&|$)"),n=window.location.search.substr(1).match(t);return null!=n?unescape(n[2]):null}},mounted:function(){var e=this,t=this.getUrlParams("code");t&&this.post("/wxapi/Apioauth/getToken",{code:t}).then(function(t){console.log(t),1e4===t.data.code?(e.$router.push({name:"Index"}),sessionStorage.setItem("token",t.data.token)):10003===t.data.code&&alert(t.data.data)})},components:{}},o={render:function(){var e=this.$createElement;return(this._self._c||e)("div")},staticRenderFns:[]};var r=n("VU/8")(a,o,!1,function(e){n("TXF4")},null,null);t.default=r.exports},TXF4:function(e,t){}});
//# sourceMappingURL=50.07bfab6e47e76d6d40d2.js.map