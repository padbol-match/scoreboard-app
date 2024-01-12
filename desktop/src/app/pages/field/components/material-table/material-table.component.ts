import { Component, Input, OnInit } from '@angular/core';
import { Observable } from 'rxjs';

import { Field } from '../../models';

@Component({
  selector: 'app-material-table',
  templateUrl: './material-table.component.html',
  styleUrls: ['./material-table.component.scss']
})
export class MaterialTableComponent implements OnInit {
  @Input() data: Observable<Field[]>;
  
  public displayedColumns: string[] = ['id', 'press_team_1', 'press_team_2', 'press_back'];
  public dataSource: Field[];

  public ngOnInit() {
  }

  ngOnChanges(changes: any) {
    this.dataSource = changes.data.currentValue;
  }
}
