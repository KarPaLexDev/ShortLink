<?php

namespace models;

require_once 'models/BaseModel.php';

use PDO;

class User extends BaseModel
{
    /**
     * @var null|integer
     */
    public ?int $id;

    /**
     * @var null|string
     */
    public ?string $token;

    /**
     * @var null|string
     */
    public ?string $name;


    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'users';
    }

    /**
     * @param $db PDO
     * @param $token string
     * @return User
     */
    public static function findByToken(PDO $db, string $token)
    {
        $query = "SELECT * FROM " . self::getTableName() . " WHERE token=:token LIMIT 1";

        $statement = $db->prepare($query);
        $token = htmlspecialchars(strip_tags($token));

        $statement->bindParam(":token", $token);
        if ($statement->execute()) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return new self($db, $row);
        }
        return null;
    }

    /**
     * @param $attributes array|null
     * @return void
     */
    public function load(?array $attributes = null)
    {
        if ($attributes) {
            $this->id = $attributes['id'] ?? $this->id;
            $this->name = $attributes['name'] ?? $this->name;
            $this->token = $attributes['token'] ?? $this->token;
        }
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'token' => $this->token,
        ];
    }
}