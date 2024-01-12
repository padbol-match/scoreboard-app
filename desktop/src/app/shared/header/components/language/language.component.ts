import { Component } from '@angular/core';

import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-language',
  templateUrl: './language.component.html',
  styleUrls: ['./language.component.scss']
})
export class LanguageComponent {
  constructor(private translate: TranslateService ) {
  }

  public changeLanguage(lang: string){
    this.translate.use(lang);
  }
}
