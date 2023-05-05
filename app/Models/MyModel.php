<?php
namespace App\Models;
use CodeIgniter\Model;

class MyModel extends Model 
{
    protected $db;
    protected $builder;
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
    }
    protected function _get_datatables_query($column_order, $column_search, $order, $join = [])
    {
        $this->builder = $this->db->table($this->table);
        if (is_array($join)) {
            foreach($join as $key) {
                $this->builder->join($key['table'], $key['on'], (isset($key['type']) ? $key['type'] : 'LEFT'));
            }
        }
        $i = 0;
    
        foreach ($column_search as $item) {
            $post = $this->request->getGet('search');
            if (isset($post['value']) && !empty($post['value'])) {
                if ($i === 0) {
                    $this->builder->groupStart();
                    $this->builder->like($item, $post['value']);
                } else {
                    $this->builder->orLike($item, $post['value']);
                }
    
                if (count($column_search) - 1 == $i)
                    $this->builder->groupEnd();
            }
            $i++;
        }
    
        if (!empty($this->request->getGet('order'))) {
            $order = $this->request->getGet('order');
            $clmnorder = $column_order[$order['0']['column']];
            if (!empty($clmnorder)){
                $this->builder->orderBy($clmnorder, $order['0']['dir']);
            }
        } else if (!empty($order)) {
            $order = $order;
            $this->builder->orderBy(key($order), $order[key($order)]);
        }
    
    }
    
    public function get_datatables($column_order, $column_search, $order, $data = '', $join=[])
    {
        $this->_get_datatables_query($column_order, $column_search, $order, $join);
        $length = $this->request->getGet('length');
        if (empty($length)) {
            $length = 10;
        }
        $start = $this->request->GetGet('start');
        if (empty($start)) {
            $start = 0;
        }
        if ($length != -1)
            $this->builder->limit($length, $start);
        if ($data) {
            $this->builder->where($data);
        }
    
        $query = $this->builder->get();
        return $query->getResult();
    }
    
    public function count_filtered($column_order, $column_search, $order, $data = '')
    {
        $this->_get_datatables_query($column_order, $column_search, $order);
        if ($data) {
            $this->builder->where($data);
        }
        $this->builder->get();
        return $this->builder->countAll();
    }
    
    public function count_all($data = '')
    {
        if ($data) {
            $this->builder->where($data);
        }
        $this->builder->from($this->table);
    
        return $this->builder->countAll();
    }
}