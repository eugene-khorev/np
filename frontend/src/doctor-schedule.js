import {WebAPI} from './web-api';
import moment from 'moment';

/**
 * Doctor schedule view-model
 */
export class DoctorSchedule {
  static inject = [WebAPI];

  /**
   * State variables
   */
  selectedDate = moment().format('YYYY-MM-DD');
  selectedTime = '00:00:00';
  error = false;
  success = false;

  /**
   * Constructor
   */
  constructor(api){
    this.api = api;
  }

  /**
   * Requests doctor schedule on activation
   */
  activate(params) {
    return this.getSchedule(params.id);
  }

  /**
   * Updates schedule from server
   */
  getSchedule(doctorId) {
    return this.api.getDoctorSchedule(doctorId, this.selectedDateTime).then(schedule => {
      this.doctor = doctorId;
      this.schedule = schedule;
      this.originalDoctor = doctorId;
    });
  }

  /**
   * Sets selected date and retrieves schedule from server
   */
  setDate(date) {
    this.selectedDate = moment(date).format('YYYY-MM-DD');
    return this.getSchedule(this.doctor)
  }

  /**
   * Sets selected time
   */
  setTime(time) {
    this.selectedTime = time + ':00';
  }

  /**
   * Returns selected date and time
   */
  get selectedDateTime() {
    return this.selectedDate + ' ' + this.selectedTime;
  }

  /**
   * Checks if there is no request already running
   */
  get canReserve() {
    return (!this.api.isRequesting && this.selectedTime != '00:00:00');
  }

  /**
   * Requests server to reserve schedule time
   */
  reserve() {
    this.error = false;

    this.api.updateDoctorSchedule(this.doctor, this.selectedDateTime)
      .then(result => {
        this.success = 'You have successfuly reserved visit time';
      })
      .catch(error => {
        this.error = error.message
      });
  }
}
