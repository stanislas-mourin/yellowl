import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CategoriessAdministrationComponent } from './categoriess-administration.component';

describe('CategoriessAdministrationComponent', () => {
  let component: CategoriessAdministrationComponent;
  let fixture: ComponentFixture<CategoriessAdministrationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CategoriessAdministrationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CategoriessAdministrationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
