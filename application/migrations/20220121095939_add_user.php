<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_user extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '127',
                'unique' => true
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'unique' => true
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'permission' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => 0
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user');
    }

    public function down()
    {
        $this->dbforge->drop_table('user');
    }
}
