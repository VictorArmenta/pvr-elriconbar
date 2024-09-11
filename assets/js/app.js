// loading screen
$(window).load(function () {
          $("#loadingimg").fadeOut("slow");
  });


// inicialización de datatable

$(document).ready(function() {
  $('[data-toggle="popover"]').popover();
   // select2 inicialización
   $(".js-select-options").select2();
   // Editor de notas de verano WYSIWYG
   $('#summernote').summernote({
      height: 200,
      toolbar: [
       // [groupName, [list of button]]
       ['style', ['bold', 'italic', 'underline', 'clear']],
       ['font', []],
       ['fontsize', ['fontsize']],
       ['color', ['color']],
       ['para', ['ul', 'ol', 'paragraph']],
       ['height', ['height']]
     ]
   });
   $('#summernote2').summernote({
      height: 200,
      toolbar: [
       // [groupName, [list of button]]
       ['style', ['bold', 'italic', 'underline', 'clear']],
       ['font', []],
       ['fontsize', ['fontsize']],
       ['color', ['color']],
       ['para', ['ul', 'ol', 'paragraph']],
       ['height', ['height']]
     ]
   });
   // tooltip 
     $('[data-toggle="tooltip"]').tooltip();
  // opciones datatable
    var table = $('#Table').DataTable( {
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "https://cdn.datatables.net/tabletools/2.2.4/swf/copy_csv_xls_pdf.swf",
            'bProcessing'    : true,
				"aButtons": [
					"xls",
					{
						"sExtends": "pdf",
						"sPdfOrientation": "landscape",
						"sPdfMessage": ""
					},
					"print"
				]
         }
      });

} );


//eliminando el teclado virtual en dispositivos móviles y tabletas
var currentState = false;

function setSize() {
  var state = $(window).width() < 961;
  if (state != currentState) {
    currentState = state;
    if (state) {
      $('.barcode').removeAttr('id');
      $('.TAX').removeAttr('id');
      $('.Remise').removeAttr('id');
    } else {
      $('.barcode').attr('id', 'keyboard');
      $('.barcode').attr('id', 'num01');
      $('.barcode').attr('id', 'num02');
    }
  }
}

setSize();
$(window).on('resize', setSize);

// configuración de desplazamiento delgado
//para la lista de productos en el lado izquierdo
$(function(){
   $('#productList').slimScroll({
      height: '355px',
      alwaysVisible: true,
      railVisible: true,
   });
});
// y el lado derecho
$(function(){
   $('#productList2').slimScroll({
      height: '740px',
      allowPageScroll: true,
      alwaysVisible: true,
      railVisible: true,
   });
});

// waves parametros
Waves.init();
Waves.attach('.flat-box', ['waves-block']);
Waves.attach('.flat-box-btn', ['waves-button']);

// parámetros del teclado virtual

$('#keyboard').keyboard({
   autoAccept : true,
    usePreview: false
})
// activar la extensión de escritura
.addTyping({
   showTyping: true,
   delay: 250
});

$('#num01')
	.keyboard({
		layout : 'numpad',
		restrictInput : true, // Evita que las teclas que no están en el teclado se tipeen
		preventPaste : true,  // evita ctrl-v y hace clic derecho
		autoAccept : true,
      usePreview: false
	})
.addTyping();

$('#num02')
	.keyboard({
		layout : 'numpad',
		restrictInput : true, // Evita que las teclas que no están en el teclado se tipeen
		preventPaste : true,  // evita ctrl-v y hace clic derecho
		autoAccept : true,
      usePreview: false
	})
.addTyping();

$('.paid')
	.keyboard({
		layout : 'numpad',
		restrictInput : true, // Evita que las teclas que no están en el teclado se tipeen
		preventPaste : true,  // evita ctrl-v y hace clic derecho
		autoAccept : true,
      usePreview: false
	})
.addTyping();

/***************************** LOGIN form ***********/

