<?php

namespace App\Http\Resources;

use Nette\Utils\DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
        ];
    }
}
