webpackJsonp([47],{OWVR:function(s,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=e("mvHQ"),o=e.n(a),d=e("Au9i"),i={data:function(){return{defaultAddress:null,goodsList:{},addressId:"",content:"",totalPrice:0}},methods:{getOrderList:function(s,t){var e=this;this.post("home/Shop/showShopInfo",{good_id:s,number:t}).then(function(s){1e4===s.data.code&&(e.defaultAddress=s.data.info.address,null!==e.defaultAddress&&(e.addressId=s.data.info.address.address_id,e.defaultAddress.city=JSON.parse(s.data.info.address.city)),e.goodsList=s.data.info.data,e.goodsList.pic_url=e.imageURL(s.data.info.data.pic_url),e.totalPrice=e.goodsList.good_price*e.goodsList.good_num)})},submitOrder:function(){var s=this,t=[],e={};e.good_id=this.goodsList.id,e.number=this.goodsList.good_num,t.push(e),""!==this.addressId?(d.Indicator.open(),this.post("home/Order/createOrder",{list:o()(t),address_id:this.addressId,content:this.content,type:2}).then(function(t){d.Indicator.close(),1e4===t.data.code?(Object(d.Toast)({message:"兑换成功",duration:1e3}),s.$router.push("/defenShop")):Object(d.Toast)({message:t.data.info,duration:1e3})})):Object(d.Toast)({message:"请添加收货地址",duration:1e3})},back:function(){this.$router.go(-1)},selectAddress:function(){this.$router.push("/site")}},mounted:function(){var s=this.$route.params.id,t=this.$route.params.num;this.getOrderList(s,t)}},n={render:function(){var s=this,t=s.$createElement,a=s._self._c||t;return a("div",{staticClass:"setOrder"},[a("header",{staticClass:"count_top header"},[a("img",{attrs:{src:e("0+aQ"),alt:""},on:{click:s.back}}),s._v(" "),a("span",[s._v("确认订单")])]),s._v(" "),a("div",{on:{click:s.selectAddress}},[null===s.defaultAddress?a("section",{staticClass:"order_w"},[a("img",{attrs:{src:e("Nn4G"),alt:"",width:"50"}}),s._v(" "),a("span",[s._v("请添加收货地址")])]):a("section",{staticClass:"order_address_t"},[s._m(0),s._v(" "),a("div",[a("section",{staticClass:"address_title"},[a("p",[s._v("收件人: "+s._s(s.defaultAddress.username))]),s._v(" "),a("p",[s._v(s._s(s.defaultAddress.telphone))])]),s._v(" "),a("p",[s._v("\n          收货地址: "),s._l(s.defaultAddress.city,function(t){return a("span",[s._v(s._s(t.name))])}),s._v(s._s(s.defaultAddress.address)+"\n        ")],2)]),s._v(" "),s._m(1)])]),s._v(" "),a("ul",[a("li",{staticClass:"order_good",on:{click:s.back}},[a("section",[a("img",{attrs:{src:s.goodsList.pic_url,alt:""}})]),s._v(" "),a("section",[a("p",{staticClass:"good_name"},[s._v(s._s(s.goodsList.good_name))]),s._v(" "),a("div",{staticClass:"good_num"},[a("p",{staticClass:"good_price"},[s._v("¥ "+s._s(s.goodsList.good_price))]),s._v(" "),a("p",[s._v("x "+s._s(s.goodsList.good_num))])])])])]),s._v(" "),a("label",{staticClass:"site"},[a("span",[s._v("买家留言:")]),s._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:s.content,expression:"content"}],attrs:{type:"text",placeholder:"选填,可填写和卖家达成一致的要求"},domProps:{value:s.content},on:{input:function(t){t.target.composing||(s.content=t.target.value)}}})]),s._v(" "),a("label",{staticClass:"goods_price"},[a("span",[s._v("运费:")]),s._v(" "),a("span",[s._v("¥"+s._s(s.goodsList.yunfei))])]),s._v(" "),a("section",{staticClass:"goods_price"},[a("span",[s._v("订单总价")]),s._v(" "),a("span",[s._v(s._s(s.totalPrice)+"麦粒")])]),s._v(" "),a("section",{staticClass:"goods_price"},[a("span",[s._v("需付款")]),s._v(" "),a("span",{staticClass:"price_d"},[s._v(s._s(s.totalPrice)+"麦粒")])]),s._v(" "),a("footer",{staticClass:"order_btn"},[a("section",{staticClass:"btn_l"},[a("span",[s._v("合计:")]),s._v(" "),a("span",[s._v(s._s(s.totalPrice+(s.goodsList.yunfei-0))+"麦粒")])]),s._v(" "),a("section",{staticClass:"btn_r",on:{click:s.submitOrder}},[s._v("\n      提交订单\n    ")])])])},staticRenderFns:[function(){var s=this.$createElement,t=this._self._c||s;return t("div",[t("img",{attrs:{src:e("Nn4G"),alt:""}})])},function(){var s=this.$createElement,t=this._self._c||s;return t("div",[t("img",{attrs:{src:e("Z++a"),alt:""}})])}]};var r=e("VU/8")(i,n,!1,function(s){e("dWXh")},"data-v-219c5f52",null);t.default=r.exports},dWXh:function(s,t){}});
//# sourceMappingURL=47.f0c8493cda57f19c9af7.js.map