// Requisito: Vetor para histórico 
const logHistorico = [];

function atualizarLogs(mensagem) {
    const logList = document.getElementById('log-list');
    const entrada = `[${new Date().toLocaleTimeString()}] ${mensagem}`;
    logHistorico.push(entrada);
    // Exibe os 4 últimos logs [cite: 121]
    logList.innerHTML = logHistorico.slice(-4).reverse().join('<br>');
}

function gerenciarCruzamento() {
    // Simulação de Sensores [cite: 122]
    const status = {
        fluxo: Math.floor(Math.random() * 100),
        chuva: Math.random() > 0.85,
        sensorAtivo: Math.random() > 0.1
    };

    // Atualiza Painel
    document.getElementById('val-fluxo').innerText = `${status.fluxo}%`;
    document.getElementById('val-clima').innerText = status.chuva ? "Chuva Forte" : "Limpo";
    document.getElementById('val-sensor').innerText = status.sensorAtivo ? "Ativo" : "FALHA";

    // Seleção de elementos
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

    // Reset de luzes
    const todasAsLuzes = [...Object.values(viaA), ...Object.values(viaB)];
    todasAsLuzes.forEach(l => l.className = 'luz');

    // Lógica de Decisão [cite: 118]
    
    // 1. Falha de Sensor 
    if (!status.sensorAtivo) {
        viaA.a.classList.add('amarelo');
        viaB.a.classList.add('amarelo');
        document.getElementById('val-modo').innerText = "SEGURANÇA";
        atualizarLogs("ALERTA: Sensor offline. Modo amarelo intermitente.");
        return;
    }

    // 2. Chuva Forte [cite: 126]
    if (status.chuva) {
        viaA.a.classList.add('amarelo');
        viaB.v.classList.add('vermelho');
        document.getElementById('val-modo').innerText = "CLIMA";
        atualizarLogs("CAUTELA: Chuva detectada. Reduzindo velocidade.");
    } 
    // 3. Fluxo Alto na Via A [cite: 125]
    else if (status.fluxo > 70) {
        viaA.g.classList.add('verde');
        viaB.v.classList.add('vermelho');
        document.getElementById('val-modo').innerText = "FLUXO ALTO";
        atualizarLogs(`CONGESTIONAMENTO: Fluxo em ${status.fluxo}%. Liberando Via A.`);
    } 
    // 4. Operação Normal
    else {
        viaA.v.classList.add('vermelho');
        viaB.g.classList.add('verde');
        document.getElementById('val-modo').innerText = "NORMAL";
        atualizarLogs("OPERAÇÃO: Tráfego normalizado.");
    }
}

// Ciclo de 3 segundos [cite: 119]
setInterval(gerenciarCruzamento, 3000);
gerenciarCruzamento();
