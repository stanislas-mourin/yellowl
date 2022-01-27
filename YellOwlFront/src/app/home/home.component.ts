import { Component, OnInit } from '@angular/core';
import { UserService } from '../_services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  content?: string;
  $arrayOfPosts:any;

  constructor(private userService: UserService, private router: Router) { }

  ngOnInit(): void {
    // this.userService.getPublicContent().subscribe(
    //   data => {
    //     this.content = data;
    //   },
    //   err => {
    //     this.content = JSON.parse(err.error).message;
    //   }
    // );
    fetch('http://localhost:4200/api/posts')
    .then(res => res.json())
    .then(data => {
      this.$arrayOfPosts = data;
      console.log(this.$arrayOfPosts)
    });
  }

  openCreatePost(){
    this.router.navigate(['/postcard-component']);
  }
}