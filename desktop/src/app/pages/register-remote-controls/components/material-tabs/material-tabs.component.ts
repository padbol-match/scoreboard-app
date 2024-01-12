import { Component, Input, OnInit } from '@angular/core';
import { Observable } from 'rxjs';

import { RegisterRemoteControls } from '../../models';

@Component({
  selector: 'app-material-tabs',
  templateUrl: './material-tabs.component.html',
  styleUrls: ['./material-tabs.component.scss']
})
export class MaterialTabsComponent implements OnInit {
  public ngOnInit() {
  }

  ngOnChanges(changes: any) {
  }

}
