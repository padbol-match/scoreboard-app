import { RouterModule, Routes } from '@angular/router';
import { NgModule } from '@angular/core';

import { RegisterRemoteControlsPageComponent } from './containers';

const routes: Routes = [
  {
    path: '',
    component: RegisterRemoteControlsPageComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routes)
  ],
  exports: [RouterModule]
})
export class RegisterRemoteControlsRoutingModule {
}
