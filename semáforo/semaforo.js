// Função para desligar todas as luzes
function desligarTodas() {
    const luzes = document.querySelectorAll('.luz');
    luzes.forEach(luz => luz.classList.remove('aceso'));
}

// Função para acender uma luz específica
function acenderLuz(id) {
    const luz = document.getElementById(id);
    luz.classList.add('aceso');
}

// MODO 1: Semáforo 1 Verde, Semáforo 2 Vermelho
function modo1() {
    desligarTodas();
    acenderLuz('sem1-verde');
    acenderLuz('sem2-vermelho');
}

// MODO 2: Ambos em Amarelo
function modo2() {
    desligarTodas();
    acenderLuz('sem1-amarelo');
    acenderLuz('sem2-amarelo');
}

// MODO 3: Semáforo 1 Vermelho, Semáforo 2 Verde
function modo3() {
    desligarTodas();
    acenderLuz('sem1-vermelho');
    acenderLuz('sem2-verde');
}

// MODO 4: Ambos em Amarelo (Alerta com piscar)
function modo4() {
    desligarTodas();
    const sem1Amarelo = document.getElementById('sem1-amarelo');
    const sem2Amarelo = document.getElementById('sem2-amarelo');
    sem1Amarelo.classList.add('aceso', 'alerta');
    sem2Amarelo.classList.add('aceso', 'alerta');
}

// MODO 5: Desligar Semáforos
function desligar() {
    desligarTodas();
    const luzes = document.querySelectorAll('.luz');
    luzes.forEach(luz => luz.classList.remove('alerta'));
}

// Inicializar com semáforos desligados
window.addEventListener('DOMContentLoaded', function() {
    desligar();
});
