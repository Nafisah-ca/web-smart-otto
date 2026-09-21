import './bootstrap';

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', () => {
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
    }

    // Alert auto-dismiss
    document.querySelectorAll('[data-dismiss]').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('[role=alert]').remove());
    });

    // Slot picker (booking page)
    const dateInput = document.getElementById('booking_date');
    const slotContainer = document.getElementById('slot-container');
    const slotInput = document.getElementById('booking_time');

    if (dateInput && slotContainer) {
        dateInput.addEventListener('change', async () => {
            const date = dateInput.value;
            if (!date) return;
            slotContainer.innerHTML = '<p class="text-sm text-gray-500">Memuat slot...</p>';
            const resp = await fetch(`/booking/slots?date=${date}`);
            const slots = await resp.json();
            slotContainer.innerHTML = '';
            slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = slot.time;
                btn.dataset.time = slot.time;
                if (!slot.available) {
                    btn.disabled = true;
                    btn.className = 'px-4 py-2 rounded-lg border border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed text-sm';
                    btn.title = 'Slot penuh';
                } else {
                    btn.className = 'px-4 py-2 rounded-lg border border-primary-300 text-primary-700 bg-primary-50 hover:bg-primary-100 text-sm font-medium transition-colors slot-btn';
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('ring-2','ring-primary-500','bg-primary-600','text-white'));
                        btn.classList.add('ring-2','ring-primary-500','bg-primary-600','text-white');
                        slotInput.value = slot.time;
                    });
                }
                slotContainer.appendChild(btn);
            });
        });
    }

    // Vehicle type toggle
    const vehicleTypeRadios = document.querySelectorAll('input[name="vehicle_type"]');
    vehicleTypeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            const existing = document.getElementById('existing-vehicle');
            const newVehicle = document.getElementById('new-vehicle');
            if (existing && newVehicle) {
                existing.classList.toggle('hidden', radio.value !== 'existing');
                newVehicle.classList.toggle('hidden', radio.value !== 'new');
            }
        });
    });
});
