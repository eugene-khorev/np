import {HttpClient} from 'aurelia-http-client';
import {EventAggregator} from 'aurelia-event-aggregator';
import {Unauthorized} from './messages';

let client = new HttpClient();

let id = 0;

function getId(){
  return ++id;
}

/**
 * API error class
 * Stores specific JSON RPC error data
 */
class WebAPIError extends Error {
  /**
   * Constructor
   */
  constructor(message, code, data) {
    // We don't need a message if we got detailed error info
    if (data) {
      message = '';
    }

    // Init an instance
    super(message, null, null);

    this.code = code;
    this.data = data;
  }
}

/**
 * JSON RPC client
 */
export class WebAPI {
  static inject = [EventAggregator];

  /**
   * Requesting state (for loading indicator)
   */
  isRequesting = false;

  /*
   * Constructor
   */
  constructor(ea) {
    this.ea = ea;
  }

  /**
   * Saves authToken to local storage
   */
  set authToken(token) {
    localStorage.setItem('authToken', token || '');
  }

  /**
   * Loads authToken from local storage
   */
  get authToken() {
    return localStorage.getItem('authToken');
  }

  /**
   * Returns interceptor that adds token to authorization HTTP header
   */
  get tokenInterceptor() {
    let token = this.authToken;
    return {
      request(request) {
        if ('string' === typeof token && token.length > 0) {
          request.headers.add('Authorization', 'Bearer ' + token);
        }
      }
    }
  }

  /**
   * Makes JSON RPC request. Returns promise
   */
  makeRequest(url, method, params) {
    // Set state of loading indicator
    this.isRequesting = true;

    // Create request promise
    return new Promise((resolve, reject) => {
      client.createRequest(url)
        .asPost()
        .withBaseUrl('/')
        .withInterceptor(this.tokenInterceptor)
        .withResponseType('json')
        .withContent({
          "jsonrpc": "2.0",
          "method": method,
          "params": params,
          "id": getId()
        })
        .send()
        .then((response) => { // Normal JSON RPC response
          // Check error codes
          if (response.content.error) {
            switch(response.content.error.code) {
              case 403: // Forbiden
                this.authToken = '';
                this.ea.publish(new Unauthorized())
                break;

              default:
            }
          }

          // Check if we got a result
          if (response.content.result) {
            // We're fine
            resolve(response.content.result);
          } else {
            // We got error
            let error = new WebAPIError(
              response.content.error.message,
              response.content.error.code,
              response.content.error.data,
            )
            reject(error);
          };

          // Set state of loading indicator
          this.isRequesting = false;
        })
        .catch((response) => { // Error on server
          alert('Server error. Please try later...')

          let error = new WebAPIError(
            response.statusText,
            response.statusCode
          )
          reject(error);
          this.isRequesting = false;
        });
    });
  }

  /**
   * Makes authentication JSON RPC request
   */
  login(email, password){
    return this.makeRequest('auth/rpc', 'Login', {
      data: {
        email: email,
        password: password
      }
    });
  }

  /**
   * Makes logout JSON RPC request
   */
  logout(email, password){
    return this.makeRequest('auth/rpc', 'Logout', {});
  }

  /**
   * Makes registration JSON RPC request
   */
  register(email, password, passwordRepeat){
    return this.makeRequest('auth/rpc', 'Register', {
      data: {
        email: email,
        password: password,
        password_repeat: passwordRepeat
      }
    });
  }

  /**
   * Makes doctor list JSON RPC request
   */
  getDoctorList(){
    return this.makeRequest('doctor/rpc', 'GetList');
  }

  /**
   * Makes doctor schedule JSON RPC request
   */
  getDoctorSchedule(id, date){
    return this.makeRequest('doctor/rpc', 'GetSchedule', {
      data: {
        doctorId: id,
        reservationTime: date,
      }
    });
  }

  /**
   * Makes doctor schedule reservation JSON RPC request
   */
  updateDoctorSchedule(id, date){
    return this.makeRequest('doctor/rpc', 'UpdateSchedule', {
      data: {
        doctorId: id,
        reservationTime: date,
      }
    });
  }

}
