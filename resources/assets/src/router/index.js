import Vue from 'vue'
import Router from 'vue-router'
import Meta from 'vue-meta'
import axios from 'axios'

import NProgress from 'nprogress';

import globals from '@/globals'

// Layouts
import dashboardsRoutes from './dashboards'
import reportsRoutes from './reports'
import authenticationRoutes from './authentication'
import buyerRoutes from './buyers'
import buyerChannels from './buyerChannels'
import buyerPandaChannels from './buyerPandaChannels'
import buyerPlatChannels from './buyerPlatChannels'
import userRoutes from './user'
import profileRoutes from './profile'
import leadRoutes from './leads'
import settingsRoutes from './settings'
import providerRoutes from './providers'
import integrationRoutes from './integrations'
import portfolioRoutes from './portfolios'
import notFoundRoutes from './notfound'
import redirectRoutes from './redirects'
import redirectSettings from './redirectSettings';

Vue.use(Router)
Vue.use(Meta)

const ROUTES = [
  // Default route
  { path: '', redirect: '/dashboard' }
]
  .concat(dashboardsRoutes)
  .concat(reportsRoutes)
  .concat(authenticationRoutes)
  .concat(buyerRoutes)
  .concat(buyerChannels)
  .concat(buyerPandaChannels)
  .concat(buyerPlatChannels)
  .concat(userRoutes)
  .concat(profileRoutes)
  .concat(leadRoutes)
  .concat(settingsRoutes)
  .concat(providerRoutes)
  .concat(integrationRoutes)
  .concat(portfolioRoutes)
  .concat(notFoundRoutes)
  .concat(redirectRoutes)
  .concat(redirectSettings)

const router = new Router({
  base: '/',
  mode: 'history',
  routes: ROUTES
})

router.afterEach(() => {
  // Remove initial splash screen
  const splashScreen = document.querySelector('.app-splash-screen')
  if (splashScreen) {
    splashScreen.style.opacity = 0
    setTimeout(() => splashScreen && splashScreen.parentNode.removeChild(splashScreen), 300)
  }

  // On small screens collapse sidenav
  if (window.layoutHelpers && window.layoutHelpers.isSmallScreen() && !window.layoutHelpers.isCollapsed()) {
    setTimeout(() => window.layoutHelpers.setCollapsed(true, true), 10)
  }

  // Scroll to top of the page
  globals().scrollTop(0, 0)

  NProgress.done()
})

router.beforeEach(async (to, from, next) => {
  NProgress.start()
    if (window.ability.rules.length <= 0) {
        let permissions = JSON.parse(localStorage.getItem('permissions'))
        window.ability.update(permissions)
    }


  // Set loading state
  document.body.classList.add('app-loading')

  // Add tiny timeout to finish page transition
  setTimeout(() => next(), 10)
})

export default router
