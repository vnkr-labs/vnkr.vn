import { bootstrapApplication } from '@angular/platform-browser';
import { IconBrandAxq, IconBrandAxqFilled, provideAxqIconConfig, provideAxqIcons } from '@vnkr-labs/icons-angular';
import { AppComponent } from './app/app.component';

bootstrapApplication(AppComponent, {
  providers: [
    provideAxqIcons({ IconBrandAxq, IconBrandAxqFilled }),
    provideAxqIconConfig({
      size: 48,
      stroke: 2,
      color: '#066fd1'
    })
  ]
}).catch(err => console.error(err));
