webpackJsonp([57],{B6pT:function(e,t){},KEat:function(e,t,o){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=o("Au9i"),s=o("rR1+"),r={data:function(){return{id:"",orderInfo:{},address:{},content:"",payInfo:{},yunfei:0}},methods:{payType:function(e){var t=this;if("0"===e)this.post("home/Pay/payForMoney",{id:this.payInfo.id,type:this.payInfo.type}).then(function(e){1e4===e.data.code?(t.$router.push("/info"),Object(n.Toast)({message:e.data.info,duration:1e3})):Object(n.Toast)({message:e.data.info,duration:1e3})});else{var o=localStorage.getItem("token");location.href="http://flm.yunkzx.com/home/Shop/order_pay?token="+o+"&id="+this.payInfo.id+"&type="+this.payInfo.type}},cancel:function(){var e=this;n.MessageBox.confirm("确定取消订单吗?","").then(function(t){e.post("home/Order/closeOrder  ",{order_id:e.id}).then(function(t){1e4===t.data.code&&e.$router.go(-1)})}).catch(function(e){})},pay:function(){if(1===this.orderInfo.order_type||4===this.orderInfo.order_type)this.$refs.pop.value="",this.$refs.pop.popupVisible=!0,this.payInfo.id=this.orderInfo.id,this.payInfo.type=this.orderInfo.order_type;else{var e=localStorage.getItem("token");location.href="http://flm.yunkzx.com/home/Shop/order_pay?token="+e+"&id="+this.orderInfo.id+"&type="+this.orderInfo.order_type}},back:function(){this.$router.go(-1)},seeAddress:function(){},getOrderInfo:function(e){var t=this;this.post("home/Order/getOrderAllInfo",{order_id:e}).then(function(e){1e4===e.data.code&&(t.orderInfo=e.data.info,s.a.countDown(e.data.info.end_time,function(e){t.content=e}),t.address=e.data.info.address,t.address.city=JSON.parse(e.data.info.address.city),t.orderInfo.detail.forEach(function(e){e.pic_url=t.imageURL(e.pic_url),t.yunfei+=e.good_yunfei-0}))})}},created:function(){this.id=this.$route.params.id,this.getOrderInfo(this.id)},mounted:function(){},components:{pop:o("UQv7").a}},a={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"order"},[n("div",{staticClass:"count_top header"},[n("img",{attrs:{src:o("0+aQ"),alt:""},on:{click:e.back}}),e._v(" "),n("span",[e._v("待付款")])]),e._v(" "),n("header",[n("p",{staticClass:"order_title"},[n("span",[e._v("待付款")]),e._v(" "),n("span",{staticClass:"order_font"},[e._v("支付还剩"+e._s(e.content))])]),e._v(" "),n("p",{staticClass:"order_font order_m"},[e._v("订单编号: "+e._s(e.orderInfo.order_sn))]),e._v(" "),n("p",{staticClass:"order_font"},[e._v("下单时间: "+e._s(e._f("time")(e.orderInfo.create_time)))])]),e._v(" "),n("div",{staticClass:"order_info",on:{click:e.seeAddress}},[n("p",{staticClass:"order_address"},[n("span",[e._v(e._s(e.address.username))]),e._v(" "),n("span",[e._v(e._s(e.address.telphone))])]),e._v(" "),n("section",[n("p",e._l(e.address.city,function(t){return n("span",{staticStyle:{"font-size":"0.28rem"}},[e._v("\n          "+e._s(t.name)+"\n        ")])})),e._v(" "),n("span",[e._v(" > ")])])]),e._v(" "),e._l(e.orderInfo.detail,function(t){return n("div",{staticClass:"order_good"},[n("section",[n("img",{attrs:{src:t.pic_url,alt:""}})]),e._v(" "),n("section",[n("p",{staticClass:"good_name"},[e._v(e._s(t.good_name))]),e._v(" "),n("div",{staticClass:"good_num"},[n("p",{staticClass:"good_price"},[e._v("¥ "+e._s(t.good_price))]),e._v(" "),n("p",[e._v("x "+e._s(t.good_num))])])])])}),e._v(" "),n("div",{staticClass:"good_way"},[2===e.orderInfo.order_type||3===e.orderInfo.order_type?n("span",[e._v("麦粒: "),n("strong",[e._v(e._s(e.orderInfo.jifen))])]):e._e(),e._v(" "),2!==e.orderInfo.order_type?n("span",[e._v("实付金额: "),n("strong",[e._v("¥ "+e._s(e.orderInfo.shopmoney))])]):e._e()]),e._v(" "),n("p",{staticClass:"leave"},[n("span",[e._v("买家留言")]),e._v(" "),n("span",[e._v(e._s(e.orderInfo.content))])]),e._v(" "),n("p",{staticClass:"leave",staticStyle:{display:"flex","justify-content":"space-between"}},[n("span",[e._v("运费")]),e._v(" "),n("span",[e._v("¥"+e._s(e.yunfei))])]),e._v(" "),n("footer",[n("p",{on:{click:e.cancel}},[e._v("取消订单")]),e._v(" "),n("p",{on:{click:e.pay}},[e._v("支付")])]),e._v(" "),n("pop",{ref:"pop",on:{show:e.payType}})],2)},staticRenderFns:[]};var i=o("VU/8")(r,a,!1,function(e){o("B6pT")},"data-v-1b3e4d23",null);t.default=i.exports}});
//# sourceMappingURL=57.cec9f510a14d94d3a708.js.map