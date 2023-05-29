
function cambiaFoto(event){
    let img = document.querySelector("#pic");
    const file = event.target.files[0]; 

    if (file && file.type.startsWith("image/")) { //&& file.size <= 7 * 1024 * 1024
        const reader = new FileReader();

        reader.onload = function (e) {
            img.src = e.target.result
        };

        reader.readAsDataURL(file);
    }
}

function editElements(event){
    let msg = document.querySelector('#errore') || document.querySelector('#successo')
    if(msg) msg.remove();
    let container = event.currentTarget.parentNode.parentNode;
    let labels = container.querySelectorAll("label");
    for(l of labels) l.classList.add("after");
    container.querySelector("div").classList.add("after");
    let editButton = event.currentTarget;
    editButton.type = "submit";
    editButton.name = "edit";
    editButton.innerHTML = "";
    editButton.classList.add("fa", "fa-check");
    let icon = document.createElement("i");
    icon.classList.add("fas", "fa-camera"); 
    /*let fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.id = "fileInput";
    fileInput.name = "propic";
    fileInput.accept = 'image/*';*/
    document.querySelector("#propic").addEventListener("change", cambiaFoto);
    labelImg.htmlFor = "propic";
    labelImg.appendChild(icon);
    //labelImg.appendChild(fileInput)
    nome = container.querySelector("#nome");
    cognome = container.querySelector("#cognome");
    data = container.querySelector("#data");
    genere = container.querySelector("#genere");
    email = container.querySelector("#email");
    username = container.querySelector("#username");
    canzone = container.querySelector("#canzone");
    newsletter = container.querySelector("#newsletter");

    input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Nome*:";
    input.maxLength = 30;
    input.value = nome.innerText;
    input.name = nome.id;
    nome.parentNode.firstChild.remove();
    nome.parentNode.replaceChild(input, nome);

    input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Cognome*:";
    input.maxLength = 30;
    input.value = cognome.innerText;
    input.name = cognome.id;
    cognome.parentNode.firstChild.remove();
    cognome.parentNode.replaceChild(input, cognome);

    input = document.createElement("input");
    input.type = "date";
    input.placeholder = "Data di nascita*:";
    input.value = data.innerText;
    input.name = data.id;
    input.addEventListener("focus", function() {
        this.type = "date";
      });
    input.addEventListener("blur", function() {
        this.type = "text";
      });
    data.parentNode.firstChild.remove();
    data.parentNode.replaceChild(input, data);

    let label = genere.parentNode;
    label.querySelector("strong").outerText = "Genere:";
    let genereValue = genere.dataset.value;
    let radioUomo = createRadioButton("Uomo", "Uomo", genereValue);
    let radioDonna = createRadioButton("Donna", "Donna", genereValue);
    let radioAltro = createRadioButton("Altro", "Altro", genereValue);
    label.appendChild(radioUomo);
    label.appendChild(radioDonna);
    label.appendChild(radioAltro);
    genere.remove();
    function createRadioButton(labelText, value, selectedValue) {
      let label = document.createElement("label");
      let input = document.createElement("input");
      input.type = "radio";
      input.name = "genere";
      input.value = value;
      if (value === selectedValue) {
        input.checked = true;
      }
      label.classList.add("after");
      label.appendChild(input);
      label.appendChild(document.createTextNode(labelText));
      return label;
    }

    input = document.createElement("input");
    input.type = "email";
    input.placeholder = "Email*:";
    input.value = email.innerText;
    input.name = email.id;
    email.parentNode.firstChild.remove();
    email.parentNode.replaceChild(input, email);

    input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Username*:";
    input.value = username.innerText;
    input.name = username.id;
    input.maxLength = 20;
    username.parentNode.firstChild.remove();
    username.parentNode.replaceChild(input, username);

    container.querySelector("#password").classList.remove('hidden');
    container.querySelector("#confermaPassword").classList.remove('hidden');
/*
    input = document.createElement("input");
    input.type = "button";
    input.value = canzone.dataset.value == '-1' ? "+" : canzone.dataset.value;
    input.data = canzone.id;
    canzone.parentNode.replaceChild(input, canzone);
*/
    newsletter.parentNode.querySelector("strong").remove();
    input = document.createElement("input");
    input.type = "checkbox";
    input.value = newsletter.innerText;
    input.name = newsletter.id;
    input.checked = (newsletter.dataset.value === "1");
    newsletter.parentNode.replaceChild(input, newsletter);
    input.parentNode.innerHTML += "Voglio essere iscritto alla newsletter per rimanere sempre aggiornato sulle ultime novità";
    event.preventDefault();
    event.stopPropagation();
}

