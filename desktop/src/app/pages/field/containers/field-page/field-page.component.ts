import { Component, NgZone } from '@angular/core';
import { Observable, of } from 'rxjs';

import { FieldService } from '../../services';
import { Field } from '../../models';

@Component({
  selector: 'app-field-page',
  templateUrl: './field-page.component.html',
  styleUrls: ['./field-page.component.scss']
})
export class FieldPageComponent {
  public data$: Observable<Field[]>

  private buttonCodes = {};

  constructor(private service: FieldService, private ngZone: NgZone) {
    this.data$ = this.service.getData();

    this.service.getButtonCodes(localStorage.getItem('scoreboard-tenant')).subscribe((data) => {
      this.buttonCodes = data;
    });
  }

  public ngOnInit() {
    this.onPushButtonPressed();
  }
 
  private onPushButtonPressed(){
    let self = this;
    this.data$.subscribe((fields) => {

      (window as any).api.response('REMOTE_CONTROL_PRESSED', (args) => {
        
        fields.map((field) => {
          for(const fieldId in self.buttonCodes){
            if(fieldId == field.id){
              self.buttonCodes[fieldId].forEach((buttonCode, index) => {
                if(args.button == buttonCode){
                  if(index == 0){
                    field.press_team_1 = field.press_team_1 + 1;
                  }else if (index == 1){
                    field.press_team_2 = field.press_team_2 + 1;
                  }else{
                    field.press_back = field.press_back + 1;
                  }
                }
              });
            }
          }
          return field;
        });

        console.log("REMOTE_CONTROL_PRESSED", args, fields);

        this.ngZone.run(() => {
          self.data$ = of(fields);
        });
      });
    });
  }
  
}
