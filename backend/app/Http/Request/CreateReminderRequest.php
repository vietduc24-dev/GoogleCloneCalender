<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateReminderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'reminder_time' => 'required|date',
            'method' => 'sometimes|string|in:email,notification,both',
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