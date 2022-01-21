<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('user')->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('user')->row_array();
    }

    public function search($where)
    {
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        return $this->db->get('user')->result_array();
    }

    public function insert($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('user', $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
        return $this->db->affected_rows();
    }
}

/* End of file Dolgozo_model.php */
