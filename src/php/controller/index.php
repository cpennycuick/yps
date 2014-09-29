<?php

namespace YP\Controller;

class Index {

	public function run () {
		echo <<<HTML
<html>
	<head>
		<title>Title!</title>

		<link href="inc/css/style.less" rel="stylesheet/less" type="text/css" />
		<link href="inc/css/nav.less" rel="stylesheet/less" type="text/css" />
		<link href="inc/css/projectview.less" rel="stylesheet/less" type="text/css" />

		<script src="inc/js/ext/jquery.js" type="text/javascript"></script>

		<script>
			less = {
				env: "development",
				logLevel: 2,
				async: false,
				fileAsync: false,
				functions: {},
				dumpLineNumbers: "comments",
			};
		</script>
		<script src="inc/js/ext/less.js" type="text/javascript"></script>

		<script src="inc/js/app.js" type="text/javascript"></script>
		<script src="inc/js/browse.js" type="text/javascript"></script>
		<script src="inc/js/projectview.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function () {
				var browse = new app.Browse();

				var buffer = 600;
				var \$footer = $('.ContainerFooter');
				var shouldLoadMore = function () {
					return ((\$footer.offset().top - $(window).scrollTop() - buffer) < window.outerHeight);
				};

				var loading = false;
				var loadMore = function () {
					if (!loading && shouldLoadMore()) {
						loading = true;
						browse.loadMore().done(function (result) {
							if (!result['Updated']) {
								\$footer.delay(1000).remove();
								return;
							}

							loading = false;
							loadMore();
						});
					}
				};

				$(window).bind('load scroll resize', function () {
					loadMore();
				});
			});
		</script>

	</head>
	<body>
		<header>
			<a href="#"><div id="Logo"></div></a>
			<nav>
				<ul class="Main">
					<li>
						<a href="#/Browse">
							<span class="Icon"></span>
							<span class="Text">Browse</span>
						</a>
						<ul class="MainSub">
							<li><a href="#/Browse/Technology"><span class="Text">Technology</span></a></li>
							<li><a href="#/Browse/Outdoor"><span class="Text">Outdoor</span></a></li>
							<li><a href="#/Browse/Craft"><span class="Text">Craft</span></a></li>
						</ul>
					</li>
					<li>
						<a href="#/About">
							<span class="Icon"></span>
							<span class="Text">About</span>
						</a>
					</li>
				</ul>
			</nav>
			<div id="Loading">
				<div class="Descriptions"></div>
			</div>
		</header>
		<div class="ContainerWrap">
			<div class="Container"></div>
			<div class="ContainerFooter"><div class="Icon" /></div>
		</div>
	</body>
</html>
HTML;
	}

}
