<?php

namespace models\search;

use models\Link;
use PDO;

class LinkSearch extends Link
{
    public $created_at_from = null;
    public $created_at_to = null;
    public $updated_at_from = null;
    public $updated_at_to = null;

    public function load($attributes = null)
    {
        parent::load($attributes);
        if ($attributes) {
            $this->created_at_from = isset($attributes['created_at_from']) ? (integer)$attributes['created_at_from'] : null;
            $this->created_at_to = isset($attributes['created_at_to']) ? (integer)$attributes['created_at_to'] : null;
            $this->updated_at_from = isset($attributes['updated_at_from']) ? (integer)$attributes['updated_at_from'] : null;
            $this->updated_at_to = isset($attributes['updated_at_to']) ? (integer)$attributes['updated_at_to'] : null;
        }
    }

    public function validate(): bool
    {
        if (!empty($this->url) && mb_strlen($this->url) > 255) {
            $this->_errors['url'] = 'url length must be less then 255';
        }
        if (!empty($this->hash) && mb_strlen($this->hash) > 255) {
            $this->_errors['hash'] = 'hash length must be less then 255';
        }
        if (!empty($this->created_at_from) && !is_int($this->created_at_from)) {
            $this->_errors['created_at_from'] = 'created_at_from must be integer';
        }
        if (!empty($this->created_at_to) && !is_int($this->created_at_to)) {
            $this->_errors['created_at_to'] = 'created_at_to must be integer';
        }
        if (!empty($this->updated_at_from) && !is_int($this->updated_at_from)) {
            $this->_errors['updated_at_from'] = 'updated_at_from must be integer';
        }
        if (!empty($this->updated_at_to) && !is_int($this->updated_at_to)) {
            $this->_errors['updated_at_to'] = 'updated_at_to must be integer';
        }
        if (!empty($this->id) && !is_int($this->id)) {
            $this->_errors['id'] = 'id must be integer';
        }
        return empty($this->_errors);
    }

    public function search()
    {
        $query = "SELECT * FROM " . self::getTableName() . " WHERE 1";
        if ($this->created_at_from) {
            $query .= " AND WHERE created_at >= :created_at_from";
        }
        if ($this->created_at_to) {
            $query .= " AND WHERE created_at <= :created_at_to";
        }
        if ($this->updated_at_from) {
            $query .= " AND WHERE updated_at >= :updated_at_from";
        }
        if ($this->updated_at_to) {
            $query .= " AND WHERE updated_at <= :updated_at_to";
        }
        if ($this->url) {
            $query .= " AND url LIKE :url";
        }
        if ($this->hash) {
            $query .= " AND hash LIKE :hash";
        }
        if ($this->id) {
            $query .= " AND WHERE id = :id";
        }
        $statement = $this->db->prepare($query);
        if ($this->created_at_from) {
            $statement->bindParam(":created_at_from", $this->created_at_from);
        }
        if ($this->created_at_to) {
            $statement->bindParam(":created_at_to", $this->created_at_to);
        }
        if ($this->updated_at_from) {
            $statement->bindParam(":updated_at_from", $this->updated_at_from);
        }
        if ($this->updated_at_to) {
            $statement->bindParam(":updated_at_to", $this->updated_at_to);
        }
        if ($this->hash) {
            $str = "%$this->hash%";
            $statement->bindParam(":hash", $str);
        }
        if ($this->url) {
            $str = "%$this->url%";
            $statement->bindParam(":url", $str);
        }
        if ($this->id) {
            $statement->bindParam(":id", $this->id);
        }
        $result = null;
        if ($statement->execute()) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $result[] = new Link($this->db, $row);
            };
        }
        return $result;
    }

}