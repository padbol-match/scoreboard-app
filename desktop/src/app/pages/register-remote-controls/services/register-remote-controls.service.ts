import { Injectable } from '@angular/core';
import { Observable} from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";

import { APP_CONFIG } from '../../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class RegisterRemoteControlsService {
  
  constructor(private http: HttpClient) {}

  registerTeamButton(tenant: string, field: string, teamButton: string, code: string): Observable<any>{
    const url = APP_CONFIG.GATEWAY_URL + '/api/device/register-remote-control-team-button';
    const headers = new HttpHeaders({
      'Content-Type':'application/x-www-form-urlencoded'
    });
    const payload = new HttpParams()
      .set('tenant', tenant)
      .set('field', field)
      .set('teamButton', teamButton)
      .set('code', code);
    
    return this.http
      .post(url, payload, { headers: headers });
  }
}