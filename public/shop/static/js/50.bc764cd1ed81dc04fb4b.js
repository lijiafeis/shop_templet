webpackJsonp([50],{Bg54:function(e,t){},oqpV:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a("Au9i"),s={name:"bindCard",data:function(){return{bankCarNum:"",username:"",bank:"",bankAddress:"",bankName:"",phone:"",yzCode:"",yz_text:"获取验证码",maskShow:!1,informTip:!1,currentText:"",phoneReg:/^((1[0-9]{1})+\d{9})$/,id:"",is_true:!1}},methods:{getCard:function(e){var t=this;this.post("home/Withdraw/getBankInfoById",{id:e}).then(function(e){1e4===e.data.code&&(t.phone=e.data.info.tel,t.username=e.data.info.name,t.bank=e.data.info.bank_name,t.bankAddress=e.data.info.bank_address,t.bankName=e.data.info.bank_zhihang,t.bankCarNum=e.data.info.bank_number,t.is_true=e.data.info.is_true)})},sendCord:function(){var e=this;n.Indicator.open(),this.post("home/Withdraw/sendMsg",{tel:this.phone,type:1}).then(function(t){n.Indicator.close(),1e4===t.data.code?function(){for(var t=60,a=e,n=0;n<=60;n++)window.setTimeout(function(){0!==t?(a.yz_text=t+"秒后重新获取",t--):(t=60,a.yz_text="获取验证码")},1e3*n)}():Object(n.Toast)({message:t.data.msg,duration:1e3})})},saveBankCard:function(){var e=this;this.post("home/Withdraw/updateBankInfo",{tel:this.phone,name:this.username,bank_number:this.bankCarNum,bank_name:this.bank,bank_address:this.bankAddress,bank_zhihang:this.bankName,msgCode:this.yzCode}).then(function(t){1e4===t.data.code?e.$router.go(-1):Object(n.Toast)({message:t.data.msg,duration:1e3})})},changeBankCard:function(e){var t=this;this.is_true=this.is_true?1:0,this.post("home/Withdraw/updateBankInfo",{tel:this.phone,name:this.username,bank_number:this.bankCarNum,bank_name:this.bank,bank_address:this.bankAddress,bank_zhihang:this.bankName,msgCode:this.yzCode,is_true:this.is_true,bank_id:e}).then(function(e){1e4===e.data.code?t.$router.push("/bankList"):Object(n.Toast)({message:e.data.msg,duration:1e3})})},submitBank:function(){var e=!isNaN(this.bankCarNum)&&this.bankCarNum.length>=16&&this.bankCarNum.length<=19;this.check.isPoneAvailable(this.phone)&&e&&this.username&&this.bank&&this.bankAddress&&this.bankName&&this.yzCode?"-1"===this.id?this.saveBankCard():this.changeBankCard(this.id):Object(n.Toast)({message:"请核对相关信息",duration:1e3})}},mounted:function(){this.id=this.$route.params.id,"-1"!==this.id&&this.getCard(this.id)},components:{}},i={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"bindCard"},[n("header",{staticClass:"count_top header"},[n("img",{attrs:{src:a("0+aQ"),alt:""},on:{click:function(t){e.$router.go(-1)}}}),e._v(" "),n("span",[e._v("银行卡信息")])]),e._v(" "),n("div",{staticClass:"bind_con"},[n("div",{staticClass:"iteList"},[n("label",[e._v("银行卡号:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.bankCarNum,expression:"bankCarNum"}],attrs:{type:"text",placeholder:"请输入银行卡号"},domProps:{value:e.bankCarNum},on:{input:function(t){t.target.composing||(e.bankCarNum=t.target.value)}}})]),e._v(" "),n("div",{staticClass:"iteList"},[n("label",[e._v("开户人姓名:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.username,expression:"username"}],attrs:{type:"text",placeholder:"请输入开户人姓名"},domProps:{value:e.username},on:{input:function(t){t.target.composing||(e.username=t.target.value)}}})]),e._v(" "),n("div",{staticClass:"iteList"},[n("label",[e._v("银行名称:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.bank,expression:"bank"}],attrs:{type:"text",placeholder:"请输入银行名称"},domProps:{value:e.bank},on:{input:function(t){t.target.composing||(e.bank=t.target.value)}}})]),e._v(" "),n("div",{staticClass:"iteList"},[n("label",[e._v("开户行地址:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.bankAddress,expression:"bankAddress"}],attrs:{type:"text",placeholder:"例:浙江-杭州-xx"},domProps:{value:e.bankAddress},on:{input:function(t){t.target.composing||(e.bankAddress=t.target.value)}}})]),e._v(" "),n("div",{staticClass:"iteList"},[n("label",[e._v("开户行支行:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.bankName,expression:"bankName"}],attrs:{type:"text",placeholder:"杭州钱江支行"},domProps:{value:e.bankName},on:{input:function(t){t.target.composing||(e.bankName=t.target.value)}}})]),e._v(" "),n("div",{staticClass:"iteList"},[n("label",[e._v("手机号:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.phone,expression:"phone"}],attrs:{type:"text",placeholder:"请填写手机号"},domProps:{value:e.phone},on:{input:function(t){t.target.composing||(e.phone=t.target.value)}}})]),e._v(" "),n("div",{staticClass:"iteList yzCode"},[n("label",[e._v("验证码:")]),n("input",{directives:[{name:"model",rawName:"v-model",value:e.yzCode,expression:"yzCode"}],attrs:{type:"text",placeholder:"验证码"},domProps:{value:e.yzCode},on:{input:function(t){t.target.composing||(e.yzCode=t.target.value)}}}),n("span",{staticClass:"yaCode_send",on:{click:e.sendCord}},[e._v(e._s(e.yz_text))])])]),e._v(" "),n("div",{staticClass:"btn_box",on:{click:e.submitBank}},[n("span",[e._v("提交绑定")])]),e._v(" "),e._m(0),e._v(" "),e.maskShow?n("div",{staticClass:"fixed_mask"}):e._e()])},staticRenderFns:[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"tips"},[t("p",[this._v("以上信息请务必填写正确，并确保您所填写的银行信息正确无误，以免影响结账活造成损失")])])}]};var o=a("VU/8")(s,i,!1,function(e){a("Bg54")},"data-v-3df85d48",null);t.default=o.exports}});
//# sourceMappingURL=50.bc764cd1ed81dc04fb4b.js.map