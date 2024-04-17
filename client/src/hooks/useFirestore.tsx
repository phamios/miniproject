import { initializeApp } from "firebase/app";
import {
  collection,
  getDocs,
  getFirestore,
  addDoc, 
} from "firebase/firestore";
import { firebaseConfig } from "../config/firebase";
import { IResponseUser } from "../interfaces/firebase";
import { Response } from "../enums/response";
import { IVideo } from "../interfaces/video";
import axios from 'axios';
 


 
const useFirestore = () => {
  initializeApp(firebaseConfig);
  const db = getFirestore();

  const getAllUsers = async () => {
    try {
      const collectionRef = collection(db, "users");
      const snapshot = await getDocs(collectionRef);
      const users = snapshot.docs.map((doc) => ({ ...doc.data(), id: doc.id }));
      return users as IResponseUser[];
    } catch (error) {
      console.error(error);
      return [];
    }
  };

  const getUser = async (
    username: string
  ): Promise<IResponseUser | undefined> => {
    try { 
      return undefined;
    } catch (error) {
      console.error(error);
      return undefined;
    }
  };

  const addUser = async (
    username: string,
    password: string,
    salt: string
  ): Promise<Response> => {
    const collectionRef = collection(db, "users");
    try {
      await addDoc(collectionRef, {
        username,
        password,
        salt,
      });
      return Response.SUCCESS;
    } catch (error) {
      //console.error(error);
      return Response.ERROR;
    }
  };

  const getAllVideos = async () => {
    try {
      axios.get( process.env.API_URL + "youtube?limit=10")
      .then(res => {
        const videos = res.data; 
        return videos as IVideo[];
      })
      .catch(error => console.log(error));
      // return videos as IVideo[];
    } catch (error) {
      //console.error(error);
      return [];
    }
  };



  const addVideo = async (video: IVideo): Promise<Response> => {
    try {
      axios({
        method: 'post',
        url: process.env.API_URL + "youtube",
        data: {
            "youtubeurl": "https://www.youtube.com/watch?v=" + video.videoId 
        },
        headers: {
          Authorization: `Bearer ${video.token}`
        }
      }).then((response) => {
        console.log(response);
      }).catch(error => {console.log(error)});
       
       return Response.SUCCESS;
    } catch (error) {
      console.error(error);
      return Response.ERROR;
    }
  };

  return { getAllUsers, addUser, getUser, addVideo, getAllVideos };
};

export { useFirestore };
