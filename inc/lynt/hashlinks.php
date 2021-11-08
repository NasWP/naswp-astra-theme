<?php
function lynt_hashlink_script(){
?>
<script>
document.addEventListener("DOMContentLoaded",function(t){function e(t){var e=document.createElement("input");document.body.append(e),url=t.target.getAttribute("href"),loc=window.location.href.split("#")[0],-1===url.indexOf(loc)&&(url=loc.concat(url)),e.value=url,e.select(),document.execCommand("copy")&&t.preventDefault(),e.remove(),t.target.childNodes[1].textContent="Zkopírováno"}for(var n=document.querySelectorAll("h2,h3"),r=0;r<n.length;r++)if(id=n[r].id,id){var o="Zkopírovat odkaz na tuto sekci do schránky",l=document.createElement("a");l.addEventListener("click",e,!1),l.setAttribute("href","#"+id),l.setAttribute("class","lynt-clip"),l.setAttribute("title",o);var a="http://www.w3.org/2000/svg",i=document.createElementNS(a,"svg");i.setAttribute("viewBox","0 0 24 24"),i.setAttribute("width","0.9rem"),i.setAttribute("height","0.9em"),i.setAttribute("fill","none"),paths=["M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71","M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"];for(var d=0;d<paths.length;d++){var c=document.createElementNS(a,"path");c.setAttribute("d",paths[d]),i.appendChild(c)}(t=document.createElement("span")).setAttribute("class","lynt-clip-tooltip"),t.textContent=o,l.appendChild(i),l.appendChild(t),n[r].appendChild(l)}});
</script>
<?php
}
add_action( 'wp_footer', 'lynt_hashlink_script' );