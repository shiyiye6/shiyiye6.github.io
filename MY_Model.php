<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * 重构Model实现更全的操作 
 * 
 * 
 */

class MY_Model extends CI_Model {

    protected $table_name;  //表名称
    
    protected $primary_key = 'id'; //主键名称
    
    protected $set_created = TRUE; //
    
    protected $set_modified = FALSE;
    
    protected $created_column = 'created';
    
    protected $modified_column = 'modified';
    
    protected $order_by_primary_key = TRUE;
    
    protected $primary_key_order = 'desc'; //默认排序
    
  
    public function __construct() {
        parent::__construct();
        $this->load->database(); //加载数据库类
        log_message('debug', 'MY_Model class loaded');
    }
    /**
     * 
     * 
     */
    public function get($primary_value) {
        return $this->get_by($this->primary_key, $primary_value);
    }

    public function get_by($where, $value = '') {
        $this->set_where($where, $value);
        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1)
            return $query->row();
       //  echo $this->db->last_query();
        return FALSE;
    }

    public function count_get_all() {
        return $this->db->count_all($this->table_name);
    }

    public function get_all($number = NULL, $offset = NULL, $order = NULL) {
        if (!is_null($order)) {
            if ($order == 'asc' || $order == 'desc' || $order == 'random') {
                $this->db->order_by($this->primary_key, $order);
            } else {
                $this->db->order_by($order);
            }
        } else {
            if ($this->order_by_primary_key) {
                $this->db->order_by($this->primary_key, $this->primary_key_order);
            }
        }
        $query = $this->db->get($this->table_name, $number, $offset);
        return $query->result();
    }

    public function count_find_in($primary_value_in) {
        return $this->count_find_in_by($this->primary_key, $primary_value_in);
    }

    public function find_in($primary_value_in, $number = NULL, $offset = NULL, $order = NULL) {
        return $this->find_in_by($this->primary_key, $primary_value_in, $number, $offset, $order);
    }

    public function count_find_in_by($where, $value_in) {
        if (!is_array($value_in) || count($value_in) == 0)
            return 0;
        $this->db->where_in($where, $value_in);
        return $this->db->count_all_results($this->table_name);
    }

    public function find_in_by($where, $value_in, $number = NULL, $offset = NULL, $order = NULL) {
        if (!is_array($value_in) || count($value_in) == 0)
            return array();
        $this->db->where_in($where, $value_in);
        if (!is_null($order)) {
            if ($order == 'asc' || $order == 'desc' || $order == 'random') {
                $this->db->order_by($this->primary_key, $order);
            } else {
                $this->db->order_by($order);
            }
        } else {
            if ($this->order_by_primary_key) {
                $this->db->order_by($this->primary_key, $this->primary_key_order);
            }
        }
        $query = $this->db->get($this->table_name, $number, $offset);
        return $query->result();
    }

    public function count_find($primary_value) {
        return $this->count_find_by($this->primary_key, $primary_value);
    }

    public function find($primary_value, $number = NULL, $offset = NULL, $order = NULL) {
        return $this->find_by($this->primary_key, $primary_value, $number, $offset, $order);
    }

    public function count_find_by($where, $value = '') {
        $this->set_where($where, $value);
        return $this->db->count_all_results($this->table_name);
    }

    public function find_by($where, $value = '', $number = NULL, $offset = NULL, $order = NULL) {
        $this->set_where($where, $value);
        if (!is_null($order)) {
            if ($order == 'asc' || $order == 'desc' || $order == 'random') {
                $this->db->order_by($this->primary_key, $order);
            } else {
                $this->db->order_by($order);
            }
        } else {
            if ($this->order_by_primary_key) {
                $this->db->order_by($this->primary_key, $this->primary_key_order);
            }
        }
        $query = $this->db->get($this->table_name, $number, $offset);
        //echo $this->db->last_query();
        return $query->result();
    }

    public function count_like_find($where_data, $like_data) {
        if (count($where_data) > 0) {
            foreach ($where_data as $k => $v){
                if (is_array($v)) {
                    $this->db->where_in($k, $v);
                } else {
                    $this->db->where($k, $v);
                }
            }
        }

        if (count($like_data) > 0)
            $this->db->like($like_data);

        return $this->db->count_all_results($this->table_name);
    }

    public function like_find($where_data, $like_data, $number = NULL, $offset = NULL, $order = NULL) {
        if (count($where_data) > 0) {
            foreach ($where_data as $k => $v) {
                if (is_array($v)) {
                    $this->db->where_in($k, $v);
                } else {
                    $this->db->where($k, $v);
                }
            }
        }
        if (count($like_data) > 0)
            $this->db->like($like_data);
        if (!is_null($order)) {
            if ($order == 'asc' || $order == 'desc' || $order == 'random') {
                $this->db->order_by($this->primary_key, $order);
            } else {
                $this->db->order_by($order);
            }
        } else {
            if ($this->order_by_primary_key) {
                $this->db->order_by($this->primary_key, $this->primary_key_order);
            }
        }

        $query = $this->db->get($this->table_name, $number, $offset);
        echo $this->db->last_query();exit;
        
        return $query->result();
    }

    public function insert($data = array()) {

        if ($this->set_created) {
            $data[$this->created_column] = date('Y-m-d H:i:s');
        }
        $query = $this->db->insert($this->table_name, $data);
        if ($query === TRUE) {
            return $this->db->insert_id();
        }
        $this->log_db_message();
      
        return FALSE;
    }

    public function fri_insert($data = array()) {
        $query = $this->db->insert($this->table_name, $data);
        if ($query === TRUE) {
            return $this->db->insert_id();
        }
        $this->log_db_message();
        return FALSE;
    }

    public function update($primary_value, $data = array()) {
        return $this->update_by($this->primary_key, $primary_value, $data);
    }

    public function update_by($where, $value = '', $data = array()){
        if ($this->set_modified) {
            $data[$this->modified_column] = time();
        }
        
        $this->set_where($where, $value);
        $query = $this->db->update($this->table_name, $data);
        
    //    echo $this->db->last_query();exit;
        if ($query === TRUE) {
            return $this->db->affected_rows() == 1;
        }
            $this->log_db_message();
        return FALSE;
    }

    public function upsert($primary_value, $data = array()) {
        return $primary_value === FALSE ? $this->insert($data) : $this->update($primary_value, $data);
    }

    public function delete($primary_value) {
        return $this->delete_by($this->primary_key, $primary_value);
    }

    public function delete_by($where, $value = '') {
        $this->set_where($where, $value);
        $query = $this->db->delete($this->table_name);
        if ($query === TRUE) {
            return $this->db->affected_rows() == 1;
        }
        $this->log_db_message();
        return FALSE;
    }

    protected function set_where($where, $value = '') {
        if (is_array($where)) {
            $this->db->where($where);
        } else {
            $this->db->where($where, $value);
        }
    }

    protected function log_db_message() {
        log_message('error', 'Database Error: ' . $this->db->last_query());
    }

    /* SQL语句多条查询 */
    public function query($sql) {
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    /* 单条语句查询 */
    public function query_one($sql) {
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

}
