import {BrowserModule} from '@angular/platform-browser';
import {LOCALE_ID, NgModule} from '@angular/core';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {HeaderComponent} from './header/header.component';
import {FooterComponent} from './footer/footer.component';
import {HomeComponent} from './home/home.component';
import {RouterModule, Routes} from "@angular/router";
import {RegisterComponent} from './register/register.component';
import {DashboardComponent} from './dashboard/dashboard.component';
import {MatListModule} from "@angular/material/list";
import {MatTabsModule} from "@angular/material/tabs";
import {BudgetComponent} from './budget/budget.component';
import {LoginComponent} from './login/login.component';
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatInputModule} from "@angular/material/input";
import {MatSelectModule} from "@angular/material/select";
import {MatIconModule} from "@angular/material/icon";
import {MatButtonModule} from "@angular/material/button";
import {MatStepperModule} from "@angular/material/stepper";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {MatChipsModule} from "@angular/material/chips";
import {AddBudgetComponent} from './add-budget/add-budget.component';
import {MatDialogModule} from "@angular/material/dialog";
import {DeleteBudgetComponent} from './delete-budget/delete-budget.component';
import {UpdateBudgetComponent} from './update-budget/update-budget.component';
import {MatTooltipModule} from "@angular/material/tooltip";
import {AddExpenseComponent} from './add-expense/add-expense.component';
import {ExpenseComponent} from './expense/expense.component';
import {DeleteExpenseComponent} from './delete-expense/delete-expense.component';
import {UpdateExpenseComponent} from './update-expense/update-expense.component';
import {HttpClient, HttpClientModule, HttpEventType, HttpHandler} from "@angular/common/http";
import {
  AuthGuardService,
  AuthGuardService as AuthGuard
} from './auth/auth-guard.service';
import { registerLocaleData } from '@angular/common';
import localeFr from '@angular/common/locales/fr';
registerLocaleData(localeFr);
import {AuthService} from "./auth/auth.service";

const appRoutes: Routes = [
  {path: '', component: HomeComponent},
  {path: 'login', component: LoginComponent},
  {path: 'register', component: RegisterComponent},
  {path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuard]},
  {path: 'budget/:id', component: BudgetComponent, canActivate: [AuthGuard]},
  {path: 'expense/:id', component: ExpenseComponent, canActivate: [AuthGuard]},
];

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    FooterComponent,
    HomeComponent,
    RegisterComponent,
    DashboardComponent,
    BudgetComponent,
    LoginComponent,
    AddBudgetComponent,
    DeleteBudgetComponent,
    UpdateBudgetComponent,
    AddExpenseComponent,
    ExpenseComponent,
    DeleteExpenseComponent,
    UpdateExpenseComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    RouterModule.forRoot(
      appRoutes,
      {enableTracing: true} // <-- debugging purposes only
    ),
    MatListModule,
    MatTabsModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatIconModule,
    MatButtonModule,
    MatStepperModule,
    ReactiveFormsModule,
    MatChipsModule,
    FormsModule,
    MatDialogModule,
    MatTooltipModule,
    HttpClientModule,
  ],
  exports: [
    MatIconModule,
    MatButtonModule
  ],
  providers: [AuthService, AuthGuardService, {provide: LOCALE_ID, useValue: 'fr-FR'}],
  bootstrap: [AppComponent]
})
export class AppModule {
}
