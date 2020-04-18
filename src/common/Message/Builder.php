<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Message;

/**
 * Class Builder
 * @package Vemid\ProjectOne\Common\Message
 */
class Builder
{
    const INFO = 'INFO';
    const SUCCESS = 'SUCCESS';
    const WARNING = 'WARNING';
    const DANGER = 'DANGER';

    protected $message;
    protected $type = self::INFO;
    protected $field;

    /**
     * @param string $message
     * @param string $field
     * @param string $type
     */
    public function __construct(string $message, $field = null, $type = self::INFO)
    {
        $this->setMessage($message);
        $this->setField($field);
        $this->setType($type);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type): void
    {
        if ($type === null) {
            throw new \InvalidArgumentException("Argument 'type' must not be null");
        }

        if (!in_array($type, [self::INFO, self::SUCCESS, self::WARNING, self::DANGER], true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Argument 'type' must be %s, %s, %s or %s",
                    self::INFO, self::SUCCESS, self::WARNING, self::DANGER
                )
            );
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message): void
    {
        if ($message === '') {
            throw new \InvalidArgumentException("Argument 'message' must not be null or empty string");
        }

        $this->message = $message;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'field' => $this->getField(),
            'type' => strtolower($this->getType()),
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s: %s', $this->type, $this->message);
    }

}
