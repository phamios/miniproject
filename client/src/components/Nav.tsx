import { FC, useCallback, useRef, useState} from "react";
import { useAuth } from "../hooks/useAuth";
import { Link, useNavigate } from "react-router-dom";
import Home from "../assets/home.png";
import {toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Modal from "../modals/modal";
import useModal from "../hooks/useModal";
 
const Nav: FC = () => {
  
  const usernameInputRef = useRef<HTMLInputElement>(null);
  const passwordInputRef = useRef<HTMLInputElement>(null);

  const [inputDisabled, setInputDisabled] = useState<boolean>(false);
  const { user, signIn, logOut } = useAuth();
  const navigate = useNavigate();
 
  const { isOpen, toggle } = useModal();

  const onSubmit = useCallback(async () => {
    setInputDisabled(true);
    const email = usernameInputRef.current?.value;
    const password = passwordInputRef.current?.value;
    if (email && password) {
      if (email.length < 6 || password.length < 6) { 
        toast("Username and password should contain more than 6 characters !")
      } else {
        signIn && (await signIn(email, password));
      }
    }
    setInputDisabled(false);
  }, [signIn]);

  return ( 
    <div className="sm:hidden fixed mx-60 xl:mx-40 lg:mx-8 top-0 left-0 w-[calc(100%-30rem)] xl:w-[calc(100%-20rem)] lg:w-[calc(100%-4rem)] h-20 bg-white flex flex-row items-center border-b border-black z-10">
      <div
        className="flex flex-row items-center cursor-pointer"
        onClick={() => {
          navigate("/");
        }}
      >
        <img
          src={Home}
          alt="home"
          className="w-[40px] h-[40px] object-contain"
        />
        <h1 className="text-3xl ml-4 font-bold lg:hidden">Share Youtube</h1>
      </div>
      {user == null || user == undefined ? ( 
        <>
        <Modal isOpen={isOpen} toggle={toggle}></Modal>
          <input
            disabled={inputDisabled}
            className="ml-auto border border-black outline-none mr-4 w-40 px-4 py-2"
            ref={usernameInputRef}
            placeholder="username"
          />
          <input
            disabled={inputDisabled}
            className="border border-black outline-none mr-4 w-40 px-4 py-2"
            ref={passwordInputRef}
            placeholder="password"
            type="password"
          />
          <button
            className="border border-black w-20 py-2 text-center bg-white hover:bg-black active:bg-black hover:text-white duration-300"
            disabled={inputDisabled}
            onClick={onSubmit}
          >
            Login
          </button>
          <button
            className="border border-black w-20 py-2 text-center bg-white hover:bg-black active:bg-black hover:text-white duration-300"
            disabled={inputDisabled}
            onClick={toggle}
          >
            Register
          </button>  
        </>
      ) : (
        <>
          <h1 className="ml-auto">
            Hi <span className="font-semibold">{user.username}</span>
          </h1>
          <Link
            className="ml-6 border border-black w-20 py-2 text-center bg-white hover:bg-black hover:text-white duration-300"
            to="/share"
          >
            Share
          </Link>
          <button
            className="ml-6 border border-black w-20 py-2 text-center bg-white hover:bg-black hover:text-white duration-300"
            onClick={logOut}
          >
            Log Out
          </button>
        </>
      )}
    </div>
  );
};

export default Nav;
