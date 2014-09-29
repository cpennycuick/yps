app.Browse = (function () {
	
	var c = function () {
		this._$container = $('.Container');
		this._$footer = $('.ContainerFooter');
		this._$template = {
			'Project': '<a href="#/Project/<:ProjectID>"><div class="Project"><div class="Title"><:Title></div></div></a>',
		};

		this._lastPage = 0;
		
		this._$container.on('click', 'a', function () {
			var projectID = $(this).data('ProjectID');
			new app.ProjectView(projectID);
		});
	};
	
	c.prototype = {
		loadMore: function () {
			var loading = app.loading.show('Loading Browse...');
			var $footer = this._$footer.addClass('isLoading');
			
			var dfd = $.Deferred();
			var req = app.request.postJSON('Project/getList', {
				'LastPage': this._lastPage,
				'Filter': {}
			});
			
			req.always(function () {
				loading.hide();
				$footer.removeClass('isLoading');
			});
			
			req.done((function (data) {
				var updated = this._render(data['Records']);
				dfd.resolve({'Updated': updated});
			}).bind(this));

			req.fail(function () {
				console.log('Show load error - try again button.');
				dfd.reject();
			});
			
			this._lastPage += 1;
			
			return dfd.promise();
		},
		
		_render: function (records) {
			if (!records || !records.length) {
				// TODO remove future loading
				return false;
			}
			
			var self = this;
			var $items = $();
			$.each(records, function (i, record) {
				$items = $items.add(self._preRenderItem(record));
			});
			
			this._$container.append($items);
			
			return true
		},
		_preRenderItem: function (record) {
			var itemHTML = this._$template['Project'].replace(/<:(.*?)>/g, function (m, m1) {
				return record[m1];
			});
			
			var $item = $(itemHTML);
			
			$item.data('ProjectID', record['ProjectID']);
			
			return $item;
		}
	};
	
	return c;

})();
