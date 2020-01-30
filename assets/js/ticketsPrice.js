const price = document.getElementById('price');
let totalPrice = document.getElementById('total-price');
const ticketsInput = document.getElementById('booking_nbTickets');
ticketsInput.addEventListener('change', function () {
    totalPrice.innerHTML = ticketsInput.value * price.innerHTML;
});
