<?php


namespace App\Tree;


class Answer
{
    CONST ENTITY_SCHOOL = 1;
    CONST ENTITY_LOCATION = 2;

    const UNKNOWN = 1;
    const NUMBER = 2;
    const LIST = 3;
    const NAME = 4;

    /** @var Boolean */
    private $within_domain = null;

    /** @var int */
    private $entity = null;

    private $type = 1;

    private $value;

    private $warnings = [];

    public static function entityName(int $entity) {
        switch ($entity){
            case self::ENTITY_SCHOOL: return 'Escola';
            case self::ENTITY_LOCATION: return 'Localização';
        }
        return null;
    }

    public static function name(int $type){
       switch ($type){
           case self::NUMBER: return 'Numero';
           case self::LIST: return 'Lista';
           case self::NAME: return 'Nome';
           case self::UNKNOWN:
           default:
               return 'Desconhecido';
       }
    }

    /**
     * @param bool $within_domain
     * @return Answer
     */
    public function setWithinDomain(bool $within_domain): Answer
    {
        $this->within_domain = $within_domain;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithinDomain(): bool
    {
        return $this->within_domain ?? $this->entity != null;
    }

    /**
     * @param int $entity
     * @return Answer
     */
    public function setEntity(int $entity): Answer
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return int
     */
    public function getEntity(): int
    {
        return $this->entity;
    }

    /**
     * @param int $type
     * @return Answer
     */
    public function setType(int $type): Answer
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param mixed $value
     * @return Answer
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $warnings
     * @return Answer
     */
    public function addWarning(string $warnings): Answer
    {
        $this->warnings[] = $warnings;
        return $this;
    }

    /**
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

}
