import axios from 'axios';
import authHeader from './auth-header';
import { IVideo } from '../interfaces/video';

const API_URL = 'http://localhost:8080/api/v1/youtube';


interface ServerResponse {
  data: IVideo
}


class VideoService {

  getListVideo() {
    // return axios.get({ data: IVideo[] })(API_URL + "?limit=10"); 
    return axios.request<IVideo>({
      url: API_URL + "?limit=10",
      transformResponse: (r: ServerResponse) => r.data
    }).then((response) => { 
      const { data } = response 
    })

  }

  getDetailVideo(videoId:any) {
    return axios.get(API_URL + "/" + videoId, { headers: authHeader() });
  }
 
}

export default new VideoService();