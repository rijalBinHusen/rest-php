source .env

phpunit tests/User_test.php
sleep 2
phpunit tests/my_report/my_report_warehouse_test.php
sleep 2
phpunit tests/my_report/my_report_supervisor_test.php
sleep 2
phpunit tests/my_report/my_report_test_item_test.php
sleep 2
phpunit tests/my_report/my_report_headspv_test.php
sleep 20

#run all those command with this ./unit_test_my_report.sh