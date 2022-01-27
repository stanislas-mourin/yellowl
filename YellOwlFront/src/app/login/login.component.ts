import { Component, OnInit } from '@angular/core';
import { AuthService } from '../_services/auth.service';
import { TokenStorageService } from '../_services/token-storage.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  form: any = {
    email: null,
    password: null,
    captcha: null
  };
  isLoggedIn = false;
  isLoginFailed = false;
  errorMessage = 'The email address and password association is incorrect. Please try again.';
  roles: string[] = [];

  constructor(private authService: AuthService, private tokenStorage: TokenStorageService, private router: Router) { }

  ngOnInit(): void {
    if (this.tokenStorage.getToken()) {
      this.isLoggedIn = true;
      this.roles = this.tokenStorage.getUser().roles;
    }
  }

  onSubmit(): void {
    const { email, password, captcha } = this.form;

    // document.cookie = "idAuth=4; expires=Thu, 18 Dec 2022 12:00:00 UTC;SameSite=Lax"; 
    // document.cookie = "isAdmin=1; expires=Thu, 18 Dec 2022 12:00:00 UTC;SameSite=Lax"; 
    if(captcha !== null) {
      fetch('http://localhost:4200/api/auth/signin/' + email.replaceAll('.','%') + '/' + password)
      .then(response => response.json())
      .then(data => {
        if (data == "User non trouvé"){
          this.isLoginFailed = true;
        } else {
          document.cookie = "idAuth=" + data.id + "; expires=Thu, 18 Dec 2022 12:00:00 UTC;SameSite=Lax"; 
          document.cookie = "isAdmin=" + data.isAdmin +"; expires=Thu, 18 Dec 2022 12:00:00 UTC;SameSite=Lax"; 
          this.router.navigate(['/']);
          window.location.reload();
        }       
      })
    } else {
      this.isLoginFailed = true;  
    }

    
    // this.authService.login(email, password, captcha).subscribe(
    //   data => {
    //     this.tokenStorage.saveToken(data.accessToken);
    //     this.tokenStorage.saveUser(data);

    //     this.isLoginFailed = false;
    //     this.isLoggedIn = true;
    //     this.roles = this.tokenStorage.getUser().roles;
    //     this.reloadPage();
    //   },
    //   err => {
    //     this.errorMessage = err.error.message;
    //     this.isLoginFailed = true;
    //   }
    // );
  }

  reloadPage(): void {
    window.location.reload();
  }

  public resolved(captchaResponse: string) {
    // console.log(`Resolved captcha with response: ${captchaResponse}`); // Write your logic here about once human verified what action you want to perform 
    this.form.captcha = captchaResponse;
  }
}