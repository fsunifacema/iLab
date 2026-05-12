import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import ptBrLocale from '@fullcalendar/core/locales/pt-br';
import SignaturePad from 'signature_pad';

window.FullCalendar = { Calendar, dayGridPlugin, timeGridPlugin, listPlugin, ptBrLocale };
window.SignaturePad = SignaturePad;

