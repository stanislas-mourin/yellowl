import { Component, OnInit, Input } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-post',
  templateUrl: './post.component.html',
  styleUrls: ['./post.component.css']
})
export class PostComponent implements OnInit {

  @Input() $id = 0;
  $likes:number = 0;
  $dislikes:number = 0;
  $creator:string ='';
  $age:string ='';
  $title:string = '';
  $content:string = '';
  $media:string = '';
  $category:string ='';
  $comments:number=0;

  constructor(private router: Router) { }

  ngOnInit(): void {
    this.getFullPostData();
  }

  // ngOnChange() : void {
  //   this.getFullPostData()
  // }

  getFullPostData(){
    fetch('http://localhost:4200/api/posts/' + this.$id)
    .then(res =>res.json())
    .then(data => {
      this.$likes = data.likes;
      this.$dislikes = data.dislikes;
      this.$title = data.title;
      this.$content = data.content;
      this.$media = data.media;
      this.calculAge(data.dateOfPost);
    })
    fetch('http://localhost:4200/api/posts/categoryName/' + this.$id)
    .then(res =>res.json())
    .then(data => {
      this.$category = data;
    })
    fetch('http://localhost:4200/api/posts/userNickname/' + this.$id)
    .then(res =>res.json())
    .then(data => {
      this.$creator = data;
    })
    fetch('http://localhost:4200/api/comments/number/post/' + this.$id)
    .then(res =>res.json())
    .then(data => {
      this.$comments = data;
    })
  }

  openPostView(){
    this.router.navigate(['/post', this.$id])
  }

  calculAge($datePost:Date) {
    var post = new Date($datePost);
    var todayDate = new Date();
    const today = todayDate.getTime()/1000;
    const postAge = post.getTime()/1000;
    var diff = today-postAge;
    if (diff <0) {
        this.$age = "N/A";
    } else if (diff <60) {
        this.$age = "less than 1 minute";
    } else if (diff <120) {
        this.$age = "1 minute ago";
    } else if (diff <3600) {
        this.$age = Math.floor(diff/60) + " minutes ago";
    } else if (diff <7200) {
        this.$age = "1 hour ago";
    } else if (diff <86400) {
        this.$age = Math.floor(diff/3600)+ " hours ago";
    } else if (diff <172800) {
        this.$age = "1 day ago";
    } else if (diff <2592000){
        this.$age = Math.floor(diff/86400) + " days ago";
    } else if (diff <5184000)  {
        this.$age = "1 month ago";
    }  else if (diff <31536000) {
        this.$age = Math.floor(diff/2592000) + " months ago";
    } else {
        this.$age = "more than 1 year ago";
    }
  }

}
