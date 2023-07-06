<?php


namespace App\Exceptions;


use Carbon\Carbon;

class BaseError
{
    /** @var int */
    private int $status;
    /** @var string */
    private string $message;
    /** @var string */
    private string $datetime;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }


    /**
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime ?? Carbon::now()->format('Y-m-d H:m:s');
    }

    /**
     * @param string $datetime
     * @return $this
     */
    public function setDatetime(string $datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->getStatus(),
            'message' => $this->getMessage(),
            'datetime' => $this->getDatetime(),
        ];
    }
}
