<?php

use App\Library\Utilities;
use Carbon\Carbon;
use App\Models\CompanySetting;
use Illuminate\Http\JsonResponse;

if (!function_exists('companyInfo')) {
    function companyInfo(): CompanySetting
    {
        return CompanySetting::first();
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, $folder = '/'): ?string
    {
        if ($file) {
            $image_name = Rand() . '.' . $file->getClientOriginalExtension();
            return $file->storeAs($folder, $image_name, 'public');
        }
        return null;
    }
}

if (!function_exists('getImageUrl')) {
    function getImageUrl(string $path = null, string $type = null): string
    {
        return Utilities::getImageUrl($path, $type);
    }
}

if (!function_exists('ean13_with_check_digit')) {
    function ean13_with_check_digit(string $digits): string
    {
        $paddedDigits = str_pad($digits, 12, '0', STR_PAD_LEFT);
        return $paddedDigits . check_digit($paddedDigits);
    }
}

if (!function_exists('check_digit')) {
    function check_digit(string $digits): string
    {
        $even_sum = (int)$digits[1] + (int)$digits[3] + (int)$digits[5] + (int)$digits[7] + (int)$digits[9] + (int)$digits[11];
        $even_sum_three = $even_sum * 3;
        $odd_sum = (int)$digits[0] + (int)$digits[2] + (int)$digits[4] + (int)$digits[6] + (int)$digits[8] + (int)$digits[10];
        $total_sum = $even_sum_three + $odd_sum;
        $next_ten = (ceil($total_sum / 10)) * 10;
        return $next_ten - $total_sum;
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse(int $statusCode, string $statusMessage, $data = [], bool $isErrors = FALSE): JsonResponse
    {
        $response['message'] = $statusMessage;
        if (!empty($data)) {
            $response[$isErrors ? 'errors' : 'data'] = $data;
        }

        return response()
            ->json($response, $statusCode);
    }
}

if (!function_exists('apiResponseResourceCollection')) {
    function apiResponseResourceCollection(int $statusCode, string $statusMessage, object $resourceCollection): JsonResponse
    {
        $resourceCollection = $resourceCollection
            ->additional([
                'message' => $statusMessage
            ])
            ->response()
            ->getData();

        return response()
            ->json(
                $resourceCollection,
                $statusCode
            );
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat(null|Carbon $carbon): null|string
    {
        return $carbon?->format('M d, Y');
    }
}

if (!function_exists('datetimeFormat')) {
    function datetimeFormat(null|Carbon $carbon): null|string
    {
        return $carbon?->format('M d, Y h:i:s A');
    }
}

if (!function_exists('getUidFromYoutubeLink')) {
    function getUidFromYoutubeLink(string $youtubeLink): string
    {
        try {
            parse_str(parse_url($youtubeLink, PHP_URL_QUERY), $vars);
            return $vars['v'];
        } catch (Throwable $th) {
            return false;
        }
    }
}
