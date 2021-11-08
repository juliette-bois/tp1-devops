import {Component, Inject, OnInit} from '@angular/core';
import {COMMA, ENTER} from '@angular/cdk/keycodes';
import {MatChipInputEvent} from '@angular/material/chips';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import axios from "axios";
import {environment} from "../../environments/environment";
import {Budget} from "../budget/Budget";

export interface DialogData {
  name: string;
  tags: string;
  amount: string;
  budget: Budget;
}

export interface Tag {
  name: string;
}

@Component({
  selector: 'app-add-expense',
  templateUrl: './add-expense.component.html',
  styleUrls: ['./add-expense.component.scss']
})
export class AddExpenseComponent implements OnInit {

  visible = true;
  selectable = true;
  removable = true;
  addOnBlur = true;

  budgets: Budget[];

  readonly separatorKeysCodes: number[] = [ENTER, COMMA];
  tags: Tag[] = [

  ];

  add(event: MatChipInputEvent): void {
    const input = event.input;
    const value = event.value;

    if ((value || '').trim()) {
      this.tags.push({name: value.trim()});
    }

    if (input) {
      input.value = '';
    }
  }

  remove(tag: Tag): void {
    const index = this.tags.indexOf(tag);

    if (index >= 0) {
      this.tags.splice(index, 1);
    }
  }

  constructor(
    public dialogRef: MatDialogRef<AddExpenseComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) {}

  onNoClick(): void {
    this.dialogRef.close();
  }

  ngOnInit(): void {
    axios.get(`${environment.url}groups/1/budgets?api_token=${sessionStorage.getItem('token')}`).then((response) => {
      this.budgets = response.data;
    }).catch((error) => {
      console.log(error);
    });
  }

  onValidate(){
    axios.post(`${environment.url}groups/1/budgets/${this.data.budget.id}/users/${sessionStorage.getItem('user_id')}/edit?api_token=${sessionStorage.getItem('token')}`).then((response) => {
      this.registerExpense()
      this.dialogRef.close();
    }).catch((error) => {
      this.registerExpense()
      console.log(error);
    });
  }

  registerExpense(){
    axios.post(`${environment.url}groups/1/budgets/${this.data.budget.id}/spents?api_token=${sessionStorage.getItem('token')}&name=${this.data.name}&amount=${this.data.amount}&comment=`).then((response) => {
      this.dialogRef.close();
    }).catch((error) => {
      console.log(error);
    });
  }

}
