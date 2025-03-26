<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

final class FlightEmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.item_id' => ['required', 'string'],
            '*.origin' => ['required', 'string'],
            '*.destination' => ['required', 'string'],
            '*.number_of_travelers' => ['required', 'integer'],
            '*.aircraft_type' => ['required', 'string'],
            '*.airline' => ['required', 'string'],
            '*.fare_class' => ['required', 'string'],
            '*.methodology' => ['nullable', 'in:ICAO,DEFRA,CO2-EMISSIEFACTOREN-NL'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->all())) {
                $validator->errors()->add('data', 'The input array cannot be empty.');
            }
        });
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors()], 422));
    }
}
