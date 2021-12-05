<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Dolgozo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all()
    {
        return $this->db->get('dolgozok')->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('dolgozok')->row_array();
    }

    public function search($where)
    {
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        return $this->db->get('dolgozok')->result_array();
    }

    public function insert($data)
    {
        $this->db->insert('dolgozok', $data);
        return $this->db->insert_id();        
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('dolgozok', $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('dolgozok');
        return $this->db->affected_rows();
    }
}

/* End of file Dolgozo_model.php */


?>