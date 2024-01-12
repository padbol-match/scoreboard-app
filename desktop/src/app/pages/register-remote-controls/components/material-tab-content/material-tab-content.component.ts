import { Component, Input, OnInit, NgZone } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { te } from 'date-fns/locale';
import { RegisterRemoteControlsService } from '../../services';

@Component({
  selector: 'app-material-tab-content',
  templateUrl: './material-tab-content.component.html',
  styleUrls: ['./material-tab-content.component.scss']
})
export class MaterialTabContentComponent implements OnInit {
  @Input() field;

  public loading = false;

  public codes: Array<string> = [];

  constructor(private ngZone: NgZone, private service: RegisterRemoteControlsService) {
  }

  ngOnChanges(changes: any) {
  }

  public ngOnInit() {
  }
 
  public startSaving(teamButton: string){
    let self = this;
    this.loading = true;

    (window as any).api.response('REMOTE_CONTROL_SAVING', (args) => {
        self.codes.push(args.button);
    });
  
    setTimeout(() => { 
      self.sendCode(self.field, teamButton);
    }, 5000);
  }

  public sendCode(field: string, teamButton: string){
    let code = this.getCode();
    
    this.service
      .registerTeamButton(localStorage.getItem('scoreboard-tenant'), this.field,teamButton,code)
      .subscribe(
        (data) => {
          this.loading = false;
          this.codes = [];
        },
        (error) => {
          this.loading = false;
          this.codes = [];
        }
      );
  }

  private getCode(){
    let allCodes = {};
    let max = 0;
    let code = "0";
    
    this.codes.forEach((code) => {
      if(!allCodes[code]){
        allCodes[code] = 1;
      }else{
        allCodes[code]++;
      }
    });

    for (const key in allCodes) {
      if(allCodes[key] > max){
        max = allCodes[key];
        code = key;
      }
    };

    return code;
  }
}
