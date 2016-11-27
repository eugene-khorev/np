import {Router} from 'aurelia-router';
import {WebAPI} from './web-api';

/**
 * Login view-model
 */
export class Login {
  static inject = [Router,WebAPI];

  /**
   * State variables
   */
  email = '';
  password = '';
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
   * Login button handler
   */
  login() {
    // Clear errors and authToken
    this.errors = {};
    this.message = '';
    this.api.authToken = null;

    // Run login API request
    this.api.login(this.email, this.password)
      .then((result) => {
        // Clear state and save authToken
        this.login = '';
        this.password = '';
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
   * Checks if fields are empty
   */
  get canLogin() {
    return (this.email != '' && this.password != '');
  }

}
