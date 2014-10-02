var app = window.app = {};

$(document).on('click', 'a', function (event) {
	history.pushState(null, null, this.href);

	event.preventDefault();
});

$(function () {
	$.each(app, function (name, object) {
		if ('init' in object) {
			object.init();
		}
	});

});
