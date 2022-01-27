import { Component, OnInit } from '@angular/core';
import {Chart} from 'chart.js';


@Component({
  selector: 'app-kpi',
  templateUrl: './kpi.component.html',
  styleUrls: ['./kpi.component.css']
})
export class KPIComponent implements OnInit {

  chart1:any;
  arrayCat1:any =[];
  arrayPosts:any = [];

  chart2:any;
  arrayCat:any =[];
  arrayLikes:any = [];

  constructor() { }

  ngOnInit(): void {
    // let ctx = document.getElementById("myChart");
    fetch('http://localhost:4200/api/posts/nbByCategory')
    .then(res => res.json())
    .then (data => {
      console.log(data);
      data.forEach((element:any) => {
        let cat:string = element['namecategory'];
        this.arrayCat1.push(cat);
        let posts:string = element['1'];
        this.arrayPosts.push(posts);
      })
      this.chart1 = new Chart ('canvas1', {
        type:'doughnut',
        data: {
          labels: this.arrayCat1,
          datasets: [
            {
              label: 'Dataset 1',
              data: this.arrayPosts,
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Number of posts by category'
            }
          }
        },
      })
    })

    fetch('http://localhost:4200/api/posts/likesByCategory')
    .then(res => res.json())
    .then (data => {
      console.log(data);
      data.forEach((element:any) => {
        let cat:string = element['namecategory'];
        this.arrayCat.push(cat);
        let likes:string = element['1'];
        this.arrayLikes.push(likes);
      })
      this.chart2 = new Chart ('canvas2', {
        type:'doughnut',
        data: {
          labels: this.arrayCat,
          datasets: [
            {
              label: 'Dataset 1',
              data: this.arrayLikes,
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Number of likes by category'
            }
          }
        },
      })
    })
  }
}