$( ".LoginInput" ).focusin(function() {
  $( this ).find( "span" ).animate({"opacity":"0"}, 200);
});

$( ".LoginInput" ).focusout(function() {
  $( this ).find( "span" ).animate({"opacity":"1"}, 300);
});

/******** passwors validacion de confirmacion ****************/

var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Las contraseñas no son iguales");
  } else {
    confirm_password.setCustomValidity('');
  }
}

if(password) password.onchange = validatePassword;
if(confirm_password) confirm_password.onkeyup = validatePassword;



/************************* modal shifting fix ****************************/

$(document.body)
.on('show.bs.modal', function () {
    if (this.clientHeight <= window.innerHeight) {
        return;
    }
    // Get scrollbar width
    var scrollbarWidth = getScrollBarWidth()
    if (scrollbarWidth) {
        $(document.body).css('padding-right', scrollbarWidth);
        $('.navbar-fixed-top').css('padding-right', scrollbarWidth);
    }
})
.on('hidden.bs.modal', function () {
    $(document.body).css('padding-right', 0);
    $('.navbar-fixed-top').css('padding-right', 0);
});

function getScrollBarWidth () {
    var inner = document.createElement('p');
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement('div');
    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild (inner);

    document.body.appendChild (outer);
    var w1 = inner.offsetWidth;
    outer.style.overflow = 'scroll';
    var w2 = inner.offsetWidth;
    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild (outer);

    return (w1 - w2);
};



//************* idioma español y habilitar enter
 $(function(){

    var t,
      o = '',
      layouts = [];


    // Change display language, if the definitions are available
    showKb = function(layout){
      var kb = $('#keyboard').getkeyboard();
      kb.options.layout = layout;
      // redraw keyboard with new layout
      kb.redraw();
    };


//**************************** boton enter *****************************
$.keyboard.keyaction.enter = function(base){
  if (base.el.tagName === "INPUT") {
    base.accept();      // accept the content
   $('.barcode').submit(); // funcion "function barcode()" de pos.php envia los datos

  } else {
    base.insertText('\r\n'); // textarea
  }
};

/*$(function(){
  $('#keyboard').keyboard({
    // custom options here
  });
});*/
///*********


/*$.extend($.keyboard.keyaction, {*/
   /* bksp : function(base){
        base.insertText('[BACKSPACE]');
    },*/
   /* enter : function(base) {
        base.accept();
        $('form').submit();
    }
});*/

/*$('#keyboard').keyboard({
    acceptValid: true, //habilita la posibilidad de hacer uso de "enter"
    restrictInput: true, //
    validate: function(kb, key, el) {
        var input = "The quick brown fox jumps over the lazy dog.",
            result = kb.$preview.val(),
            len = result.length;
        while ( !input.match( result.substring(0,len) )) {
            len--;
        }
        kb.$preview.val( result.substring(0, len - 1) );
        return true;
    }
});*/

//******************************************

    $.each(jQuery.keyboard.layouts, function(i, l){
      if (l.name) {
        layouts.push([i,l.name]);
      }
    });
    // sort select options by language name, not
    layouts.sort( function( a, b ) {
      return a[1] > b[1] ? 1 : a[1] < b[1] ? -1 : 0;
    });
    $.each(layouts, function(i, l){
      o += '<option value="' + l[0] + '">' + l[1] + '</option>';
    });


    $('#keyboard').keyboard({
      layout: 'qwerty',
      stayOpen: false

    })
    // activate the typing extension
    .addTyping({
      showTyping: true,
      delay: 250
    })
    .previewKeyset();

//**** teclado español ********
    $('#lang')
      .html(o)
      .change(function(){
        var kb = $('#keyboard'),
          $this = $(this),
          $opt = $this.find('option:selected'),
          layout = $this.val();

       // $('h5').text( $opt.text() ); //el h5 cambia los botones de: "cancelar" y "procesar venta" poner h6
        showKb( layout ); //****comentar
      }).trigger('change');

  });