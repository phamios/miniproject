import { FC, useRef, useState } from "react";
import { youtube_parser } from "../utils/youtube";
import axios from "axios";
import { getLocalEnvVariable } from "../utils/localEnv";
import { useFirestore } from "../hooks/useFirestore";
import { Response } from "../enums/response";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
const notify = () => toast("Share Successfully !");

const Share: FC = () => {
  const [loading, setLoading] = useState<boolean>(false);
  const [thumbnailPreview, setThumbnailPreview] = useState<string | null>(null);
  const inputRef = useRef<HTMLInputElement>(null);
  const { addVideo } = useFirestore();
  const { user } = useAuth();
  const navigate = useNavigate();
  

  const onSubmit = async () => {
    try {
      setLoading(true);
      const url = inputRef.current?.value;
      const id = youtube_parser(url ? url : "");

      if (!url || id === false) {
        alert("Invalid Url");
        setLoading(false);
        return;
      }

      const apiUrl = "https://www.googleapis.com/youtube/v3/videos?id="+id+"&key=AIzaSyB21pVMcgQTE9P6UQiOqmtadpp-k4nKtEI&part=snippet";

      const data = await axios.get(apiUrl);
      const snippet = data.data.items[0].snippet; 
      snippet && setThumbnailPreview(snippet.thumbnails.high.url);

      setTimeout(async () => {
        console.log(user);
        const response = await addVideo({
          videoId: id,
          publishedAt: snippet.publishedAt,
          channelId: snippet.channelId,
          title: snippet.title, 
          description: snippet.description,
          thumbnails: snippet.thumbnails.high.url,
          channelTitle: snippet.channelTitle,
          userid: user.id, 
          username: user.username,
          token: user.token
        });
        if (confirm("Are you sure to share this video")) {
          if (response === Response.SUCCESS) {
            notify();
            // navigate(-1);
          } else {
            alert("Fail to share your video, please try again");
          }
        }
        setLoading(false);
       
      }, 2500);
    } catch (error) {
      console.error(error);
      setLoading(false);
    }
  };
  return (
    <div className="flex flex-col items-center justify-center w-full h-screen">
      <div className="w-full h-[500px] border border-black flex flex-col items-center justify-center py-10">
        <h1 className="font-bold text-2xl mb-auto">Share a Youtube movie</h1>
        <div className="w-[300px] h-[200px] bg-gray-500 mb-10 flex flex-col items-center justify-center">
          {thumbnailPreview ? (
            <img
              src={thumbnailPreview}
              alt="preview"
              className="w-full h-full object-cover"
            />
          ) : (
            <h2 className="text-black">Preview thumbnail here</h2>
          )}
        </div>
        <div className="flex flex-row items-center mb-4">
          <h2>Youtube URL</h2>
          <input
            disabled={loading}
            className="border border-black outline-none ml-4 w-48 px-2 py-1"
            ref={inputRef}
            placeholder="Youtube URL"
          />
        </div>
        <button
          className="border border-black w-40 py-2 text-center bg-white hover:bg-black active:bg-black hover:text-white duration-300"
          disabled={loading}
          onClick={onSubmit}
        >
          {loading ? "Sharing..." : "Share"}
        </button>
        <ToastContainer
        position="top-right"
        autoClose={5000}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme="dark" 
        />
      </div>
    </div>
  );
};

export default Share;
