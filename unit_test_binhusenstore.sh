# source .env
vendor/bin/phpunit tests/binhusenstore/user_test.php
vendor/bin/phpunit tests/binhusenstore/access_code_test.php
vendor/bin/phpunit tests/binhusenstore/cart_test.php
vendor/bin/phpunit tests/binhusenstore/category_test.php
vendor/bin/phpunit tests/binhusenstore/order_test.php
vendor/bin/phpunit tests/binhusenstore/payment_test.php
vendor/bin/phpunit tests/binhusenstore/payment_details_test.php
vendor/bin/phpunit tests/binhusenstore/product_test.php
vendor/bin/phpunit tests/binhusenstore/testimony_test.php
vendor/bin/phpunit tests/binhusenstore/date_test.php
vendor/bin/phpunit tests/binhusenstore/admin_charge_test.php
vendor/bin/phpunit tests/binhusenstore/product_archived_test.php
sleep 6

#run all those command by using unit_test_binhusenstore.sh

# multiple test unit_test_binhusenstore.sh && unit_test_binhusenstore.sh && unit_test_binhusenstore.sh && unit_test_binhusenstore.sh && unit_test_binhusenstore.sh