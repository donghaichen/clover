<template>
    <div class="login-vue" :style="bg">
        <div class="container">
            <div class="loginBox" v-bind:class="{ 'shake': shake }">
                <div class="userImage">
                    <!--          <img src="@/assets/img/logo-big.png">-->
                    <img :src="logo">
                </div>
                <form id="loginForm">
                    <div class="input-wrapper" v-bind:class="{ 'ivu-form-item-error': accountError }">
                        <Input prefix="ios-contact" v-model="account" placeholder="用户名" @keyup.enter.native="submit" clearable @on-blur="verifyAccount"/>
                    </div>
                    <div class="input-wrapper" v-bind:class="{ 'ivu-form-item-error': passwordError }">
                        <Input type="password" v-model="password" prefix="md-lock" placeholder="密码" @keyup.enter.native="submit" clearable @on-focus="changeLogo" @on-blur="verifyPassword"/>
                    </div>
                    <div class="input-wrapper" v-bind:class="{ 'ivu-form-item-error': verifyError }">
                        <div v-show="waitShow === false" id="captcha"></div>
                        <div v-show="waitShow" style="height: 32px;color: #000" id="wait"></div>
                    </div>
                    <div class="auto-login">
                        <Checkbox v-model="checked">自动登录</Checkbox>
                        <a @click="forgetPassword">忘记密码?</a>
                    </div>
                    <Button :loading="isShowLoading" class="submit" type="primary" @click="submit">登陆</Button>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
  import Gt from "../api/Gt.js" ;//用于加载id对应的验证码库，并支持宕机模式 * 暴露
  import logo from '../assets/img/logo.png'
  import logoFace from '../assets/img/logo-facepalm.png'
  export default {
    name: 'login',
    data() {
      return {
        logo: logo,
        shake:false,
        waitShow: true,
        captchaObj: {},
        result: {}, // 是否已验证极验
        config: '',
        menu: '',
        account: '',
        password: '',
        checked: true,
        isShowLoading: false,
        accountError: false,
        passwordError: false,
        verifyError: false,
        backgroundImage: this.api.common.bing,
        bg: {}
      }
    },
    created() {
      this.bg.backgroundImage = 'url(' + this.backgroundImage +')'
      this.getInitGtTest()
    },
    watch: {
      $route: {
        handler: function(route) {
          this.redirect = route.query && route.query.redirect
        },
        immediate: true
      }
    },
    methods: {
      getInitGtTest() {
        this.$axios.get(this.api.static.geet + (new Date()).getTime())
          .then(res => {
            if (res.status === 200) {
              const data = res.data
              window.initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                offline: !data.success, // 表示用户后台检测极验服务器是否宕机
                new_captcha: data.new_captcha, // 用于宕机时表示是新验证码的宕机
                product: "float", // 产品形式，包括：float，popup
                width: "100%",
                https: true
              }, captchaObj => { // 箭头函数 若使用function 使用this报错
                this.captchaObj = captchaObj
                captchaObj.appendTo("#captcha");
                captchaObj.onReady(() => {
                  this.waitShow = false // 隐藏等待提示
                });
                captchaObj.onSuccess(() => {
                  this.verifyError = false
                  this.result = captchaObj.getValidate();
                });
                captchaObj.onError(() => {
                  this.$Message.error("出错啦, 请稍后重试!");
                })
              })
            }
          })
          .catch(err => {
            window.console.log(err)
          })
      },
      verifyAccount() {
        if (this.account.length < 1) {
          this.$Message.error('用户名必填')
          this.accountError = true
        } else {
          this.accountError = false
        }
      },
      changeLogo() {
        window.console.log(1111)
        this.logo = logoFace
      },
      verifyPassword() {
        // this.logo = logoFace
        this.logo = logo
        if (this.password.length < 1) {
          this.$Message.error('密码必填，我不会偷看的 ＝。＝')
          this.passwordError = true
        } else {
          this.passwordError = false
        }
      },
      forgetPassword() {
        this.$Message.info('请联系您的管理员重置密码')
      },
      submit() {
        if (this.account != '' && this.password != '') {
          if (!this.result.geetest_challenge) {
            this.verifyError = true
            return this.$Message.error("请先完成验证")
          }
          this.$axios.post(this.api.common.login, {
            username: this.account,
            password: this.password,
            geetest_challenge: this.result.geetest_challenge,
            geetest_validate: this.result.geetest_validate,
            geetest_seccode: this.result.geetest_seccode
          }).then((response) => {
              var res = response.data
              if (res.code == 0)
              {
                var data = res.data
                this.isShowLoading = true
                this.userImg = data.avatar
                this.token = data.remember_token
                this.uid = data.id
                this.config = JSON.stringify(data.config)
                this.menu = JSON.stringify(data.menu)
                this.userInfo = JSON.stringify(data.userInfo)
                localStorage.setItem('userImg', this.userImg)
                localStorage.setItem('userName', this.account)
                localStorage.setItem('token', this.token)
                localStorage.setItem('uid', this.uid)
                localStorage.setItem('config', this.config)
                localStorage.setItem('menu', this.menu)
                localStorage.setItem('userInfo', this.userInfo)
                localStorage.setItem('userInfo', this.userInfo)
                localStorage.setItem('uploadBase', this.api.static.baseUrl)
                localStorage.setItem('uploadContent', this.api.common.upload + '?module=content')
                this.$router.push({path: this.redirect || '/'})
              }else{
                this.captchaObj.reset()
                let that = this;
                that.shake = true
                setTimeout(function(){
                  that.shake = false
                }, 500)
                this.result = {}
                this.$Message.error(res.msg);
              }
            }
          )
        } else {
          if (this.account == '') {
            this.$Message.error('用户名不能为空')
            this.accountError = true
          }
          if (this.password == '') {
            this.$Message.error('密码不能为空')
            this.passwordError = true
          }
        }
      }
    }
  }
