import { FC } from "react";
import Nav from "./Nav";
import { Outlet } from "react-router-dom";
import MobileNav from "./MobileNav";
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';


const Layout: FC = () => {
  return (
    <div className="relative w-full h-auto">
      <Nav />
      <MobileNav />
      <Outlet />
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
  );
};

export default Layout;
