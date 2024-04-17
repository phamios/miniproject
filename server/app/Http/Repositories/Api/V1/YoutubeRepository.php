<?php

namespace App\Http\Repositories\Api\V1;

use App\Traits\ApiFilterTrait;
use App\Models\YoutubeVideo;
use App\Http\Resources\Api\V1\YoutubeResource;

class YoutubeRepository
{
    use ApiFilterTrait;

    public function __construct(YoutubeVideo $youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * Search Youtube
     * @param Illuminate\Http\Request $request
     * @return App\Http\Resources\Api\V1\YoutubeResource
     */
    public function searchYoutube($request)
    {
        $limit = $request->limit ?? 10;
        $data = $this->youtube;
        $data = $this->setOrder($data, ['created_at', '-1']);
        $data = $data->paginate($limit);
        return YoutubeResource::collection($data);
    }


    /**
     * Create Youtube
     * @param array $data
     * @return array
     */
    public function saveVideo($data)
    {
        $status = true;
        $message   = "Successed";
        // $check = $this->checkVideoItemAvailable($data);
        // $status = $check['status'];
        // $message = $check['message'];
        // $item = $check['item'];
        try {
            $youtube = $this->youtube->create($data);
        } catch (\Throwable $th) {
            //throw $th;
            $status = false;
            $message = $th->getMessage();
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $youtube ?? null,
        ];
        return $response;
    }



}
