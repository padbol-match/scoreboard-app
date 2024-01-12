import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";
import { Observable, of } from 'rxjs';
import { catchError, map, tap } from 'rxjs/operators';
import { User } from '../models';

import { APP_CONFIG } from '../../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  constructor(private http: HttpClient) {}

  login(username: string, password: string): Observable<any>{
    const url = APP_CONFIG.GATEWAY_URL + '/api/login';
    const headers = new HttpHeaders({
      'Content-Type':'application/x-www-form-urlencoded'
    });
    const payload = new HttpParams()
      .set('username', username)
      .set('password', password);
    
    return this.http
      .post(url, payload, { headers: headers })
      .pipe(tap((response) => {
        localStorage.setItem('scoreboard-tenant-token', response['scoreboard_tenant_token']);
        localStorage.setItem('scoreboard-tenant', response['scoreboard_tenant']);
        localStorage.setItem('user-token', response['token']);
        localStorage.setItem('user-id', response['user_id']);
        localStorage.setItem('username', response['user_email']);
      }));
  }

  public signOut(): void {
    localStorage.removeItem('scoreboard-tenant');
    localStorage.removeItem('scoreboard-tenant-token');
    localStorage.removeItem('user-token');
    localStorage.removeItem('user-id');
    localStorage.removeItem('username');
  }

  public getUser(): Observable<User> {
    return of({
      name: 'John',
      lastName: 'Smith'
    });
  }
}
