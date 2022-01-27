import { Component, OnInit, Input } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';


@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})

export class RegisterComponent implements OnInit {

    @Input() newUser: any
  
    private apiURL = 'http://localhost:8000'
    firstName: any
    lastName: any
    nickName: any
    emailAddress: any
    // dateOfBirth: any
    myPassword: any
    confirmPassword: any
    
    isSuccessful = false;
    isSignUpFailed = false;
    errorMessage = '';

  
  constructor(private sanitizer: DomSanitizer) { }
  
  public resolved(captchaResponse: string) {
    console.log(`Resolved captcha with response: ${captchaResponse}`); // Write your logic here about once human verified what action you want to perform 
  }
  
  ngOnInit(): void {
    this.createNewUser();
  }

  

  createNewUser() {
    
    fetch('http://localhost:4200/api/users', {
      method: 'POST',
      body: JSON.stringify({
      "firstName": this.firstName,
      "lastName": this.lastName,
      "nickName": this.nickName,
      "emailAddress": this.emailAddress,
      // "dateOfBirth": this.dateOfBirth,
      "dateOfBirth": "1987-11-07",
      "password": this.myPassword,
      "isAdmin": 0,
      "preferences": "test",
      "avatar" : "test"
      })
    })
    .then(res =>res.json())
    .then(data => {
      console.log('after');
      console.log(data);
    })

      // console.log(this.dateOfBirth);
  

    fetch('http://localhost:4200/api/users')
    .then(res=>res.json())
    .then(data=>console.log(data))
    }
    
  }

