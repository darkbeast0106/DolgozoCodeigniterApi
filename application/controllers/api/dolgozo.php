<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. 'libraries/REST_Controller.php';

class Dolgozo extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('dolgozo_model');
    }

    public function index_get($id = 0)
    {   
        $adatok = [];
        $error = false;
        $message = "Dolgozók sikeresen lekérdezve";
        $response_code = REST_Controller::HTTP_OK;
        if ($id == 0) {
            $adatok = $this->dolgozo_model->get_all();
        } else{
            $adatok = $this->dolgozo_model->get_by_id($id);
            if (is_null($adatok)) {
                $error = true;
                $message = "A megadott azonosítóval nem található dolgozó";
                $adatok = [];
                $response_code = REST_Controller::HTTP_NOT_FOUND;
            } else{
                $adatok = [$adatok];
            }
        }
        $data = [
            'adatok' => $adatok,
            'error' => $error,
            'message' => $message,
        ];
        $this->response($data, $response_code);
    }

    public function index_post()
    {
        $adatok = [];
        $error = false;
        $message = "";
        $response_code = REST_Controller::HTTP_CREATED;

        $this->load->library('form_validation');
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('nev', 'Név', 'trim|required');
        $this->form_validation->set_rules('nem', 'Nem', 'trim|required');
        $this->form_validation->set_rules('kor', 'Kor', 'trim|required|numeric|greater_than_equal_to[14]|less_than_equal_to[200]');
        $this->form_validation->set_rules('fizetes', 'Fizetés', 'trim|required|numeric|greater_than_equal_to[80000]|less_than_equal_to[999999]');
        
        if ($this->form_validation->run() == FALSE) {
            $error = true;
            $message = validation_errors();
            $message = str_replace("<p>", "", $message);
            $message = str_replace("</p>", "", $message);
            $message = str_replace("\n", " ", $message);
            $message = trim($message);
            $response_code = REST_Controller::HTTP_BAD_REQUEST;
        } else {
            $insert_data = $this->post();
            $lehetseges = ['nev', 'nem', 'kor', 'fizetes'];
            foreach ($insert_data as $key => $value) {
                if (!in_array($key, $lehetseges) ) {
                    unset($insert_data[$key]);
                }
            }
            $id = $this->dolgozo_model->insert($insert_data);
            $adatok = [$this->dolgozo_model->get_by_id($id)];
            $message = "Új dolgozó sikeresen létrehozva";
        }
        
        
        $data = [
            'adatok' => $adatok,
            'error' => $error,
            'message' => $message,
        ];
        $this->response($data, $response_code);
    }

    public function index_put($id)
    {
        $adatok = [];
        $error = false;
        $message = "";
        $response_code = REST_Controller::HTTP_OK;
        $this->load->library('form_validation');
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('nev', 'Név', 'trim|required');
        $this->form_validation->set_rules('nem', 'Nem', 'trim|required');
        $this->form_validation->set_rules('kor', 'Kor', 'trim|required|numeric|greater_than_equal_to[14]|less_than_equal_to[200]');
        $this->form_validation->set_rules('fizetes', 'Fizetés', 'trim|required|numeric|greater_than_equal_to[80000]|less_than_equal_to[999999]');
        
        if ($this->form_validation->run() == FALSE) {
            $error = true;
            $message = validation_errors();
            $message = str_replace("<p>", "", $message);
            $message = str_replace("</p>", "", $message);
            $message = str_replace("\n", " ", $message);
            $message = trim($message);
            $response_code = REST_Controller::HTTP_BAD_REQUEST;
        } else {
            $update_data = $this->put();
            $lehetseges = ['nev', 'nem', 'kor', 'fizetes'];
            foreach ($update_data as $key => $value) {
                if (!in_array($key, $lehetseges) ) {
                    unset($update_data[$key]);
                }
            }
            $success = $this->dolgozo_model->update($id, $update_data);
            if ($success == 0) {
                $error = true;
                $message = "A megadott azonosítóval nem található dolgozó";
                $response_code = REST_Controller::HTTP_NOT_FOUND;
            } else {
                $message = "Dolgozó sikeresen módosítva";
                $adatok = [$this->dolgozo_model->get_by_id($id)];
            }
        }
        
        $data = [
            'adatok' => $adatok,
            'error' => $error,
            'message' => $message,
        ];
        $this->response($data, $response_code);
        
    }

    public function index_delete($id)
    {
        $adatok = [];
        $error = false;
        $message = "";
        $response_code = REST_Controller::HTTP_OK;
        
        $success = $this->dolgozo_model->delete($id);
        if ($success == 0) {
            $error = true;
            $message = "A megadott azonosítóval nem található dolgozó";
            $response_code = REST_Controller::HTTP_NOT_FOUND;
        } else{
            $message = "Dolgozó sikeresen törölve";
        }

        $data = [
            'adatok' => $adatok,
            'error' => $error,
            'message' => $message,
        ];
        $this->response($data, $response_code);
    }

}

/* End of file Dolgozo.php */



?>