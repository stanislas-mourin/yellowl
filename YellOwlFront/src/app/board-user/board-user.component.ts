import { Component, OnInit } from '@angular/core';
import { UserService } from '../_services/user.service';
import { DomSanitizer } from '@angular/platform-browser';
import { CookieService } from 'ngx-cookie';

@Component({
  selector: 'app-user',
  templateUrl: './board-user.component.html',
  styleUrls: ['./board-user.component.css']
})
export class BoardUserComponent implements OnInit {
  content?: string;

  private apiURL = 'http://localhost:4200/api'
  $arrayOfComments: any;
  $arrayOfFavorites: any;
  $arrayOfHidden: any;
  $arrayOfPosts: any;
  $arrayOfProfile: any;
  $arrayOfUpvoted: any =[];
  $arrayOfDownvoted: any =[];
  $displayComments:boolean = false;
  $displayFavorites:boolean = false;
  $displayHidden:boolean = false;
  $displayPosts:boolean = false;
  $displayProfile:boolean = false;
  $displayDownvoted:boolean = false;
  $displayUpvoted:boolean = false;
  dateOfBirth: any
  emailAddress: any
  firstName: any
  imgUrl: any
  lastName: any
  nickName: any
  password: any
  urlAvatar: any
  userId: any

  constructor(private userService: UserService, private sanitizer: DomSanitizer, private cookieService: CookieService) { }

  ngOnInit(): void {
    this.getAuthData();


    fetch(this.apiURL + '/users/' + this.userId).
    then(response2 => response2.json()).
    then(data2 => {
      this.firstName = data2.firstName
      this.lastName = data2.lastName
      this.nickName = data2.nickName
    });

    fetch(this.apiURL + '/users/avatar/' + this.userId).
    then(response => response.blob()).
    then(data => {
      this.imgUrl = URL.createObjectURL(data)
      this.urlAvatar = this.sanitizer.bypassSecurityTrustUrl(this.imgUrl);
    });
  }

  getAuthData(){
    if (Number.isInteger(parseInt(this.cookieService.get('idAuth')))){
      this.userId = parseInt(this.cookieService.get('idAuth'));
    }   
  }

  getAllComments(){
    fetch('http://localhost:4200/api/comments/user/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfComments = data;
    })
    this.$displayPosts=false
    this.$displayFavorites=false
    this.$displayComments=true
    this.$displayHidden=false
    this.$displayProfile=false
    this.$displayUpvoted=false
    this.$displayDownvoted=false
  }
  getAllFavorites(){
    fetch('http://localhost:4200/api/favorites/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfFavorites = data;
    })
    this.$displayPosts=false
    this.$displayFavorites=true
    this.$displayComments=false
    this.$displayHidden=false
    this.$displayProfile=false
    this.$displayUpvoted=false
    this.$displayDownvoted=false
  }
  getAllHidden(){
    fetch('http://localhost:4200/api/hidden/user/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfHidden = data;
    })
    this.$displayPosts=true
    this.$displayFavorites=false
    this.$displayComments=false
    this.$displayHidden=true
    this.$displayProfile=false
    this.$displayUpvoted=false
    this.$displayDownvoted=false
  }
  getAllPosts(){
    fetch('http://localhost:4200/api/posts/user/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfPosts = data;
    })
    this.$displayPosts=true
    this.$displayFavorites=false
    this.$displayComments=false
    this.$displayHidden=false
    this.$displayProfile=false
    this.$displayUpvoted=false
    this.$displayDownvoted=false
  }
  getProfile(){
    fetch('http://localhost:4200/api/users/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      this.$arrayOfProfile = data;
      this.firstName = this.$arrayOfProfile.firstName;
      this.lastName = this.$arrayOfProfile.lastName;
      this.nickName = this.$arrayOfProfile.nickName;
      this.emailAddress = this.$arrayOfProfile.emailAddress;
      this.password = this.$arrayOfProfile.password;
      this.dateOfBirth = this.$arrayOfProfile.dateOfBirth;
      console.log(this.$arrayOfProfile)
    })
    
    this.$displayPosts=false
    this.$displayFavorites=false
    this.$displayComments=false
    this.$displayHidden=false
    this.$displayProfile=true
    this.$displayUpvoted=false
    this.$displayDownvoted=false
  }
  getUpvoted(){
    fetch('http://localhost:4200/api/favorites/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      data.forEach((element:any) => {
        if(element['isLiked'] == 1) {
          this.$arrayOfUpvoted.push(element);
        }
      });
    })
    this.$displayPosts=false
    this.$displayFavorites=false
    this.$displayComments=false
    this.$displayHidden=false
    this.$displayProfile=false
    this.$displayUpvoted=true
    this.$displayDownvoted=false
  }
  getDownvoted(){
    fetch('http://localhost:4200/api/favorites/' + this.userId)
    .then(res =>res.json())
    .then(data => {
      data.forEach((element:any) => {
        if(element['isLiked'] == 0) {
          this.$arrayOfDownvoted.push(element);
        }
      });
    })
    this.$displayPosts=false
    this.$displayFavorites=false
    this.$displayComments=false
    this.$displayHidden=false
    this.$displayProfile=false
    this.$displayUpvoted=false
    this.$displayDownvoted=true
  }
}