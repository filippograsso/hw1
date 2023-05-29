

function apriForm(event) {
    modale.classList.remove('hidden');
    document.body.classList.add('no-scroll');
}

function chiudiModale(event) {
	if(event.key === 'Escape')
	{
        container = document.querySelector("#modaleScheda");
        container.innerHTML = "";

		modali = document.getElementsByClassName("modale")
        for(m of modali)
		    m.classList.add('hidden');
		document.body.classList.remove('no-scroll');
	}
}

document.querySelector("#creaScheda").addEventListener('click', apriForm);
const modale = document.querySelector('#modaleForm');
window.addEventListener('keydown', chiudiModale);


function eliminaEsercizio(event){
    let idEsercizio = event.currentTarget.dataset.id;
    let idScheda = document.querySelector("#modaleScheda").dataset.idScheda;
    fetch("elimina_esercizio.php?q="+encodeURIComponent(idEsercizio)).then(onResponse).then(caricaEsercizi(idScheda));
}

function onJsonEsercizi(json) {
    container = document.querySelector("#modaleScheda");
    container.innerHTML = "";

    let divEsercizi = document.createElement("div");
    divEsercizi.classList.add('hidden');

    if (json.length == 0) {
        let msg = document.createElement("p");
        msg.classList.add("msg");
        msg.id = "msgEsercizi";
        msg.innerText = "Non hai ancora aggiunto esercizi alla tua scheda...";
        container.appendChild(msg);
    }
  
    function handleFetchResponse(json2, section, serie, ripetizioni) {
      json2 = JSON.parse(json2);
      //console.log(json2);
      if (json2.length !== 0) {
        let div = document.createElement("div");
        let closeButton = document.createElement("button");
        closeButton.classList.add("eliminaScheda");
        closeButton.classList.add("sideButton");
        closeButton.innerHTML = "&#x2716;";
        closeButton.dataset.id = section.dataset.id;
  
        let closeButtonDiv = document.createElement("div");
        closeButtonDiv.classList.add("closeButtonDiv");
        closeButtonDiv.appendChild(closeButton);
        
        closeButton.addEventListener("click", eliminaEsercizio);

  
        let image = document.createElement("img");
        image.src = json2.images[0].image;
        let nome = document.createElement("h2");
        nome.innerText = json2.name;
        let muscoli = document.createElement("p");
        muscoli.innerHTML = "<strong>Muscoli: </strong>";
        for (muscle of json2.muscles) {
          muscoli.innerHTML += muscle.name_en + " ";
        }
        for (muscle of json2.muscles_secondary) {
          muscoli.innerHTML += muscle.name_en + " ";
        }
        let descrizione = document.createElement("p");
        let description = json2.description.replace(/<\/?p>/g, "");
        descrizione.innerHTML = "<strong>Descrizione:</strong> " + description;
        div.appendChild(nome);
        div.appendChild(descrizione);
        div.appendChild(muscoli);
  
        let serieElement = document.createElement("p");
        serieElement.innerHTML = "<strong>Serie: </strong>" + serie;
        let ripetizioniElement = document.createElement("p");
        ripetizioniElement.innerHTML = "<strong>Ripetizioni: </strong>" + ripetizioni;
  
        div.appendChild(serieElement);
        div.appendChild(ripetizioniElement);
  
        section.appendChild(div);
        section.appendChild(image);
        section.appendChild(closeButtonDiv);

        divEsercizi.appendChild(section);
        i++;

        if(i==n){
            gif.remove();
            divEsercizi.classList.remove('hidden');
            container.appendChild(divEsercizi);
            modaleScheda.classList.add("start");
        }
      }
    }

    modaleScheda.classList.remove("start");
    let gif = document.createElement("img");
    gif.src = "images/loading.gif";
    if(json.length !== 0) container.appendChild(gif);

    const n = json.length;
    let i=0;
  
    for (item of json) {
      var section = document.createElement("section");
      section.dataset.id = item.id;
      section.dataset.idOnline = item.idOnline;
      var div = document.createElement("div");
  
      (function (currentItem, currentSection) {
        fetch("cerca_dettagli.php?q=" + encodeURIComponent(currentItem.idOnline))
          .then(onResponse)
          .then(function (json2) {
            handleFetchResponse(json2, currentSection, currentItem.serie, currentItem.ripetizioni);
          });
      })(item, section);
    }
}

