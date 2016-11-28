import pickmeup from 'pickmeup';

export class DatePickerCustomElement {
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

    // Setup date picker
    pickmeup(this.element, {
        flat : true,
        format  : 'Y-m-d',
        min: new Date()
    });

    // Setup date change event
    this.element.addEventListener('pickmeup-change', (e) => this.parent.setDate(e.detail.date));
  }
}
