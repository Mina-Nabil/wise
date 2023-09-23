<?php

namespace App\Http\Requests\Cars;

use App\Models\Cars\CarModel;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SetCarModelRequest extends FormRequest
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
        return $user->can('create', CarModel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "id"        =>  "nullable|exists:car_models",
            "name"      =>  "required",
            "brand_id"  =>  "required|exists:brands"
        ];
    }
}
