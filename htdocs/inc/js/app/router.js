app.router = {};

(function (router) {

	var routes = {
		'/Project/2/': function () {
			alert('Project');
		}
	};

	router.init = function () {

		if (location.pathname in routes) {
			routes[location.pathname]();
		}
	};

})(app.router);
