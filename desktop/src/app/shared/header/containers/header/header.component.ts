import { Component, EventEmitter, Input, Output, NgZone } from '@angular/core';
import { Observable } from 'rxjs';
import { Router } from '@angular/router';

import { User } from '../../../../pages/auth/models';
import { AuthService } from '../../../../pages/auth/services';
import { routes } from '../../../../consts';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
  @Input() isMenuOpened: boolean;
  @Output() isShowSidebar = new EventEmitter<boolean>();
  public user$: Observable<User>
  public routers: typeof routes = routes;
  public padbolMatchHubStatus: string = "header__title-button-icon-red";

  constructor(
    private userService: AuthService,
    private router: Router,
    private ngZone: NgZone
  ) {
    this.user$ = this.userService.getUser();
  }

  public ngOnInit() {
    this.readPadbolMatchHubStatus();
  }

  public openMenu(): void {
    this.isMenuOpened = !this.isMenuOpened;

    this.isShowSidebar.emit(this.isMenuOpened);
  }

  public signOut(): void {
    this.userService.signOut();

    this.router.navigate([this.routers.LOGIN]);
  }

  private readPadbolMatchHubStatus(): void{
    if((window as any).api)
      (window as any).api.response('SERIAL_PORT_AVAILABLE', (args) => {
        
        this.padbolMatchHubStatus = (args.status) ? 
          "header__title-button-icon-green" :
          "header__title-button-icon-red";

        this.ngZone.run(() => {
        });
      });
  }
}
