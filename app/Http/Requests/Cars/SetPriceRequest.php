<?php

namespace App\Http\Requests\Cars;

use App\Models\Cars\CarPrice;
use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SetPriceRequest extends FormRequest
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
        return $user->can('create', CarPrice::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "car_id"    =>  "required|exists:cars",
            "model_year" =>  "required|numeric",
            "price"     =>  "required|numeric",
            "desc"      =>  "nullable"
        ];
    }
}
