import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import axios from 'axios';
import {environment} from "../../environments/environment";
import {Router} from "@angular/router";
import {AuthService} from "../auth/auth.service";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  isLinear = true;
  hide = true;
  firstFormGroup: FormGroup;
  secondFormGroup: FormGroup;
  thirdFormGroup: FormGroup;
  lastname: string;
  firstname: string;
  username: string;
  email: string;
  password: string;

  // tslint:disable-next-line:variable-name
  constructor(private _formBuilder: FormBuilder, private router: Router, private authService: AuthService) {}

  register() {
    document.getElementById('register_form').addEventListener('submit', () => {
      axios.post(`${environment.url}auth/register`, {
        name: this.username,
        email: this.email,
        password: this.password
      })
      .then((response) => {
        this.authService.authentificate(response.data);
        return this.router.navigate(['/dashboard'])
      }).catch((error) => {
        console.log(error);
      });
    });
  }

  ngOnInit() {
    this.firstFormGroup = this._formBuilder.group({
      firstCtrl: ['', Validators.required]
    });
    this.secondFormGroup = this._formBuilder.group({
      secondCtrl: ['', Validators.required]
    });
    this.thirdFormGroup = this._formBuilder.group({
      thirdCtrl: ['', Validators.required]
    });
  }
}
