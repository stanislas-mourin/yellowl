import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {

  // userLeftAuth: string = "Log in";
  // userLeftIsAuth: string = "My account";
  // userRightAuth: string = "Register";
  // userRightIsAuth: string = "Log out";
  $isAuth: boolean = false;
  $isAdmin:boolean = false;
  $idAuth:number = 0;
  



  constructor(private cookieService: CookieService, private activeRoute: ActivatedRoute, private router: Router) {
    // setTimeout(
    //   () => {
    //     this.isAuth = true;
    //   }, 4000
    // );
  }

  ngOnInit(): void {
    this.getAuthData();
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

  async logOut(){
    document.cookie = "idAuth="+ this.$idAuth+"; expires=Thu, 18 Dec 2012 12:00:00 UTC;SameSite=Lax"; 
    if (this.$isAdmin == true){
      document.cookie = "isAdmin=1; expires=Thu, 18 Dec 2012 12:00:00 UTC;SameSite=Lax";
    } else {
      document.cookie = "isAdmin=0; expires=Thu, 18 Dec 2012 12:00:00 UTC;SameSite=Lax";
    }
    await this.router.navigate(['/'])
  }

  getLeftAuth() {
    // if (this.isAuth === "false") {
    //   return this.userLeftAuth;
    // } else if (this.isAuth === "true") {
    //   return this.userLeftIsAuth;
    // }
  }

  getRightAuth() {
    // return this.userRightAuth;
  }


}


