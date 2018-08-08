<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url')); 
    }

	public function index()
	{
		$this->load->view('quizdemo');

	}

	public function deleteall(){
		$this->db->empty_table('tempdata');
	}

	public function addanswer(){

		if($this->input->is_ajax_request()){
			$parent_id = $this->input->post('parent_id');
			$quesType =  $this->input->post('questtype');	
			$html='';
			if ($quesType == 1){
	                $html = '<textarea class="input subquery_input" name="answer['.$parent_id.'][]" placeholder="Type something"/>';
	        } else if($quesType == 2){
	        	$questid=time();
	                $html = '<div class="subquestdiv"><input class="input" name="answer['.$parent_id.'][]" type="text"/><a  class="addQues subquest" data-id="'.$questid.'" >+Add sub question</a><div class="input_div_'.$questid.' col-md-12 ans" ></div></div>';
	        } else if($quesType == 3){
	            for ($i = 1; $i <= 5; $i++) {
	            	$questid=time().$i;
	                $html.= '<div class="subquestdiv"><input class="input multi" name="answer['.$parent_id.'][]" type="text" /><a class="addQues subquest" data-id="'.$questid.'" >+Add sub question</a><div class="input_div_'.$questid.' col-md-12 ans" ></div></div>';   
	        	}
	        }
        	echo $html;
		}
	} 


	public function addquest(){
		if($this->input->is_ajax_request()){
			$questid = $this->input->post('question_id');
			$questid = time();
			$question = '
	        	<div class="col-md-12 mycustom">
	                <div class="col-md-6 custom">
	                    <input type="text" name="quest['.$questid.'][]" class="input"/>
	                </div>
	                <div class="col-md-6 custom">
	                    <select name="nextafter['.$questid.'][]" data-id="'.$questid.'" data-parent="0" class="type">
		                    <option value="">Select Type</option>
		                    <option value="1">Multiline</option>
		                    <option value="2">Single</option>
		                    <option value="3">Multiple</option>
	                    </select>
	                </div>    
	                <div class="input_div_'.$questid.' col-md-12 clearfix" id="'.$questid.'"></div>
	            </div>';
		 	echo $question;    
		} else {
			echo "no url allowed";
		}
	}

	public function savedata(){
		echo "<pre>";print_r($_POST);
		echo "<pre>";print_r($this->input->post('quest'));
		echo "<pre>";print_r($this->input->post('answer'));
	}

}
