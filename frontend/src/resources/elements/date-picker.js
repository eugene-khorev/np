//bootstrap-datepicker/js/bootstrap-datepicker
//import $ from 'jquery';
import pickmeup from 'pickmeup';

export class DatePickerCustomElement {
  constructor() {
  }

  bind() {
    pickmeup(this.container, {
        flat : true,
        format  : 'Y-m-d',
        min: new Date()
    })
  }
}
