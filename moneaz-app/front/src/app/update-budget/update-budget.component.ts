import {Component, Inject, OnInit} from '@angular/core';
import {COMMA, ENTER} from '@angular/cdk/keycodes';
import {MatChipInputEvent} from '@angular/material/chips';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import axios from "axios";
import {environment} from "../../environments/environment";

export interface DialogData {
  name: string;
  tags: string;
  amount: string;
  id: number;
}

export interface Tag {
  name: string;
}

@Component({
  selector: 'app-update-budget',
  templateUrl: './update-budget.component.html',
  styleUrls: ['./update-budget.component.scss']
})
export class UpdateBudgetComponent implements OnInit {

  visible = true;
  selectable = true;
  removable = true;
  addOnBlur = true;
  readonly separatorKeysCodes: number[] = [ENTER, COMMA];
  tags: Tag[] = [];

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
    public dialogRef: MatDialogRef<UpdateBudgetComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) {
  }

  onNoClick(): void {
    this.dialogRef.close();
  }

  onValidate() {
    axios.post(`${environment.url}groups/1/budgets/${this.data.id}?api_token=${sessionStorage.getItem('token')}&name=${this.data.name}`).then((response) => {
      this.dialogRef.close();
    }).catch((error) => {
      console.log(error);
    });
  }

  ngOnInit(): void {
  }

}
