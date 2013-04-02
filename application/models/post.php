<?php

class Post extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_last_ten_entries($Tag,$Var)
    {
		$this->db->select();
		$this->db->from('DocUploaded');  
		$this->db->order_by('date_added','desc');  
		$this->db->limit('DocUploaded');     
        $query = $this->db->get(10,0);
        return $query->result_array();
    }

    function insert_document($data)
    {
        $this->db->insert('DocUploaded', $data);
        return $this->db->insert_id();
    }
    
    function update_document($data)
    {
	  $this->db->where('DocID', $data['DocID']);        
        $this->db->update('DocUploaded', $data);
    }
   
    function insert_entity($tag, $data,$docid)
    {
    	//Person,Benjamin,12

    	$this->db->where($tag, $data);
    	$this->db->set('DocID', 'CONCAT(DocID,",",'.$docid.')', FALSE);
    	$this->db->update($tag);

    	if ($this->db->affected_rows()==0){
	    	 $entity_data = array($tag => $data, 'DocID' => ','.$docid);
	    	 $this->db->insert($tag, $entity_data);
    	$EntityID =  $this->db->insert_id();
    	} else {
	    	$this->db->select();
		$this->db->from($tag);
	    	$this->db->where($tag, $data);
	    	$this->db->get();
    	$EntityID =  $this->db->row()->$tag.'ID';
    	}
    	
	$entity_data = array('EntityType' => $tag, 'EntityID' => $EntityID, 'DocID' => $docid);
	 $this->db->insert('SubTexts', $entity_data);
    	//$entity_data = array($tag => $data, 'DocID' => $docid);
      //  $this->db->insert($tag, $entity_data);
       // return $this->db->insert_id();
    }

    function get_entries($tag,$var)
    {
		$this->db->select();
		$this->db->from($tag);
		$this->db->like($tag,$var);  
		$this->db->limit(6);   
		//if($this->db->count_all_results()>0){  
	        $query = $this->db->get();
	        return $query->result_array();
	      //} else {return '';}
    }
    
    
    function get_entry2($tag,$docid)
    {
		$this->db->select();
		$this->db->from($tag);
		$this->db->where('DocID',$docid);  
		$this->db->limit(6);     
        $query = $this->db->get();
        return $query->result_array();
    }

}