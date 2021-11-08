import {Injectable} from '@angular/core';
import {User} from "../user";
import axios from "axios";
import {environment} from "../../environments/environment";

@Injectable()
export class AuthService {

  constructor() {
  }

  public isAuthenticated(): boolean {
    const token = sessionStorage.getItem('token');
    return !!token;
  }

  public authentificate(user: User){
    sessionStorage.setItem('token', user.api_token);
    sessionStorage.setItem('user_id', user.id.toLocaleString());
    this.addGroup(user);
  }

  addGroup(user: User){
    axios.post(`${environment.url}groups/1/users`,{
      api_token: sessionStorage.getItem('token'),
      user_email: user.email
    })
      .then((response) => {
      }).catch((error) => {
    });
  }
}
