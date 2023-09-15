# source .env
phpunit tests/binhusenstore/user_test.php
phpunit tests/binhusenstore/cart_test.php
phpunit tests/binhusenstore/category_test.php
phpunit tests/binhusenstore/order_test.php
phpunit tests/binhusenstore/payment_test.php
phpunit tests/binhusenstore/product_test.php
sleep 6

#run all those command by using unit_test_note_app.sh

# multiple test unit_test_note_app.sh && unit_test_note_app.sh && unit_test_note_app.sh && unit_test_note_app.sh && unit_test_note_app.sh