<?php

namespace models;

require_once 'models/BaseModel.php';

use PDO;

class Link extends BaseModel
{
    /**
     * @var null|integer
     */
    public ?int $id = null;
    /**
     * @var null|string
     */
    public ?string $url = null;
    /**
     * @var null|string
     */
    public ?string $hash = null;
    /**
     * @var null|integer
     */
    public ?int $created_at = null;
    /**
     * @var null|integer
     */
    public ?int $updated_at = null;

    /**
     * @param $attributes
     * @return void
     */
    public function load($attributes = null)
    {
        if ($attributes) {
            $this->id = $attributes['id'] ?? $this->id;
            $this->url = $attributes['url'] ?? $this->url;
            $this->hash = $attributes['hash'] ?? $this->hash;
            $this->created_at = $attributes['created_at'] ?? $this->created_at;
            $this->updated_at = $attributes['updated_at'] ?? $this->updated_at;
        }
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'links';
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
            $this->_errors['hash'] = 'hash must not be empty and length less then 255';
        }
        return empty($this->_errors);
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        parent::save();
        if ($this->id) {
            $updated_at = time();
            $query = "UPDATE
                " . $this->getTableName() . "
            SET
                url = :url,
                hash = :hash,
                updated_at = :updated_at
            WHERE
                id = :id";
        } else {
            $created_at = time();
            $updated_at = $created_at;
            $query = "INSERT INTO
                " . $this->getTableName() . "
            SET
                url=:url, hash=:hash, created_at=:created_at, updated_at=:updated_at";
        }
        $statement = $this->db->prepare($query);
        if ($this->url) {
            $statement->bindParam(":url", $this->url);
        }
        if ($this->hash) {
            $statement->bindParam(":hash", $this->hash);
        }
        if ($this->id) {
            $statement->bindParam(":id", $this->id);
        }
        if (isset($updated_at)) {
            $statement->bindParam(":updated_at", $updated_at);
        }
        if (isset($created_at)) {
            $statement->bindParam(":created_at", $updated_at);
        }

        $result = $statement->execute();
        if ($result) {
            if (!$this->id) {
                $this->id = $this->db->lastInsertId();
            }
            if (isset($created_at)) {
                $this->created_at = $created_at;
            }
            if (isset($updated_at)) {
                $this->updated_at = $updated_at;
            }
        }
        return $result;
    }

    /**
     * @param $db
     * @param $hash
     * @return Link|null
     */
    public static function findByHash($db, $hash): ?Link
    {
        $query = "SELECT * FROM " . self::getTableName() . " WHERE hash=:hash LIMIT 1";

        $statement = $db->prepare($query);
        $hash = htmlspecialchars(strip_tags($hash));

        $statement->bindParam(":hash", $hash);
        if ($statement->execute()) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return new self($db, $row);
        }
        return null;
    }

    /**
     * @param $db
     * @param $hash
     * @return Link|null
     */
    public static function findById($db, $id): ?Link
    {
        $query = "SELECT * FROM " . self::getTableName() . " WHERE id=:id LIMIT 1";

        $statement = $db->prepare($query);
        $id = htmlspecialchars(strip_tags($id));

        $statement->bindParam(":id", $id);
        if ($statement->execute()) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return new self($db, $row);
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'hash' => $this->hash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $query = "DELETE FROM " . self::getTableName() . " WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(":id", $this->id);
        return $statement->execute();
    }
}