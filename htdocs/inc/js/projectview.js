app.ProjectView = (function () {
	
	var c = function (projectID) {
		this._projectID = projectID;
		
		this._$cloak = $('<div class="Cloak"></div>').appendTo($('body')).fadeIn(200);
		this._$container = $('<div class="ProjectView"></div>');
		this._$template = {
			'Project': '<div class="Title"><:Title></div><div class="Description"><:Description></div>',
		};
		
		var self = this;
		this._$cloak.click(function (event) {
			if (event.target == self._$cloak.get(0)) {
				self._close();
			}
		});
		
		this._scrollTop = $(window).scrollTop();
		$(window).scrollTop(0);
		
		$('body').addClass('ProjectViewOpen');
		$('.Container').css('top', -this._scrollTop);
		
		this.load();
	};
	
	c.prototype = {
		load: function () {
			var loading = app.loading.show('Loading Project...');

			var dfd = $.Deferred();
			var req = app.request.postJSON('Project/getView', {
				'ProjectID': this._projectID
			});
			
			req.always(function () {
				loading.hide();
			});
			
			req.done((function (data) {
				this._render(data['Record']);
				dfd.resolve();
			}).bind(this));
			
			req.fail(function () {
				console.log('Show load error - try again button.');
				dfd.reject();
			});
			
			return dfd.promise();
		},
		
		_render: function (project) {
			if (!project) {
				return;
			}
			
			var projectHTML = this._$template['Project'].replace(/<:(.*?)>/g, function (m, m1) {
				return project[m1];
			});
			
			var $project = $(projectHTML);
			this._$container.append($project);
			
			this._doBinding($project);

			this._$cloak.append(this._$container);
			this._$container.fadeIn(200);
		},
		_doBinding: function ($project) {

		},
		
		_close: function () {
			var self = this;
			this._$cloak.fadeOut(200, function () {
				$(this).remove();
				$('body').removeClass('ProjectViewOpen');
				$('.Container').css('top', null);
				$(window).scrollTop(self._scrollTop);
			});
		}
	};
	
	return c;

})();
