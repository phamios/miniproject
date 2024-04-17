<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\YoutubeDetailResource;
use App\Models\YoutubeVideo;
use App\Http\Requests\Api\V1\YoutubeRequest;
use Facades\App\Http\Repositories\Api\V1\YoutubeRepository;
use Illuminate\Http\Request;
use Alaouy\Youtube\Facades\Youtube;

class YoutubeController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *  path="/api/v1/youtube",
     *  summary="Get list YOUTUBEEEEE",
     *  description="Endpoint to get list youtube",
     *  tags={"Youtube"},
     *  security={
     *      {"token": {}}
     *  },
     *  @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      description="Limit (Default 10)"
     *  ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Num Of Page"
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Ok",
     *      @OA\JsonContent(
     *          @OA\Property(property="data", type="object", example={}),
     *              @OA\Property(property="pagination", type="object", example={}),
     *      )
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Failed to get list orders."),
     *              @OA\Property(property="code", type="number", example=500),
     *              @OA\Property(property="error", type="string", example="Something Wrong."),
     *      )
     *  ),
     * )
     */
    public function index(Request $request)
    {
        try {
            $data = YoutubeRepository::searchYoutube($request);
            return $this->responseJson('pagination', 'Get list youtube successfully.', $data, 200, []);
        } catch (\Throwable $th) {
            return $this->responseJson('error', 'Failed to get list youtube.', $th, 500);
        }
    }


    /**
     * @OA\Get(
     *       path="/api/v1/youtube/{id}",
     *       summary="Get detail vehicle",
     *       description="Endpoint to get detail vehicle",
     *       tags={"Youtube"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\Parameter(
     *          name="title",
     *          in="path",
     *          description="Search Video By Title"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *      ),
     * )
     */
    public function show($id)
    {
        $youtube = YoutubeVideo::where('videoid',$id)->first();
        if (!$youtube) {
            return $this->responseJson('error', 'Not found.', '', 404);
        }
        return $this->responseJson('success', 'Get detail order successfully', new YoutubeDetailResource($youtube));
    }



     /**
     * @OA\Post(
     *  path="/api/v1/youtube",
     *  summary="Create Video Youtube",
     *  description="Endpoint to handle create video",
     *  tags={"Youtube"},
     *  security={
     *      {"token": {}}
     *  },
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required={"youtubeurl"},
     *          @OA\Property(property="youtubeurl", type="string", example="https://youtube.com/videos/asdasd"),
     *      ),
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="OK",
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *  ),
     * )
     */
    public function store(YoutubeRequest $request)
    {
        //Extract URL and get Youtube video ID
        $parts = parse_url($request->youtubeurl);
        parse_str($parts['query'], $query);
        $video_id = $query['v'];
        //ETL Youtube link

        $url = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.trim($video_id).'&key=AIzaSyB21pVMcgQTE9P6UQiOqmtadpp-k4nKtEI&part=snippet');
        $decoded = json_decode($url, true);
        $data = null;
        $user = auth()->user();
        foreach ($decoded['items'] as $items) {
            $data['videoid']= $video_id;
            $data['title']= $items['snippet']['title'];
            $data['description']= $items['snippet']['description'];
            $data['thumbnails']= $items['snippet']['thumbnails']['standard']['url'];
            $data['publishAt']= $items['snippet']['publishedAt'];
            $data['channelId']= $items['snippet']['channelId'];
            $data['channelTitle']= $items['snippet']['channelTitle'];
            $data['userid']=$user->id;
            $data['username']=$user->name;
        }

        $response = YoutubeRepository::saveVideo($data);
        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['status'] ? $response['data'] : null,
            $response['status'] ? 201 : 400,
        );
    }

}
