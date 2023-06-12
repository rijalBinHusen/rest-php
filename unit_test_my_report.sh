# source .env

phpunit tests/access_code_test.php
phpunit tests/User_test.php
# sleep 2
phpunit tests/my_report/my_report_warehouse_test.php
# sleep 2
phpunit tests/my_report/my_report_supervisor_test.php
# sleep 2
phpunit tests/my_report/my_report_test_item_test.php
# sleep 2
phpunit tests/my_report/my_report_headspv_test.php
# sleep 2
phpunit tests/my_report/my_report_complain_test.php
# sleep 2
phpunit tests/my_report/my_report_complain_import_test.php
# sleep 2
phpunit tests/my_report/my_report_case_test.php
# sleep 2
phpunit tests/my_report/my_report_case_import_test.php
# sleep 2
phpunit tests/my_report/my_report_field_problem_test.php
# sleep 2
phpunit tests/my_report/my_report_base_file.php
# sleep 2
phpunit tests/my_report/my_report_base_stock_test.php
# sleep 2
phpunit tests/my_report/my_report_base_clock_test.php
# sleep 2
phpunit tests/my_report/my_report_problem_test.php
# sleep 2
phpunit tests/my_report/my_report_document_test.php
sleep 6

#run all those command with this ./unit_test_my_report.sh

# multiple test unit_test_my_report.sh && unit_test_my_report.sh && unit_test_my_report.sh && unit_test_my_report.sh && unit_test_my_report.sh