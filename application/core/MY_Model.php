<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    public $table = '';
    public $primaryKey = '';

    public $has_one = [];
    public $has_many = [];
    public $belongs_to = [];
    public $belongs_to_many = [];

    public $order_col_fields = [];
    public $order_by_parm = 'order_by';
    public $order_col_param = 'order';

    private $redirectOnNotFound = '';
    private $flashMessageOnNotFound = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function orderCol()
    {
        $orderPossibilities = ['asc', 'desc'];

        $order_by = $this->input->get($this->order_by_parm, true);

        $order = $this->input->get($this->order_col_param, true);

        if (in_array($order_by, $this->order_col_fields) && in_array($order, $orderPossibilities)) {
            $this->db->order_by($order_by, $order);
        }

        return $this;
    }

    public function all()
    {
        return $this->db->get($this->table)->result();
    }

    public function find($primaryKey = null)
    {
        $result = $this->db->where($this->table.'.'.$this->primaryKey,  $primaryKey)
            ->get($this->table)->row();

        if ($result) {
            return $result;
        }

        if ($this->flashMessageOnNotFound) {
            $this->session->set_flashdata('msg', [['type' => 'warning', 'message' => $this->flashMessageOnNotFound]]);
        }

        if ($this->redirectOnNotFound) {
            redirect($this->redirectOnNotFound);
        }
    }

    public function redirectNotFound($redirect)
    {
        $this->redirectOnNotFound = $redirect;

        return $this;
    }

    public function messageNotFound($message)
    {
        $this->flashMessageOnNotFound = $message;

        return $this;
    }

    public function where($clausula, $valor)
    {
        $this->db->where($clausula, $valor);

        return $this;
    }

    public function like($clausula, $valor)
    {
        $this->db->like($clausula, $valor);

        return $this;
    }

    public function order($field, $valor)
    {
        $this->db->order_by($field, $valor);

        return $this;
    }

    public function fields($fields)
    {
        $this->db->select($fields);

        return $this;
    }

    public function count($table = null)
    {
        if ($table) {
            return $this->db->count_all_results($table);

            return false;
        }

        return $this->db->count_all_results($this->table);
    }

    public function limit($limit = null, $offset = null)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this;
    }

    public function join($relationName, $direction = null)
    {
        $relation = $this->belongs_to[$relationName];

        $model = $relation[0];
        
        $modelName = explode('/', $model);
        $modelName = end($modelName);
        $this->load->model($model);

        $tableName = $this->{$modelName}->table;
        $fk = $this->{$modelName}->primaryKey;

        $relationTable = $tableName ;
        $relationKey = $fk;
        $localKey = $relation[1];

        if ($direction) {
            $this->db->join($relationTable, $relationTable.'.'.$relationKey.' = '.$this->table.'.'.$localKey, $direction);

            return $this;
        }

        $this->db->join($relationTable, $relationTable.'.'.$relationKey.' = '.$this->table.'.'.$localKey);

        return $this;
    }

    public function hasOne($relationName,$pkValue){
        $relation = $this->belongs_to[$relationName];

        $model = $relation[0];
        
        $modelName = explode('/', $model);
        $modelName = end($modelName);
        $this->load->model($model);

        $tableName = $this->{$modelName}->table;
        $fk = $this->{$modelName}->primaryKey;

        $relationTable = $tableName ;
        $relationKey = $fk;

        $this->db->where($fk,$pkValue);
        return $this->db->get($tableName)->row();

    }


    public function hasMany($relationName, $pkValue){
        $relation = $this->has_many[$relationName];

        $model = $relation[0];
        
        $modelName = explode('/', $model);
        $modelName = end($modelName);
        $this->load->model($model);

        $tableName = $this->{$modelName}->table;
        $fk = $this->{$modelName}->primaryKey;

        $relationTable = $tableName ;

        $localKey = $relation[1];

        $this->db->where($localKey, $pkValue);
        return $this->db->get($tableName)->result();

    }

    public function joinMany($relations)
    {
        $relations = explode('|', $relations);

        foreach ($relations as $relation) {
            $this->join($relation);
        }

        return $this;
    }

    public function save($dados = null, $primaryKey = null)
    {
        if ($dados) {
            if ($primaryKey) {
                $this->db->where($this->primaryKey, $primaryKey);
                if ($this->db->update($this->table, $dados)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($this->db->insert($this->table, $dados)) {
                    return $this->db->insert_id();
                } else {
                    return false;
                }
            }
        }
    }

    public function delete($id = null)
    {
        if ($primaryKey) {
            $this->db->where($this->primaryKey, $primaryKey);
            $this->db->delete($this->table);
            if ($this->db->affected_rows() > 0) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function getMany($relationName, $primaryKey)
    {
        $relationsData = $this->belongs_to_many[$relationName];

        if (empty($relationsData)) {
            return [];
        }

        $model = $relationsData[0];
        $pivot = $relationsData[1];
        $pivotLeftFk = $relationsData[3];
        $fk = $relationsData[2];

        $modelName = explode('/', $model);
        $modelName = end($modelName);
        $this->load->model($model);

        $tableLeft = $this->{$modelName}->table;
        $idLeft = $this->{$modelName}->primaryKey;

        $this->db->where($pivot.'.'.$fk, $primaryKey);
        $this->db->join($pivot, $pivot.'.'.$pivotLeftFk.' = '.$tableLeft.'.'.$idLeft);

        return $this->db->get($tableLeft)->result();
    }

    public function attach($relationName, $idLocalValue, $pivotLeftFkValue, $deleteNotFounded = true)
    {
        if (!is_array($pivotLeftFkValue)) {
            $temp = $pivotLeftFkValue;
            $pivotLeftFkValue = [];
            $pivotLeftFkValue[] = $temp;
        }

        $relationsData = $this->belongs_to_many[$relationName];

        if (empty($relationsData)) {
            return false;
        }

        $model = $relationsData[0];
        $pivot = $relationsData[1];
        $pivotLeftFk = $relationsData[3];
        $fk = $relationsData[2];

        $updated = [];
        $inserted = [];
        $existing = [];

        $result = $this->db->where($fk, $idLocalValue)->get($pivot)->result();

        foreach ($result as $r) {
            // print_r($r);
            $existing[] = $r->{$pivotLeftFk};
        }

        foreach ($pivotLeftFkValue as $key => $val) {

            // se o valor for um array
            if (is_array($val)) {
                if (!in_array($key, $existing)) {
                    $insert = [
                        $pivotLeftFk => $key,
                        $fk => $idLocalValue,
                    ];

                    foreach ($val as $field => $value) {
                        $insert[$field] = $value;
                    }

                    $this->db->insert($pivot, $insert);
                    $inserted[] = $key;
                } else {
                    $insert = [
                        $pivotLeftFk => $key,
                        $fk => $idLocalValue,
                    ];

                    foreach ($val as $field => $value) {
                        $insert[$field] = $value;
                    }

                    $this->db->where($pivotLeftFk, $key);
                    $this->db->where($fk, $idLocalValue);
                    $this->db->update($pivot, $insert);
                    $updated[] = $key;
                }
            } else {
                if (!in_array($val, $existing)) {
                    $insert = [
                        $pivotLeftFk => $val,
                        $fk => $idLocalValue,
                    ];

                    $this->db->insert($pivot, $insert);
                    $inserted[] = $val;
                } else {
                    $updated[] = $val;
                }
            }
        }

        $toDelete = array_diff($existing, array_merge($updated, $inserted));

        if (!empty($toDelete) && $deleteNotFounded) {
            $this->db->where($fk, $idLocalValue)
                ->where_in($pivotLeftFk, $toDelete)
                ->delete($pivot);
        }
    }
}// end class
