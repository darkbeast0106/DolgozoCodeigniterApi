<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. 'libraries/REST_Controller.php';

class User extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index_get($id = 0)
    {   
        $adatok = [];
        $error = false;
        $message = "Felhasználók sikeresen lekérdezve";
        $response_code = REST_Controller::HTTP_OK;
        if ($id == 0) {
            $adatok = $this->user_model->get_all();
        } else{
            $adatok = $this->user_model->get_by_id($id);
            if (is_null($adatok)) {
                $error = true;
                $message = "A megadott azonosítóval nem található felhasználó";
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

    public function register_post()
    {
        $adatok = [];
        $error = false;
        $message = "";
        $response_code = REST_Controller::HTTP_CREATED;

        $this->load->library('form_validation');
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('username', 'Felhasználónév', 'trim|required|is_unique[user.username]', ['is_unique' => "A megadott felhasználónévvel már szerepel felhasználó"]);
        $this->form_validation->set_rules('password', 'Jelszó', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]', ['is_unique' => "A megadott email címmel már szerepel felhasználó"]);
        
        if ($this->form_validation->run() == FALSE) {
            $error = true;
            $message = validation_errors();
            $message = str_replace("<p>", "", $message);
            $message = str_replace("</p>", "", $message);
            $message = str_replace("\n", " ", $message);
            $message = trim($message);
            $response_code = REST_Controller::HTTP_BAD_REQUEST;
        } else {
            $insert_data = [];
            $insert_data['username'] = $this->post('username');
            $insert_data['email'] = $this->post('email');
            $password = $this->post('password');
            $insert_data['password'] = password_hash($password, PASSWORD_DEFAULT);
            $id = $this->user_model->insert($insert_data);
            $adatok = [$this->user_model->get_by_id($id)];
            $message = "Sikeres regisztráció";
        }
        
        $data = [
            'adatok' => $adatok,
            'error' => $error,
            'message' => $message,
        ];
        $this->response($data, $response_code);
    }

    public function login_post()
    {
        $adatok = [];
        $error = false;
        $message = "";
        $response_code = REST_Controller::HTTP_OK;

        $this->load->library('form_validation');
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('username', 'Felhasználónév', 'trim|required');
        $this->form_validation->set_rules('password', 'Jelszó', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $error = true;
            $message = validation_errors();
            $message = str_replace("<p>", "", $message);
            $message = str_replace("</p>", "", $message);
            $message = str_replace("\n", " ", $message);
            $message = trim($message);
            $response_code = REST_Controller::HTTP_BAD_REQUEST;
        } else {
            $username = $this->post('username');
            $password = $this->post('password');
            $users = $this->user_model->search(['username' => $username]);
            if (count($users) == 0) {
                $response_code = REST_Controller::HTTP_UNAUTHORIZED;
                $message = "Hibás felhasználónév vagy jelszó";
            } else {
                $user = $users[0];
                if (!password_verify($password, $user['password'])) {
                    $response_code = REST_Controller::HTTP_UNAUTHORIZED;
                    $message = "Hibás felhasználónév vagy jelszó";                    
                } else {
                    $adatok = $users;
                    $message = "Sikeres bejelentkezés";
                }
            }
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
        $this->form_validation->set_rules('username', 'Felhasználónév', 'trim|required|is_unique[user.username]');
        $this->form_validation->set_rules('password', 'Jelszó', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('permission', 'Jogosultság', 'trim|required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $error = true;
            $message = validation_errors();
            $message = str_replace("<p>", "", $message);
            $message = str_replace("</p>", "", $message);
            $message = str_replace("\n", " ", $message);
            $message = trim($message);
            $response_code = REST_Controller::HTTP_BAD_REQUEST;
        } else {
            $update_data = [];
            $update_data['username'] = $this->post('username');
            $update_data['email'] = $this->post('email');
            $update_data['permission'] = $this->post('permission');
            $password = $this->post('password');
            $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
            
            $success = $this->user_model->update($id, $update_data);
            if ($success == 0) {
                $error = true;
                $message = "A megadott azonosítóval nem található felhasználó";
                $response_code = REST_Controller::HTTP_NOT_FOUND;
            } else {
                $message = "Felhasználó sikeresen módosítva";
                $adatok = [$this->user_model->get_by_id($id)];
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
        
        $success = $this->user_model->delete($id);
        if ($success == 0) {
            $error = true;
            $message = "A megadott azonosítóval nem található felhasználó";
            $response_code = REST_Controller::HTTP_NOT_FOUND;
        } else{
            $message = "Felhasználó sikeresen törölve";
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