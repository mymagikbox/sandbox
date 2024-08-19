<?php
declare(strict_types=1);

namespace PhpLab\Application\Response;

use Fig\Http\Message\StatusCodeInterface;
use JsonSerializable;

final readonly class ResponsePayload implements JsonSerializable
{
    public function __construct(
        private mixed $data = null,
        private int $statusCode = StatusCodeInterface::STATUS_OK,
        private ?ResponseError $error = null
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    public function getError(): ?ResponseError
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = [
            'success' => true,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
            $payload['success'] = false;
        }

        return $payload;
    }
}