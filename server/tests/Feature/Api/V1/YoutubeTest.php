<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\YoutubeVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class YoutubeTest extends TestCase
{
    /**
     * Test API V1 get list youtube video success
     *
     * @return void
     */
    public function test_get_list_video_success()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get('/api/v1/youtube');

        $response->assertOk()
                 ->assertSeeText('data')
                 ->assertSeeText('pagination');
    }

    /**
     * Test API V1 get list youtube failed
     *
     * @return void
     */
    public function test_get_list_youtube_failed()
    {
        $response = $this->get('/api/v1/youtube');

        $response->assertUnauthorized();
    }

    /**
     * Test API V1 get detail youtube success
     *
     * @return void
     */
    public function test_get_detail_youtube_success()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $youtube = YoutubeVideo::factory(['userid' => $user->_id])->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get('/api/v1/youtube/'.$youtube->videoid);

        $response->assertOk()
                 ->assertJson([
                     'status' => true
                 ]);
    }

    /**
     * Test API V1 get list youtube failed
     *
     * @return void
     */
    public function test_get_detail_youtube_failed()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $youtube = YoutubeVideo::factory(['userid' => $user->_id])->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get('/api/v1/youtube/xx'.$youtube->videoid);

        $response->assertNotFound();
    }
}
