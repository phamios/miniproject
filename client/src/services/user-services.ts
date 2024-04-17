import axios from 'axios';
import authHeader from './auth-header';

const API_URL = 'http://localhost:8080/api/v1/users/';

class UserService {
  
  getUserBoard() {
    return axios.get(API_URL + 'profile', { headers: authHeader() });
  }
 
}

export default new UserService();