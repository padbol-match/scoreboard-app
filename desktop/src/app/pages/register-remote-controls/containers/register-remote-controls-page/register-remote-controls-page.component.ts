import { Component, NgZone } from '@angular/core';
import { Observable, of } from 'rxjs';

import { RegisterRemoteControlsService } from '../../services';
import { RegisterRemoteControls } from '../../models';

@Component({
  selector: 'app-register-remote-controls-page',
  templateUrl: './register-remote-controls-page.component.html',
  styleUrls: ['./register-remote-controls-page.component.scss']
})
export class RegisterRemoteControlsPageComponent {

  constructor(private service: RegisterRemoteControlsService, private ngZone: NgZone) {
  }

  public ngOnInit() {
    let self = this;

  }
  
}
