webpackJsonp([45],{NGP9:function(e,s){},qXpo:function(e,s,t){"use strict";Object.defineProperty(s,"__esModule",{value:!0});t("Au9i");var r={data:function(){return{id:"",orderInfo:{},address:{},yunfei:0,callback:function(){console.log("结束了")}}},methods:{back:function(){this.$router.go(-1)},seeAddress:function(){},getOrderInfo:function(e){var s=this;this.post("home/Order/getOrderAllInfo",{order_id:e}).then(function(e){1e4===e.data.code&&(s.orderInfo=e.data.info,s.address=e.data.info.address,s.address.city=JSON.parse(e.data.info.address.city),s.orderInfo.detail.forEach(function(e){e.pic_url=s.imageURL(e.pic_url),s.yunfei+=e.good_yunfei-0}))})}},created:function(){this.id=this.$route.params.id,this.getOrderInfo(this.id)},mounted:function(){},components:{countDown:t("r6q5").a}},o={render:function(){var e=this,s=e.$createElement,r=e._self._c||s;return r("div",{staticClass:"order"},[r("div",{staticClass:"count_top header"},[r("img",{attrs:{src:t("0+aQ"),alt:""},on:{click:e.back}}),e._v(" "),r("span",[e._v("已完成")])]),e._v(" "),r("header",[e._m(0),e._v(" "),r("p",{staticClass:"order_font order_m"},[e._v("订单编号: "+e._s(e.orderInfo.order_sn))]),e._v(" "),r("p",{staticClass:"order_font order_m"},[e._v("快递单号: "+e._s(e.orderInfo.kd_number))]),e._v(" "),r("p",{staticClass:"order_font"},[e._v("下单时间: "+e._s(e._f("time")(e.orderInfo.create_time)))])]),e._v(" "),r("div",{staticClass:"order_info",on:{click:e.seeAddress}},[r("p",{staticClass:"order_address"},[r("span",[e._v(e._s(e.address.username))]),e._v(" "),r("span",[e._v(e._s(e.address.telphone))])]),e._v(" "),r("section",[r("p",e._l(e.address.city,function(s){return r("span",{staticStyle:{"font-size":"0.28rem"}},[e._v("\n            "+e._s(s.name)+"\n          ")])})),e._v(" "),r("span",[e._v(" > ")])])]),e._v(" "),e._l(e.orderInfo.detail,function(s){return r("div",{staticClass:"order_good"},[r("section",[r("img",{attrs:{src:s.pic_url,alt:""}})]),e._v(" "),r("section",[r("p",{staticClass:"good_name"},[e._v(e._s(s.good_name))]),e._v(" "),r("div",{staticClass:"good_num"},[r("p",{staticClass:"good_price"},[e._v("¥ "+e._s(s.good_price))]),e._v(" "),r("p",[e._v("x "+e._s(s.good_num))])])])])}),e._v(" "),r("div",{staticClass:"good_way"},[2===e.orderInfo.order_type||3===e.orderInfo.order_type?r("span",[e._v("麦粒: "),r("strong",[e._v(e._s(e.orderInfo.jifen))])]):e._e(),e._v(" "),2!==e.orderInfo.order_type?r("span",[e._v("实付金额: "),r("strong",[e._v("¥ "+e._s(e.orderInfo.shopmoney))])]):e._e()]),e._v(" "),r("p",{staticClass:"leave"},[r("span",[e._v("买家留言")]),e._v(" "),r("span",[e._v(e._s(e.orderInfo.content))])]),e._v(" "),r("p",{staticClass:"leave",staticStyle:{display:"flex","justify-content":"space-between"}},[r("span",[e._v("运费")]),e._v(" "),r("span",[e._v("¥"+e._s(e.yunfei))])])],2)},staticRenderFns:[function(){var e=this.$createElement,s=this._self._c||e;return s("p",{staticClass:"order_title"},[s("span",[this._v("已完成")])])}]};var n=t("VU/8")(r,o,!1,function(e){t("NGP9")},"data-v-4c11ac12",null);s.default=n.exports}});
//# sourceMappingURL=45.af66ebf7362a521fb57b.js.map