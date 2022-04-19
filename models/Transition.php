<?php

namespace models;

require_once 'models/BaseModel.php';

class Transition extends BaseModel
{
    /**
     * @var null|integer
     */
    public ?int $id = null;
    /**
     * @var null|integer
     */
    public ?int $link_id = null;
    /**
     * @var null|integer
     */
    public ?int $created_at = null;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'transitions';
    }

    /**
     * @param $attributes
     * @return void
     */
    public function load($attributes = null)
    {
        if ($attributes) {
            $this->id = $attributes['id'] ?? $this->id;
            $this->link_id = $attributes['link_id'] ?? $this->link_id;
            $this->created_at = $attributes['created_at'] ?? $this->created_at;
        }
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->url) || mb_strlen($this->url) > 255) {
            $this->_errors['url'] = 'url must not be empty and length less then 255';
        }
        if (empty($this->hash) || mb_strlen($this->hash) > 255) {
            $this->_errors['url'] = 'hash must not be empty and length less then 255';
        }
        return empty($this->_errors);
    }

    /**
     * @return bool
     */
    public function insert(): bool
    {
        $created_at = time();
        $query = "INSERT INTO
            " . $this->getTableName() . "
        SET
            link_id=:link_id, created_at=:created_at";

        $statement = $this->db->prepare($query);
        if ($this->link_id) {
            $statement->bindParam(":link_id", $this->link_id);
        }
        if (isset($created_at)) {
            $statement->bindParam(":created_at", $created_at);
        }
        $result = $statement->execute();
        if ($result) {
            if (!$this->id) {
                $this->id = $this->db->lastInsertId();
            }
            if (isset($created_at)) {
                $this->created_at = $created_at;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'link_id' => $this->link_id,
            'created_at' => $this->created_at,
        ];
    }
}