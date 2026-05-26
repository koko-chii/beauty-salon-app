import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import jaLocale from '@fullcalendar/core/locales/ja';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const form = document.getElementById('reservation-form');
    const formTitle = document.getElementById('form-title');
    const methodContainer = document.getElementById('method-container');
    const submitBtn = document.getElementById('btn-submit');
    const resetBtn = document.getElementById('btn-reset-form');
    const deleteBtn = document.getElementById('btn-delete-trigger');
    const deleteForm = document.getElementById('delete-reservation-form');
    
    const inputCustomer = document.getElementById('input-customer');
    const inputStart = document.getElementById('input-start');
    const inputEnd = document.getElementById('input-end');
    const inputMenu = document.getElementById('input-menu');
    const inputMemo = document.getElementById('input-memo');

    function formatDateTime(dateObj) {
        if (!dateObj) return '';
        const tzOffset = dateObj.getTimezoneOffset() * 60000;
        const localISOTime = (new Date(dateObj - tzOffset)).toISOString().slice(0, 16);
        return localISOTime;
    }

    function resetFormToCreate() {
        form.action = "/reservations";
        formTitle.textContent = "📅 新規予約を登録";
        methodContainer.innerHTML = "";
        submitBtn.textContent = "予約を確定する";
        resetBtn.classList.add('hidden');
        deleteBtn.classList.add('hidden');
        form.reset();
    }

    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
            initialView: 'dayGridMonth',
            locale: jaLocale, // 日本語化
            firstDay: 0,      // 日曜日スタート
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: { today: '今日', month: '月', week: '週', day: '日' },
            navLinks: true,
            editable: false,
            selectable: true,
            events: "/reservations/events", // 予約データ用URL
            eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },

            eventClick: function(info) {
                const event = info.event;
                
                formTitle.textContent = "📝 予約内容の編集";
                form.action = `/reservations/${event.id}`;
                methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                submitBtn.textContent = "変更を保存する";
                
                resetBtn.classList.remove('hidden');
                deleteBtn.classList.remove('hidden');
                
                deleteForm.action = `/reservations/${event.id}`;
                
                inputCustomer.value = event.extendedProps.customerId || '';
                inputStart.value = formatDateTime(event.start);
                inputEnd.value = formatDateTime(event.end);
                inputMenu.value = event.extendedProps.menuOnly || '';
                inputMemo.value = event.extendedProps.description || '';

                form.scrollIntoView({ behavior: 'smooth' });
            }
        });
        
        calendar.render();

        if (resetBtn) {
            resetBtn.addEventListener('click', resetFormToCreate);
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                if (confirm('この予約スケジュールを本当に削除してもよろしいですか？')) {
                    deleteForm.submit();
                }
            });
        }
    }
});
