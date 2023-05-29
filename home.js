function controllaNome(event){
    if (!/^[a-zA-Z]+([\-'\s][a-zA-Z]+)*$/.test(event.currentTarget.value))
        document.querySelector("#erroreNome").classList.add("visible");
    else document.querySelector("#erroreNome").classList.remove("visible");
}

function controllaCognome(event){
    if (!/^[a-zA-Z]+([\-'\s][a-zA-Z]+)*$/.test(event.currentTarget.value))
        document.querySelector("#erroreCognome").classList.add("visible");
    else document.querySelector("#erroreCognome").classList.remove("visible");
}

function controllaData(event){
    var today = new Date();
    var birthDate = new Date(event.currentTarget.value);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    if(age<18 || age>=100)
        document.querySelector("#erroreData").classList.add("visible");
    else document.querySelector("#erroreData").classList.remove("visible");
}

function onJsonEmail(json){
    //console.log(json);
    if(json.exists){
        document.querySelector("#erroreEmail").textContent = "Email già utilizzata";
        document.querySelector("#erroreEmail").classList.add("visible");
    } else document.querySelector("#erroreEmail").classList.remove("visible");
}

function onResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function controllaEmail(event){
    email = event.currentTarget.value;
    if(!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,}$/.test(email)){
        document.querySelector("#erroreEmail").textContent = "Inserisci un'email valida";
        document.querySelector("#erroreEmail").classList.add("visible");
    }
    else{
        fetch("cerca_email.php?q="+encodeURIComponent(String(email).toLowerCase())).then(onResponse).then(onJsonEmail);
    }
}

function onJsonUsername(json){
    //console.log(json);
    if(json.exists){
        document.querySelector("#erroreUsername").textContent = "Username già utilizzato";
        document.querySelector("#erroreUsername").classList.add("visible");
    } else document.querySelector("#erroreUsername").classList.remove("visible");
}

function controllaUsername(event){
    username = event.currentTarget.value;
    if(!/^[a-zA-Z0-9_]{1,20}$/.test(username)){
        document.querySelector("#erroreUsername").textContent = "Lo username deve avere max. 20 caratteri (alfanumerici o underscore)";
        document.querySelector("#erroreUsername").classList.add("visible");
    }
    else{
        fetch("cerca_username.php?q="+encodeURIComponent(username)).then(onResponse).then(onJsonUsername);
    }
}

function controllaPassword(event){
    if(!/^(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/.test(event.currentTarget.value))
        document.querySelector("#errorePassword").classList.add("visible");
    else document.querySelector("#errorePassword").classList.remove("visible");
}

function controllaConferma(event){
    if(event.currentTarget.value !== document.forms['registrazione'].password.value)
        document.querySelector("#erroreConferma").classList.add("visible");
    else document.querySelector("#erroreConferma").classList.remove("visible");
}

function cambiaVisibilitaPassword(event){
    //event.stopPropagation();
    event.preventDefault();
    input = event.currentTarget.parentNode.querySelector('input');
    if(input.type === 'password'){
        input.type = 'text';
        event.currentTarget.querySelector('i').classList.remove('fa-eye');
        event.currentTarget.querySelector('i').classList.add('fa-eye-slash');
    }else if(input.type === 'text'){
        input.type = 'password';
        event.currentTarget.querySelector('i').classList.remove('fa-eye-slash');
        event.currentTarget.querySelector('i').classList.add('fa-eye');
    }
}
if(document.forms['registrazione']){
    document.forms['registrazione'].nome.addEventListener('blur', controllaNome);
    document.forms['registrazione'].cognome.addEventListener('blur', controllaCognome);
    document.forms['registrazione'].data.addEventListener('blur', controllaData);
    document.forms['registrazione'].email.addEventListener('blur', controllaEmail);
    document.forms['registrazione'].username.addEventListener('blur', controllaUsername);
    document.forms['registrazione'].password.addEventListener('blur', controllaPassword);
    document.forms['registrazione'].confermaPassword.addEventListener('blur', controllaConferma);
}
const buttons = document.querySelectorAll('.showPasswordButton');
buttons.forEach(button => {
  button.addEventListener('click', cambiaVisibilitaPassword);
});




function onJsonPubbliche(json){
    container = document.querySelector("#container");
    container.innerHTML = "";
    for(item of json){
        section = document.createElement("section");
        section.classList.add("scheda");
        section.dataset.id = item.id;
        section.addEventListener('click', apriScheda);
        
        img = document.createElement("img");
        img.src = item.pic;

        div = document.createElement("div");
        
        nome = document.createElement("h2");
        nome.innerText = item.nome;
        
        proprietario = document.createElement("p");
        proprietario.innerHTML = "<strong>Proprietario: </strong>"+ item.username;

        tipo = document.createElement("p");
        tipo.innerHTML = "<strong>Tipo: </strong>"+ item.tipo;

        categorie = document.createElement("p");
        categorie.innerHTML = "<strong>Parti: </strong>"+ item.categorie;

        giorno = document.createElement("p");
        giorno.innerHTML = "<strong>Giorno: </strong>"+ item.giorno;

        hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "idScheda";
        hidden.value = item.id;

        div.appendChild(nome);
        div.appendChild(proprietario);
        div.appendChild(tipo);
        div.appendChild(categorie);
        div.appendChild(giorno);

        section.appendChild(div);
        section.appendChild(img);
        container.appendChild(section);
    }
}

fetch("carica_pubbliche.php").then(onResponse).then(onJsonPubbliche);





function onJsonScheda(json) {
    //console.log(json)
    modaleScheda.innerHTML = "";
  
    function handleFetchResponse(json2, section, serie, ripetizioni) {
      json2 = JSON.parse(json2);
      //console.log(json2);
      if (json2.length !== 0) {
        var div = document.createElement("div");
  
        var image = document.createElement("img");
        image.src = json2.images[0].image;
        var nome = document.createElement("h2");
        nome.innerText = json2.name;
        var muscoli = document.createElement("p");
        muscoli.innerHTML = "<strong>Muscoli: </strong>";
        for (muscle of json2.muscles) {
          muscoli.innerHTML += muscle.name_en + " ";
        }
        for (muscle of json2.muscles_secondary) {
          muscoli.innerHTML += muscle.name_en + " ";
        }
        var descrizione = document.createElement("p");
        var description = json2.description.replace(/<\/?p>/g, "");
        descrizione.innerHTML = "<strong>Descrizione:</strong> " + description;
        div.appendChild(nome);
        div.appendChild(descrizione);
        div.appendChild(muscoli);
  
        var serieElement = document.createElement("p");
        serieElement.innerHTML = "<strong>Serie: </strong>" + serie;
        var ripetizioniElement = document.createElement("p");
        ripetizioniElement.innerHTML = "<strong>Ripetizioni: </strong>" + ripetizioni;
  
        div.appendChild(serieElement);
        div.appendChild(ripetizioniElement);
  
        section.appendChild(div);
        section.appendChild(image);
        
        divEsercizi.appendChild(section);

        i++;
        if(i==n){
            gif.classList.add("hidden");
            modaleScheda.appendChild(divEsercizi);
            modaleScheda.classList.add("start");
        }
      }
    }
  
    const n = json.length;
    let i=0;
    let divEsercizi = document.createElement("div");
    divEsercizi.id = "divEsercizi";
    modaleScheda.classList.remove('hidden');
    modaleScheda.classList.remove("start");
    document.body.classList.add('no-scroll');
    let gif = document.createElement("img");
    gif.src = "images/loading.gif";
    modaleScheda.appendChild(gif);
    for (item of json) {
      var section = document.createElement("section");
      section.dataset.idOnline = item.idOnline;
  
      (function (currentItem, currentSection) {
        fetch("cerca_dettagli.php?q=" + encodeURIComponent(currentItem.idOnline))
          .then(onResponse)
          .then(function (json2) {
            handleFetchResponse(json2, currentSection, currentItem.serie, currentItem.ripetizioni);
          });
      })(item, section);
    }
  }

function apriScheda(event) {
    id = event.currentTarget.dataset.id;
    fetch("carica_pubblica.php?scheda="+encodeURIComponent(id)).then(onResponse).then(onJsonScheda);
}

function chiudiModale(event) {
	if(event.key === 'Escape')
	{
        modali = document.getElementsByClassName("modale")
        for(modale of modali)
		    modale.classList.add('hidden');
		document.body.classList.remove('no-scroll');
	}
}

const modaleScheda = document.getElementById('modaleScheda');
window.addEventListener('keydown', chiudiModale);

function accedi(event){
    document.forms['login'].parentNode.classList.remove('hidden');
    document.body.classList.add('no-scroll');
}

const loginNav = document.getElementById("loginNav");
if (loginNav) {
  loginNav.addEventListener('click', accedi);
}

function registrati(event){
    document.forms['registrazione'].parentNode.classList.remove('hidden');
    document.body.classList.add('no-scroll');
}

const signupNav = document.getElementById("signupNav");
if (signupNav) {
    signupNav.addEventListener('click', registrati);
}



const passwordInput = document.querySelector('input[name="passwordLogin"]');
const usernameEmailInput = document.querySelector('input[name="usernameEmail"]');
const submitButton = document.querySelector('input[name="login"]');

function handleSubmit(event) {
  event.preventDefault();
  
  const loginForm = document.forms['login'];
  
  if (usernameEmailInput.value.trim() === '' || passwordInput.value.trim() === '') {
    return;
  }
  
  const submitInput = document.createElement('input');
  submitInput.setAttribute('type', 'hidden');
  submitInput.setAttribute('name', submitButton.name);
  submitInput.setAttribute('value', submitButton.value);
  
  loginForm.appendChild(submitInput);
  
  loginForm.submit();
}
if(passwordInput)
    passwordInput.addEventListener('keydown', function(event) {
    if (event.keyCode === 13) {
        handleSubmit(event);
    }
    });

if(usernameEmailInput)
    usernameEmailInput.addEventListener('keydown', function(event) {
    if (event.keyCode === 13) {
        handleSubmit(event);
    }
    });


const regForm = document.forms['registrazione'];
if(regForm){
    inputs = regForm.querySelectorAll("input");
    for(i of inputs)
        i.addEventListener('keydown', preventShowButton);
    
    document.forms['registrazione'].addEventListener('keydown', preventShowButton);

    function preventShowButton(event){
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    }
}
