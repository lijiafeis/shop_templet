webpackJsonp([62],{Gu6H:function(t,e){},SH6D:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s={data:function(){return{list:[]}},filters:{typeFilter:function(t){return 1===t?"开店礼包":"直推礼包"},sendFilter:function(t){return 0===t?"未发货":"已发货"}},methods:{back:function(){this.$router.go(-1)},record:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.post("home/Order/getLiBaoOrder",{page:e,number:20}).then(function(e){1e4===e.data.code&&(t.list=e.data.info.info)})}},mounted:function(){this.record()}},_={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"convertRecord"},[s("header",{staticClass:"count_top header"},[s("img",{attrs:{src:n("0+aQ"),alt:""},on:{click:t.back}}),t._v(" "),s("span",[t._v("领取记录")])]),t._v(" "),t._l(t.list,function(e){return s("div",{staticClass:"record_list"},[s("section",{staticClass:"record_list_c"},[s("p",[s("span",[t._v("礼包类型:")]),t._v(" "),s("span",[t._v(t._s(t._f("typeFilter")(e.libao_type)))])]),t._v(" "),s("p",[s("span",[t._v("商品名称:")]),t._v(" "),s("span",[t._v(t._s(e.good_name))])]),t._v(" "),s("p",[s("span",[t._v("领取时间:")]),t._v(" "),s("span",[t._v(t._s(t._f("time")(e.create_time)))])]),t._v(" "),s("p",[s("span",[t._v("快递名称:")]),t._v(" "),s("span",[t._v(t._s(e.kd_name))])]),t._v(" "),s("p",[s("span",[t._v("快递单号:")]),t._v(" "),s("span",[t._v(t._s(e.kd_number))])]),t._v(" "),s("p",[s("span",[t._v("姓名:")]),t._v(" "),s("span",[t._v(t._s(e.name))])]),t._v(" "),s("p",[s("span",[t._v("电话:")]),t._v(" "),s("span",[t._v(t._s(e.tel))])])]),t._v(" "),s("section",{staticClass:"record_list_r"},[s("p",[t._v(t._s(t._f("sendFilter")(e.type)))])])])})],2)},staticRenderFns:[]};var a=n("VU/8")(s,_,!1,function(t){n("Gu6H")},"data-v-0be3f242",null);e.default=a.exports}});
//# sourceMappingURL=62.a9a4f884de8252b2779a.js.map