<?php
require_once(__DIR__ ."/../model/query_database.php");

class queryBuilderTest extends PHPUnit_Framework_TestCase
{
    private $email;
    private $password;
    private $name;
    private $newPassword;
    private $newName;
    public function __construct()
    {
        $faker = Faker\Factory::create();
        $this->email = $faker->email();
        $this->password = $faker->numberBetween(120200, 303000);
        $this->name = $faker->name('male');
        $this->newPassword = $faker->numberBetween(404000, 505000);
        $this->newName = $faker->name('female');
    }
    // insert data
    public function testInsert()
    {
        $db = new Query_builder();
        $data = array(
            'email' => $this->email, 
            'password' => $this->password,
            'username' => $this->name,
        );
        $result = $db->insert('users', $data);
        $this->assertEquals('1 row inserted.', $result);
    }
}
// update data
// read data
// delete data