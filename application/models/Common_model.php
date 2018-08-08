<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_model extends CI_Model {

    /* INSERT RECORD FROM SINGLE TABLE */

    function insertData($table, $dataInsert) {
        $this->db->insert($table, $dataInsert);
        return $this->db->insert_id();
    }

    /* UPDATE RECORD FROM SINGLE TABLE */

    function updateFields($table, $data, $where) {
        $this->db->update($table, $data, $where);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function makequery($query){
         return $this->db->query($query)->result_array();
    }

    /* UPDATE SINGLE ROW WITH AMOUNT */
    function updateAmount($table,$field1,$amt,$where)
    {
        $this->db->set($field1, "$field1+$amt",FALSE);
        $this->db->where($where);
        $this->db->update($table);
    }
    /* UPDATE SINGLE ROW WITH MINUS AMOUNT */
    function updateAmountMinus($table,$field1,$amt,$where)
    {
        $this->db->set($field1, "$field1-$amt",FALSE);
        $this->db->where($where);
        $this->db->update($table);
    }
    
    // update by operator fee in failled transaction (user)
    function updateTrans($table,$field,$where)
    {
        $this->db->set($field, "$field+1",FALSE);
        $this->db->where($where);        
        $this->db->update($table);
    }
    //update wallet amount
    function updateWalletamount($table,$amt,$field,$where){
        $this->db->set($field,"$field+$amt",FALSE);
        $this->db->where($where);        
        $this->db->update($table);
    }
    
    function updateWalletamountmul($table,$amt,$field,$amt2,$field2,$where){
        $this->db->set($field,"$field+$amt",FALSE);
        $this->db->set($field,"$field2+$amt2",FALSE);
        $this->db->where($where);        
        $this->db->update($table);
    }

    function deleteData($table,$where)
    {
        $this->db->where($where);
        $this->db->delete($table); 
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }   
    }

    function getfavourite($uid='', $postid=''){
        $this->db->select('*');
        $this->db->from('hh_favourite');
        $this->db->where(array('user_id'=>$uid ,'post_id'=>$postid));
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getDataByCloumn($tableName,$columnName,$id,$oderColumn ='',$type ='')
    {
        $this->db->select('*');
        $this->db->from($tableName);
        $this->db->where($columnName, $id);
        if(!empty($oderColumn) && !empty($type))
        {
            $this->db->order_by($oderColumn, $type);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /* ---GET SINGLE RECORD--- */
    function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = '') {

        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);

        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        $num = $q->num_rows();
        if ($num > 0) {
            return $q->row();
        }
    }

    function getsingle_or($table, $where = '',$where_or = '', $fld = NULL, $order_by = '', $order = '') {

        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);

        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where_or != '') {
            $this->db->or_where($where_or);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        $num = $q->num_rows();
        if ($num > 0) {
            return $q->row();
        }
    }

    /* <!--Join tables get single record with using where condition--> */
    
    function GetJoinRecord($table, $field_first, $tablejointo, $field_second,$field_val='',$where="",$group_by='',$order_fld='',$order_type='') {
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first","inner");
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        // echo $this->db->last_query();die;
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }

    function GetJoinRecordGame($table, $field_first, $tablejointo, $field_second,$field_val='',$where="",$order_fld='',$order_type='') {
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first","left outer");
        if(!empty($where)){
            $this->db->where($where);
        }
        // if(!empty($group_by)){
        //     $this->db->group_by($group_by);
        // }
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        // echo $this->db->last_query();die;
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
    
    
    /* ---GET MULTIPLE RECORD--- */
    function getAllwhere($table, $where = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='') {
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if ($where != '') {
            $this->db->where($where);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        if(!empty($group_by)){
            $this->db->group_by($group_by); 
        }

        $q = $this->db->get($table);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
        else
            return false;
    }

/* ---GET MULTIPLE RECORD--- */
    function getAll($table, $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='') {
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }

        $q = $this->db->get($table);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }

    function getAllwherenew($table, $where, $select = 'all') {
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->where($where, NULL, FALSE);
        $q = $this->db->get($table);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        } else {
            return 'no';
        }
    }


    /* <!--GET ALL COUNT FROM SINGLE TABLE--> */
    function getcount($table, $where="") {
        if(!empty($where)){
           $this->db->where($where);
        }
        $q = $this->db->count_all_results($table);
        return $q;
    }

    function getTotalsum($table, $where, $data) {
        $this->db->where($where);
        $this->db->select_sum($data);
        $q = $this->db->get($table);
        return $q->row();
    }

    
    function GetJoinRecordNew($table, $field_first, $tablejointo, $field_second, $field, $value, $field_val) {
        $this->db->select("$field_val");
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first");
        $this->db->where("$table.$field", "$value");
        $this->db->group_by("$table.$field");
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }

    function GetJoinRecordThree($table, $field_first, $tablejointo, $field_second,$tablejointhree,$field_three,$table_four,$field_four,$field_val='',$where="" ,$group_by="") {
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first",'inner');
        $this->db->join("$tablejointhree", "$tablejointhree.$field_three = $table_four.$field_four",'inner');
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by); 
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }


    function getsinglejoin($catname=''){
        $sql = "SELECT hh_groups.name,hh_add_post.category from hh_add_post 
                join hh_categories on hh_categories.name = hh_add_post.category
                join hh_groups on hh_groups.id = hh_categories.group_id
                where hh_add_post.category = '".$catname."' ";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if($count > 0){
            $data = $query->row_array();
            return $data; 
        }else{
            $data = $query->row_array();
            return $data;  
        }
    }




