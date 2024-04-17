import { FC, useEffect, useState } from "react";
import Post from "./Post";
import { useFirestore } from "../hooks/useFirestore";
import { IVideo } from "../interfaces/video";
import videoServices from "../services/video-services";
import axios from 'axios';


const Feed: FC = () => {
  const { getAllVideos } = useFirestore();
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [videos, setVideos] = useState([]);
  
 
 
  useEffect(() => {
    async function fetchData() {
      try {
        setIsLoading(true);
        const response = await axios.get('http://localhost:8080/api/v1/youtube?limit=10');
        setVideos(response.data.data);
        console.log(response.data.data);
        setIsLoading(false);
      } catch (error) {
        console.log(error);
      }
    }
    fetchData();
  }, []);


    // useEffect(() => {
    //   async function fetchVideos() {
    //     setIsLoading(true);
    //     const response = await getAllVideos();
    //     console.log(response);
    //     setVideos(response as IVideo[]);
    //     setIsLoading(false);
    //   }
    //   fetchVideos();
    // }, []);

  // const getAllVideo = () => {
  //   videoServices.getListVideo()
  //     .then((response: any) => {
  //       setVideos(response.data);
  //       console.log(response.data);
  //     })
  //     .catch((e: Error) => {
  //       console.log(e);
  //     });
  // };

  // useEffect(() => {
  //   setIsLoading(true);
  //   getAllVideo();
  //   setIsLoading(false);
  // }, []);

  return (
    <div className="flex flex-col items-center pt-28 w-full h-auto">
      {isLoading && <h1>Loading...</h1>}
      {!isLoading && videos.length === 0 && <h1>Feed is currently empty</h1>}
      {videos.map((video, index) => (
        <Post data={video} key={index} />
      ))}
    </div>
  );
};

export default Feed;
