import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import axios from "axios";
import {environment} from "../../environments/environment";

export interface DialogData {
  id: number;
}

@Component({
  selector: 'app-delete-budget',
  templateUrl: './delete-budget.component.html',
  styleUrls: ['./delete-budget.component.scss']
})
export class DeleteBudgetComponent implements OnInit {

  constructor(
    public dialogRef: MatDialogRef<DeleteBudgetComponent>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) {
  }

  onNoClick(): void {
    this.dialogRef.close('quit');
  }

  onValidClick(): void {
    axios.get(`${environment.url}groups/1/budgets/${this.data.id}?api_token=${sessionStorage.getItem('token')}`).then((response) => {
      this.dialogRef.close('valid');
    }).catch((error) => {
      console.log(error);
    });
  }

  ngOnInit(): void {
  }

}
