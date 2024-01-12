import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule} from '@angular/material/card';
import { MatIconModule } from '@angular/material/icon';
import { MatMenuModule } from '@angular/material/menu';
import { MatTableModule } from '@angular/material/table';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatTabsModule } from '@angular/material/tabs';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatInputModule } from '@angular/material/input';

import { RegisterRemoteControlsPageComponent } from './containers';
import { SharedModule } from '../../shared/shared.module';
import { MaterialTabsComponent } from './components';
import { MaterialTabContentComponent } from './components';
import { RegisterRemoteControlsService } from './services';
import { TranslateModule } from '@ngx-translate/core';
import { RegisterRemoteControlsRoutingModule } from './register-remote-controls-routing.module';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@NgModule({
  declarations: [
    RegisterRemoteControlsPageComponent,
    MaterialTabsComponent,
    MaterialTabContentComponent
  ],
  imports: [
    CommonModule,
    MatCardModule,
    MatIconModule,
    MatMenuModule,
    MatTableModule,
    MatButtonModule,
    MatCheckboxModule,
    MatToolbarModule,
    MatPaginatorModule,
    MatFormFieldModule,
    MatTabsModule,
    MatGridListModule,
    MatInputModule,
    SharedModule,
    TranslateModule,
    RegisterRemoteControlsRoutingModule,
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [
    RegisterRemoteControlsService
  ]
})
export class RegisterRemoteControlsModule { }
