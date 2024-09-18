// public/js/togglePassword.js
var mespasswords = document.querySelectorAll("input[type='password']");

function togglePasswordVisibility() {
  var allAreText = Array.from(mespasswords).every((input) => input.type === 'text');
  mespasswords.forEach((input) => {
    // Change le type du champ de mot de passe entre 'password' et 'text'
    input.type = allAreText ? 'password' : 'text';
  });
}
