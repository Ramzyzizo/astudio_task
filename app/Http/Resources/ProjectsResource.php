<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'attributes' => $this->attributes
                ->groupBy('id') 
                ->map(function ($groupedAttributes) {
                    return [
                        'name' => $groupedAttributes->first()->name,
                        'type' => $groupedAttributes->first()->type,
                        'values' => $groupedAttributes->map(function ($attribute) {
                            return [
                                'value' => $attribute->pivot->value,
                                'start_date' => $attribute->pivot->start_date,
                                'end_date' => $attribute->pivot->end_date,
                            ];
                        })->values(),
                    ];
                })->values(), // Reset array keys
        ];
    }
}
