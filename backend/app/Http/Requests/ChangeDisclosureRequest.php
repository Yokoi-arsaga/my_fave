<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeDisclosureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'disclosure_range_id' => ['required', 'numeric', 'between:1,3'],
        ];
    }

    /**
     * 公開範囲ID
     *
     * @return int
     */
    public function getDisclosureRangeId(): int
    {
        return $this->input('disclosure_range_id');
    }
}
