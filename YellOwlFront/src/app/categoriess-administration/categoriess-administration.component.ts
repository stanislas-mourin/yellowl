import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-categoriess-administration',
  templateUrl: './categoriess-administration.component.html',
  styleUrls: ['./categoriess-administration.component.css']
})
export class CategoriessAdministrationComponent implements OnInit {

  $name:string = '';
  $avatar='';
  $arrayAllCategories:any;
  $id:number = 1;

  constructor() { }

  ngOnInit(): void {
    this.getAllCategories();
  }

createNewCategory(){
  console.log(this.$name);
  console.log(this.$avatar);
  fetch('http://localhost:4200/api/categories', {
      method: 'POST',
      body: JSON.stringify({
        "namecategory" : this.$name,
        "avatar" : this.$avatar,
      })
    })
      .then(res =>res.json())
      .then(data => {
        this.getAllCategories();
        this.$name = "";
        this.$avatar = "";
      })
}

getAllCategories(){
  fetch('http://localhost:4200/api/categories')
    .then(res =>res.json())
    .then(data => {
      this.$arrayAllCategories = data;
      console.log(this.$arrayAllCategories);
    })
}

updateCategory(catId:any, $event: any){
  console.log(catId);
  console.log($event.target);
  // console.log($event.target.catName.value);
  // console.log($event.target.catAvatar.value);
  // console.log(name);
  // console.log(avatar);
}

deleteCategory(catId: any){
  console.log(catId);
  fetch('http://localhost:4200/api/categories/'+ catId, {
      method: 'DELETE'
    })
      .then(res =>res.json())
      .then(data => {
        this.getAllCategories();
      })
}

}
