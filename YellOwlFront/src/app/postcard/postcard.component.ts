import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {HttpClient} from '@angular/common/http';
import { ApiService } from '../api.service';


@Component({
  selector: 'app-postcard',
  templateUrl: './postcard.component.html',
  styleUrls: ['./postcard.component.css']
})

export class PostcardComponent implements OnInit {

  url:string="";
  blogs:any=[];
  title: string="";
  content:string="";
  media:string="";
  liste: any =[];
  api: any;
  posts:any;

  constructor(private apiservice:ApiService, private httpService:HttpClient){
     
}

  selectFile(event:any){
    if(event.target.files){
      var reader = new FileReader()
      reader.readAsDataURL(event.target.files[0])
      reader.onload=(event: any)=>{
        this.url = event.target.result
      }
    }
}
  ngOnInit() {
    this.httpService.get('localhost:4200/api/categories').subscribe(
      (response:any) => { this.posts = response; },
      (error:any) => { console.log(error); });
    } 
    
    addpost(){
      console.log("clicked on add");
      let blog={ "idUser":2, "idCategory":6, "title":this.title, "content":this.content, "media":"Text" };
      
      fetch('http://localhost:4200/api/posts', {
      method: 'POST',
      body: JSON.stringify(blog)
      })
      .then(res =>res.json())
      .then(data => {
        console.log(data);
      })
      // if(localStorage.getItem("blogs")){
      //   this.blogs=JSON.parse(localStorage.getItem("blogs")!)
      // }
      // this.blogs.push(blog)
      // localStorage.setItem("blogs", JSON.stringify(this.blogs))
      // this.title=""
      // alert("Post submitted")
      
    }
  };
