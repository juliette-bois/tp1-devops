export interface Expense {
  id: number;
  budget_id: number;
  name: string;
  amount: number;
  comment: string;
  created_at: Date;
  updated_at: Date;
}
