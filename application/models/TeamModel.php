<?php

class TeamModel extends CI_Model{
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function showMember(){
        $this->db->from("team");
        return $this->db->get()->result_array();
    }
    
    public function getData($id, $select = NULL) {
        if(!empty($select)){
            $this->db->select($select);
        } 
        $this->db->from("team");
        $this->db->where("memberId", $id);
        return $this->db->get();
    }
    
    public function insert($data) {
        $this->db->insert("team", $data);
    }
    
    public function update($id, $data) {
        $this->db->where("memberId", $id);
        $this->db->update("team", $data);
    }
    
    public function delete($id) {
        $this->db->where("memberId", $id);
        $this->db->delete("team");
    }
    
    public function isDuplicated($field, $value, $id = NULL) {
        if(!empty($id)){
            $this->db->where("memberId <>", $id);
        }
        $this->db->from("team");
        $this->db->where($field, $value);
        return $this->db->get()->num_rows() > 0;
    }
    
    var $column_search = array("memberName", "memberDescription");
    var $column_order = array("memberName");
    
    private function _get_datatable() {
        
        $search = NULL;
        if($this->input->post("search")) {
            $search = $this->input->post("search")["value"];
        }
        
        $order_column = NULL;
        $order_dir = NULL;
        $order = $this->input->post("order");
        if(isset($order)){
            $order_column = $order[0]["column"];
            $order_dir = $order[0]["dir"];
        }
        
        $this->db->from("team");
        if(isset($search)){
            $first = TRUE;
            foreach ($this->column_search as $field){
                if($first){
                    $this->db->group_start();
                    $this->db->like($field, $search);
                    $first = FALSE;
                } else {
                    $this->db->or_like($field, $search);
                }
            }
            if(!$first){
                $this->db->group_end();
            }
        }
        if(isset($order)){
            $this->db->order_by($this->column_order[$order_column], $order_dir);
        }
    }
    
    public function get_datatable(){
        $length = $this->input->post("length");
        $start = $this->input->post("start");
        
        $this->_get_datatable();
        if(isset($length) && $length != -1){
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }
    
    public function records_filtered(){
        $this->_get_datatable();
        return $this->db->get()->num_rows();
    }
    
    public function records_total(){
        $this->db->from("team");
        return $this->db->count_all_results();
    }
}
