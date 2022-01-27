import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CommentsAdministrationComponent } from './comments-administration.component';

describe('CommentsAdministrationComponent', () => {
  let component: CommentsAdministrationComponent;
  let fixture: ComponentFixture<CommentsAdministrationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CommentsAdministrationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CommentsAdministrationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