/* <!--GET SUM FROM SINGLE TABLE--> */

    function getSum($table, $where, $data) {
        $this->db->where($where);
        $this->db->select_sum($data);
        $q = $this->db->get($table);
        return $q->result();
    }

    function getSumfield($table, $data) {
        //$this->db->where($where);
        $this->db->select_sum($data);
        $q = $this->db->get($table);
        return $q->row();
    }

    function getAllwhereIn($table,$where = '',$column ='',$wherein = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='') {
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if ($where != '') {
            $this->db->where($where);
        }
        if ($wherein != '') {
            $this->db->where_in($column,$wherein);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        $q = $this->db->get($table);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }

    public function getSingleData($tableName,$columnName,$id,$oderColumn ='',$type ='',$select='')
    {
            if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $this->db->where($columnName, $id);
            if(!empty($oderColumn) && !empty($type))
            {
                $this->db->order_by($oderColumn, $type);
            }
            $query = $this->db->get();
            return $query->result_array();
    }



    public function add($tableName, $data)
    {
        $this->db->insert($tableName, $data);
        return $this->db->insert_id();
    }

    public function edit($tableName,$columnName,$data,$id)
    {
        $this->db-> where($columnName, $id);
        $this->db->update($tableName, $data);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function getsinglewhere($tableName,$columnName,$id,$select=''){
          if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $this->db->where($columnName, $id);
            $query = $this->db->get();
            return $query->row_array();
    }

    function getuserwhere($tableName,$where,$select=''){
          if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $this->db->where($where);
            $query = $this->db->get();
            return $query->row_array();
    }


    function getallwherefield($tableName,$columnName,$id,$select=''){
          if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $this->db->where($columnName, $id);
            $query = $this->db->get();
            $rowcount = $query->num_rows();
            return $query->result_array();
    }

    function getallwherefielddesc($tableName,$columnName,$id,$select='',$oderColumn ='',$type =''){
            if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $this->db->where($columnName, $id);
            if(!empty($oderColumn) && !empty($type))
            {
                $this->db->order_by($oderColumn, $type);
            }
            $query = $this->db->get();
            $rowcount = $query->num_rows();
            return $query->result_array();
    }

    function getdistinctwhere($tableName,$columnName,$id,$select=''){
        $this->db->distinct();
          if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $this->db->where($columnName, $id);
            $query = $this->db->get();
            $rowcount = $query->num_rows();
            return $query->result_array();
    }

    public function getallarray($tableName,$select=''){
        if($select !=''){
                    $this->db->select($select); 
                }else{
                    $this->db->select('*');
                }
            $this->db->from($tableName);
            $query = $this->db->get();
            $rowcount = $query->num_rows();
            return $query->result_array();
    } 


    public function getattributes($val=''){
       $attrdata = $this->getdistinctwhere('hh_post_attributes','attr_name',$val,'attr_value');
       return $attrdata;
    }


    public function get_distinct_pincode($title){
       $this->db->select('DISTINCT(location)');
       $this->db->from('hh_add_post');   
       $this->db->like('title', $title);
       $query=$this->db->get();  
       $data = $query->num_rows();
       if($data > 0){
          return $query->result_array();
       }else{
          return "No data found";
       }   
    }


    function geturgent(){
        $sql = "SELECT * from hh_add_post where is_approve = 1 order by stand_spot desc";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if($count > 0){
            $data = $query->result();
            return $data; 
        }else{
            $data = $query->result();
            return $data;  
        }
    }

    public function groupbyseller($title){
      $this->db->select('DISTINCT(activity)');
       $this->db->from('hh_add_post');   
       $this->db->like('title', $title);
       $query=$this->db->get();  
       $data = $query->num_rows();
       if($data > 0){
          return $query->result_array();
       }else{
          return "No data found";
       }  

    }


    public function getgroupname($title){
        $this->db->select('*');
        $this->db->from('hh_add_post');
        $this->db->like('title', $title);
        $arr = $this->db->get()->row_array();
        if($arr){
            $catdata = $this->db->get_where('hh_categories',array('name'=>$arr['category']))->row_array();
            if($catdata){
            $grpname = $this->db->get_where('hh_groups',array('id'=>$catdata['group_id']))->row_array();   
            return $grpname['name'];
            }
        }
    }


   public function get_categories($title){
       $this->db->select('DISTINCT(category)');
       $this->db->from('hh_add_post');   
       $this->db->like('title', $title);
       $query=$this->db->get();  
       $data = $query->num_rows();
       if($data > 0){
          return $query->result_array();
       }else{
          return "No data found";
       }   
    }


    public function get_edit_category($id){
        $sql = "SELECT hh_categories.*, hh_groups.name as gname, subcat.name as subcategory,subcat.id as subcatid,  subcat.catprice as subcatprice,
                subcat2.name as subcategory2, subcat2.catprice as subcat2price, subcat2.id as subcat2id from hh_categories 
                inner join hh_groups on hh_groups.id = hh_categories.group_id 
                left join hh_categories as subcat on subcat.parent_id = hh_categories.id
                left join hh_categories as subcat2 on subcat2.parent_id = subcat.id
                where hh_categories.id = $id";
       $query = $this->db->query($sql);
       $count = $query->num_rows();
       if($count > 0){
            $data['catdata'] = $query->result_array();
            $data['flag'] = 1;
            return $data; 
       }else{
            $data['flag'] = 0;
            $data['catdata'] = $query->result_array();
            return $data;  
       }
    }
 
    public function get_all_related_post($id){
        $sql = "SELECT hh_add_post.*, hh_categories.name from hh_categories 
                inner join hh_add_post on hh_add_post.category = hh_categories.name where hh_categories.id = $id";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if($count > 0){
            $data = $query->result_array();
            return $data; 
        }else{
            $data = $query->result_array();
            return $data;  
        }
    }

    function getbanner()
    {
        $sql = "SELECT * FROM hh_banner";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if($count > 0){
            $data = $query->row_array();
            return $data; 
        }else{
            $data = $query->row_array();
            return $data;  
        }
    }

    public function getlatestmsg($id =''){
        if(!empty($id)){
            $sql = "SELECT replay_onID, mail_content,send_date from hh_mail where replay_onID = $id order by id DESC";
            $query = $this->db->query($sql);
            $count = $query->num_rows();
            if($count > 0){
                $data = $query->row_array();
                return $data; 
            }else{
                $data = $query->row_array();
                return $data;  
            }  
        }
    }


    public function deletealert($id){
        $this->db->where('id', $id);
        $this->db->delete('hh_set_alert'); 
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }   
    }

    public function getdetailpost($userid){
        $sql = "SELECT hh_add_post.*, hh_favourite.is_favourite from hh_add_post 
                inner join hh_favourite on hh_favourite.post_id  = hh_add_post.id
                where hh_favourite.user_id = $userid";
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if($count > 0){
            $data = $query->result_array();
            return $data; 
        }else{
            $data = $query->result_array();
            return $data;  
        }
    }

    public function alltotalsearch($max_price='', $min_price='', $posttitle ='', $postcode='', $made_by='', $made_year='', $mileage='', $seller_type='', $body_type='',
            $fuel_type='',$transmission='', $colour='', $engine_size='',$it_jobs='', $urgent_filter='', $feature_filter='',$picture_filter='', $category='', $contract_type='',
            $price_order,$location)
    {
        $sql= "SELECT * from hh_add_post WHERE 1=1";
        if(isset($posttitle) && ($posttitle!=''))
            $sql.= " and hh_add_post.title like'%".$posttitle."%' ";
        if(isset($category) && ($category!=''))
            $sql.= " and hh_add_post.category='".$category."' ";
        if(isset($postcode) && ($postcode!=''))
            $sql.= " and hh_add_post.location='".$postcode."' ";
        if(isset($location) && ($location!=''))
            $sql.= " and hh_add_post.address like'%".$location."%' ";
       
        if((isset($max_price)&&($max_price!=''))&&(isset($min_price)&& ($min_price!='')))
            $sql.= "and hh_add_post.price BETWEEN '".$min_price."' and '".$max_price."' ";
        else if(isset($max_price)&& ($max_price!=''))
            $sql.= "and hh_add_post.price <= '".$max_price."' ";
        else if(isset($min_price)&& ($min_price!=''))
            $sql.= "and hh_add_post.price >= '".$min_price."' ";

           
        $attrQArray = array();
        if(isset($made_by)&& ($made_by!=''))
            $attrQArray[] = "(hh_post_attributes.attr_name = 'car_company' and hh_post_attributes.attr_value='".$made_by."') ";
        if(isset($made_year)&& ($made_year!=''))
            $attrQArray[] = "(hh_post_attributes.attr_name = 'buy_year' and hh_post_attributes.attr_value='".$made_year."') ";
        if(isset($mileage)&& ($mileage!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'car_mileage' and hh_post_attributes.attr_value='".$mileage."') ";
        if(isset($body_type)&& ($body_type!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'car_type' and hh_post_attributes.attr_value='".$body_type."' )";
        if(isset($fuel_type)&& ($fuel_type!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'fuel_type' and hh_post_attributes.attr_value='".$fuel_type."') ";
        if(isset($transmission)&& ($transmission!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'transmission' and hh_post_attributes.attr_value='".$transmission."') ";
        if(isset($colour)&& ($colour!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'car_color' and hh_post_attributes.attr_value='".$colour."') ";
        if(isset($engine_size)&& ($engine_size!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'car_engine' and hh_post_attributes.attr_value='".$engine_size."') ";
        if(isset($contract_type)&& ($contract_type!=''))
            $attrQArray[]= " (hh_post_attributes.attr_name = 'contract_type' and hh_post_attributes.attr_value='".$contract_type."') ";
        if(isset($seller_type)&& ($seller_type!=''))
            $sql.= " and hh_add_post.activity = '".$seller_type."' ";
        if(isset($urgent_filter)&& ($urgent_filter!=''))
            $sql.= " and hh_add_post.stand_urgent = '".$urgent_filter."' ";
        if(isset($feature_filter)&& ($feature_filter!=''))
            $sql.= " and hh_add_post.stand_featured = '".$feature_filter."'";
        // if(isset($it_jobs)&& ($it_jobs!=''))
        //     $sql.= " and hh_post_attributes.attr_name = 'it_jobs' and hh_post_attributes.attr_value='".$it_jobs."' ";



        if(!empty($attrQArray)){
             $subquery = "SELECT post_id FROM `hh_post_attributes` WHERE (".implode(' OR ' , $attrQArray).") GROUP BY `post_id` HAVING COUNT(`id`) = ".count($attrQArray);

             $sql.= " OR `id` in (".$subquery.")";
        }
        if((isset($price_order)) && ($price_order!='') && ($price_order=='lth'))
        {
            $sql.= " ORDER BY price ASC,stand_featured DESC, stand_urgent DESC";    
        } 
        else if((isset($price_order)) && ($price_order!='') && ($price_order =='htl'))
        {
            $sql.= " ORDER BY price DESC, stand_featured DESC, stand_urgent DESC"; 
        }
        else {
            $sql.= " ORDER BY stand_featured DESC, stand_urgent DESC";
        }
       //echo $sql;
        $query = $this->db->query($sql);
        $count = $query->num_rows();
        if($count > 0){
            // print_r($query->result_array());
            return $query->result_array();
        } 
    }

}