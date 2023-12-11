# source .env
phpunit tests/binhusenstore/user_test.php
phpunit tests/binhusenstore/access_code_test.php
phpunit tests/binhusenstore/cart_test.php
phpunit tests/binhusenstore/category_test.php
phpunit tests/binhusenstore/order_test.php
phpunit tests/binhusenstore/payment_test.php
phpunit tests/binhusenstore/payment_details_test.php
phpunit tests/binhusenstore/product_test.php
phpunit tests/binhusenstore/testimony_test.php
phpunit tests/binhusenstore/date_test.php
sleep 6

#run all those command by using unit_test_binhusenstore.sh

# multiple test unit_test_binhusenstore.sh && unit_test_binhusenstore.sh && unit_test_binhusenstore.sh && unit_test_binhusenstore.sh && unit_test_binhusenstore.sh