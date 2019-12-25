import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

var admin = [
  {
    path: '/login',
    name: 'login',
    component: () => import('./views/Login.vue')
  },
  {
    path: '/',
    name: 'home',
    component: () => import('./views/Index.vue'),
    redirect:'dashboard',//在children的后面加一个redirect：'/想要默认展示的子路由名字'
    children: [
      {
        path:'dashboard',
        name:'dashboard',
        component: () => import('./views/Dashboard.vue')
      },
      {
        path:'setting/setting',
        name:'setting',
        component: () => import('./views/setting/Setting.vue')
      },
      {
        path:'setting/adminUser',
        name:'adminUser',
        meta:{
          title:'管理员用户',
        },
        component: () => import('./views/setting/AdminUser.vue')
      },
      {
        path:'setting/adminUser/view',
        name:'adminUserView',
        meta:{
          title:'管理员用户',
        },
        component: () => import('./views/setting/AdminUserView.vue')
      },
      //todo 当前页面携带不同参数跳转到当前页面
      //管理员不能禁用自己逻辑，所以始终有一个能登录的管理员
      {
        path:'setting/adminUser/userUpdate',
        name:'userUpdate',
        meta:{
          title:'管理员用户',
        },
        component: () => import('./views/setting/UserUpdate.vue')
      },
      {
        path:'setting/adminRole',
        name:'adminRole',
        component: () => import('./views/setting/AdminRole.vue')
      },
      {
        path:'setting/adminPermission',
        name:'adminPermission',
        component: () => import('./views/setting/AdminPermission.vue')
      },
      {
        path:'setting/msg',
        name:'msg',
        component: () => import('./views/setting/Msg.vue')
      },
      {
        path:'setting/msgView',
        name:'msgView',
        component: () => import('./views/setting/MsgView.vue')
      },
      {
        path:'setting/log',
        name:'log',
        component: () => import('./views/setting/Log.vue')
      },
      {
        path:'nav',
        name:'nav',
        component: () => import('./views/nav/Nav.vue')
      },
      {
        path:'category/category',
        name:'category',
        component: () => import('./views/category/Category.vue')
      },
      {
        path:'category/category/view',
        name:'categoryView',
        component: () => import('./views/category/View.vue')
      },
      {
        path:'category/model',
        name:'model',
        component: () => import('./views/category/Model.vue')
      },
      {
        path:'content/file',
        name:'file',
        component: () => import('./views/content/File.vue')
      },
      {
        path:'content/page',
        name:'page',
        component: () => import('./views/content/Page.vue')
      },
      {
        path:'content/page/view',
        name:'pageView',
        component: () => import('./views/content/PageView.vue')
      },
      {
        path:'content/product',
        name:'product',
        component: () => import('./views/content/Product.vue')
      },
      {
        path:'content/product/views',
        name:'productView',
        component: () => import('./views/content/ProductView.vue')
      },
      {
        path:'content/news',
        name:'news',
        component: () => import('./views/content/News.vue')
      },
      {
        path:'content/news/views',
        name:'newsView',
        component: () => import('./views/content/NewsView.vue')
      },
      {
        path:'content/video',
        name:'video',
        component: () => import('./views/content/Video.vue')
      },
      {
        path:'content/video/views',
        name:'videoView',
        component: () => import('./views/content/VideoView.vue')
      },
      {
        path:'content/download',
        name:'download',
        component: () => import('./views/content/Download.vue')
      },
      {
        path:'content/download/views',
        name:'downloadView',
        component: () => import('./views/content/DownloadView.vue')
      },
      {
        path:'content/case',
        name:'case',
        component: () => import('./views/content/Case.vue')
      },
      {
        path:'content/case/views',
        name:'caseView',
        component: () => import('./views/content/CaseView.vue')
      },
      {
        path:'content/job',
        name:'job',
        component: () => import('./views/content/Job.vue')
      },
      {
        path:'content/job/views',
        name:'jobView',
        component: () => import('./views/content/JobView.vue')
      },
      {
        path:'marketing/banner',
        name:'banner',
        component: () => import('./views/marketing/Banner.vue')
      },
      {
        path:'marketing/link',
        name:'link',
        component: () => import('./views/marketing/Link.vue')
      },
      {
        path:'marketing/ad',
        name:'ad',
        component: () => import('./views/marketing/Ad.vue')
      },
      {
        path:'user/user',
        name:'user',
        component: () => import('./views/user/User.vue')
      },
      {
        path:'user/userGroup',
        name:'userGroup',
        component: () => import('./views/user/UserGroup.vue')
      },
      {
        path:'user/userSetting',
        name:'userSetting',
        component: () => import('./views/user/UserSetting.vue')
      },
      {
        path:'mobile',
        name:'mobile',
        component: () => import('./views/mobile/Mobile.vue')
      },
      {
        path:'developer/bak',
        name:'bak',
        component: () => import('./views/developer/Bak.vue')
      },
      {
        path:'developer/cloud',
        name:'cloud',
        component: () => import('./views/developer/Cloud.vue')
      },
      {
        path:'developer/runningLog',
        name:'runningLog',
        component: () => import('./views/developer/RunningLog.vue')
      },
      {
        path: '*',
        name: 'error',
        component: () => import('@/components/Error.vue'),
        redirect:'dashboard',//在children的后面加一个redirect：'/想要默认展示的子路由名字'
      },
    ]
  },

]
export default new Router({
  routes: admin
})
