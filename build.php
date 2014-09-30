<?php

echo "Compilling LESS...\n";

exec('lessc src/css/bootstrap.less htdocs/inc/css/yps_min.css');

echo "DONE!\n";
