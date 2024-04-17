import axios from "axios";

const API_URL = "http://localhost:8080/api/v1/auth";

class AuthService {
    login(username: string, password: string) {
      return axios
        .post(API_URL + "/login", {
          username,
          password
        })
        .then(response => {
          if (response.data.accessToken) {
            localStorage.setItem("user", JSON.stringify(response.data));
          }
  
          return response.data;
        });
    }
  
    logout() {
      localStorage.removeItem("user");
    }
  
    register( email: string, password: string) {
      return axios.post(API_URL + "signup", { 
        email,
        password
      });
    }
  
    getCurrentUser() {
      const userStr = localStorage.getItem("user");
      if (userStr) return JSON.parse(userStr);
  
      return null;
    }
  }
  
  export default new AuthService();