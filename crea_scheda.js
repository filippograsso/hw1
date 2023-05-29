
function onJsonSearch(json){
    //console.log(json);
    container = document.querySelector("#container");
    json = JSON.parse(json);
    let i=0;
    
    if(json.suggestions.length != 0){
        for(item of json.suggestions){
            if(item.data.image !== null){
                section = document.createElement("section");
                h2 = document.createElement("h2");
                h2.innerText = item.data.name;
                p = document.createElement("p");
                p.innerHTML = "<strong>Category:</strong> " + item.data.category;
                img = document.createElement("img");
                img.src = 'https://wger.de' + item.data.image;
                div = document.createElement("div");
                button = document.createElement("button");
                button.innerText = "Aggiungi alla scheda"
                button.dataset.id = item.data.id;
                button.addEventListener('click', apriModale);
                section.appendChild(img);
                innerDiv = document.createElement("div");
                innerDiv.appendChild(h2);
                innerDiv.appendChild(p);
                div.appendChild(innerDiv);
                div.appendChild(button);
                section.appendChild(div);
                container.appendChild(section);
                i++;
            }
        }
        gif.remove();
    }
    if(i == 0){
        msg = document.createElement("p");
        msg.id = "msgRicerca";
        msg.innerText = "La ricerca non ha fornito alcun risultato..."
        container.appendChild(msg);
    }
    
    
}

function onResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

const gif = document.createElement("img");
gif.id = "loading";
gif.src = "images/loading2.gif";

function cerca(event){
    event.preventDefault();
    if(document.querySelector("input[type='search']").value.trim() !== ''){
        document.querySelector("#container").innerHTML = "";
        document.querySelector("#container").appendChild(gif);
        let text = document.forms['ricerca'].search.value;
        fetch("cerca_esercizi.php?q="+encodeURIComponent(text)).then(onResponse).then(onJsonSearch);
    }
}

document.forms['ricerca'].submit.addEventListener('click', cerca);



function onText(text){
    msg = document.querySelector("#msgInserimento");
    msg.classList.remove('hidden');
    msg.innerText = text ? "Esercizio aggiunto correttamente" : "Qualcosa è andato storto...";
    if(text) msg.classList.add("green"); else msg.classList.add("red");
}

function onAggiungiResponse(response) {
    if (!response.ok) return null;
    return response.text();
}

function aggiungiEsercizio(event){
    event.preventDefault();
    const form = event.currentTarget.parentNode;
    const form_data = {method: 'post', body: new FormData(form)};
    fetch("aggiungi_esercizio.php", form_data).then(onAggiungiResponse).then(onText);
}

function onJsonDettagli(json){
    json = JSON.parse(json);
    modale.innerHTML = '';
    //console.log(json);
    section = document.createElement("section");
    div = document.createElement("div");
    image = document.createElement("img");
    image.src = json.images[0].image;
    nome = document.createElement("h2");
    nome.innerText = json.name;
    muscoli = document.createElement("p");
    muscoli.innerHTML = "<strong>Muscoli: </strong>";
    for(muscle of json.muscles)
        muscoli.innerHTML += muscle.name_en + " ";
    for(muscle of json.muscles_secondary)
        muscoli.innerHTML += muscle.name_en + " ";
    descrizione = document.createElement("p");
    description = json.description.replace(/<\/?p>/g, "");
    descrizione.innerHTML = "<strong>Descrizione:</strong> " + description;
    div.appendChild(nome);
    div.appendChild(descrizione);
    div.appendChild(muscoli);

    form = document.createElement("form");
    form.method="post";
    form.name = "aggiungiEsercizio";
    id = document.createElement("input");
    id.type = "hidden";
    id.name = "idEsercizio";
    id.value = json.id;
    label1 = document.createElement("label");
    label1.innerHTML = "<strong>Serie:</strong> ";
    serie = document.createElement("input");
    serie.name = "serie"
    serie.type = "number";
    serie.required = true;
    serie.value = 1
    serie.min = 1; serie.max = 10;
    label1.appendChild(serie);
    label2 = document.createElement("label");
    label2.innerHTML = "<strong>Ripetizioni:</strong> ";
    ripetizioni = document.createElement("input");
    ripetizioni.name = "ripetizioni"
    ripetizioni.type = "number";
    ripetizioni.required = true;
    ripetizioni.value = 1;
    ripetizioni.min=1; ripetizioni.max=50;
    label2.appendChild(ripetizioni);
    submit = document.createElement("input");
    submit.type = "submit";
    submit.value = "Aggiungi";
    submit.addEventListener('click', aggiungiEsercizio)
    msg = document.createElement("p");
    msg.classList.add("hidden");
    msg.id = "msgInserimento";
    form.appendChild(id);
    form.appendChild(label1);
    form.appendChild(label2);
    form.appendChild(submit);
    div.appendChild(form);
    div.appendChild(msg);

    section.appendChild(div);
    section.appendChild(image);
    modale.appendChild(section);
    modale.classList.remove('hidden');
    document.body.classList.add('no-scroll');
    
}

function apriModale(event) {
    id = event.currentTarget.dataset.id;
    fetch("cerca_dettagli.php?q="+encodeURIComponent(id)).then(onResponse).then(onJsonDettagli);
}

function chiudiModale(event) {
	if(event.key === 'Escape')
	{
		modale.classList.add('hidden');
		document.body.classList.remove('no-scroll');
	}
}

const modale = document.querySelector('#modale');
window.addEventListener('keydown', chiudiModale);