const labelImg = document.createElement("label");

function onJson(json){
    let container = document.querySelector("#container");
    if(json.length!=0){
        let editButtonSpan = document.createElement("span");
        let editButton = document.createElement("button");
        editButton.innerHTML = "&#9998;";
        editButton.addEventListener('click', editElements);
        editButtonSpan.appendChild(editButton);

        let img = document.createElement("img");
        img.src = json.propic;
        img.id = "pic";
        labelImg.appendChild(img);

        let nome = document.createElement("label");
        let nomeSpan = document.createElement("span");
        nomeSpan.innerText = json.nome;
        nomeSpan.id = "nome";
        nome.innerHTML = "<strong>Nome:<strong> ";
        nome.appendChild(nomeSpan);

        let cognome = document.createElement("label");
        let cognomeSpan = document.createElement("span");
        cognomeSpan.innerText = json.cognome;
        cognomeSpan.id = "cognome";
        cognome.innerHTML = "<strong>Cognome:<strong> ";
        cognome.appendChild(cognomeSpan);

        let data = document.createElement("label");
        let dataSpan = document.createElement("span");
        dataSpan.innerText = json.dataNascita;
        dataSpan.id = "data";
        data.innerHTML = "<strong>Data di nascita:<strong> ";
        data.appendChild(dataSpan);

        let genere = document.createElement("div");
        let genereSpan = document.createElement("span");
        genereSpan.innerText = json.genere;
        genereSpan.id = "genere";
        genereSpan.dataset.value = json.genere;
        genere.innerHTML = "<strong>Genere:<strong> ";
        genere.appendChild(genereSpan);

        let email = document.createElement("label");
        let emailSpan = document.createElement("span");
        emailSpan.innerText = json.email;
        emailSpan.id = "email";
        email.innerHTML = "<strong>Email:<strong> ";
        email.appendChild(emailSpan);

        let username = document.createElement("label");
        let usernameSpan = document.createElement("span");
        usernameSpan.innerText = json.username;
        usernameSpan.id = "username";
        username.innerHTML = "<strong>Username:<strong> ";
        username.appendChild(usernameSpan);

        let password = document.createElement("label");
        password.id = "password";
        let passwordInput = document.createElement("input");
        passwordInput.type = 'password';
        passwordInput.name = "password";
        passwordInput.placeholder = "Nuova password: ";
        password.appendChild(passwordInput);
        password.classList.add('hidden');

        let conferma = document.createElement("label");
        conferma.id = "confermaPassword";
        let confermaInput = document.createElement("input");
        confermaInput.type = 'password';
        confermaInput.name = "confermaPassword";
        confermaInput.placeholder = "Conferma password: ";
        conferma.appendChild(confermaInput);
        conferma.classList.add('hidden');

        let newsletter = document.createElement("label");
        let newsletterSpan = document.createElement("span");
        newsletterSpan.innerText = (json.newsletter != 0 ? "Iscritto" : "Non iscritto");
        newsletterSpan.dataset.value = json.newsletter;
        newsletterSpan.id = "newsletter";
        newsletter.innerHTML = "<strong>Newsletter:<strong> ";
        newsletter.appendChild(newsletterSpan);

        /*let canzone = document.createElement("label");
        let canzoneSpan = document.createElement("span");
        if(json.canzone !== null){
            canzoneSpan.innerText = "...";
            //canzoneSpan.dataset.value = ;
        }else{ 
            canzoneSpan.innerText = "Non selezionata";
            canzoneSpan.dataset.value = -1;
        }
        canzoneSpan.id = "canzone";
        canzone.innerText = "Canzone preferita in palestra: ";
        canzone.appendChild(canzoneSpan);*/

        container.appendChild(editButtonSpan);
        container.appendChild(labelImg);
        container.appendChild(nome);
        container.appendChild(cognome);
        container.appendChild(data);
        container.appendChild(genere);
        container.appendChild(email);
        container.appendChild(username);
        container.appendChild(password);
        container.appendChild(conferma);
        container.appendChild(newsletter);
//        container.appendChild(canzone);

    }
}

function onResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function caricaProfilo(){
    fetch("carica_profilo.php").then(onResponse).then(onJson);
}

caricaProfilo();
