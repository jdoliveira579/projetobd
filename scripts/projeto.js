
function gotoTorneio(r){
    var i = r.parentNode.parentNode.rowIndex;
    nome_torneio=document.getElementById("torneios-table").rows[i].cells[1].innerHTML;
    location.replace("torneio.php?nome_torneio="+nome_torneio);
}

function gotoTorneioLogged(r){
    var i = r.parentNode.parentNode.rowIndex;
    nome_torneio=document.getElementById("torneios-table").rows[i].cells[1].innerHTML;
    estado=document.getElementById("torneios-table").rows[i].cells[0].innerHTML;
    location.replace("torneio_logged.php?nome_torneio="+nome_torneio+"&estado="+estado);
}

function gotoMeuTorneio(r){
    var i = r.parentNode.parentNode.rowIndex;
    nome_torneio=document.getElementById("torneios-table").rows[i].cells[1].innerHTML;
    location.replace("meu_torneio.php?nome_torneio="+nome_torneio);
}

function gotoVerEquipa(r){
    var i = r.parentNode.parentNode.rowIndex;
    nome_equipa=document.getElementById("minha-equipa-table").rows[i].cells[0].innerHTML;
    location.replace("minha_equipa.php?nome_equipa="+nome_equipa);
}

function gotoSairEquipa(){
    nome_equipa=document.getElementById("equipa-table").rows[1].cells[0].innerHTML;
    if (confirm("Quer mesmo sair da " + nome_equipa + "?")){
        location.replace("sair_equipa.php?nome_equipa="+nome_equipa);
    }
}