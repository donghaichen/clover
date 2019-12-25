<template>
  <div class="index-vue">
      <div class="search-wrapper open">
          <button type="button" class="close">×</button>
          <div class="vertical-center text-center">
              <form>
                  <input type="search" value="" placeholder="搜索其实很简单" />
              </form>
          </div>
      </div>
      <!-- 侧边栏 -->
      <section ref="mainLeft">
          <aside class="menu-mini" v-show="asideShow === 'mini'">
              <!-- logo -->
              <div class="logo-c" @click="gotoPage('home')">
                  <img src="@/assets/img/logo-tran.png" alt="logo" class="logo">
              </div>
              <Menu ref="asideMenu" theme="dark" width="100%">
                  <!-- 动态菜单 -->
                  <div v-for="(item, index) in menu" :key="index">
                      <MenuItem v-if="item.children" :name="index">
                          <Poptip trigger="hover" title="" :transfer="true" placement="right">
                              <Icon size="20" :type="item.type"/>
                              <ul class="ivu-dropdown-menu" slot="content" v-for="(subItem, i) in item.children" :key="index + i">
                                  <MenuItem class="ivu-dropdown-item" :name="subItem.name" @click.native="goNav(subItem.name, subItem.text)">
                                      <span>{{subItem.text}}</span>
                                  </MenuItem>
                              </ul>
                          </Poptip>
                      </MenuItem>
                      <MenuItem v-else :name="item.name" @click.native="goNav(item.name, item.text)">
                          <Poptip trigger="hover" title="" :transfer="true" placement="right">
                              <Icon size="20" :type="item.type"/>
                              <ul class="ivu-dropdown-menu" slot="content">
                                  <MenuItem class="ivu-dropdown-item" :name="item.name" @click.native="goNav(item.name, item.text)">
                                      <span>{{item.text}}</span>
                                  </MenuItem>
                              </ul>
                          </Poptip>
                      </MenuItem>
                  </div>
              </Menu>
          </aside>
          <aside class="aside-big" v-show="asideShow === 'big'">
              <!-- logo -->
              <div class="logo-c">
                  <img src="@/assets/img/logo-tran.png" alt="logo" class="logo">
                  <span>后台管理系统</span>
              </div>
              <!-- 菜单栏 -->
              <Menu ref="asideMenu" theme="dark" width="100%">
                  <!-- 动态菜单 -->
                  <div v-for="(item, index) in menu" :key="index">
                      <Submenu v-if="item.children" :name="index">
                          <template slot="title">
                              <Icon :size="item.size" :type="item.type"/>
                              <span>{{item.text}}</span>
                          </template>
                          <div v-for="(subItem, i) in item.children" :key="index + i">
                              <Submenu v-if="subItem.children" :name="index + '-' + i">
                                  <template slot="title" >
                                      <Icon :size="subItem.size" :type="subItem.type"/>
                                      <span>{{subItem.text}}</span>
                                  </template>
                              </Submenu>
                              <MenuItem v-else :name="subItem.name" @click.native="goNav(subItem.name, subItem.text)">
                                  <span>{{subItem.text}}</span>
                              </MenuItem>
                          </div>
                      </Submenu>
                      <MenuItem v-else :name="item.name" @click.native="goNav(item.name, item.text)">
                          <Icon :size="item.size" :type="item.type" />
                          <span>{{item.text}}</span>
                      </MenuItem>
                  </div>
              </Menu>
          </aside>
      </section>
    <!-- 右侧部分 -->
    <section class="sec-right" ref="mainRight">
        <!-- 头部 -->
        <div class="top-c">
            <header>
                <div class="h-left">
                    <div class="i-layout-header-trigger i-layout-header-trigger-min" @click="asideSwitch()">
                        <Icon type="ios-apps-outline" />
                    </div>

                    <!-- 面包屑功能 -->
<!--                    <Breadcrumb>-->
<!--                        <BreadcrumbItem to="/">Home</BreadcrumbItem>-->
<!--                        <BreadcrumbItem to="/components/breadcrumb">Components</BreadcrumbItem>-->
<!--                        <BreadcrumbItem>Breadcrumb</BreadcrumbItem>-->
<!--                    </Breadcrumb>-->
                </div>
                <div class="h-right">
                    <!-- 搜索 -->
                    <div class="search">
                        <Input class="i-layout-header-search" clearable placeholder="搜索..." />
                    </div>
                    <!-- 消息 -->
