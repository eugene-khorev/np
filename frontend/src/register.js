import {Router} from 'aurelia-router';
import {WebAPI} from './web-api';

/**
 * Registration view-model
 */
export class Register {
  static inject = [Router,WebAPI];

  /**
   * State variables
   */
  email = '';
  password = '';
  passwordRepeat = '';
  errors = {};
  message = '';

  /**
   * Constructor
   */
  constructor(router, api) {
    this.router = router;
    this.api = api;
  }

  /**
   * Register button handler
   */
  register() {
    // Clear errors and authToken
    this.errors = {};
    this.message = '';
    this.api.authToken = null;

    // Run registration API request
    this.api.register(this.email, this.password, this.passwordRepeat)
      .then((result) => {
        // Clear state and save authToken
        this.login = '';
        this.password = '';
        this.passwordRepeat = '';
        this.api.authToken = result.token;
        
        // Switch to doctor list
        this.router.navigateToRoute('doctor-list');
      })
      .catch((error) => {
        // Set error state
        this.errors = error.data;
        this.message = error.message;
      })
  }

  /**
   * Checks if there are email errors
   */
  get hasEmailError() {
    return (this.errors && this.errors.email) || false;
  }

  /**
   * Checks if there are password errors
   */
  get hasPasswordError() {
    return (this.errors && this.errors.password) || false;
  }

  /**
   * Checks if fields are empty
   */
  get canRegister() {
    return (this.email != '' && this.password != '' && this.passwordRepeat != '');
  }

}
