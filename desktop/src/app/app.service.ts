import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { map, tap } from 'rxjs/operators';
import { HttpClient } from "@angular/common/http";

import { APP_CONFIG } from '../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AppService {
  
  constructor(private http: HttpClient) {}

  public getData(): Observable<any> {
    if(localStorage.getItem('user-id') != "undefined"){
      return of<any>(); 
    }
    return of<any>(); 
  }
}