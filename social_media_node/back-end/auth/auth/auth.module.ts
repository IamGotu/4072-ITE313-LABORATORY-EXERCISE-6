import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';



@NgModule({
  declarations: [
  LoginComponent,
  RegisterComponent
  ],
  imports: [
  CommonModule
  ],
  exports : [
  LoginComponent,
  RegisterComponent
  ]
  })
  export class AuthModule { }