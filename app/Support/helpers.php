<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

function responseJson(mixed $data = [], int $status = Response::HTTP_OK, array $headers = [], $options = 0): JsonResponse
{
    return response()->json($data, $status, $headers, $options);
}
