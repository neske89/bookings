<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyOccupancyRatesRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'date' => 'required|date_format:Y-m-d',
            'room_ids' => 'sometimes|array',
            'room_ids.*' => 'required|integer',
        ];
    }
    public function validationData():array
    {
        // Merge route parameters with the request's query parameters
        return array_merge($this->route()->parameters(), $this->query());
    }
}

