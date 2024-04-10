let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;
let total = 0;

cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: [ ]
};

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();

});

function iniciarApp(){
    mostrarSeccion();   //Muestra y oculta las secciones
    tabs();             //Cambiar la seccion cuando se precionen los tabs
    botonesPaginador(); //Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();     //Consulta la API en el backend de PHP

    idCliente();
    nombreCliente();    //añade el nombre del cliente al objeto de cita
    seleccionarFecha(); //añade fecha al objeto de cita
    seleccionarHora();  //añade hora al objeto de cita

    mostrarResumen(); //Muestra el resumen de la cita
};

function mostrarSeccion(){
    const seccionAnterior = document.querySelector('.mostrar');
    const tabAnterior = document.querySelector('.actual');
    const seccion = document.querySelector(`#paso-${paso}`);
    const tab = document.querySelector(`[data-paso="${paso}"]`);

    // Ocultar el que tenga la clase de mostrar
    if(seccionAnterior){

        seccionAnterior.classList.remove('mostrar'); 

    }
    
    if(tabAnterior){

        tabAnterior.classList.remove('actual'); 
        
    }
    // Seleccionar la seccion con el paso...
    seccion.classList.add('mostrar');
    
    //Resalta el tab actual
    tab.classList.add('actual');
};

function botonesPaginador() {
    const botonSiguiente = document.querySelector('#siguiente');
    const botonAnterior = document.querySelector('#anterior');

    if(paso === 1){
        botonAnterior.classList.add('ocultar');
        botonSiguiente.classList.remove('ocultar');
    } else if(paso === 3){
        botonAnterior.classList.remove('ocultar');
        botonSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        botonAnterior.classList.remove('ocultar');
        botonSiguiente.classList.remove('ocultar');
    };
    mostrarSeccion();

};

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton => {
        boton.addEventListener('click', function(e){
        paso = parseInt(e.target.dataset.paso);

        mostrarSeccion();
        botonesPaginador();
        
        });
    });
};

function paginaAnterior(){

    const botonAnterior = document.querySelector('#anterior');

    botonAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
            paso--;
            botonesPaginador();
    });
};

function paginaSiguiente(){

    const botonSiguiente = document.querySelector('#siguiente');

    botonSiguiente.addEventListener('click', function(){

        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();

    });

};


async function consultarAPI(){
    try{
        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    }catch(error){
        console.log(error);
    };
};

function mostrarServicios(servicios){
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = precio + '$';

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = () => {
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        const listado = document.querySelector('#servicios');
        listado.appendChild(servicioDiv);
    })
    
};

function seleccionarServicio(servicio){
    const {id} = servicio;
    const {servicios} = cita;
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio fue agregado
    if(servicios.some( agregado => agregado.id === id ) ){
        //eliminarlo
        divServicio.classList.remove('seleccionado')
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
    }else{
        //agregarlo
        divServicio.classList.add('seleccionado');
        cita.servicios = [...servicios, servicio];
    };

};

function nombreCliente(){
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;
};

function idCliente(){
    const id = document.querySelector('#id').value;
    cita.id = id;
};

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        (dia);

        if( [0, 6].includes(dia) ){
            e.target.value = '';
            //mostrarAlerta('Sabados y Domingo no Trabajamos','error','.formulario');
            alert('Sabados y Domingo no Trabajamos');
        }else{
            cita.fecha = e.target.value;
        };
    });
};

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {

        horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if(hora < 8 || hora > 20){
            horaCita = '';
            alert('Hora No valida');
        }else{
            cita.hora = horaCita;
        };
    });
};

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    //Previene mas que se genere mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if( alertaPrevia ) {
        alertaPrevia.remove();
    };

    //Scripting para crear una alerta
    const alerta = document.createElement('DIV');
    const referencia = document.querySelector(elemento);

    alerta.textContent = mensaje;
    alerta.classList.add(tipo);
    alerta.classList.add('alerta');

    referencia.appendChild(alerta);

    if(desaparece){
        //eliminar la alerta
        setTimeout( () => {
            alerta.remove();
        }, 3000 );
    };
};

function mostrarResumen(){
    const Contenidoresumen = document.querySelector('.contenido-resumen');
    const resumen = document.querySelector('.resumen');

    //Limpiar el contenido de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    };

    if(Object.values(cita).includes('') || cita.servicios.length === 0){

        mostrarAlerta('Faltan datos','error','.resumen',false);
        return;
    };

    const {nombre, fecha, hora, servicios } = cita;

            // Formatear la fecha en español //////////////////////////////////////////
            const fechaObj = new Date(fecha);
        
            const mes = fechaObj.getMonth();
    
            const dia = fechaObj.getDate() + 2;
    
            const year = fechaObj.getFullYear();
    
            const fechaUTC = new Date( Date.UTC(year, mes, dia));
    
            const opciones = {day: 'numeric', weekday: 'long', month: 'long', year: 'numeric'};
    
            const fechaFormateada = fechaUTC.toLocaleDateString('es-VE', opciones);
    
            ////////////////////////////////////////////////////////////////////////

    const nombreCliente = document.createElement('P');
    const fechaCliente = document.createElement('P');
    const horaCliente = document.createElement('P');

    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;
    fechaCliente.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;
    horaCliente.innerHTML = `<span>Hora: </span> ${hora}`;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio;
        const contenedorServicio = document.createElement('DIV');
        const textoServicio = document.createElement('P');
        const nombreServicio = document.createElement('P');
        const precioServicio = document.createElement('P');

        contenedorServicio.classList.add('contenedor-Servicio');
        textoServicio.textContent = servicio;

        nombreServicio.textContent = nombre;
        precioServicio.innerHTML = `<span>Precio:</span>  ${precio}$`;

        contenedorServicio.appendChild(nombreServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);

        total += Number(precio);

    });

    total.toFixed(2);

    const precioTotal = document.createElement('P');
    precioTotal.innerHTML = `<span>Total:</span>  ${total}$`;
    resumen.appendChild(precioTotal);

    //Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita

    resumen.appendChild(botonReservar);


};

async function reservarCita(){
    const {id, fecha, hora, servicios} = cita;

    const idServicios = servicios.map(servicio => servicio.id);
    const datos = new FormData();
    
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);

    try{

        //Peticion hacia la api
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        if(resultado.resultado){
            Swal.fire({
                title: "Cita creada",
                text: "Tu cita fue creada correctamente!",
                icon: "success"
            }).then(() => {
                setTimeout( () => {
                    window.location.reload();
                },1000)
            });
        };

    }catch(error){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Hubo un error al guardar la cita!"
          });
    }
    

    //formatear para mostrar datos
    //console.log([...datos]);
};