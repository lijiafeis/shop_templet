webpackJsonp([41],{hbgH:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n={data:function(){return{items:[]}},methods:{getMoneyLog:function(t){this.post("home/User/getMoneyLog",{page:t,number:20}).then(function(t){console.log(t),1e4===t.data.code&&(this.items=t.data.info.data)}.bind(this))}},mounted:function(){this.getMoneyLog(1)}},s={render:function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"balance"},[i("div",{staticClass:"jifen-detail"},[i("ul",t._l(t.items,function(e){return i("li",[i("div",{staticClass:"top"},[1==e.type?i("div",[t._v("晋级奖励")]):t._e(),t._v(" "),2==e.type?i("div",[t._v("佣金奖")]):t._e(),t._v(" "),3==e.type?i("div",[t._v("团队奖励")]):t._e(),t._v(" "),4==e.type?i("div",[t._v("联合创始人的薪酬佣金")]):t._e(),t._v(" "),5==e.type?i("div",[t._v("提现")]):t._e(),t._v(" "),i("div",[t._v(t._s(e.number))])]),t._v(" "),i("div",{staticClass:"top"},[i("p",[t._v(t._s(t.$Num.Totime(e.create_time)))]),t._v(" "),0==e.state?i("p",[t._v("未审核")]):t._e(),t._v(" "),1==e.state?i("p",[t._v("成功")]):t._e(),t._v(" "),2==e.state?i("p",[t._v("驳回")]):t._e()])])}))]),t._v(" "),i("p",{staticClass:"tip"},[t._v("没有更多了哦~")])])},staticRenderFns:[]};var _=i("VU/8")(n,s,!1,function(t){i("rRLi")},null,null);e.default=_.exports},rRLi:function(t,e){}});
//# sourceMappingURL=41.209561a137fb2efe9c32.js.map