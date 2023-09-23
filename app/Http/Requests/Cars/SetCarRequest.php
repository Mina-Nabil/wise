<?php

namespace App\Http\Requests\Cars;

use App\Models\Cars\Car;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SetCarRequest extends FormRequest
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
        return $user->can('create', Car::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "id"            =>  "nullable|exists:cars",
            "car_model_id"  =>  "required|exists:brands",
            "category"      =>  "required",
            "desc"          =>  "nullable"
        ];
    }
}
