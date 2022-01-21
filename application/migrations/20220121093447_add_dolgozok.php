<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_dolgozok extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'auto_increment' => TRUE
                        ),
                        'nev' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '30',
                                'default' => null
                        ),
                        'nem' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '6',
                                'default' => null
                        ),
                        'kor' => array(
                                'type' => 'INT',
                                'constraint' => '3',
                                'default' => null
                        ),
                        'fizetes' => array(
                                'type' => 'INT',
                                'constraint' => '9',
                                'default' => null
                        ),
                ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('dolgozok');
        }

        public function down()
        {
                $this->dbforge->drop_table('dolgozok');
        }
}