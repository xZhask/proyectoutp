function App() {}

window.onload = function(event) {
	var app = new App();
	window.app = app;
};

const btnMenuToggle = document.querySelector('.icon-menu');
const btnUserToggle = document.querySelector('.cont-datosuser');

btnMenuToggle.addEventListener('click', function() {
	document.getElementById('sidebar').classList.toggle('responsive');
});
btnUserToggle.addEventListener('click', function() {
	document.getElementById('opciones-user').classList.toggle('active');
});
