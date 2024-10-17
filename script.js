document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.favorite');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            if (star.classList.contains('active')) {
                star.classList.remove('active');
            } else {
                star.classList.add('active');
            }
        });
    });
});

document.querySelector('.menu-hamburger').addEventListener('click', function() {
    this.classList.toggle('active'); // Ativa ou desativa o ícone de hambúrguer
    document.querySelector('.dropdown-menu').classList.toggle('active'); // Mostra/esconde o menu suspenso
});

