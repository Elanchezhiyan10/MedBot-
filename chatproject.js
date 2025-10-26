const signUpBut = document.getElementById('sigbut');

signUpBut.addEventListener('click', function (e) {
    const confirmation = confirm('Confirm to redirect to the SIGNUP page');
    if (!confirmation) {
        e.preventDefault();

    }
})