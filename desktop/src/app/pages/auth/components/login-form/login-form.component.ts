import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { throwError } from 'rxjs';

import { AuthService } from '../../services';
import { routes } from '../../../../consts';

@Component({
  selector: 'app-login-form',
  templateUrl: './login-form.component.html',
  styleUrls: ['./login-form.component.scss']
})
export class LoginFormComponent implements OnInit {
  @Output() sendLoginForm = new EventEmitter<FormGroup>();
  
  public form: FormGroup;
  public username = '';
  public password = '';
  public loading = false;
  public routers: typeof routes = routes;
  public login_error: boolean = false;

  constructor(
    private service: AuthService,
    private router: Router
  ) { }

  public ngOnInit(): void {
    this.form = new FormGroup({
      username: new FormControl(this.username, [Validators.required]),
      password: new FormControl(this.password, [Validators.required])
    });
  }

  public login(): void {
    let self = this;

    if (this.form.valid) {
      this.login_error = false;
      this.loading = true;
      this.service.login(this.form.controls['username'].value, this.form.controls['password'].value)
        .subscribe((data) => {
          if(data["token"] != undefined){
            self.router.navigate([self.routers.FIELD]);
          }else{
            self.loading = false;
            self.login_error = true;
          }
        },
        (error) => {
          self.loading = false;
          self.login_error = true;
        })
    }
  }
    
}
