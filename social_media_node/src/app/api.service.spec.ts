import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Component, OnInit } from '@angular/core';
import { ApiService } from './api.service';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = 'http://localhost:3000/tasks';
  constructor(private http: HttpClient) { }
  getTasks(): Observable<any> {
    return this.http.get(this.apiUrl);
  }
  createTask(task: any): Observable<any> {
    return this.http.post(this.apiUrl, task);
  }
  // Implement other CRUD operations (getTaskById, updateTask, deleteTask)

  @Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
  })
  export class AppComponent implements OnInit {
    tasks: any[] = [];
    constructor(private apiService: ApiService) {}
    ngOnInit() {
      this.fetchTasks();
    }
    fetchTasks() {
      this.apiService.getTasks().subscribe(
        (data: any) => {
          this.tasks = data;
        },
        (error: any) => {
          console.error('An error occurred:', error);
        }
      );
    }
    // Implement other CRUD operations (createTask, updateTask, deleteTask)
  }