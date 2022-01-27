import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PostsAdministrationComponent } from './posts-administration.component';

describe('PostsAdministrationComponent', () => {
  let component: PostsAdministrationComponent;
  let fixture: ComponentFixture<PostsAdministrationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PostsAdministrationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PostsAdministrationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
