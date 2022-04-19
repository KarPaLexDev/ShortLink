<?php

namespace models;

use PDO;

abstract class BaseModel
{
    /**
     * @var PDO
     */
    protected PDO $db;
    /**
     * @var array|null
     */
    protected ?array $_errors = [];

    /**
     * @param $db PDO
     * @param $attributes array|null
     */
    public function __construct(PDO $db, array $attributes = null)
    {
        $this->db = $db;
        $this->load($attributes);
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->_errors;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return empty($this->_errors);
    }

    /**
     * @return array|null
     */
    public function save(): bool
    {
        return $this->validate();
    }

    /**
     * @return string
     */
    abstract public static function getTableName(): string;

    /**
     * @param array|null $attributes
     * @return void
     */
    abstract public function load(array $attributes = null);

    /**
     * @return array
     */
    abstract public function getAttributes(): array;
}