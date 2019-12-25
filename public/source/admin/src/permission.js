import router from './router'
import { LoadingBar } from 'view-design'

router.beforeEach(async (to, from, next) => {
  LoadingBar.start()
  if (localStorage.getItem('token')) {
    next()
  }else {
    if (to.path === '/login') {
      next()
    } else {
      next(`/login?redirect=${to.path}`)
    }
  }
})

router.afterEach(() => {
  LoadingBar.finish()
})
