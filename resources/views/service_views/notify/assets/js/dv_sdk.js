/**
@author Devless
@version 0.01
@description Devless sdk for Javascript	
*/
/* Initizialize library */

(function(global /*this will contain the global window object*/) {
	"use strict";

	var baseUrl = "/api/v1/service/"; //makes reference to service url easier

	//this func help create devless instance without having to use the new operator. 
	//which is easy to forget sometimes.(idea borrowed from jquery)
	var Devless = function(constants) {
		//just return if nothing is passed. no need to instantiate
		if (!constants) {
			console.error("Your app failed to  connect to Devless ): Please make sure token and key is set properly ");
			return;
		}
		console.info("App is trying to connect to Devless ...");
		var sub_url = baseUrl + "/dvauth/script";
		var data = {};
		var DevlessInstance = new Devless.init(constants);
		global.returnedInstance = ''; //serve a hook for devless instance . will be taken off if i find a better way
         
        //call is used to control the (this) instance to use ,bcoz requestProcessor is treated as private function 
		requestProcessor.call(DevlessInstance, data, sub_url, "POST", function(response) {
			response = JSON.parse(response);
			if (response.status_code == 631) {

				console.error("Your app failed to  connect to Devless ): Please make sure token and key is set properly ");
			} else if (response.status_code == 1000) {

				console.debug("Your app connected to Devless successfully and you have auth service installed");
				returnedInstance = DevlessInstance; //and returns a new Devless instance only if connected successfully

			} else {
				console.debug("your app connected to Devless successfully. you can get services from store.devless.io ");

				returnedInstance = DevlessInstance; //returns a new Devless instance only if connected successfully

			}
		}, true);

		return DevlessInstance;
	}

	Devless.prototype = {
		queryData: queryData,
		addData: addData,
		updateData: updateData,
		deleteData: deleteData,
		getToken: getToken,
		setToken: setToken,
		call: call
	};

	Devless.init = function(constants) {
		var Self = this; //using this can be ambigiouse in certain context. so  i aliased it to point to this very constructor.
		Self.devless_token = constants.token;
		Self.devless_instance_url = constants.domain;
	}

	Devless.init.prototype = Devless.prototype;



	//add options to params object
	function queryData(serviceName, table, params, callback) {
		params = params || {};
		var parameters = "";

		//get nested params 
		var innerParams = function(key, params) {
				for (var eachParam in params) {
					parameters = "&" + key + "=" + params[eachParam] + parameters;
				}

			}
			//organise parameters
		for (var key in params) {
			if (!params.hasOwnProperty(key)) { /**/ }
			if (params[key] instanceof Array) {
				innerParams(key, params[key])
			} else {
				parameters = "&" + key + "=" + params[key] + parameters;
			}

		}

		var sub_url = baseUrl + serviceName + "/db?table=" + table + parameters;

		requestProcessor.call(this, "", sub_url, "GET", function(response) {
			callback(response);
		})
		return this;
	}

	function addData(serviceName, table, data, callback) {

		var payload = JSON.stringify({
			"resource": [{
					"name": table,
					"field": [

						data
					]
				}

			]
		});

		var sub_url = baseUrl + serviceName + "/db";
		requestProcessor.call(this, payload, sub_url, "POST", function(response) {

			callback(response);

		});
		return this;

	}

	function updateData(serviceName, table, where_key, where_value, data, callback) {

		var payload = JSON.stringify({
			"resource": [{
					"name": table,
					"params": [{
						"where": where_key + "," + where_value,
						"data": [
							data
						]

					}]
				}

			]
		});

		var sub_url = baseUrl + serviceName + "/db";
		requestProcessor.call(this, payload, sub_url, "PATCH", function(response) {
			callback(response);
		});
		return this;
	}

	function deleteData(serviceName, table, where_key, where_value, callback) {

		var payloadObj = {
			"resource": [{
					"name": table,
					"params": [{
						"where": where_key + ",=," + where_value
					}]
				}

			]
		};

		payloadObj.resource[0].params[0]['delete'] = "true";

		var payloadStr = JSON.stringify(payloadObj);

		var sub_url = baseUrl + serviceName + "/db";

		requestProcessor.call(this, payloadStr, sub_url, "DELETE", function(response) {

			callback(response);

		});
		return this;
	}

	function getToken(callback) {
		var withCallback = callback || false;
		if (withCallback) {
			callback(sessionStorage.getItem('devless_user_token' + this.devless_instance_url + this.devless_token));
		} else {

			return sessionStorage.getItem('devless_user_token' + this.devless_instance_url + this.devless_token)
		}

	}

	function setToken(token) {
		sessionStorage.setItem('devless_user_token' + this.devless_instance_url + this.devless_token, token);
		return true;
	}

	function call(service, method, params, callback) {

		var payload = JSON.stringify({
			"jsonrpc": "2.0",
			"method": service,
			"id": getId(1, 10000000),
			"params": params
		});

		var sub_url = baseUrl + service + "/rpc?action=" + method;

		requestProcessor.call(this, payload, sub_url, "POST", function(response) {

			callback(response);

		});
	}

	function getId(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}



	//Took off the requestPrecessor from the base prototype to make it private for internal operations only.
	//it is inaccessible outside but can be called within because of its lexical scope with respect to the
	//other function. 
	function requestProcessor(data, sub_url, method, callback, parse) {
		parse = parse || false;

		var xhr = new XMLHttpRequest();

		xhr.addEventListener("readystatechange", function() {


			var response = '';
			if (this.readyState === 4 && parse == false) {
				if (this.status == 200) {
					response = JSON.parse(this.responseText);
					callback(response);
				} else {
					callback(response);
					console.error("Devless cannot be found at " + this.devless_instance_url + " Please copy the url from the `App tab`  on you Devless instance by clicking on  `connect to my app`")
				}


			} else if (this.readyState === 4 && parse == true) {

				if (this.status == 200) {
					response = this.responseText;
					callback(response);
				} else {
					callback(response);
					console.error("Devless cannot be found at " + this.devless_instance_url + " Please copy the url from the `App tab`  on you Devless instance by clicking on  `connect to my app`")
				}
			}
		});

		xhr.open(method.toUpperCase(), this.devless_instance_url + sub_url);
		xhr.setRequestHeader("content-type", "application/json");
		xhr.setRequestHeader("devless-token", this.devless_token);
		if (sessionStorage.getItem('devless_user_token' + this.devless_instance_url + this.devless_token) != "") {

			xhr.setRequestHeader("devless-user-token", sessionStorage.getItem('devless_user_token' + this.devless_instance_url + this.devless_token));
		}



		xhr.send(data);

	}
	global.Devless = global.DV = Devless; //exposes devless to the world;
})(window /*injects the window object into the library.*/);
