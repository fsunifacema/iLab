import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import SignaturePad from 'signature_pad';

window.FullCalendar = { Calendar, dayGridPlugin, timeGridPlugin, listPlugin };
window.SignaturePad = SignaturePad;

