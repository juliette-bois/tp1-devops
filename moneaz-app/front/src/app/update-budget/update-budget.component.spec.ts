import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UpdateBudgetComponent } from './update-budget.component';

describe('UpdateBudgetComponent', () => {
  let component: UpdateBudgetComponent;
  let fixture: ComponentFixture<UpdateBudgetComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UpdateBudgetComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UpdateBudgetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
