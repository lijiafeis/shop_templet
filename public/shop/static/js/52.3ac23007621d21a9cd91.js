webpackJsonp([52],{"4sQ9":function(t,e){},R0SA:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=a("Au9i"),s={data:function(){return{gash:{},list:[],moreLoading:!1,queryLoading:!1,allLoaded:!1,pageSize:20,pageNum:1,totalNum:10}},filters:{timeFilter:function(t){return new Date(1e3*t).toLocaleDateString()}},methods:{loadMore:function(){this.allLoaded?this.moreLoading=!0:this.queryLoading||(this.moreLoading=!this.queryLoading,this.pageNum++,this.getRecord(this.pageNum))},getGash:function(){var t=this;i.Indicator.open(),this.post("home/User/lingquLd",{}).then(function(e){i.Indicator.close(),1e4===e.data.code?(Object(i.Toast)({message:e.data.info,duration:1e3}),t.getInfo(),t.getRecord()):Object(i.Toast)({message:e.data.info,duration:1e3})})},getInfo:function(){var t=this;this.post("home/User/getLegouNumber",{}).then(function(e){1e4===e.data.code&&(t.gash=e.data.info)})},getRecord:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.post("home/User/leDouLog",{page:e,number:20}).then(function(e){1e4===e.data.code&&(t.list=[],t.totalNum=e.data.info.number,t.list=t.list.concat(e.data.info.data),t.allLoaded=t.list.length===t.totalNum,t.moreLoading=t.allLoaded)})},back:function(){this.$router.go(-1)}},mounted:function(){this.getInfo(),this.getRecord()}},n={render:function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"sign"},[i("header",{staticClass:"count_top header"},[i("img",{attrs:{src:a("0+aQ"),alt:""},on:{click:t.back}}),t._v(" "),i("span",[t._v("我的乐豆")])]),t._v(" "),i("div",{staticClass:"sign_package"},[i("div",{staticClass:"sign_wrapper "},[i("section",{staticClass:"sign_content"},[i("p",{staticClass:"sign_title"},[i("span",[t._v("乐豆")]),t._v(" "),i("strong",[t._v(t._s(t.gash.yilingqu))])]),t._v(" "),i("p",{staticClass:"line"}),t._v(" "),i("p",{staticClass:"sign_btn"},[i("span",[t._v("每天可领取"+t._s(t.gash.day_number)+"个,还剩"+t._s(t.gash.weilingqu)+"乐豆可领取")]),t._v(" "),i("a",{attrs:{href:"javascript:void(0)"},on:{click:t.getGash}},[t._v("领取乐豆")])])])])]),t._v(" "),i("p",{staticClass:"inter"}),t._v(" "),i("div",{staticClass:"sign_record"},[t._m(0),t._v(" "),i("ul",{directives:[{name:"infinite-scroll",rawName:"v-infinite-scroll",value:t.loadMore,expression:"loadMore"}],staticClass:"record_list",attrs:{"infinite-scroll-disabled":"moreLoading","infinite-scroll-distance":"0","infinite-scroll-immediate-check":"false"}},[t._l(t.list,function(e){return i("li",[i("p",{staticClass:"time"},[t._v(t._s(t._f("timeFilter")(e.create_time)))]),t._v(" "),i("p",{staticClass:"num"},[t._v(t._s(e.number)+"个")])])}),t._v(" "),t.queryLoading||0===t.list.length?t._e():i("li",{staticClass:"more_loading"},[i("mt-spinner",{directives:[{name:"show",rawName:"v-show",value:t.moreLoading&&!t.allLoaded,expression:"moreLoading&&!allLoaded"}],attrs:{type:"fading-circle",size:25}}),t._v(" "),i("span",{directives:[{name:"show",rawName:"v-show",value:t.allLoaded,expression:"allLoaded"}]},[t._v("已全部加载")])],1)],2)])])},staticRenderFns:[function(){var t=this.$createElement,e=this._self._c||t;return e("p",[e("span",{staticClass:"record"},[this._v("乐豆明细")])])}]};var o=a("VU/8")(s,n,!1,function(t){a("4sQ9")},"data-v-23e66b1e",null);e.default=o.exports}});
//# sourceMappingURL=52.3ac23007621d21a9cd91.js.map