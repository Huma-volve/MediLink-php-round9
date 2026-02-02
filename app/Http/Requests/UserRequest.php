<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user?->id,
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,doctor,patient',
            'profile_picture' => 'nullable|image|mimes:jpg,png|max:2048',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|unique:users,phone,' . $this->user?->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نصاً',
            'name.max' => 'الاسم يجب ألا يزيد عن 255 حرفاً',

            'email.required' => 'الايميل مطلوب',
            'email.email' => 'ادخل ايميل صحيح',
            'email.unique' => 'الايميل مستخدم بالفعل',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'كلمة المرور يجب أن تكون نصية',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق',

            'role.required' => 'اختر نوع المستخدم',
            'role.in' => 'نوع المستخدم غير صالح',

            'profile_picture.image' => 'الملف يجب أن يكون صورة',
            'profile_picture.mimes' => 'الملف يجب أن يكون jpg أو png',
            'profile_picture.max' => 'حجم الصورة لا يزيد عن 2MB',

            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً',
        ];
    }
}
