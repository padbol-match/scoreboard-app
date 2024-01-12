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

import { FieldPageComponent } from './containers';
import { FieldRoutingModule } from './field-routing.module';
import { SharedModule } from '../../shared/shared.module';
import { MaterialTableComponent } from './components';
import { FieldService } from './services';

import { TranslateModule } from '@ngx-translate/core';

@NgModule({
  declarations: [
    FieldPageComponent,
    MaterialTableComponent
  ],
  imports: [
    CommonModule,
    FieldRoutingModule,
    MatCardModule,
    MatIconModule,
    MatMenuModule,
    MatTableModule,
    MatButtonModule,
    MatCheckboxModule,
    MatToolbarModule,
    MatPaginatorModule,
    MatFormFieldModule,
    TranslateModule,
    SharedModule
  ],
  providers: [
    FieldService
  ]
})
export class FieldModule { }
