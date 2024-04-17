import React, { ReactNode } from "react";
import { useState } from 'react';
import axios from 'axios'
import { IUser } from "../interfaces/user";

import TextField from '@mui/material/TextField';
import Button from '@mui/material/Button';
import { FormControl, FormLabel } from '@mui/material';
import { toast } from "react-toastify";

interface ModalType {
  children?: ReactNode;
  isOpen: boolean;
  toggle: () => void;
}

export default function Modal(props: ModalType) {

  const [formData, setFormData] = useState<IUser> ({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  })

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const {name, value} = e.target;
    setFormData(prevData => ({...prevData, [name]: value}))
  }

  const handleSubmit = async (e: React.ChangeEvent<HTMLFormElement>) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost:8080/api/v1/auth/signup', formData);
      console.log(response); //Will result in an error because the above endpoint doesn't exist yet
        if(response.data.success){
          toast('Resiger Successfully !');
          window.location.reload();
        }  
      
    }catch (error) {
      console.error(error);
    }
  }

  console.log(formData);
 

  return (
    <>
      {props.isOpen && (
        <div className="modal-overlay" onClick={props.toggle}>
          <div onClick={(e) => e.stopPropagation()} className="modal-box">
            {props.children}
           <form className="formsubmit" onSubmit={handleSubmit}>
            <FormControl >
                <h3>Signup to System</h3>
                <br/>
                <FormLabel>Name</FormLabel>
                <TextField type="text" variant='outlined' name="name" onChange={handleChange} size='small' />
                <FormLabel>Email</FormLabel>
                <TextField type="text" variant='outlined' name="email" onChange={handleChange}  size='small'  />
                <FormLabel>Password</FormLabel>
                <TextField type="password" variant='outlined' name="password" onChange={handleChange}  size='small'  />
                <FormLabel>Confirm Password</FormLabel>
                <TextField type="password" variant='outlined' name="password_confirmation" onChange={handleChange} size='small' />
                <Button type="submit">Submit</Button>
            </FormControl>
            </form>

          </div>
        </div>
      )}
    </>
  );
}
 


