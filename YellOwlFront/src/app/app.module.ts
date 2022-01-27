import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import { MatNativeDateModule } from '@angular/material/core';
import { HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { CategoriesComponent } from './categories/categories.component';
import { NavbarComponent } from './navbar/navbar.component';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { HomeComponent } from './home/home.component';
import { ProfileComponent } from './profile/profile.component';
import { BoardAdminComponent } from './board-admin/board-admin.component';
import { BoardUserComponent } from './board-user/board-user.component';
import { RecaptchaModule } from 'ng-recaptcha';

import { authInterceptorProviders } from './_helpers/auth.interceptor';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { SidenavComponent } from './sidenav/sidenav.component';
import { PostComponent } from './post/post.component';
import { PostModalComponent } from './post-modal/post-modal.component';

import { ToastrModule } from 'ngx-toastr';
import { CookieModule } from 'ngx-cookie';
import { CommentsComponent } from './comments/comments.component';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatDatepickerModule} from '@angular/material/datepicker';
import { KPIComponent } from './kpi/kpi.component';
import { UsersAdministrationComponent } from './users-administration/users-administration.component';
import { PostsAdministrationComponent } from './posts-administration/posts-administration.component';
import { CommentsAdministrationComponent } from './comments-administration/comments-administration.component';
import { CategoriessAdministrationComponent } from './categoriess-administration/categoriess-administration.component';
import { PersonalInformationComponent } from './personal-information/personal-information.component';
import { NgChartsModule } from 'ng2-charts';
import { MaterialModule } from './material.module';
import { AdpostComponent } from './adpost/adpost.component';
import { PostcardComponent } from './postcard/postcard.component';


@NgModule({
  declarations: [
    AppComponent,
    CategoriesComponent,
    NavbarComponent,
    LoginComponent,
    RegisterComponent,
    HomeComponent,
    ProfileComponent,
    BoardAdminComponent,
    BoardUserComponent,
    SidenavComponent,
    PostComponent,
    PostModalComponent,
    CommentsComponent,
    KPIComponent,
    UsersAdministrationComponent,
    PostsAdministrationComponent,
    CommentsAdministrationComponent,
    CategoriessAdministrationComponent,
    PersonalInformationComponent,
    AdpostComponent,
    PostcardComponent


  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MatNativeDateModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    MaterialModule,
    BrowserAnimationsModule,
    ToastrModule.forRoot(),
    CookieModule.forRoot(),
    MatFormFieldModule,
    MatDatepickerModule,
    
    NgChartsModule,
    
    [RecaptchaModule, BrowserAnimationsModule],
  ],
  providers: [authInterceptorProviders],
  bootstrap: [AppComponent, SidenavComponent]
})
export class AppModule { }