<!--                    <div class="notice-c" title="查看新消息">-->
<!--                    </div>-->
                    <Tooltip content="打开网站前台">
                        <Icon size="20" type="ios-home-outline" @click="home" />
                    </Tooltip>
                    <Tooltip content="查看消息">
                        <Badge dot>
                            <Icon size="20" type="ios-notifications-outline" />
                        </Badge>
                    </Tooltip>
<!--                    <Tooltip content="查看日志">-->
<!--                        <Icon size="20" type="ios-ionic-outline" @click="log" />-->
<!--                    </Tooltip>-->
                    <Dropdown trigger="hover" @on-click="selectLang">
                        <a href="javascript:void(0)" style="margin-left: 10px">
                            <Icon size="20" type="ios-globe-outline" />
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem v-for="(value, key) in localList" :name="key" :key="`lang-${key}`" v-html="value"></DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                    <!-- 用户头像 -->
                    <div class="user-img-c">
                        <Avatar icon="ios-person" :src="userImg" />
                    </div>
                    <!-- 下拉菜单 -->
                    <Dropdown trigger="hover" @on-click="userOperate" @on-visible-change="showArrow">
                        <div class="pointer">
                            <span>Hi, {{userName}}</span>
                            <Icon v-show="arrowDown" type="md-arrow-dropdown"/>
                            <Icon v-show="arrowUp" type="md-arrow-dropup"/>
                        </div>
                        <DropdownMenu slot="list">
                            <!-- name标识符 -->
                            <DropdownItem name="userInfo"><Icon type="ios-contact-outline" />基本资料</DropdownItem>
                            <DropdownItem name="userUpdate"><Icon type="ios-settings-outline" />修改资料</DropdownItem>
                            <DropdownItem divided name="login"><Icon type="ios-log-out" />退出登陆</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
        </header>
            <Drawer :closable="false" width="640" v-model="drawer">
                <p :style="pStyle">基本信息</p>
                <div class="drawer-profile">
                    <Row>
                        <Col span="12">
                            <Tag type="border">用户名 : </Tag>{{user.username}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">头像  : </Tag><Avatar :src="imgUrl(user.avatar)" icon="ios-person" />
                        </Col>
                        <Col span="12">
                            <Tag type="border">昵称  : </Tag>{{user.nickname}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">邮箱  : </Tag>{{user.email}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">用户状态 : </Tag>
                            <Tag type="dot" color="success" v-if="user.status == 1">正常</Tag>
                            <Tag type="dot" color="warning" v-if="user.status == 2">禁用</Tag>
                            <Tag type="dot" color="error" v-if="user.status !== 1 && user.status !== 2">未知</Tag>
                        </Col>
                        <Col span="12">
                            <Tag type="border">注册时间 : </Tag>{{user.created_at}}
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <Tag type="border">注册IP : </Tag>{{user.created_ip}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">更新时间 : </Tag>{{user.updated_at}}
                        </Col>
                    </Row>

                </div>
                <Divider />
                <p :style="pStyle">个人简介</p>
                <div class="drawer-profile">
                    <Row>
                        <Col span="24" class="desc">
                            {{user.desc}}
                        </Col>
                    </Row>
                </div>
                <Divider />
                <p :style="pStyle">联系方式</p>
                <div class="drawer-profile">
                    <Row>
                        <Col span="12">
                            <Tag type="border">手机号 : </Tag>{{user.mobile}}
                        </Col>
                        <Col span="12">
                            <Tag type="border">个人主页: </Tag>{{user.url}}
                        </Col>
                    </Row>
                </div>
            </Drawer>
        <!-- 标签栏 -->
            <div class="tags-nav" ref="scrollOuter">
                <div class="btn-con left-btn">
                    <Icon :size="18" type="ios-arrow-back" @click="handleScroll(240)"/>
<!--                    <Button ghost >-->
<!--                        -->
<!--                    </Button>-->
                </div>
                <div class="btn-con right-btn">
                    <Icon :size="18" type="ios-arrow-forward" @click="handleScroll(-240)"/>
<!--                    <Button ghost >-->
<!--                       -->
<!--                    </Button>-->
                </div>
                <div class="scroll-wrap">
                    <div class="scroll-body" ref="scrollBody" :style="{left: tagBodyLeft + 'px'}">
                        <transition-group name="taglist-moving-animation">
                            <Tag v-for="(data, index) in tag"
                                 :key="index"
                                 :name="index"
                                 type="dot"
                                 checkable
                                 :closable="data.closable"
                                 :color="data.color"
                                 @on-close="tagClose(index)"
                                 @on-change="tagChange(data.name, data.title)"
                            >
                                {{ data.title }}
                            </Tag>
                        </transition-group>
                    </div>
                </div>
            </div>
        </div>
      <!-- 页面主体 -->
      <div class="main-content">
          <!-- 子页面 -->
          <transition name="fade">
              <router-view></router-view>
          </transition>

          <footer class="layout-foote ivu-global-footer i-copyright">
              <div class="ivu-global-footer-copyright">
                  Code based on Vue, iView development,
                  <a class="a-style" @click="link('https://github.com/donghaichen')">
                      view more to github <Icon type="ios-heart" /></a>
              </div>
          </footer>
      </div>
    </section>
  </div>
</template>

<script>
import avatar from '@/assets/img/avatar.png'
  export default {
    data () {
      return {
        tagBodyLeft: 42,
        rightOffset: 42,
        tag: [
          {
            name: 'home',
            title: '主页',
            color: 'primary',
            closable: false
          }
        ],
        lang: 'zh-CN',
        localList: {
          'zh-CN': '<span class="flag-icon flag-icon-cn"></span>中文简体',
          'zh-TW': '<span class="flag-icon flag-icon-tw"></span>中文繁体',
          'en-US': '<span class="flag-icon flag-icon-gb"></span>English'
        },
        asideShow: 'big',
        drawer: false,
        user: JSON.parse(localStorage.getItem('userInfo')),
        pStyle: {
          fontSize: '16px',
          color: 'rgba(0,0,0,0.85)',
          lineHeight: '24px',
          display: 'block',
          marginBottom: '16px'
        },
        avatar: avatar,
        menu: [],
        theme: 'dark',
        isShowRouter: true,
        showLoading: false, // 是否显示loading
        // 用于储存页面路径
        paths: {},
        // 当前显示页面
        currentPage: '',
        openMenus: [], // 要打开的菜单名字 name属性
        menuCache: [], // 缓存已经打开的菜单
        asideClassName: 'aside-big', // 控制侧边栏宽度变化
        userName: '',
        userImg: '',
        arrowUp: false, // 用户详情向上箭头
        arrowDown: true, // 用户详情向下箭头
        arrowUpLang: false, // 用户详情向上箭头
        arrowDownLang: true, // 用户详情向下箭头
      }
    },
    created: function () {
      this.handleSpinShow()
      this.menu = JSON.parse(localStorage.getItem('menu'))
      this.asideShow = localStorage.getItem('asideShow') === null ? 'big' : localStorage.getItem('asideShow')
      if (this._isMobile())
      {
        this.asideShow = 'mini'
      }
    },
    watch: {
      lang (lang) {
        this.$i18n.locale = lang
      },
      // '$route' (to) {
      //   this.getTagElementByRoute(to)
      // },
      // visible (value) {
      //   if (value) {
      //     document.body.addEventListener('click', this.closeMenu)
      //   } else {
      //     document.body.removeEventListener('click', this.closeMenu)
      //   }
      // }
    },
    computed: {
      // currentRouteObj () {
      //   const { name, params, query } = this.value
      //   return { name, params, query }
      // }
    },
    methods: {
      _isMobile() {
        let flag = navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i)
        return flag;
      },
      handleScroll (offset) {
        // this.tagBodyLeft = this.tagBodyLeft + offset
        console.log(offset)
        const outerWidth = this.$refs.scrollOuter.offsetWidth
        const bodyWidth = this.$refs.scrollBody.offsetWidth
        console.log(outerWidth, bodyWidth)
        if (offset > 0) {
          this.tagBodyLeft = Math.min(0, this.tagBodyLeft + offset)
        } else {
          if (outerWidth < bodyWidth) {
            if (this.tagBodyLeft < -(bodyWidth - outerWidth)) {
              this.tagBodyLeft = this.tagBodyLeft
            } else {
              this.tagBodyLeft = Math.max(this.tagBodyLeft + offset, outerWidth - bodyWidth)
            }
          } else {
            this.tagBodyLeft = 0
          }
        }
      },
      tagClose (index) {
        this.tag.splice(index, 1)
        if (this.tag.length === 1)
        {
          let str = JSON.stringify(this.tag)
          str = str.replace(/default/, "primary")
          this.tag = JSON.parse(str)
          this.gotoPage('home')
        }
      },
      tagChange (name, title) {
        let str = JSON.stringify(this.tag)
        str = str.replace(/primary/, "default")
        this.tag = JSON.parse(str)
        let index = str.indexOf(title)
        if (index !== -1)
        {
          this.tag.map((item, index)=>{
            if(item.title === title){
              this.tag[index]['color'] = 'primary'
            }
          })
        }
        this.gotoPage(name)
      },
      selectLang (name) {
        this.lang = name
        localStorage.setItem('lang', name)
      },
      asideSwitch() {
        let mainLeft = this.$refs.mainLeft.offsetWidth
        this.mainRight = window.innerWidth - mainLeft
        this.asideShow = this.asideShow === 'big' ? 'mini' : 'big'
        localStorage.setItem('asideShow', this.asideShow)
      },
      handleSpinShow () {
        this.$Spin.show();
        setTimeout(() => {
          this.$Spin.hide();
        }, 1000)
      },
      log()
      {
        this.gotoPage('log')
      },
      home()
      {
        let home = this.api.static.baseUrl
        window.open(home)
      },
      link (url) {
        window.open(url,'_blank') // 新窗口打开外链接
      },
      // 用户操作
      userOperate(name) {
        switch(name) {

          case 'login':
            // 退出登陆 清除用户资料
            localStorage.setItem('token', '')
            this.$router.replace({name})
            break
          case 'userUpdate':
            this.gotoPage(name,{
              uid:localStorage.getItem('uid')
            })
            break
          case 'userInfo':
            this.drawer = true
            break
        }
      },
      // 控制用户三角箭头显示状态
      showArrow(flag) {
        this.arrowUp = flag
        this.arrowDown = !flag
      },
      goNav(name, title, params = {}) {
        this.showLoading = true
        this.gotoPage(name, params)
        let str = JSON.stringify(this.tag)
        str = str.replace(/primary/, "default")
        this.tag = JSON.parse(str)
        let index = str.indexOf(title)
        if (index !== -1)
        {
          this.tag.map((item, index)=>{
            if(item.title === title){
              this.tag[index]['color'] = 'primary'
            }
          })
        }else{
          this.tag.push({
            name: name,
            title: title,
            color: 'primary',
            closable: true
          })
          // this.tagColor.push(title, 'primary')
        }
      },
      gotoPage: function(name, params = {}){
        this.showLoading = true
        this.$router.push({  //核心语句
         name:name,   //跳转的路径
         query:params,   //跳转的参数
        })
        this.showLoading = false
      },
      imgUrl(src)
      {
        if(src.length <= 0)
        {
          return false
        }
        if(src.substr(0,4).toLowerCase() != "http")
        {
          src = this.api.static.baseUrl + src;
        }
        return src
      },
    },
    mounted() {
      // setTimeout(() => {
      //   this.getTagElementByRoute(this.$route)
      // }, 200)
      // 设置用户信息
      this.userName = localStorage.getItem('userName')
      let userImg = localStorage.getItem('userImg')
      this.userImg = this.imgUrl(userImg)
    }
  }
</script>

<style>
@import "../assets/css/flag-icon.css";
body{
    font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
}
.flag-icon{
    margin-right: 5px;
}
/*提示*/
.top,.bottom{
    text-align: center;
}
.center{
    width: 300px;
    margin: 10px auto;
    overflow: hidden;
}
.center-left{
    float: left;
}
.center-right{
    float: right;
}
.ivu-poptip-body {
    padding: 0!important;
}
.ivu-poptip-popper {
    min-width: auto!important;
}
.i-layout-header-trigger {
    text-align: center;
    cursor: pointer;
    transition: all .2s ease-in-out;
}
.i-layout-header-trigger-min {
    width: auto;
    padding: 0 12px;
}
.i-layout-header-trigger:hover {

}
.ivu-menu-dark{
    background: transparent;
}
ul.ivu-menu li ul div{
    margin-left: 10px;
}
.ivu-menu-dark.ivu-menu-vertical .ivu-menu-opened .ivu-menu-submenu-title {
    background: #13182b;
}
.ivu-menu-dark.ivu-menu-vertical .ivu-menu-opened {
    background: #13182b;
}
.ivu-menu-dark.ivu-menu-vertical .ivu-menu-item:hover, .ivu-menu-dark.ivu-menu-vertical .ivu-menu-submenu-title:hover {
    color: #2d8cf0;
    background: #13182b;
}
.ivu-global-footer {
    margin: 0;
    padding: 24px 16px;
    text-align: center;
}
.i-copyright {
    flex: 0 0 auto;
}
.index-vue {
    height: 100%;
    display: flex;
    justify-content: space-between;
    color: #666;
}
/* 侧边栏 */
aside {
    min-width: 70px;
    background: #20222A;
    height: 100%;
    transition: all .5s;
    overflow: auto;
}
.logo-c {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,.8);
    font-size: 16px;
    margin: 20px 0;
    justify-content: center;
}
.logo {
    width: 40px;
    margin-right: 10px;
}
.aside-mini .logo {
    margin-right: 0;
}
.aside-big {
    min-width: 220px;
}
/* 主体页面 */
.sec-right {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    transition: width .5s;
}
/* 主体页面头部 */
.top-c {
    background: rgba(230,230,230,.5);
    width: 100%;
}
header {
    height: 50px;
    border-bottom: none;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,.05);
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-right: 30px;
    padding-left: 0px;
    font-size: 14px;
}
header .ivu-icon {
    font-size: 24px;
}
.refresh-c {
    margin: 0 30px;
    cursor: pointer;
}
.h-right {
    display: flex;
    font-size: 14px;
    align-items: center;
}
.h-left {
    display: flex;
    align-items: center;
}
.user-img-c img {
    width: 100%;
}
.notice-c {
    cursor: pointer;
    position: relative;
}
.newMsg {
    position: absolute;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #FF5722;
    right: 0;
    top: 0;
}
.user-img-c {
    width: 34px;
    height: 34px;
    background: #ddd;
    border-radius: 50%;
    margin: 0 5px 0 20px;
    overflow: hidden;
}
.tag-options {
    cursor: pointer;
    position: relative;
}
.div-tags {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.div-icons {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    background: #fff;
    height: 34px;
    width: 160px;
    font-size: 18px;
}
/* 标签栏 */
.ul-c {
    height: 34px;
    margin-top: 2px;
    background: #fff;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding: 0 10px;
    overflow: hidden;
    width: calc(100% - 160px);
}
.ul-c li {
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
    height: 24px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 3px 5px 2px 3px;
    border: 1px solid #e6e6e6;
}
a {
    color: #666;
    transition: none;
}
.li-a {
    max-width: 80px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.ul-c .ivu-icon {
    margin-left: 6px;
}
.active {
    background: #409eff;
    border: 1px solid #409eff;
}
.active a {
    color: #fff;
}
.active .ivu-icon {
    color: #fff;
}
/* 主要内容区域 */
.main-content {
    overflow: auto;
    height: 100%;
    width: 100%;
    background: #eee;
    padding: 10px 15px 15px 15px;
}
.content {
   /*padding-right: 20px;*/
}
.content .card_search {
    margin-bottom: 20px;
}
.content .card_search .ivu-card{
    background: rgba(255, 255, 255, .6);
}
.pointer {
    cursor: pointer;
}
/* loading */
.loading-c {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    position: absolute;
    background: rgba(255,255,255,.5);
    display: flex;
    align-items: center;
    justify-content: center;
}
.mask {
    position: fixed;
    background: #eee;
    height: 10px;
    width: 100%;
    top: 85px;
    z-index: 10;
}
.crumbs {
    margin-left: 10px;
    color: #97a8be;
    cursor: default;
}
.menu-level-3 .ivu-icon {
    font-size: 18px;
}
.ivu-menu-vertical .ivu-menu-item, .ivu-menu-vertical .ivu-menu-submenu-title {
    padding: 10px 24px!important;
}
.content h1{
    padding-bottom: 20px;
    margin-bottom: 30px;
    border-bottom: 1px solid rgb(215, 215, 215);
}
.content h1 a {
    float: right;
    display: inline-block;
}
/*
用户详情
*/
.drawer-profile{
    font-size: 14px;
}
.drawer-profile .ivu-col{
    margin-bottom: 12px;
}
.drawer-profile .desc{
    background-color: #f3f3f3;
    width: 100%;
    font-size: 13px;
    color: #666666;
    overflow: auto;
    height: 196px;
    padding: 20px;
}
.drawer-profile .ivu-col .ivu-tag-border {
    border: none;
    width: 70px;
}
.h-right .search .ivu-input-default {
    outline: none;
    border: none;
    background: transparent;
    color: inherit;
}
.h-right .search .ivu-input-default.ivu-input:focus
{
    box-shadow:none;
}
.h-right .ivu-tooltip-rel {

    padding: 0 6px;
}
.h-right .ivu-icon {

}
.h-right .ivu-dropdown-item .ivu-icon {
    font-size: 19px;
    vertical-align: middle;
    padding-right: 7px;
    margin-top: -3px;
}
.tags-nav {
    position: relative;
    padding: 5px;
    height: 40px;
    overflow: hidden;
    background: #f0f0f0;
    width: 100%;
    border-bottom: 1px solid #f0f0f0;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.tag-nav-wrapper {

}
.tags-nav .btn-con, .tags-nav .close-con {
    position: absolute;
    background: #eee;
    top: 0;
    height: 100%;
    z-index: 10;
}
.tags-nav i {
    font-size: 18px;
    padding: 10px;
    cursor: pointer;
}
.tags-nav .btn-con.left-btn {
    left: 0;
}

.tags-nav .btn-con.right-btn {
    right: 20px;
}
.tags-nav .btn-con {
    padding-top: 3px;
}
.tags-nav .scroll-outer {
    position: absolute;
    left: 28px;
    right: 61px;
    top: 0;
    bottom: 0;
    -webkit-box-shadow: 0 0 3px 2px hsla(0,0%,39.2%,.1) inset;
    box-shadow: inset 0 0 3px 2px hsla(0,0%,39.2%,.1);
}
.scroll-body{
    height: calc(100% - 1px);
    display: inline-block;
    padding: 1px 4px 0;
    position: absolute;
    overflow: visible;
    white-space: nowrap;
    -webkit-transition: left .3s ease;
    transition: left .3s ease;
}
.menu-mini .logo{
    margin-right: 0;
}
@media only screen and (max-width: 767px){
    header{
        padding-right: 10px;
    }
    .search{
        display: none;
    }
    .h-right {
        margin-right: 0;
    }
    aside {
        min-width: 40px;

    }
    .logo {
        width: 30px;
    }
    .ivu-menu-vertical .ivu-menu-item, .ivu-menu-vertical .ivu-menu-submenu-title {
        padding: 10px!important;
    }
    .tags-nav .btn-con.right-btn {
        right: 0;
    }

}

/*-----------------------------------------------------------------------------------*/


/*  13 - SEARCH
/*-----------------------------------------------------------------------------------*/

.search-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.95);
    -webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    -ms-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
    -webkit-transform: translate(0px, -100%) scale(1, 0);
    -moz-transform: translate(0px, -100%) scale(1, 0);
    -o-transform: translate(0px, -100%) scale(1, 0);
    -ms-transform: translate(0px, -100%) scale(1, 0);
    transform: translate(0px, -100%) scale(1, 0);
    opacity: 0;
    z-index: 9999999
}

.search-wrapper.open {
    -webkit-transform: translate(0px, 0px) scale(1, 1);
    -moz-transform: translate(0px, 0px) scale(1, 1);
    -o-transform: translate(0px, 0px) scale(1, 1);
    -ms-transform: translate(0px, 0px) scale(1, 1);
    transform: translate(0px, 0px) scale(1, 1);
    opacity: 1;
}

.search-wrapper input[type="search"] {
    width: 320px;
    color: #fff;
    background: rgba(0, 0, 0, 0);
    font-size: 14px;
    font-weight: 700;
    text-align: left;
    border: 0;
    margin: 0 auto;
    margin-top: 0;
    padding-left: 0;
    padding-bottom: 13px;
    outline: none;
    border-bottom: 3px solid #fff;
    display: inline;
    text-transform: uppercase;
}

.search-wrapper .btn {
    display: inline-block;
}

.search-wrapper .close {
    position: fixed;
    top: 15px;
    right: 15px;
    color: #fff;
    background-color: transparent;
    border: none;
    opacity: 1;
    padding: 10px 17px;
    font-size: 27px;
    outline: none;
}

</style>