</script>

<style>
    @import "../assets/css/shake.css";
    .login-vue {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        background-repeat: no-repeat;
        background-size:cover;
    }
    .login-vue .container {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-vue .loginBox {
        width: 350px;
        background-color: white;
        box-shadow: 0px 0px 43px -2px rgba(135,130,135,1);
        border-radius: 8px;
        padding: 25px;
        opacity: .7;
    }
    .login-vue .userImage {
        border-radius: 50%;
        overflow: hidden;
        width: 120px;
        height: 120px;
        margin: 10px auto 30px;
        opacity: .7;
    }
    .login-vue img {
        width: 100%;
    }
    .login-vue .input-wrapper {
        position: relative;
    }
    .login-vue .ivu-input {
        width: 100%;
        /*height: 42px;*/
        padding-top: 15px;
        padding-bottom: 15px;
        margin: 10px 0;
        /*font-size: 14px;*/
    }
    .login-vue .ivu-input-icon {
        top: 8px;
    }
    .login-vue .ivu-input-prefix, .login-vue .ivu-input-suffix {
        top: 10px;
    }
    .login-vue .ivu-checkbox-wrapper {
        color: #9a9a9a;
    }
    .login-vue .auto-login a{
        float: right;
    }
    .login-vue .submit {
        width: 100%;
        margin-top: 35px;
        color: white;
        font-weight: lighter;
    }
    #captcha{
        margin: 10px 0 20px;
        height: 32px;
        color: rgb(0, 0, 0);
        overflow: hidden;
        border: 1px solid #dcdee2;
        border-radius: 4px;
    }
    .geetest_holder.geetest_wind {
        margin-top: -6px;
    }
    .geetest_holder.geetest_wind .geetest_radar_btn, .geetest_holder.geetest_wind .geetest_success_btn
    {
        border: none!important;
    }
    .geetest_holder.geetest_wind .geetest_radar_tip, .geetest_holder.geetest_wind .geetest_success_radar_tip
    {
        font-size: 12px!important;
    }
    @media (max-width: 640px)
    {

        .login-vue .loginBox {
            width: 320px;
        }
    }
</style>
