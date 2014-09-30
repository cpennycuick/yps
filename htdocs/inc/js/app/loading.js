app.loading = {};

(function (loading) {

	var count = 0;
	var $loading = $();
	var $loadingDescs = $();

	loading.init = function () {
		$loading = $('#Loading');
		$loadingDescs = $loading.find('.Descriptions');
	};

	loading.show = function (description) {
		count += 1;
		$loading.addClass('isLoading');

		var $description = $('<div></div>')
			.text(description)
			.appendTo($loadingDescs);

		return {
			hide: function () {
				$description.remove();
				loading.hide();
			}
		};
	};

	loading.hide = function (force) {
		count -= 1;
		if (count <= 0 || force) {
			count = 0;
			$loading.removeClass('isLoading');
		}
	};

})(app.loading);