function modifica (event){
    document.querySelector("#modaleScheda").classList.remove('hidden');
    document.querySelector("#modaleScheda").dataset.idScheda = event.currentTarget.dataset.id;
    document.querySelector("body").classList.add('no-scroll');
    caricaEsercizi(event.currentTarget.dataset.id);
}

function caricaEsercizi(idScheda){
    fetch("carica_esercizi.php?q="+encodeURIComponent(idScheda)).then(onResponse).then(onJsonEsercizi);
}

function stop(event){
    event.stopPropagation();
}


function eliminaScheda(event){
    event.stopPropagation();
    let id = event.currentTarget.parentNode.parentNode.dataset.id;
    fetch("elimina_scheda.php?q="+encodeURIComponent(id)).then(onResponse).then(caricaSchede);
}

function onJson(json){
    //console.log(json);
    if(json.length == 0){
        msg = document.querySelector("#msgSchede");
        msg.classList.remove("hidden")
        msg.innerText = "Non hai ancora creato una scheda...";
    }

    container = document.querySelector("#container");
    container.innerHTML = "";
    for(item of json){
        section = document.createElement("section");
        section.classList.add("scheda");
        section.dataset.id = item.id;
        section.addEventListener('click', modifica);

        div = document.createElement("div");
        section2 = document.createElement("section");
        
        nome = document.createElement("h2");
        nome.innerText = item.nome;
        
        tipo = document.createElement("p");
        tipo.innerHTML = "<strong>Tipo: </strong>"+ item.tipo;

        categorie = document.createElement("p");
        categorie.innerHTML = "<strong>Parti: </strong>"+ item.categorie;

        giorno = document.createElement("p");
        giorno.innerHTML = "<strong>Giorno: </strong>"+ item.giorno;

        pubblica = document.createElement("p");
        pubblica.innerHTML = parseInt(item.pubblica) ? "<strong>Scheda pubblica</strong>" : "<strong>Scheda privata</strong>";

        form = document.createElement("form");
        form.method = "post";
        form.action = "crea_scheda.php"

        hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "idScheda";
        hidden.value = item.id;

        submit = document.createElement("button");
        submit.type = "submit";
        submit.classList.add("aggiungiEsercizi");
        submit.classList.add("sideButton");
        submit.title = "Aggiungi esercizi";
        i1 = document.createElement("i");
        i1.classList.add("fa");
        i1.classList.add("fa-plus");
        submit.addEventListener('click', stop);
        submit.appendChild(i1);
        
        button = document.createElement("button");
        button.classList.add("eliminaScheda");
        button.classList.add("sideButton");
        button.title = "Elimina scheda";
        i2 = document.createElement("i");
        i2.classList.add("fa");
        i2.classList.add("fa-times");
        button.addEventListener('click', eliminaScheda);
        button.appendChild(i2);

        form.appendChild(hidden);
        form.appendChild(submit);

        div.appendChild(nome);
        div.appendChild(tipo);
        div.appendChild(categorie);
        div.appendChild(giorno);
        div.appendChild(pubblica);

        section2.appendChild(form);
        section2.appendChild(button);

        section.appendChild(div);
        section.appendChild(section2);
        section.classList.add("scheda");
        container.appendChild(section);
    }
    
}

function onResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function caricaSchede(){
    fetch("carica_schede.php").then(onResponse).then(onJson);
}

caricaSchede();




function onText(text){
    msg = document.querySelector("#msgSchede");
    /*msg.classList.remove("hidden");
    msg.innerText = text;*/
    msg.classList.add("hidden");
    caricaSchede();
    chiudiModale(new KeyboardEvent("keydown", { key: "Escape" }));
}

function onAggiungiResponse(response) {
    if (!response.ok) return null;
    return response.text();
}

function creaScheda(event){
    event.preventDefault();
    const form = document.forms['creaScheda'];
    const form_data = {method: 'post', body: new FormData(form)};
    fetch("aggiungi_scheda.php", form_data).then(onAggiungiResponse).then(onText);
}

document.forms['creaScheda'].submit.addEventListener('click', creaScheda);
