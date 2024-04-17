<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class YoutubeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // $details = [
        //     'Youtube' => YoutubeDetailResource::class,
        // ];
        return [
            'videoid'=>$this->videoid,
            'publishedAt'=>$this->publishedAt,
            'channelId'=>$this->channelId,
            'title'=>$this->title,
            'description'=>$this->description,
            'thumbnails'=>$this->thumbnails,
            'channelTitle'=>$this->channelTitle,
            'userid'=>$this->userid,
            'username'=>$this->username,

        ];
    }
}
