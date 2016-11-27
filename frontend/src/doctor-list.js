import {WebAPI} from './web-api';

/**
 * Doctor list view-model
 */
export class DoctorList {
  static inject = [WebAPI];

  /**
   * State variables
   */
  doctors = [];

  /**
   * Constructor
   */
  constructor(api) {
    this.api = api;
  }

  /**
   * Requests list of doctors on creation
   */
  created() {
    this.api.getDoctorList()
      .then(doctors => this.doctors = doctors)
      .catch((error) => console.log(error));
  }
}
