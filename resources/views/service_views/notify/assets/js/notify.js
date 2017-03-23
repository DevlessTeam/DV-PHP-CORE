$(function() {
	$('.loader').hide();

	$("#randomSmsForm").submit(function(e) {
		e.preventDefault();
		var UsersNumbers = $('#randomUserNumbers').val().replace(/\s/g, "").split(',');
		var message = $('#randomMessage').val();

		$('.loader').fadeIn('slow');
		console.log([UsersNumbers, message]);

		SDK.call('notify', 'sms', [UsersNumbers, message], function(response) {
			$('.loader').hide();
			if (response.payload.error) {
				toastr.error('Sms not Sent. Try again or contact Devless Support', 'Error');
			} else {

				toastr.success('Sms Sent', 'Success');
			}
			console.log(response);
		});


	});



	$("#smsWithInfo").submit(function(e) {
		e.preventDefault();
		$('.loader').fadeIn('slow');

		var usersNumberColumn = $('#usersNumberColumn').val().replace(/\s/g, "");
		var userInfoColumns = $('#userInfoColumns').val().replace(/\s/g, "");
		var condition = $('#condition').val().replace(/\s/g, "").split(',');
		var limit = $('#limit').val().replace(/\s/g, "");
		var serviceName = $('#serviceName').val().replace(/\s/g, "");
		var table = $('#table').val().replace(/\s/g, "");
		var message = $('#message').val();

		SDK.call('notify', 'smsFromRecords', [message, userInfoColumns, serviceName, table, usersNumberColumn, condition, limit], function(response) {
			$('.loader').hide();
			if (response.payload.error) {
				toastr.error('Sms not Sent. Try again or contact Devless Support', 'Error');
				console.log(response.payload.error);
			} else {

				toastr.success('Sms Sent', 'Success');
			}

		});

	});


	$("#randomEmailForm").submit(function(e) {
		e.preventDefault();
		var UsersEmails = $('#randomEmails').val().replace(/\s/g, "").split(',');
		var subject = $('#randomSubject').val();
		var message = $('#randomMessage').val();


		$('.loader').fadeIn('slow');
		console.log([UsersEmails, subject, message]);

		SDK.call('notify', 'email', [UsersEmails, subject, message], function(response) {
			$('.loader').hide();
			if (response.payload.error) {
				toastr.error('Email not Sent. Try again or contact Devless Support', 'Error');
			} else {

				toastr.success('Email Sent', 'Success');
			}

		});


	});



	$("#EmailWithInfo").submit(function(e) {
		e.preventDefault();
		$('.loader').fadeIn('slow');

		var usersEmailColumn = $('#usersEmailColumn').val().replace(/\s/g, "");
		var userInfoColumns = $('#userInfoColumns').val().replace(/\s/g, "");
		var condition = $('#condition').val().replace(/\s/g, "").split(',');
		var limit = $('#limit').val().replace(/\s/g, "");
		var serviceName = $('#serviceName').val().replace(/\s/g, "");
		var table = $('#table').val().replace(/\s/g, "");
		var emailSubject = $('#emailsubject').val();
		var message = $('#message').val();

		SDK.call('notify', 'emailFromRecords', [emailSubject, message, userInfoColumns, serviceName, table, usersEmailColumn, condition, limit], function(response) {
			$('.loader').hide();
			if (response.payload.error) {
				toastr.error('Email not Sent. Try again or contact Devless Support', 'Error');
				console.log(response.payload.error);
			} else {

				toastr.success('Email Sent', 'Success');
			}

		});

	});

	$("#randomPushForm").submit(function(e) {
		e.preventDefault();
		var UserChannel = $('#randomUserChannel').val().replace(/\s/g, "");
		var event = $('#randomEvent').val().replace(/\s/g, "");
		var randomMessage = $('#randomMessage').val();

		$('.loader').fadeIn('slow');
		console.log([UserChannel, event, randomMessage]);

		SDK.call('notify', 'push', [UserChannel, event, randomMessage], function(response) {
			$('.loader').hide();
			if (response.payload.error) {
				toastr.error('Push Notification not Sent. Try again or contact Devless Support', 'Error');
			} else {

				toastr.success('Push Notification', 'Success');
			}

		});


	});



	$("#pushWithInfo").submit(function(e) {
		e.preventDefault();
		$('.loader').fadeIn('slow');

		var usersChannelColumn = $('#usersChannelColumn').val().replace(/\s/g, "");
		//var usersChannel = $('#usersChannel').val().replace(/\s/g, "");
		var event = $('#event').val().replace(/\s/g, "");
		var userInfoColumns = $('#userInfoColumns').val().replace(/\s/g, "");
		var condition = $('#condition').val().replace(/\s/g, "").split(',');
		var limit = $('#limit').val().replace(/\s/g, "");
		var serviceName = $('#serviceName').val().replace(/\s/g, "");
		var table = $('#table').val().replace(/\s/g, "");
		var usermessage = $('#usermessage').val();

		SDK.call('notify', 'pushFromRecords', ['', event, usermessage, userInfoColumns, serviceName, table, usersChannelColumn, condition, limit], function(response) {
			$('.loader').hide();
			if (response.payload.error) {
				toastr.error('Push Notification not Sent. Try again or contact Devless Support', 'Error');
				console.log(response);
			} else {

				toastr.success('Push Notification Sent', 'Success');
			}

		});

	});


	SDK.queryData("notify", "sms_config", params = {}, function(res) {
		if (res.payload.results[0].sender_number && res.payload.results[0].auth_token && res.payload.results[0].account_id) {
			$('#senderNumber').val(res.payload.results[0].sender_number);
			$('#authToken').val(res.payload.results[0].auth_token);
			$('#accountId').val(res.payload.results[0].account_id);
		} else {
			toastr.warning('You cant send sms without setting up', 'Pssssss');
		}


	});



	SDK.queryData("notify", "email_config", params = {}, function(res) {
		if (res.payload.results[0].sender_email && res.payload.results[0].sender_name && res.payload.results[0].api_key) {
			$('#senderEmail').val(res.payload.results[0].sender_email);
			$('#senderName').val(res.payload.results[0].sender_name);
			$('#apiKey').val(res.payload.results[0].api_key);
		} else {
			toastr.warning('You cant send sms without setting up', 'Pssssss');
		}
	});



	SDK.queryData("notify", "push_config", params = {}, function(res) {
		if (res.payload.results[0].app_id && res.payload.results[0].app_key && res.payload.results[0].app_secret && res.payload.results[0].app_options && res.payload.results[0].broadcast_event && res.payload.results[0].broadcast_channel) {
			$('#appID').val(res.payload.results[0].app_id);
			$('#appkey').val(res.payload.results[0].app_key);
			$('#appOptions').val(res.payload.results[0].app_options);
			$('#appsecrete').val(res.payload.results[0].app_secret);
			$('#brodEvent').val(res.payload.results[0].broadcast_event);
			$('#brodChannel').val(res.payload.results[0].broadcast_channel);
		} else {
			toastr.warning('You cant send Push notification without setting up', 'Pssssss');
		}
	});

	$("#smsSettings").submit(function(e) {
		e.preventDefault();
		console.log('sms update');
		data = {
			"sender_number": $('#senderNumber').val(),
			"auth_token": $('#authToken').val(),
			"account_id": $('#accountId').val()
		};

		SDK.updateData("notify", "sms_config", "id", "1", data,function  (response) {
			if (response.payload.error) {
				toastr.error('Sms settings not updated. Try again or contact Devless Support', 'Error');
				console.log(response);
			} else {

				toastr.success('Sms settings Updated', 'Success');
			}
		});
	});

	$("#emailSettings").submit(function(e) {
		e.preventDefault();
		console.log('email update');
		data = {
			"sender_email": $('#senderEmail').val(),
			"sender_name": $('#senderName').val(),
			"api_key": $('#apiKey').val()
		};
		SDK.updateData("notify", "email_config", "id", "1", data,function  (response) {
			if (response.payload.error) {
				toastr.error('Email settings not updated. Try again or contact Devless Support', 'Error');
				console.log(response);
			} else {

				toastr.success('Email  settings updated', 'Success');
			}
		});
	});

	$("#pushsettings").submit(function(e) {
		e.preventDefault();
		console.log('push update');
		data = {
			"app_id" :$('#appID').val(),
			"app_key" :$('#appkey').val(),
			"app_options" :$('#appOptions').val(),
			"app_secret" :$('#appsecrete').val(),
			"broadcast_event" :$('#brodEvent').val(),
			"broadcast_channel" :$('#brodChannel').val()
		};
		SDK.updateData("notify", "push_config", "id", "1", data,function  (response) {
			if (response.payload.error) {
				toastr.error('Push Notification settings not updated. Try again or contact Devless Support', 'Error');
				console.log(response);
			} else {

				toastr.success('Push Notification  settings updated', 'Success');
			}
		});
	});

	

});