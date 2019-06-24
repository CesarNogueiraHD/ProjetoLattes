<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class Template{
    function show($view, $data = array()){
        $CI = & get_instance();
        
        $CI->load->view('template/header', $data);
        
        $CI->load->view($view, $data);
        
        $CI->load->view('template/footer', $data);
        
        $CI->load->view('template/scripts', $data);
    }
}        
?>