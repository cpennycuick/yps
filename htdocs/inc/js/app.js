var app = window.app = {};

app.request = {};
app.request.postJSON = function (path, data) {
	var url = 'https://php_test-c9-chrispennycuick.c9.io/index.php?path=/'+(path || '');
	var dfd = $.Deferred();
	
	$.ajax({
		'url': url,
		'type': 'POST',
		'contentType': 'application/json',
		'dataType': 'json',
		'data': JSON.stringify(data || {}),
	}).done(function (data) {
		if (data['Code'] == 200) {
			dfd.resolve(data['Payload']);
		} else {
			console.error('Server Error', data);
			dfd.reject()
		}
	}).fail(function (jqXHR, status, error) {
		console.error('Request Failed', arguments);
		dfd.reject();
	});
	
	return dfd.promise();
};

app.loading = {
	layers: 0,
	$loading: $(),
	$loadingDescs: $(),
	init: function () {
		this.$loading = $('#Loading');
		this.$loadingDescs = this.$loading.find('.Descriptions');
	},
	show: function (description) {
		this.layers += 1;
		this.$loading.addClass('isLoading');
		
		var $description = $('<div></div>')
			.text(description)
			.appendTo(this.$loadingDescs);
		
		return {
			hide: function () {
				$description.remove();
				app.loading.hide();
			}
		};
	},
	hide: function (force) {
		this.layers -= 1;
		if (this.layers <= 0 || force) {
			this.layers = 0;
			this.$loading.removeClass('isLoading');
		}
	}
};

$(function () {
	app.loading.init();
});
