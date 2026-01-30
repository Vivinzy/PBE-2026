
const logHistorico = [];
let modoEspecial = null;

function atualizarLogs(mensagem) {
    const logList = document.getElementById('log-list');
    const entrada = `[${new Date().toLocaleTimeString()}] ${mensagem}`;
    logHistorico.push(entrada);
         
    logList.innerHTML = logHistorico.slice(-4).reverse().join('<br>');
}

function gerenciarCruzamento() {
    // Simulação de Sensores [cite: 122]
    const status = {
        fluxo: Math.floor(Math.random() * 100),
        chuva: Math.random() > 0.85,
        sensorAtivo: Math.random() > 0.1
    };

    document.getElementById('val-fluxo').innerText = `${status.fluxo}%`;
    document.getElementById('val-clima').innerText = status.chuva ? "Chuva Forte" : "Limpo";
    document.getElementById('val-sensor').innerText = status.sensorAtivo ? "Ativo" : "FALHA";

    const viaA = {
        v: document.getElementById('luz-vermelha-a'),
        a: document.getElementById('luz-amarela-a'),
        g: document.getElementById('luz-verde-a')
    };
    const viaB = {
        v: document.getElementById('luz-vermelha-b'),
        a: document.getElementById('luz-amarela-b'),
        g: document.getElementById('luz-verde-b')
    };

    const todasAsLuzes = [...Object.values(viaA), ...Object.values(viaB)];
    todasAsLuzes.forEach(l => l.className = 'luz');

    if (!status.sensorAtivo) {
        viaA.a.classList.add('amarelo');
        viaB.a.classList.add('amarelo');
        document.getElementById('val-modo').innerText = "SEGURANÇA";
        atualizarLogs("ALERTA: Sensor offline. Modo amarelo intermitente.");
        return;
    }

    if (status.chuva) {
        viaA.a.classList.add('amarelo');
        viaB.v.classList.add('vermelho');
        document.getElementById('val-modo').innerText = "CLIMA";
        atualizarLogs("CAUTELA: Chuva detectada. Reduzindo velocidade.");
    } 
    else if (status.fluxo > 70) {
        viaA.g.classList.add('verde');
        viaB.v.classList.add('vermelho');
        document.getElementById('val-modo').innerText = "FLUXO ALTO";
        atualizarLogs(`CONGESTIONAMENTO: Fluxo em ${status.fluxo}%. Liberando Via A.`);
    } 

    else {
        viaA.v.classList.add('vermelho');
        viaB.g.classList.add('verde');
        document.getElementById('val-modo').innerText = "NORMAL";
        atualizarLogs("OPERAÇÃO: Tráfego normalizado.");
    }
}

setInterval(gerenciarCruzamento, 3000);
gerenciarCruzamento();

// Funções para simular condições especiais
function simularChuva() {
    modoEspecial = 'chuva';
    const viaA = {
        v: document.getElementById('luz-vermelha-a'),
        a: document.getElementById('luz-amarela-a'),
        g: document.getElementById('luz-verde-a')
    };
    const viaB = {
        v: document.getElementById('luz-vermelha-b'),
        a: document.getElementById('luz-amarela-b'),
        g: document.getElementById('luz-verde-b')
    };

    const todasAsLuzes = [...Object.values(viaA), ...Object.values(viaB)];
    todasAsLuzes.forEach(l => l.className = 'luz');

    viaA.a.classList.add('amarelo');
    viaA.a.classList.add('amarelo-piscar');
    viaB.v.classList.add('vermelho');
    document.getElementById('val-modo').innerText = "CLIMA";
    document.getElementById('val-clima').innerText = "Chuva Forte";
    atualizarLogs("CAUTELA: Chuva detectada. Reduzindo velocidade.");
}

function simularFluxoAlto() {
    modoEspecial = 'fluxo';
    const viaA = {
        v: document.getElementById('luz-vermelha-a'),
        a: document.getElementById('luz-amarela-a'),
        g: document.getElementById('luz-verde-a')
    };
    const viaB = {
        v: document.getElementById('luz-vermelha-b'),
        a: document.getElementById('luz-amarela-b'),
        g: document.getElementById('luz-verde-b')
    };

    const todasAsLuzes = [...Object.values(viaA), ...Object.values(viaB)];
    todasAsLuzes.forEach(l => l.className = 'luz');

    viaA.g.classList.add('verde');
    viaB.v.classList.add('vermelho');
    document.getElementById('val-modo').innerText = "FLUXO ALTO";
    document.getElementById('val-fluxo').innerText = "85%";
    atualizarLogs("CONGESTIONAMENTO: Fluxo em 85%. Liberando Via A.");
}

function simularFalhaSensor() {
    modoEspecial = 'sensor';
    const viaA = {
        v: document.getElementById('luz-vermelha-a'),
        a: document.getElementById('luz-amarela-a'),
        g: document.getElementById('luz-verde-a')
    };
    const viaB = {
        v: document.getElementById('luz-vermelha-b'),
        a: document.getElementById('luz-amarela-b'),
        g: document.getElementById('luz-verde-b')
    };

    const todasAsLuzes = [...Object.values(viaA), ...Object.values(viaB)];
    todasAsLuzes.forEach(l => l.className = 'luz');

    viaA.a.classList.add('amarelo');
    viaA.a.classList.add('amarelo-piscar');
    viaB.a.classList.add('amarelo');
    viaB.a.classList.add('amarelo-piscar');
    document.getElementById('val-modo').innerText = "SEGURANÇA";
    document.getElementById('val-sensor').innerText = "FALHA";
    atualizarLogs("ALERTA: Sensor offline. Modo amarelo intermitente.");
}

function resetarModo() {
    modoEspecial = null;
    const viaA = {
        v: document.getElementById('luz-vermelha-a'),
        a: document.getElementById('luz-amarela-a'),
        g: document.getElementById('luz-verde-a')
    };
    const viaB = {
        v: document.getElementById('luz-vermelha-b'),
        a: document.getElementById('luz-amarela-b'),
        g: document.getElementById('luz-verde-b')
    };

    const todasAsLuzes = [...Object.values(viaA), ...Object.values(viaB)];
    todasAsLuzes.forEach(l => l.className = 'luz');

    document.getElementById('val-clima').innerText = "Limpo";
    document.getElementById('val-sensor').innerText = "Ativo";
    document.getElementById('val-fluxo').innerText = "0%";
    document.getElementById('val-modo').innerText = "Normal";
    atualizarLogs("RETORNO: Sistema normalizado.");
}

// Adicionar event listeners aos botões
document.getElementById('btn-chuva').addEventListener('click', simularChuva);
document.getElementById('btn-fluxo').addEventListener('click', simularFluxoAlto);
document.getElementById('btn-sensor').addEventListener('click', simularFalhaSensor);
document.getElementById('btn-reset').addEventListener('click', resetarModo);
