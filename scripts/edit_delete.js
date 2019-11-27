function editRow(r) {
  var i = r.parentNode.parentNode.rowIndex;
  user=document.getElementById("jogadores").rows[i].cells[0].innerHTML;
  equi=document.getElementById("jogadores").rows[i].cells[1].innerHTML;
  
  location.replace("estatutos.php?user="+user+"&equi="+equi);   
  
}

function deleteRow(r) {

 var i = r.parentNode.parentNode.rowIndex;
 user=document.getElementById("jogadores").rows[i].cells[0].innerHTML;
 equi=document.getElementById("jogadores").rows[i].cells[1].innerHTML;

 if (confirm("Confirme remoção de jogador?")) {
 document.getElementById("jogadores").deleteRow(i);
 location.replace("apagajogador.php?user="+user+"&equi="+'equipa10');
 
 } else {

 }

}
