webpackJsonp([64],{v22e:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s={render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"code"},[e("header",{staticClass:"count_top header"},[e("img",{attrs:{src:i("0+aQ"),alt:""},on:{click:this.back}}),this._v(" "),e("span",[this._v("分享二维码")])]),this._v(" "),e("div",{staticClass:"code_img"},[e("img",{attrs:{src:this.pic,alt:""}})])])},staticRenderFns:[]};var a=i("VU/8")({data:function(){return{pic:"",tel:""}},methods:{back:function(){this.$router.go(-1)}},mounted:function(){var t=this;this.post("home/User/getQr",{}).then(function(e){1e4===e.data.code&&(t.pic=t.imageURL("/"+e.data.info))})}},s,!1,function(t){i("wMfD")},"data-v-03673976",null);e.default=a.exports},wMfD:function(t,e){}});
//# sourceMappingURL=64.7a7e164aec24d7df6823.js.map