import {bindable} from 'aurelia-framework';
import moment from 'moment';

export class TimeSliderCustomElement {
  @bindable() schedule;
  @bindable() selectedDate;
  @bindable() selectedTime;

  /**
   * Constructor
   */
  constructor() {

  }

  /**
   * Initializes custom element on bind
   */
  bind(bindingContext) {
    this.parent = bindingContext;
    this.scheduleChanged();
  }

  /**
   * Rebuilds slider when schedule changed
   */
  scheduleChanged() {
    this.slider = [];

    // Convert schedule to convinient time format
    let reservedTime = this.schedule.reserved.map((range) => {
      return {
        from: moment(range.reserved_from),
        till: moment(range.reserved_till),
      }
    });

    // Setup time boundaries (doctor's work time)
    let midnight = moment(this.selectedDate).startOf('day');
    let fromTime = midnight.clone().add(this.schedule.reservation_from, 'hours');
    let tillTime = midnight.clone().add(this.schedule.reservation_till, 'hours');

    // Generate slider ranges
    while (fromTime < tillTime) {
      let reserved = false;

      // Check if range is already reserved
      for (let i in reservedTime) {
        let from = reservedTime[i].from;
        let till = reservedTime[i].till;

        reserved = from.diff(fromTime, 'seconds') <=0
                && till.diff(fromTime, 'seconds') > 0;

        if (reserved) {
          break;
        }
      }

      // Add new range
      let range = {
        time: fromTime.format('HH:mm'),
        reserved: reserved,
      };
      this.slider.push(range);

      // Calculate next range start time
      fromTime.add(this.schedule.reservation_time, 'seconds');
    }
  }
}
