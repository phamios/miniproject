<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\YoutubeVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

class YoutubeVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'videoid' => $this->faker->randomAscii(),
            'publishedAt' => $this->faker->randomDigit() * 1000,
            'channelId' => $this->faker->randomDigit() * 1000,
            'title'=>$this->faker->randomAscii(),
            'description'=>$this->faker->randomAscii(),
            'thumbnails'=>"https://youtube.com/thisisfortesting.png",
            'channelTitle'=>$this->faker->randomAscii(),
            'userid' => User::factory(['password' => bcrypt('password123')])
        ];
    }
}
