<?php

namespace models\search;

require_once 'models/Transition.php';
require_once 'models/Link.php';

use models\Link;
use models\Transition;
use PDO;

class TransitionSearch extends Transition
{
    public ?int $created_at_from = null;
    public ?int $created_at_to = null;
    public ?string $url = null;
    public ?string $hash = null;

    public function load($attributes = null)
    {
        parent::load($attributes);
        if ($attributes) {
            $this->created_at_from = isset($attributes['created_at_from']) ? (integer)$attributes['created_at_from'] : null;
            $this->created_at_to = isset($attributes['created_at_to']) ? (integer)$attributes['created_at_to'] : null;
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
        if (!empty($this->id) && !is_int($this->id)) {
            $this->_errors['id'] = 'id must be integer';
        }
        if (!empty($this->link_id) && !is_int($this->link_id)) {
            $this->_errors['link_id'] = 'link_id must be integer';
        }
        return empty($this->_errors);
    }

    public function search()
    {
        $query = "
            SELECT 
                " . self::getTableName() . ".link_id AS link_id,
                " . self::getTableName() . ".created_at AS created_at,
                " . self::getTableName() . ".id AS id,
                " . Link::getTableName() . ".hash AS hash,
                " . Link::getTableName() . ".url AS url
            FROM " . self::getTableName() . " 
            LEFT JOIN " . Link::getTableName() . " ON " . self::getTableName() . ".link_id = " . Link::getTableName() . ".id 
            WHERE 1
        ";
        if ($this->created_at_from) {
            $query .= " AND WHERE " . self::getTableName() . ".created_at >= :created_at_from";
        }
        if ($this->created_at_to) {
            $query .= " AND WHERE " . self::getTableName() . ".created_at <= :created_at_to";
        }
        if ($this->id) {
            $query .= " AND WHERE " . self::getTableName() . ".id = :id";
        }
        if ($this->url) {
            $query .= " AND " . Link::getTableName() . ".url LIKE :url";
        }
        if ($this->hash) {
            $query .= " AND " . Link::getTableName() . ".hash LIKE :hash";
        }
        if ($this->link_id) {
            $query .= " AND " . Link::getTableName() . ".link_id LIKE :link_id";
        }
        $statement = $this->db->prepare($query);
        if ($this->created_at_from) {
            $statement->bindParam(":created_at_from", $this->created_at_from);
        }
        if ($this->created_at_to) {
            $statement->bindParam(":created_at_to", $this->created_at_to);
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
        if ($this->link_id) {
            $statement->bindParam(":link_id", $this->link_id);
        }
        $result = null;
        if ($statement->execute()) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $result[] = new Link($this->db, $row);
            };
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return array_merge(parent::getAttributes(), [
            'hash' => $this->hash,
            'url' => $this->url,
        ]);
    }

}