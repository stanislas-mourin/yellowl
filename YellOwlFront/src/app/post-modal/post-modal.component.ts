import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { CookieService } from 'ngx-cookie';
// import { PostComponent } from '../post/post.component';

@Component({
  selector: 'app-post-modal',
  templateUrl: './post-modal.component.html',
  styleUrls: ['./post-modal.component.css']
})
export class PostModalComponent implements OnInit {
  $alreadyLiked = false
  $id:number = 0;
  $likes:number = 0;
  $dislikes:number = 0;
  $creator:string ='';
  $age:string ='';
  $title:string = '';
  $content:string = '';
  $media:string = '';
  $category:string ='';
  $comments:number=0;
  $isAuth = false;
  $idAuth = 0;  
  $isAdmin = false;
  $newComment ='';
  $arrayOfComments: any;
  $arrayOfFavorites: any;

  constructor(private activeRoute: ActivatedRoute, private router: Router, private toastr: ToastrService, private cookieService: CookieService) { }


  ngOnInit(): void {
    // document.cookie = "idAuth=4; expires=Thu, 18 Dec 2022 12:00:00 UTC;SameSite=Lax"; 
    // document.cookie = "isAdmin=1; expires=Thu, 18 Dec 2022 12:00:00 UTC;SameSite=Lax"; 
    this.getId();
    this.getFullPostData();
    this.getAuthData();
    this.getAllComments();
  }


  getId(){
    this.$id = Number(this.activeRoute.snapshot.paramMap.get('id'));
  }

  getAuthData(){
    if (Number.isInteger(parseInt(this.cookieService.get('idAuth')))){
      this.$isAuth = true;
      this.$idAuth = parseInt(this.cookieService.get('idAuth'));
      if (parseInt(this.cookieService.get('isAdmin')) == 1){
        this.$isAdmin = true;
      }
    }   
  }


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


  getAllComments(){
    fetch('http://localhost:4200/api/comments/post/' + this.$id)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfComments = data;
    })
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


  closeWindow(){
    this.router.navigate(['/'])
  }


  toasterNotConnected(){
    this.toastr.warning('Please register or login.', 'You are not connected');
  }

  like(){
    if (this.$isAuth == false){
      this.toasterNotConnected();
    } else {
      // On récupère la liste des favoris
      fetch('http://localhost:4200/api/favorites/' + this.$idAuth)
      .then(res =>res.json())
      .then(data => {
        this.$arrayOfFavorites = data;        
          if (this.$arrayOfFavorites) {
            // on boucle sur le la liste des favoris
            this.$arrayOfFavorites.forEach((element:any) => {
              if(element['idPost'] == this.$id && element['isLiked'] == 1) {
                // Le favoris existe déjà et est liké
                this.$alreadyLiked = true
              }
            });
              if (this.$alreadyLiked == false) {
                // Le favoris n'existe pas et on le créé
                fetch('http://localhost:4200/api/favorites', {
                  method: 'POST',
                  body: JSON.stringify({'idUser': this.$idAuth, 'idPost': this.$id, 'isLiked': 1})
                })
                fetch('http://localhost:4200/api/posts/like/' + this.$id, {method: 'PUT'})
                  .then(res =>res.json())
                  .then(data => this.getFullPostData())
              }            
            } else {
              // Le favoris n'existe pas et on le créé
              fetch('http://localhost:4200/api/favorites', {
                method: 'POST',
                body: JSON.stringify({'idUser': this.$idAuth, 'idPost': this.$id, 'isLiked': 1})
              })
              fetch('http://localhost:4200/api/posts/like/' + this.$id, {method: 'PUT'})
                .then(res =>res.json())
                .then(data => this.getFullPostData())
            }
        })  
    }
    if(this.$alreadyLiked) {
      this.toastr.warning('Go f..k yourself and like something else!!', 'This Post has already been liked')
    };
}

    dislike(){
      if (this.$isAuth == false){
        this.toasterNotConnected();
      } else {
        // On récupère la liste des favoris
        fetch('http://localhost:4200/api/favorites/' + this.$idAuth)
        .then(res =>res.json())
        .then(data => {
          this.$arrayOfFavorites = data;        
            if (this.$arrayOfFavorites) {
              // on boucle sur le la liste des favoris
              this.$arrayOfFavorites.forEach((element:any) => {
                if(element['idPost'] == this.$id && element['isLiked'] == 0) {
                  // Le favoris existe déjà et est liké
                  this.$alreadyLiked = true
                }
              });
                if (this.$alreadyLiked == false) {
                  // Le favoris n'existe pas et on le créé
                  fetch('http://localhost:4200/api/favorites', {
                    method: 'POST',
                    body: JSON.stringify({'idUser': this.$idAuth, 'idPost': this.$id, 'isLiked': 0})
                  })
                  fetch('http://localhost:4200/api/posts/dislike/' + this.$id, {method: 'PUT'})
                    .then(res =>res.json())
                    .then(data => this.getFullPostData())
                }            
              } else {
                // Le favoris n'existe pas et on le créé
                fetch('http://localhost:4200/api/favorites', {
                  method: 'POST',
                  body: JSON.stringify({'idUser': this.$idAuth, 'idPost': this.$id, 'isLiked': 0})
                })
                fetch('http://localhost:4200/api/posts/dislike/' + this.$id, {method: 'PUT'})
                  .then(res =>res.json())
                  .then(data => this.getFullPostData())
              }
          })  
      }
      if(this.$alreadyLiked) {
        this.toastr.warning('Go f..k yourself and like something else!!', 'This Post has already been disliked')
      };
  }


  report(){
    if (this.$isAuth == false){
      this.toasterNotConnected();
    } else {
      fetch('http://localhost:4200/api/posts/report/' + this.$id, { 
        method: 'PUT'
      })
      .then(res =>res.json())
      .then(data => this.getFullPostData());
    }
  }


  sendNewComment(){
    fetch('http://localhost:4200/api/comments', {
      method: 'POST',
      body: JSON.stringify({
        "idPost" : this.$id,
        "idUser" : this.$idAuth,
        "content" : this.$newComment
      })
    })
      .then(res =>res.json())
      .then(data => {
        this.getAllComments();
        this.$newComment='';
      })
  }

  getUserFavorites() {
    fetch('http://localhost:4200/api/favorites/' + this.$idAuth)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfFavorites = data;
      console.log(data)
    })
  }

  ngOnDestroy() {
    // document.cookie = "idAuth=1; expires=Thu, 18 Dec 2012 12:00:00 UTC;SameSite=Lax"; 
    // document.cookie = "isAdmin=0; expires=Thu, 18 Dec 2012 12:00:00 UTC;SameSite=Lax";
    // this.postComponent.getFullPostData();
  }

}
