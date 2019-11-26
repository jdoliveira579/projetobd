function abrirEquipa(r) {
  var i = r.parentNode.parentNode.rowIndex;
  nome_torneio = document.getElementById("torneios-table").rows[i].cells[1].innerHTML;
  location.replace("vertorneio.php?torneio="+nome_torneio);
}
