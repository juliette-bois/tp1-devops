import {Component, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import axios from 'axios';
import {Router} from "@angular/router";
import {environment} from "../../environments/environment";
import {AuthService} from "../auth/auth.service";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  constructor(private router: Router, private authService: AuthService) {
  }

  ngOnInit(): void {
  }

  emailFormControl = new FormControl('', [
    Validators.required,
    Validators.email,
  ]);

  passwordFormControl = new FormControl('', [
    Validators.required
  ]);

  hide = true;

  dataForm: FormGroup = new FormGroup({
    email: this.emailFormControl,
    password: this.passwordFormControl
  });

  email: string;
  password: string;

  getEmailErrorMessage() {
    return this.emailFormControl.hasError('required') ? 'Vous devez entrer une adresse email' :
      this.emailFormControl.hasError('email') ? 'Email invalide' : '';
  }

  getRequiredErrorMessage(field) {
    return this.passwordFormControl.get(field).hasError('required') ? 'Vous devez entrer un mot de passe' : '';
  }

  login() {
    axios.post(`${environment.url}auth/login`, {
      email: this.email,
      password: this.password
    }).then((response) => {
        this.authService.authentificate(response.data);
        return this.router.navigate(['/dashboard'])
      }).catch((error) => {
      console.log(error);
    });
  }
}
