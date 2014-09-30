app.request = {};

(function (request) {

	request.postJSON = function (path, data) {
		var url = 'https://php_test-c9-chrispennycuick.c9.io/index.php?path=/'+(path || '');
		var dfd = $.Deferred();

		$.ajax({
			'url': url,
			'type': 'POST',
			'contentType': 'application/json',
			'dataType': 'json',
			'data': JSON.stringify(data || {}),
		}).done(function (data) {
			var payload = parseResponse(data);
			if (!payload) {
				dfd.reject()
			} else {
				dfd.resolve(payload);
			}
		}).fail(function (jqXHR, status, error) {
			alert('Request Error');
			console.error('Request Error', arguments);
			dfd.reject();
		});

		return dfd.promise();
	};

	var parseResponse = function (response) {
		if (response['Code'] == 200) {
			return response['Payload'];
		} else if (response['Error'] == 'UserError') {
			alert(response['Message']);
			return false;
		}

		alert('Internal Server Error');
		console.log('Error', response);
		return false;
	};

})(app.request);
