import { Component, Input, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
// import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-comments', 
  templateUrl: './comments.component.html',
  styleUrls: ['./comments.component.css']
})
export class CommentsComponent implements OnInit {
  @Input() commentId: any

  private apiURL = 'http://localhost:4200/api'
  ageOfComment: any
  avatar: any
  content: any
  dateOfComment: any
  dislikes: any
  firstName: any
  lastName: any
  likes: any
  modif: any
  nickName: any
  urlAvatar: any
  userId: any

  imgUrl = ""



  constructor(private sanitizer: DomSanitizer) { }

  ngOnInit(): void {
    this.modif = false

    fetch(this.apiURL + '/comments/' + this.commentId, {
      headers: {
        "Content-Type": "application/json"
      }
    }).
      then(response => response.json()).
      then(data => {
        this.content = data.content
        this.dateOfComment = data.dateOfComment
        this.dislikes = data.dislikes
        this.likes = data.likes
        this.userId = data.idUser

        fetch(this.apiURL + '/users/' + this.userId).
          then(response2 => response2.json()).
          then(data2 => {
            this.firstName = data2.firstName
            this.lastName = data2.lastName
            this.nickName = data2.nickName
          });

        fetch(this.apiURL + '/users/avatar/' + this.userId).
          then(response => response.blob()).
          then(data3 => {
            this.imgUrl = URL.createObjectURL(data3)
            this.urlAvatar = this.sanitizer.bypassSecurityTrustUrl(this.imgUrl);
          });

        this.calculAge()

      });

  }

  like() {
    fetch(this.apiURL + '/comments/like/' + this.commentId, {
      method: 'PUT',
    })
      .then(response => response.json())
      this.likes++
  }

  dislike() {
    fetch(this.apiURL + '/comments/dislike/' + this.commentId, {
      method: 'PUT',
    })
      .then(response => response.json())
      this.dislikes++
  }

  toggleCommentForm() {
    if (this.modif == true) {
      this.modif = false;
    } else {
      this.modif = true;
    }
  }

  calculAge() {
    const today = new Date().getTime() / 1000;
    const commentAge = Date.parse(this.dateOfComment) / 1000;
    var diff = today - commentAge;
    if (diff < 0) {
      this.ageOfComment = "Not available";
    } else if (diff < 60) {
      this.ageOfComment = "Less than 1 minute";
    } else if (diff < 120) {
      this.ageOfComment = "1 minute ago";
    } else if (diff < 3600) {
      this.ageOfComment = Math.floor(diff / 60) + " minutes ago";
    } else if (diff < 7200) {
      this.ageOfComment = "1 hour ago";
    } else if (diff < 86400) {
      this.ageOfComment = Math.floor(diff / 3600) + " hours ago";
    } else if (diff < 172800) {
      this.ageOfComment = "1 day ago";
    } else if (diff < 2592000) {
      this.ageOfComment = Math.floor(diff / 86400) + " days ago";
    } else if (diff < 5184000) {
      this.ageOfComment = "1 month ago";
    } else if (diff < 31536000) {
      this.ageOfComment = Math.floor(diff / 2592000) + " months ago";
    } else {
      this.ageOfComment = "More than 1 year ago";
    }
  }

}
