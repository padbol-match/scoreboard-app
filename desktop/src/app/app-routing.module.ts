import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { NotFoundComponent } from './pages/not-found/not-found.component';
import { AuthGuard } from './pages/auth/guards';

const routes: Routes = [
  {
    path: 'login',
    loadChildren: () => import('./pages/auth/auth.module').then(m => m.AuthModule)
  },
  {
    path: 'field',
    pathMatch: 'full',
    canActivate: [AuthGuard],
    loadChildren: () => import('./pages/field/field.module').then(m => m.FieldModule)
  },
  {
    path: 'register-remote-controls',
    pathMatch: 'full',
    canActivate: [AuthGuard],
    loadChildren: () => import('./pages/register-remote-controls/register-remote-controls.module').then(m => m.RegisterRemoteControlsModule)
  },
  {
    path: '**',
    component: NotFoundComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { relativeLinkResolution: 'legacy' }),
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
