import {Router} from 'aurelia-router';
import {EventAggregator} from 'aurelia-event-aggregator';
import {WebAPI} from './web-api';
import {Unauthorized} from './messages';

/**
 * Main app view-model
 */
export class App {
  static inject = [WebAPI,EventAggregator,Router];

  /**
   * Constructor
   */
  constructor(api, ea, router) {
    this.api = api;
    this.router = router
    
    // Subscribe to event "Unauthorized" (it invokes by WebAPI on 403 status)
    ea.subscribe(Unauthorized, msg => router.navigateToRoute('auth'));
  }

  /**
   * Defines router configuration
   */
  configureRouter(config, router) {
    config.title = 'Napopravku';
    config.map([
      { route: '',              moduleId: 'auth',            name: 'auth'},
      { route: 'login',         moduleId: 'login',           name: 'login'},
      { route: 'register',      moduleId: 'register',        name: 'register'},
      { route: 'doctors',       moduleId: 'doctor-list',     name: 'doctor-list' },
      { route: 'doctors/:id',   moduleId: 'doctor-schedule', name: 'doctor-schedule' },
    ]);

    this.router = router;
  }
  
  /**
   * Logout button handler
   */
  logout() {
    this.api.logout()
      .then(() => {
        this.api.authToken = '';
        this.router.navigateToRoute('auth');
      })
  }
  
  /**
   * Checks if user is authorized
   */
  get isAuhthorized() {
      return this.api.authToken != '';
  }
}
