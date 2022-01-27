import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  // constructor (private http:HttpClient) {}
  //getCategories(){
    //return this.http.get('http://http://localhost:8000/api/categories'); 
    //}
    

    private url = "http://localhost:4200/api/categories";
  
    constructor(private httpClient: HttpClient) { }
    public sendGetRequest(){
      return this.httpClient.get(this.url);
    }
  }

