<?php

namespace App\Extendables\Core\Http\Request\States\QueryString\JsonApi;

use App\Extendables\Core\Http\Request\States\QueryString\PaginateQueryStringState;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class JsonApiPaginateQueryStringState implements PaginateQueryStringState
{
    /**
     * @var int
     */
    private readonly int $pageNumber;

    /**
     * @var int
     */
    private readonly int $pageSize;

    /**
     * @var string
     */
    private readonly string $cursor;

    public function __construct(
        mixed $paginateRequestData,
        int $defaultPageNumber = 1,
        int $defaultPageSize = 30
    ) {
        if (! empty($paginateRequestData) && is_array($paginateRequestData) && Arr::isAssoc($paginateRequestData)) {
            $this->pageNumber = $this->getIntValueFromRequestData($paginateRequestData, 'number', $defaultPageNumber);
            $this->pageSize = $this->getIntValueFromRequestData($paginateRequestData, 'size', $defaultPageSize);
            $this->cursor = $this->getStringValueFromRequestData($paginateRequestData, 'cursor', '');
        } else {
            $this->pageNumber = $defaultPageNumber;
            $this->pageSize = $defaultPageSize;
            $this->cursor = '';
        }
    }

    /**
     * @param  array  $requestData
     * @param  string  $requestDataField
     * @param  int  $defaultValue
     * @return int
     */
    private function getIntValueFromRequestData(array $requestData, string $requestDataField, int $defaultValue): int
    {
        if (empty($requestData[$requestDataField])) {
            return $defaultValue;
        }
        $requestDataValue = $requestData[$requestDataField];

        if (filter_var($requestDataValue, FILTER_VALIDATE_INT) !== false && (int)$requestDataValue >= 1) {
            return (int)$requestDataValue;
        }

        return $defaultValue;
    }

    /**
     * @param  array  $requestData
     * @param  string  $requestDataField
     * @param  string  $defaultValue
     * @return string
     */
    private function getStringValueFromRequestData(array $requestData, string $requestDataField, string $defaultValue): string
    {
        if (empty($requestData[$requestDataField])) {
            return $defaultValue;
        }
        $requestDataValue = $requestData[$requestDataField];

        if (is_string($requestDataValue)) {
            return strip_tags($requestDataValue);
        }

        return $defaultValue;
    }

    /**
     * @inheritDoc
     */
    function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @inheritDoc
     */
    function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * @inheritDoc
     */
    public function getCursor(): string
    {
        return $this->cursor;
    }
}
