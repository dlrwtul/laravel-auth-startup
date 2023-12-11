<?php

namespace App\Utils;

use App\Enums\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;


class ResponseUtils
{

    public static function formatResponse(bool $success = true, int $status = 200, string $message = '',  $data = null,)
    {
        return Response::json([
            "success" => $success,
            "message" => $message,
            "data" => $data,
        ], $status);
    }

    public static function formatResponseWithPagination(bool $success = true, int $status = 200, string $message = '',  $data = null,)
    {

        if ($data->resource instanceof LengthAwarePaginator) {
            $resource = $data->resource;
            $data = [
                'data' => $resource->items(),
                'meta' => [
                    'current_page' => $resource->currentPage(),
                    'last_page' => $resource->lastPage(),
                    'per_page' => $resource->perPage(),
                    'total' => $resource->total(),

                ],
                'links' => [
                    'first' => $resource->url(1),
                    'last' => $resource->url($resource->lastPage()),
                    'prev' => $resource->previousPageUrl(),
                    'next' => $resource->nextPageUrl(),
                ],
            ];
        }

        return Response::json([
            "success" => $success,
            "message" => $message,
            "data" => $data,
        ], $status);
    }
}
