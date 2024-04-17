<?php

namespace Database\Seeders;

use App\Models\YoutubeVideo;
use Illuminate\Database\Seeder;

class YoutubeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        YoutubeVideo::create([
            'videoid' => 'KaeNCVjeZO4',
            'publishedAt' => 'youtube',
            'channelId' => 'UCXwBPO45wLC_lGDB-C-Dvqg"',
            'title' => 'Heart Meditation | 1 hour handpan music | Malte Marten',
            'description' => 'In the embrace of the heart we can find inner peace and healing. It is the place where painful memories and broken parts of ourselves can be healed. When we allow the pain and meet ourselves and others with love, the wounds of life can fade in the warm light of the heart',
            'thumbnails' => 'https://i.ytimg.com/vi/KaeNCVjeZO4/sddefault.jpg',
            'channelTitle' => 'Yatao Music',
            'userid' => '6619f1d0dca6c4ecbc0f341a',
        ]);

        YoutubeVideo::factory(10)->create([ ]);
    }
}
