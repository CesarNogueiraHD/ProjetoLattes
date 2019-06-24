<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
CI = CodeIgnite
 */

class Restrict extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("session");
    }
    
    public function index()
    {
        /*$this->load->model("usersModel");
        print_r($this->usersModel->getUserData("cesar369"));*/
        
        if($this->session->userdata("user_id")){
            $data = array(
                "styles" => array(
                    "sweetalert2.css",
                    "sweetalert2.min.css",
                    "dataTables.bootstrap.min.css",
                    "datatables.min.css"
                ),
                "scripts" => array(
                    "sweetalert2.js",
                    //"sweetalert2.all.min.js",
                    "datatables.min.js",
                    "dataTables.bootstrap.min.js",
                    "util.js",
                    "restrict.js"
                ),
                "userId" => $this->session->userdata("user_id")
            );
            $this->template->show("restrict.php", $data);
        } else {
            $data = array(
                "scripts" => array(
                    "util.js",
                    "login.js"
                )
            );
            $this->template->show('login.php', $data);
        }
    }
    
    public function logoff() {
        $this->session->sess_destroy();
        header("Location: " . base_url() . "restrict");
    }
    
    public function ajax_login(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        
        $username = $this->input->post("username");
        $userpass = $this->input->post("userpass");
        
        if(empty($username)){
            $json["status"] = 0;
            $json["error_list"] ["#username"] = "Usuário não pode ser vazio!";
        } else {
            $this->load->model("usersModel");
            $result = $this->usersModel->getUserData($username);
            
            if($result){
                $userId = $result->userId;
                $userPasswordHash = $result->userPasswordHash;
                if(password_verify($userpass, $userPasswordHash)){
                    $this->session->set_userdata("user_id", $userId);
                } else {
                    $json["status"] = 0;
                }
            } else {
                $json["status"] = 0;
            }
            if($json["status"] == 0){
                $json["error_list"] ["#btn_login"] = "Usuário e/ou senha incorretos!";
            }
        }
        
        echo json_encode($json);
    }  
    
    public function ajax_import_image(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $config["upload_path"] = "./tmp/";
        $config["allowed_types"] = "gif|png|jpg";
        $config["overwrite"] = TRUE;
        
        $this->load->library("upload", $config);
        
        $json = array();
        $json["status"] = 1;
        
        if(!$this->upload->do_upload("image_file")){
            $json["status"] = 0;
            $json["error"] = $this->upload->display_errors("", "");
        } else {
            if($this->upload->data()["file_size"] <= 1024){
                $file_name = $this->upload->data()["file_name"];
                $json["img_path"] = base_url() . "tmp/" . $file_name;
            } else {
                $json["status"] = 0;
                $json["error"] = "Arquivo não deve ser maior que 1Mb";
            }
        }
        
        echo json_encode($json);
    }
    
    public function ajax_save_course(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        
        $this->load->model("coursesModel");
        
        $data = $this->input->post();
        
        if(empty($data["courseName"])){
            $json["error_list"]["#course_name"] = "Nome do curso é obrigatório!";
        } else {
            if($this->coursesModel->isDuplicated("courseName", $data["courseName"], $data["courseId"])){
                $json["error_list"]["#course_name"] = "Nome do curso ja existe!";
            }
        }
        
        $data["courseDuration"] = floatval($data["courseDuration"]);
        if(empty($data["courseDuration"])){
            $json["error_list"]["#course_duration"] = "Duração do curso é obrigatória!";
        } else {
            if(!($data["courseDuration"] > 0 && $data["courseDuration"] < 100)){
                $json["error_list"]["#course_duration"] = "Duração do curso deve ser maior que 0h e menor que 100h!";
            }
        }
        
        if(!empty($json["error_list"])){
            $json["status"] = 0;
        } else {
            if(!empty($data["courseImg"])){
                $file_name = basename($data["courseImg"]);
                $old_path = getcwd() . "/tmp/" . $file_name;
                $new_path = getcwd() . "/public/images/courses/" . $file_name;
                rename($old_path, $new_path);
                
                $data["courseImg"] = "/public/images/courses/" . $file_name;
            } else {
                unset($data["courseImg"]);
            }
            
            if(empty($data["courseId"])){
                $this->coursesModel->insert($data);
            } else {
                $courseId = $data["courseId"];
                unset($data["courseId"]);
                $this->coursesModel->update($courseId, $data);
            }
        }
        
        echo json_encode($json);
    }
    
    public function ajax_save_member(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        
        $this->load->model("teamModel");
        
        $data = $this->input->post();
        
        if(empty($data["memberName"])){
            $json["error_list"]["#member_name"] = "Nome do membro é obrigatório!";
        } else {
            if($this->teamModel->isDuplicated("memberName", $data["memberName"], $data["memberId"])){
                $json["error_list"]["#member_name"] = "Nome do membro ja existe!";
            }
        }
        
        if(!empty($json["error_list"])){
            $json["status"] = 0;
        } else {
            if(!empty($data["memberPhoto"])){
                $file_name = basename($data["memberPhoto"]);
                $old_path = getcwd() . "/tmp/" . $file_name;
                $new_path = getcwd() . "/public/images/team/" . $file_name;
                rename($old_path, $new_path);
                
                $data["memberPhoto"] = "/public/images/team/" . $file_name;
            } else {
                unset($data["memberPhoto"]);
            }
            
            if(empty($data["memberId"])){
                $this->teamModel->insert($data);
            } else {
                $memberId = $data["memberId"];
                unset($data["memberId"]);
                $this->teamModel->update($memberId, $data);
            }
        }
        
        echo json_encode($json);
    }
    
    public function ajax_save_user(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        
        $this->load->model("usersModel");
        
        $data = $this->input->post();
        
        if(empty($data["userLogin"])){
            $json["error_list"]["#user_login"] = "Login do usuário é obrigatório!";
        } else {
            if($this->usersModel->isDuplicated("userLogin", $data["userLogin"], $data["userId"])){
                $json["error_list"]["#user_Login"] = "Login do usuário ja existe!";
            }
        }
        
        if(empty($data["userFullName"])){
            $json["error_list"]["#user_full_name"] = "Nome do usuário é obrigatório!";
        }
        
        if(empty($data["userEmail"])){
            $json["error_list"]["#user_email"] = "Email do usuário é obrigatório!";
        } else {
            if($this->usersModel->isDuplicated("userEmail", $data["userEmail"], $data["userId"])){
                $json["error_list"]["#user_email"] = "Email do usuário ja existe!";
            } else {
                if($data["userEmail"] != $data["userEmailConfirm"]){
                    $json["error_list"]["#user_email"] = "";
                    $json["error_list"]["#user_email_confirm"] = "Email informado não bate com a confirmação";
                }
            }
        }
        
        if(empty($data["userPassword"])){
            $json["error_list"]["#user_password"] = "Senha do usuário é obrigatória!";
        } else {
            if($data["userPassword"] != $data["userPasswordConfirm"]){
                $json["error_list"]["#user_password"] = "";
                $json["error_list"]["#user_password_confirm"] = "Senha informada não bate com a confirmação";
            }
        }
        
        if(!empty($json["error_list"])){
            $json["status"] = 0;
        } else {  
            
            $data["userPasswordHash"] = password_hash($data["userPassword"], PASSWORD_DEFAULT);
            
            unset($data["userPassword"]);
            unset($data["userPasswordConfirm"]);
            unset($data["userEmailConfirm"]);
            
            if(empty($data["userId"])){
                $this->usersModel->insert($data);
            } else {
                $userId = $data["userId"];
                unset($data["userId"]);
                $this->usersModel->update($userId, $data);
            }
        }
        
        echo json_encode($json);
    }
    
    public function ajax_get_course_data(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();
        
        $this->load->model("coursesModel");
        
        $course_id = $this->input->post("course_id");
        $data = $this->coursesModel->getData($course_id)->result_array()[0];
        $json["input"]["course_id"] = $data["courseId"];
        $json["input"]["course_name"] = $data["courseName"];
        $json["img"]["course_img_path"] = base_url() . $data["courseImg"];
        $json["input"]["course_duration"] = $data["courseDuration"];
        $json["input"]["course_description"] = $data["courseDescription"];
        
        echo json_encode($json);
    }
    
    public function ajax_get_member_data(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();
        
        $this->load->model("teamModel");
        
        $member_id = $this->input->post("member_id");
        $data = $this->teamModel->getData($member_id)->result_array()[0];
        $json["input"]["member_id"] = $data["memberId"];
        $json["input"]["member_name"] = $data["memberName"];
        $json["img"]["member_photo_path"] = base_url() . $data["memberPhoto"];
        $json["input"]["member_description"] = $data["memberDescription"];
        
        echo json_encode($json);
    }
    
    public function ajax_get_user_data(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();
        
        $this->load->model("usersModel");
        
        $user_id = $this->input->post("user_id");
        $data = $this->usersModel->getData($user_id)->result_array()[0];
        $json["input"]["user_id"] = $data["userId"];
        $json["input"]["user_full_name"] = $data["userFullName"];
        $json["input"]["user_login"] = $data["userLogin"];
        $json["input"]["user_full_name"] = $data["userFullName"];
        $json["input"]["user_email"] = $data["userEmail"];
        $json["input"]["user_email_confirm"] = $data["userEmail"];
        $json["input"]["user_password"] = $data["userPasswordHash"];
        $json["input"]["user_password_confirm"] = $data["userPasswordHash"];
        
        echo json_encode($json);
    }
    
    public function ajax_delete_course_data(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        
        $this->load->model("coursesModel");
        $course_id = $this->input->post("course_id");
        $this->coursesModel->delete($course_id);
        
        echo json_encode($json);
    }
    
    public function ajax_delete_member_data(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        
        $this->load->model("teamModel");
        $member_id = $this->input->post("member_id");
        $this->teamModel->delete($member_id);
        
        echo json_encode($json);
    }
    
    public function ajax_delete_user_data(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        
        $this->load->model("usersModel");
        $user_id = $this->input->post("user_id");
        $this->usersModel->delete($user_id);
        
        echo json_encode($json);
    }
    
    public function ajax_get_user(){
        
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();
        
        $this->load->model("usersModel");
        
        $user_id = $this->input->post("user_id");
        $data = $this->usersModel->getData($user_id)->result_array()[0];
        $json["input"]["user_id"] = $data["userId"];
        $json["input"]["user_login"] = $data["userLogin"];
        $json["input"]["user_full_name"] = $data["userFullName"];
        $json["input"]["user_full_name"] = $data["userFullName"];
        $json["input"]["user_email"] = $data["userEmail"];
        $json["input"]["user_email_confirm"] = $data["userEmail"];
        $json["input"]["user_password"] = $data["userPasswordHash"];
        $json["input"]["user_password_confirm"] = $data["userPasswordHash"];
        
        echo json_encode($json);
    }
    
    public function ajax_list_course(){
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $this->load->model("coursesModel");
        $courses = $this->coursesModel->get_datatable();
        
        $data = array();
        foreach ($courses as $course) {
            $row = array();
            $row[] = $course->courseName;
            
            if($course->courseImg){
                $row[] = '<img src = "'.base_url().$course->courseImg.'" style="max-height:100px; max-width: 100px;">';
            } else {
                $row[] = "";
            }
            
            $row[] = $course->courseDuration;
            $row[] = '<div class="description">'.$course->courseDescription.'</div>';
            
            $row[] = 
                    '  <div style="display: inline-block;">'
                    . ' <button class="btn btn-primary btn-edit-course" course_id="'.$course->courseId.'">'
                    . '     <i class="fa fa-edit"></i>'
                    . ' </button>'
                    . ' <button class="btn btn-danger btn-del-course" course_id="'.$course->courseId.'">'
                    . '     <i class="fa fa-times"></i>'
                    . ' </button>'
                    . '</div>';
            
            $data[] = $row;
        }
        
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->coursesModel->records_total(),
            "recordsFiltered" => $this->coursesModel->records_filtered(),
            "data" => $data,
        );
        
        echo json_encode($json);
    }
    
    public function ajax_list_member(){
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $this->load->model("teamModel");
        $team = $this->teamModel->get_datatable();
        
        $data = array();
        foreach ($team as $member) {
            $row = array();
            $row[] = $member->memberName;
            
            if($member->memberPhoto){
                $row[] = '<img src = "'.base_url().$member->memberPhoto.'" style="max-height:100px; max-width: 100px;">';
            } else {
                $row[] = "";
            }
            
            $row[] = '<div class="description">'.$member->memberDescription.'</div>';
            
            $row[] = 
                    '  <div style="display: inline-block;">'
                    . ' <button class="btn btn-primary btn-edit-member" member_id="'.$member->memberId.'">'
                    . '     <i class="fa fa-edit"></i>'
                    . ' </button>'
                    . ' <button class="btn btn-danger btn-del-member" member_id="'.$member->memberId.'">'
                    . '     <i class="fa fa-times"></i>'
                    . ' </button>'
                    . '</div>';
            
            $data[] = $row;
        }
        
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->teamModel->records_total(),
            "recordsFiltered" => $this->teamModel->records_filtered(),
            "data" => $data,
        );
        
        echo json_encode($json);
    }
    
    public function ajax_list_user(){
        if(!$this->input->is_ajax_request()){
            exit("Nenhum acesso de script direto permitido!!");
        }
        
        $this->load->model("usersModel");
        $users = $this->usersModel->get_datatable();
        
        $data = array();
        foreach ($users as $user) {
            $row = array();
            $row[] = $user->userFullName;
            $row[] = $user->userLogin;
            $row[] = $user->userEmail;
            
            $row[] = 
                    '  <div style="display: inline-block;">'
                    . ' <button class="btn btn-primary btn-edit-user" user_id="'.$user->userId.'">'
                    . '     <i class="fa fa-edit"></i>'
                    . ' </button>'
                    . ' <button class="btn btn-danger btn-del-user" user_id="'.$user->userId.'">'
                    . '     <i class="fa fa-times"></i>'
                    . ' </button>'
                    . '</div>';
            
            $data[] = $row;
        }
        
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->usersModel->records_total(),
            "recordsFiltered" => $this->usersModel->records_filtered(),
            "data" => $data,
        );
        
        echo json_encode($json);
    }
}
