import { ChangeDetectionStrategy, Component, signal } from '@angular/core';
import {
  IconAd,
  IconHeartFilled,
  IconHome,
  IconHomeOff,
  VNKRIcon,
  VNKRIconComponent
} from '@vnkr-labs/icons-angular';

const colors = ['#e64980', '#4dabf7', '#51cf66', '#ffd43b', '#845ef7'];

@Component({
  selector: 'app-root',
  imports: [VNKRIconComponent],
  templateUrl: './app.component.html',
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class AppComponent {
  active = signal(false);

  iconAd = IconAd;
  iconHome = IconHome;
  iconHomeOff = IconHomeOff;
  iconHeartFilled = IconHeartFilled;

  toggledIcon = signal<VNKRIcon>(IconHome);
  mySize = signal(56);
  stroke = signal(1);
  colorIndex = signal(0);
  currentColor = signal(colors[0]);

  toggleIcon() {
    this.toggledIcon.update(value => value === IconHome ? IconHomeOff : IconHome);
  }

  toggleStroke() {
    this.stroke.update(value => value === 2.5 ? 1 : value + 0.25);
  }

  toggleActive() {
    this.active.update(value => !value);
  }

  toggleColor() {
    this.colorIndex.update(value => (value + 1) % colors.length);
    this.currentColor.set(colors[this.colorIndex()]);
  }
}
