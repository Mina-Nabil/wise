<?php

namespace App\Http\Requests\Cars;

use App\Models\Cars\Brand;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SetBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User */
        $user = Auth::user();
        return $user->can('create', Brand::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "id"            =>  "nullable|exists:brands",
            "name"          =>  "required|unique:brands,name," . $this->input('id'),
            "country_id"    =>  "required|exists:countries"
        ];
    }
}
