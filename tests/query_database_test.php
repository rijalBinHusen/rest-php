<?php
require_once(__DIR__ ."/../model/query_database.php");

class queryBuilderTest extends PHPUnit_Framework_TestCase
{
    private $email;
    private $password;
    private $name;
    private $newPassword;
    private $newName;
    private $db;
    private $table = "users";
    private $insertedId;
    public function __construct()
    {
        $faker = Faker\Factory::create();
        $this->email = $faker->email();
        $this->password = $faker->numberBetween(120200, 303000);
        $this->name = $faker->name('male');
        $this->newPassword = $faker->numberBetween(404000, 505000);
        $this->newName = $faker->name('female');
        $this->db = new Query_builder();
    }
    // insert data
    public function testInsert()
    {
        // $db = new Query_builder();
        $data = array(
            'email' => $this->email, 
            'password' => $this->password,
            'name' => $this->name,
        );
        $this->insertedId = $this->db->insert($this->table, $data);
        $this->assertGreaterThan(0, $this->insertedId);
    }
    // update data
    public function testUpdate() {
        $data = array(
            'name' => $this->newName,
            'password' => $this->newPassword
        );
        $result = $this->db->update($this->table, $data, 'id', $this->insertedId);
        $this->assertEquals(true, $result);
    }
    // read data
    // public function testRead() {
    //     $result = $this->db->select_where($this->table, 'email', $this->email)->fetch();
    //     fwrite(STDERR, print_r($result, TRUE));
    //     // $this->assertNotEquals(null, $result['name']);
    //     $this->assertEquals($this->newName, $result['name']);
    // }
    // delete data
    public function testDelete() {
        $result = $this->db->delete($this->table, 'id', $this->insertedId);
        $this->assertEquals(true, $result);
    }
    public function testRowCount() {
        // $result = $this->db->select_from($this->table)->rowCount()
        $result = $this->db->select_where($this->table, 'id', $this->insertedId)->rowCount();
        $this->assertEquals(0, $result);
    }
}