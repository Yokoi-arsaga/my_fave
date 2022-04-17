<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildFolderRequest extends FormRequest
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
            'folder_name' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:250'],
            'disclosure_range_id' => ['required', 'numeric', 'between:1,3'],
            'is_nest' => ['boolean']
        ];
    }

    /**
     * フォルダ名
     *
     * @return string
     */
    public function getFolderName(): string
    {
        return $this->input('folder_name');
    }

    /**
     * フォルダの説明文
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->input('description');
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

    /**
     * ネストフラグ
     *
     * @return bool
     */
    public function getIsNest(): bool
    {
        return $this->input('is_nest');
    }
}
