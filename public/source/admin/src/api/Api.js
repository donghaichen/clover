// const BASE_URL = 'http://www.cms.com/admin/v1'
const BASE_URL = 'http://www.kdnewconnector.com/admin/v1'

export default {
  static:{
    // baseUrl: 'http://www.cms.com',
    baseUrl: 'http://www.kdnewconnector.com/public/',
    geet: 'https://www.geetest.com/demo/gt/register-fullpage?t='
  },
  common: {
    msg: BASE_URL + '/common/msg',
    bing: BASE_URL + '/bing',
    login: BASE_URL + '/login',
    upload: BASE_URL + '/common/upload',
    file: BASE_URL + '/common/file',
    localConfig: BASE_URL + '/common/localConfig',
    setting: BASE_URL + '/common/setting',
    dashboard: BASE_URL + '/common/dashboard',
  },
  user: {
    list: BASE_URL + '/user', 
    adminList: BASE_URL + '/user/adminList', 
    save: BASE_URL + '/user', 
    view: BASE_URL + '/user', 
  },
  log: {
    admin: BASE_URL + '/log/admin', 
  },
  marketing: {
    banner: BASE_URL + '/ad?type=banner', 
    link: BASE_URL + '/ad?type=link&per_page=100',
  },
  category: {
    list: BASE_URL + '/category',
    cascader: BASE_URL + '/category/cascader/all',
    cascaderContent: BASE_URL + '/category/cascader/',
    save: BASE_URL + '/category',
    sort: BASE_URL + '/category',
    remove: BASE_URL + '/category',
    view: BASE_URL + '/category',
  },
  content: {
    page: BASE_URL + '/content/page',
    pageDelete: BASE_URL + '/content/page',
    pageSave: BASE_URL + '/content/page',
    pageView: BASE_URL + '/content/page',
    pageCascader: BASE_URL + '/content/page/cascader',
    contentDelete:  BASE_URL + '/content/contentDelete',
    contentRecommend:  BASE_URL + '/content/contentRecommend',
    list: BASE_URL + '/content/list/',
    view: BASE_URL + '/content/view/',
    save: BASE_URL + '/content/save/',
  },
  developer: {
    runningLog: BASE_URL + '/log/getRunData',
  }
}
