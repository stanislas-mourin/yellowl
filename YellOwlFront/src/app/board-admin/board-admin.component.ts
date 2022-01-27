import { Component, OnInit } from '@angular/core';
import { UserService } from '../_services/user.service';

@Component({
  selector: 'app-board-admin',
  templateUrl: './board-admin.component.html',
  styleUrls: ['./board-admin.component.css']
})
export class BoardAdminComponent implements OnInit {
  content?: string;
  $displayPersonalInfo:boolean = false;
  $displayUsersAdmin:boolean = false;
  $displayPostsAdmin:boolean = false;
  $displayCategoriesAdmin:boolean = false;
  $displayCommentsAdmin:boolean = false;
  $displayKPI:boolean = false;

  constructor(private userService: UserService) { }

  ngOnInit(): void {
    this.userService.getAdminBoard().subscribe(
      data => {
        this.content = data;
      },
      err => {
        this.content = JSON.parse(err.error).message;
      }
    );
  }


  personalInfo(){
    this.$displayPersonalInfo = true;
    this.$displayUsersAdmin = false;
    this.$displayPostsAdmin = false;
    this.$displayCategoriesAdmin = false;
    this.$displayCommentsAdmin = false;
    this.$displayKPI = false;
  }

  usersAdmin(){
    this.$displayPersonalInfo = false;
    this.$displayUsersAdmin = true;
    this.$displayPostsAdmin = false;
    this.$displayCategoriesAdmin = false;
    this.$displayCommentsAdmin = false;
    this.$displayKPI = false;
  }

  postsAdmin(){
    this.$displayPersonalInfo = false;
    this.$displayUsersAdmin = false;
    this.$displayPostsAdmin = true;
    this.$displayCategoriesAdmin = false;
    this.$displayCommentsAdmin = false;
    this.$displayKPI = false;
  }

  categoriesAdmin(){
    this.$displayPersonalInfo = false;
    this.$displayUsersAdmin = false;
    this.$displayPostsAdmin = false;
    this.$displayCategoriesAdmin = true;
    this.$displayCommentsAdmin = false;
    this.$displayKPI = false;
  }

  commentsAdmin(){
    this.$displayPersonalInfo = false;
    this.$displayUsersAdmin = false;
    this.$displayPostsAdmin = false;
    this.$displayCategoriesAdmin = false;
    this.$displayCommentsAdmin = true;
    this.$displayKPI = false;
  }

  KPI(){
    this.$displayPersonalInfo = false;
    this.$displayUsersAdmin = false;
    this.$displayPostsAdmin = false;
    this.$displayCategoriesAdmin = false;
    this.$displayCommentsAdmin = false;
    this.$displayKPI = true;
  }


}