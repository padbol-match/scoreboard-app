import { RouterModule, Routes } from '@angular/router';
import { NgModule } from '@angular/core';

import { FieldPageComponent } from './containers';

const routes: Routes = [
  {
    path: '',
    component: FieldPageComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routes)
  ],
  exports: [RouterModule]
})

export class FieldRoutingModule {
}
