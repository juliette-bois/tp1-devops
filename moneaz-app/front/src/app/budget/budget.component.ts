import {Component, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {UpdateBudgetComponent} from '../update-budget/update-budget.component';
import {DeleteBudgetComponent} from '../delete-budget/delete-budget.component';
import {ActivatedRoute, Router} from '@angular/router';
import axios from 'axios';
import {environment} from '../../environments/environment';
import {Budget} from './Budget';
import {Expense} from "../expense/Expense";

@Component({
  selector: 'app-budget',
  templateUrl: './budget.component.html',
  styleUrls: ['./budget.component.scss']
})
export class BudgetComponent implements OnInit {

  name: string;
  tags: string;
  amount: number = 0;
  favorite = false;

  id: number;
  budget: Budget;

  constructor(public dialog: MatDialog, private route: ActivatedRoute, private router: Router) {
  }

  ngOnInit(): void {
    this.populateBudget();
  }

  updateDialog(): void {
    const dialogRef = this.dialog.open(UpdateBudgetComponent, {
      width: '500px',
      data: {name: this.budget.name, tags: this.tags, amount: this.amount, id: this.budget.id}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      console.log(result);
      this.populateBudget();
    });
  }

  deleteDialog(): void {
    const dialogRef = this.dialog.open(DeleteBudgetComponent, {
      width: '500px',
      data: {id: this.budget.id}
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      console.log(result);
      if (result === 'valid') {
        this.router.navigate(['dashboard']);
      }
    });
  }

  populateBudget() {
    this.route.params.subscribe(params => {
      this.id = +params.id;
      if (this.id) {
        axios.get(`${environment.url}groups/1/budgets?api_token=${sessionStorage.getItem('token')}`).then((response) => {
          const budgets: Budget[] = response.data;
          this.budget = budgets.find(b => b.id == this.id);
          this.calculateAmount();
        }).catch((error) => {
          console.log(error);
        });
      }
    });
  }

  calculateAmount(){
    axios.get(`${environment.url}groups/1/budgets/${this.budget.id}/spents?api_token=${sessionStorage.getItem('token')}`).then((response) => {
      const expenses: Expense[] = response.data
      expenses.forEach(e => this.amount = this.amount + +e.amount)
    }).catch((error) => {
      console.log(error);
    });
  }

}
