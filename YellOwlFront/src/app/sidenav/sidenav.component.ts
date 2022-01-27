import { Component } from '@angular/core';

@Component({
  selector: 'app-sidenav',
  templateUrl: './sidenav.component.html',
  styleUrls: ['./sidenav.component.css']
})
export class SidenavComponent {

  shouldRun = /(^|.)(stackblitz|webcontainer).(io|com)$/.test(
    window.location.host

  );
}