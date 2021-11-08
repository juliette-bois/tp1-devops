import {Component, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {DeleteBudgetComponent} from '../delete-budget/delete-budget.component';
import {Expense} from './Expense';
import axios from 'axios';
import {environment} from '../../environments/environment';
import {Budget} from '../budget/Budget';
import {UpdateExpenseComponent} from '../update-expense/update-expense.component';
import {ActivatedRoute, Router} from '@angular/router';
import {DeleteExpenseComponent} from "../delete-expense/delete-expense.component";

@Component({
  selector: 'app-expense',
  templateUrl: './expense.component.html',
  styleUrls: ['./expense.component.scss']
})
export class ExpenseComponent implements OnInit {

  name: string;
  tags: string;
  amount: string;
  favorite = false;

  id: number;
  expense: Expense;
  budget: Budget;

  constructor(public dialog: MatDialog, private route: ActivatedRoute, private router: Router) {
  }

  ngOnInit(): void {
    this.populateExpense();
  }

  updateDialog(): void {
    const dialogRef = this.dialog.open(UpdateExpenseComponent, {
      width: '500px',
      data: {name: this.expense.name, tags: this.tags, amount: this.expense.amount, id: this.expense.id, budget: this.budget}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      console.log(result);
      this.populateExpense();
    });
  }

  deleteDialog(): void {
    const dialogRef = this.dialog.open(DeleteExpenseComponent, {
      width: '500px',
      data: {id: this.expense.id, id_budget: this.budget.id}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      console.log(result);
      if (result === 'valid') {
        this.router.navigate(['dashboard']);
      }
    });
  }

  populateExpense() {
    this.route.params.subscribe(params => {
      this.id = +params.id;
      if (this.id) {
        axios.get(`${environment.url}spents?api_token=${sessionStorage.getItem('token')}`).then((response) => {
          const expenses: Expense[] = response.data;
          this.expense = expenses.find(b => b.id == this.id);
          axios.get(`${environment.url}groups/1/budgets?api_token=${sessionStorage.getItem('token')}`).then((response) => {
            const budgets: Budget[] = response.data;
            this.budget = budgets.find(b => b.id == this.expense.budget_id);
          }).catch((error) => {
            console.log(error);
          });
        }).catch((error) => {
          console.log(error);
        });
      }
    });
  }

}
