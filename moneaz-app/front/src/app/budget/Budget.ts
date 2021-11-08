export interface Budget {
  id: number;
  name: string;
  parent: number;
  group_id: number;
  created_at: Date;
  updated_at: Date;
  perms: any[];
}
