webpackJsonp([24],{YtR0:function(t,a){},wUZA:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var i=e("c/Tr"),n=e.n(i),c={name:"home",data:function(){return{tabs:[{name:"首页",pic:"tab-icon-default1.png",url:"/index",active:"tab-icon-active1.png",icon:"tab-icon-default1.png"},{name:"分类",pic:"tab-icon-default2.png",url:"/defenShop",active:"tab-icon-active2.png",icon:"tab-icon-default2.png"},{name:"购物车",pic:"tab-icon-default3.png",url:"/shopCar",active:"tab-icon-active3.png",icon:"tab-icon-default3.png"},{name:"我的",pic:"tab-icon-default4.png",url:"/info",active:"tab-icon-active4.png",icon:"tab-icon-default4.png"}]}},methods:{setTab:function(t){switch(t){case"Index":this.setTabColor(0);break;case"defenShop":this.setTabColor(1);break;case"shopCar":this.setTabColor(2);break;case"info":this.setTabColor(3)}},setTabColor:function(t){var a=document.querySelectorAll(".list"),e=n()(a);e.forEach(function(t){t.className="list"}),e[t].className="active list",this.tabs.forEach(function(t){t.pic=t.icon}),this.tabs[t].pic=this.tabs[t].active},tab:function(t,a){this.setTabColor(a),this.$router.push(t.url)}},mounted:function(){var t=this.$route.name;this.setTab(t)},filters:{imgFilter:function(t){return"static/shop/"+t}}},s={render:function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"home"},[e("div",{staticClass:"view"},[e("router-view")],1),t._v(" "),e("ul",{staticClass:"tab"},t._l(t.tabs,function(a,i){return e("li",{on:{click:function(e){t.tab(a,i)}}},[e("img",{staticStyle:{width:"0.44rem"},attrs:{src:t._f("imgFilter")(a.pic),alt:""}}),t._v(" "),e("p",{staticClass:"list",class:0===i?"active":""},[t._v(t._s(a.name))])])}))])},staticRenderFns:[]};var o=e("VU/8")(c,s,!1,function(t){e("YtR0")},null,null);a.default=o.exports}});
//# sourceMappingURL=24.556dcb45b48ad0db9b4e.js.map