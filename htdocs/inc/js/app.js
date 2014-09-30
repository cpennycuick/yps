var app = window.app = {};


$(function () {
	$.each(app, function (name, object) {
		if ('init' in object) {
			object.init();
		}
	})
});
