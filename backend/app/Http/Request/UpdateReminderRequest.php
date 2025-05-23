<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReminderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'reminder_time' => 'sometimes|required|date',
            'method' => 'sometimes|required|string|in:email,notification,both',
            'color' => 'sometimes|string|max:7'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề là bắt buộc',
            'reminder_time.required' => 'Thời gian nhắc nhở là bắt buộc',
            'reminder_time.date' => 'Thời gian nhắc nhở không hợp lệ',
            'method.in' => 'Phương thức nhắc nhở không hợp lệ',
        ];
    }
} 