import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
  })
  export class AuthService {
    private apiUrl = 'http://localhost:3000';
    constructor(private http: HttpClient) { }
    login(credentials: any): Observable<any> {
      return this.http.post(`${this.apiUrl}/login`, credentials);
    }
    register(user: any): Observable<any> {
      return this.http.post(`${this.apiUrl}/register`, user);
    }
    // Implement methods for handling JWT tokens
  }