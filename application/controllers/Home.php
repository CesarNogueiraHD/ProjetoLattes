<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
            
            $this->load->model("coursesModel");
            $courses = $this->coursesModel->showCourse();
            
            $this->load->model("teamModel");
            $team = $this->teamModel->showMember();
            
            $data = array(
                "scripts" => array(
                    "owl.carousel.min.js",
                    "theme-scripts.js"
                ),
                "courses" => $courses,
                "team" => $team
            );
            $this->template->show('home.php', $data);
	}
}
