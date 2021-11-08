import {Component, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {AddBudgetComponent} from '../add-budget/add-budget.component';
import axios from 'axios';
import {environment} from '../../environments/environment';
import {Budget} from '../budget/Budget';
import {AddExpenseComponent} from '../add-expense/add-expense.component';
import {Expense} from '../expense/Expense';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

  name: string;
  tags: string;
  amount: number = 0;

  budgets: Budget[];
  expenses: Expense[];

  constructor(public dialog: MatDialog) {
  }

  openDialogBudget(): void {
    const dialogRef = this.dialog.open(AddBudgetComponent, {
      width: '500px',
      data: {name: this.name, tags: this.tags, amount: 0}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      console.log(result);
      this.populateBudget();
    });
  }

  openDialogExpense(): void {
    const dialogRef = this.dialog.open(AddExpenseComponent, {
      width: '500px',
      data: {name: this.name, tags: this.tags, amount: 0}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      console.log(result);
      this.populateExpense();
    });
  }

  ngOnInit(): void {
    this.populateBudget();
    this.populateExpense()
  }

  populateBudget() {
    axios.get(`${environment.url}groups/1/budgets?api_token=${sessionStorage.getItem('token')}`).then((response) => {
      this.budgets = response.data;
    }).catch((error) => {
      console.log(error);
    });
  }

  populateExpense() {
    axios.get(`${environment.url}spents?api_token=${sessionStorage.getItem('token')}`).then((response) => {
      this.expenses = response.data;
      this.amount = 0;
      this.expenses.forEach(d => this.amount = this.amount + +d.amount)
    }).catch((error) => {
      console.log(error);
    });
  }

}
