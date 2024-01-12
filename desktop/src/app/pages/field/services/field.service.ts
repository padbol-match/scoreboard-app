import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders, HttpParams } from "@angular/common/http";
import { Field } from '../models';

import { APP_CONFIG } from '../../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class FieldService {
  
  constructor(private http: HttpClient) {}

  public getData(): Observable<any> {
    const url = APP_CONFIG.GATEWAY_URL + "/api/tenant/get-fields/" + localStorage.getItem('user-id');
    return this.http.get<Field[]>(url);
  }

  public getButtonCodes(tenant: string): Observable<any> {
    const url = APP_CONFIG.GATEWAY_URL + '/api/device/get-button-codes';
    const headers = new HttpHeaders({
      'Content-Type':'application/x-www-form-urlencoded'
    });
    const payload = new HttpParams()
      .set('tenant', tenant);
    
    return this.http
      .post(url, payload, { headers: headers });
  }
  
}
