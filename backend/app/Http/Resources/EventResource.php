class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_time,
            'end_date' => $this->end_time,
            'location' => $this->location,
            'all_day' => $this->is_all_day,
            'color' => $this->color,
            'calendar_id' => $this->calendar_id
        ];
    }
} 