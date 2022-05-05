<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeRegistrationFavoriteVideoRequest extends FormRequest
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
            'source_folder_type' => ['required', 'string', Rule::in(['parent', 'child', 'grandchild'])],
            'source_folder_id' => ['required', 'numeric'],
            'destination_folder_id' => ['required', 'numeric']
        ];
    }

    /**
     * 各フォルダーのタイプ（親、子、孫）
     *
     * @return string
     */
    public function getSourceFolderType(): string
    {
        return $this->input('source_folder_type');
    }

    /**
     * 変更元のフォルダーのID
     *
     * @return int
     */
    public function getSourceFolderId(): int
    {
        return $this->input('source_folder_id');
    }

    /**
     * 変更先のフォルダーのID
     *
     * @return int
     */
    public function getDestinationFolderId(): int
    {
        return $this->input('destination_folder_id');
    }
}
