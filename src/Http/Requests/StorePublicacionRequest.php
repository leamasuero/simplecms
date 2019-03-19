<?php
namespace Lebenlabs\SimpleCMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicacionRequest extends FormRequest
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
            'titulo' => 'required',
            'cuerpo' => 'required|min:20',
            'fecha_publicacion' => 'required',
            'extracto' => 'required|min:20|max:750',
            'categoria' => 'required'
        ];
    }
}
