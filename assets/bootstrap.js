import { startStimulusApp } from '@symfony/stimulus-bundle';
import Clipboard from 'stimulus-clipboard';

const app = startStimulusApp();

app.register('clipboard', Clipboard);
console.log('stimulus app haas been  registered')