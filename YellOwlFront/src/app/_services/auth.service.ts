import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

const AUTH_API = 'http://localhost:4200/api/auth/';

const httpOptions = {
  headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable({ 
  providedIn: 'root'
})
export class AuthService {
  constructor(private http: HttpClient) { }


  register(firstname: string, lastname: string, nickname: string, email: string, password: string, confirmPassword: string): Observable<any> {
    return this.http.post(AUTH_API + 'signup', {
      firstname,
      lastname,
      nickname,
      email,
      password,
      confirmPassword,
      
    }, httpOptions);
  }
}