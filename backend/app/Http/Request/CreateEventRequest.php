<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'calendar_id' => 'required|exists:calendars,id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'all_day' => 'boolean',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7'
        ];
    }

    public function messages()
    {
        return [
            'calendar_id.required' => 'Calendar ID là bắt buộc',
            'calendar_id.exists' => 'Calendar không tồn tại',
            'title.required' => 'Tiêu đề là bắt buộc',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc',
            'end_date.required' => 'Ngày kết thúc là bắt buộc',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'location.max' => 'Địa điểm không được dài quá 255 ký tự',
        ];
    }
} 