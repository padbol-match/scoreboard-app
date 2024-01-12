import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatTabsModule } from '@angular/material/tabs';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatIconModule } from '@angular/material/icon';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

import { AuthPageComponent } from './containers';
import { AuthRoutingModule } from './auth-routing.module';
import { YearPipe } from './pipes';
import { AuthService } from './services';
import { LoginFormComponent } from './components';
import { AuthGuard } from './guards';
import { TranslateModule } from '@ngx-translate/core';

@NgModule({
  declarations: [
    AuthPageComponent,
    YearPipe,
    LoginFormComponent
  ],
  imports: [
    CommonModule,
    AuthRoutingModule,
    MatTabsModule,
    MatButtonModule,
    MatInputModule,
    MatIconModule,
    ReactiveFormsModule,
    FormsModule,
    TranslateModule
  ],
  providers: [
    AuthService,
    AuthGuard
  ]
})
export class AuthModule { }
