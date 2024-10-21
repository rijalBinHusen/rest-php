# source .env
vendor/bin/phpunit tests/notes/user_test.php
vendor/bin/phpunit tests/notes/note_crud_test.php
sleep 6

#run all those command by using unit_test_note_app.sh

# multiple test unit_test_note_app.sh && unit_test_note_app.sh && unit_test_note_app.sh && unit_test_note_app.sh && unit_test_note_app.sh