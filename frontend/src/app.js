import {Router} from 'aurelia-router';
import {EventAggregator} from 'aurelia-event-aggregator';
import {WebAPI} from './web-api';

export class App {
  static inject = [WebAPI,EventAggregator,Router];

  constructor(api,ea,router) {
    this.api = api;
  }

  configureRouter(config, router){
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
}
