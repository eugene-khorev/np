import {WebAPI} from './web-api';

/**
 * Doctor schedule view-model
 */
export class DoctorSchedule {
  static inject = [WebAPI];

  /**
   * Constructor
   */
  constructor(api){
    this.api = api;
  }

  /**
   * Requests doctor schedule on activation
   */
  activate(params, routeConfig) {
    return this.api.getDoctorSchedule(params.id).then(schedule => {
      this.doctor = params.id;
      this.schedule = schedule;
      this.originalDoctor = params.id;
    });
  }

  /**
   * Checks if there is no request already running
   */
  get canReserve() {
    return !this.api.isRequesting;
  }

  reserve() {
    // this.api.updateSchedule(...).then(result => {
    // });
  }

}
