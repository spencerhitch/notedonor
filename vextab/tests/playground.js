// Load VexTab module.
vextab = require("vextab");
$ = require("jquery");
_ = require("underscore");
require("jquery-mousewheel")($);

$(function() {
  VexTab = vextab.VexTab;
  Artist = vextab.Artist;
  Renderer = vextab.Vex.Flow.Renderer;

  Artist.DEBUG = false;
  Artist.NOLOGO = true;
  VexTab.DEBUG = false;
  var score_scroll;
  var text = "";
  autoscroll = {
    isScrolling: false,
    current_scroll: 0,
    scrollInterval: setInterval(function() {
      if (autoscroll.isScrolling){
        if (artist.conductor.done_count == 8) {
          stopAutoScroll();
        }
        autoscroll.current_scroll = autoscroll.current_scroll + 16.5;
        score_scroll.scrollLeft(autoscroll.current_scroll);
      } else if (autoscroll.current_scroll != 0) {
        autoscroll.current_scroll = 0;
        score_scroll.scrollLeft(autoscroll.current_scroll);
      }
    } , 124)}

  // Create VexFlow Renderer from canvas element with id #boo
  renderer = new Renderer($('#boo')[0], Renderer.Backends.SVG);

  // Initialize VexTab artist and parser.
  artist = new Artist(10, 10, 24000, {scale: 0.75});
  vextab = new VexTab(artist);

  function startAutoScroll(){
    $(".preview_container").css("display","none");
    autoscroll.isScrolling = true;
  }

  function stopAutoScroll(){
    autoscroll.isScrolling = false;
    $(".preview_container").css("display","block");
    //Stop the scroller and set scroll back to 0

  }

  function render() {
    try {
      vextab.reset();
      artist.reset();
//      $.get("../score.txt", function(data) {
      $.get("./score.txt", function(data) {
        text = data;
        vextab.parse(data);
        artist.render(renderer);
        artist.conductor.play_button.onMouseUp = function(event){
          artist.conductor.play();
          // Something's wrong with visualiztion on first play so play a second time for now
          artist.conductor.play();
          startAutoScroll();
        };
        artist.conductor.stop_button.onMouseUp = function(event){
          artist.conductor.stopPlayers();
          stopAutoScroll();
        };
      });
//      vextab.parse($("#blah").val());
      $("#error").text("");
      score_scroll = $(".score_container")
    } catch (e) {
      console.log(e);
      $("#error").html(e.message.replace(/[\n]/g, '<br/>'));
    }
  }

  function validate_name(name){
    if (name.charAt(name.length - 1) == " ") name = name.substring(0, name.length - 1);
    var regex =  /^([A-ZÀ-Ú][a-zà-ú]*\s?)+$/;
    if (name == "") throw "¡Falta un nombre!";
    if (!name.includes(" ")) throw "Requeremos nombre y apellido";
    if (!regex.test(name)) throw "Nombres requieren mayusculas iniciales. \n"
                                       + "Ejemplo: Primera Segunda Tercera";
  }

  function validate_email(email){
    var regex = /^[-a-zà-ú0-9~!$%^&*_=+}{\'?]+(\.[-a-zà-ú0-9~!$%^&*_=+}{\'?]+)*@([a-zà-ú0-9_][-a-zà-ú0-9_]*(\.[-a-zà-ú0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i
    if (email == "") throw "¡Falta un correo electrónico!";
    if (!regex.test(email)) throw "Por favor ponga un correo electrónico válido.";
  }

  Values = {
    "thirtyseconds": ["32", "Fusa", "$2000", "../note_svgs/32.svg"],
    "dotted_eighths": ["8d", "Corchea con puntillo", "$2000", "../note_svgs/8d.svg"],
    "dotted_halves": ["hd", "Blanca con puntillo", "$2000", "../note_svgs/hd.svg"],
    "dotted_quarters": ["4d", "Negra con puntillo", "$1000", "../note_svgs/4d.svg"],
    "wholes": ["w", "Redonda", "$500", "../note_svgs/w.svg"],
    "halves": ["h", "Blanca", "$200", "../note_svgs/h.svg"],
    "quarters": ["4", "Negra", "$100", "../note_svgs/4.svg"],
    "sixteenths": ["16", "Semicorchea", "$50", "../note_svgs/16.svg"],
    "eighths": ["8", "Corchea", "$20", "../note_svgs/8.svg"],
  }

  function renderAvailableNotesInputs() {
    $('.note_duration').empty();
    var instrument_number = $("#buy_note option[name='instrument']:selected").val();
    post_data= {instrument_number: instrument_number};
    $.ajax({
      type: 'POST',
      url: './getAvailableNotes.php', 
//      url: '../getAvailableNotes.php', 
      dataType: "JSON",
      data: post_data,
      success: function(json) {
                 var html = "";
                 for (var duration in json) {
                   if (json[duration] > 0) {
                     var value =  Values[duration][0];
                     var name =  Values[duration][1];
                     var amount = Values[duration][2];
                     var input = "<option name='note_duration' value='"
                                 + value +  "'> "  + name + " | " + amount + " USD</option>";
                     html = html.concat(input);
                   }
                 }
                 $('.note_duration').append(html);
               }
    });
  }
  function renderImgVisual(){
    var note_type =  $("#buy_note option[name='note_duration']:selected").val();
    var img_url = "";
    for (var v in Values) {
      if (Values[v][0] == note_type){
        img_url = Values[v][3];
        break;
      }
    }
    var img = "<img id='note_visual' src='" + img_url + "'>"
    $('#note_visual').replaceWith(img);
  }

  $('select[name="os1"]').change(function(e) {
    renderAvailableNotesInputs();
    renderImgVisual();
  });

  $('select[name="os0"]').change(function(e) {
    renderImgVisual();
  });
  
  $("#paypal_buynow_button").click(function(e) {
      var name = "";
      var email = "";
      var instrument = "0"; 
      var duration = ""; 

      if ($("#buy_note input[name='os2']").val() && $("#buy_note input[name='os3']").val()) {
          name = $("#buy_note input[name='os2']").val();
          email = $("#buy_note input[name='os3']").val();
          instrument = $("#buy_note option[name='instrument']:selected").val().toString();
          duration =  $("#buy_note option[name='note_duration']:selected").val().toString();
      }

      try {
        validate_name(name);
        validate_email(email);
      }
      catch (err) {
          $("#error").html(err.replace(/[\n]/g, '<br/>'));
          console.log("Throwing error: ", err);
          e.preventDefault();
          return;
      }
      var donor_name = name.replace(/ /g, "_");

      post_data  = {
        name: donor_name, 
        email: email, 
        instrument: instrument,
        duration: duration 
      };
      $.post("../dbPost.php", post_data).done(render());
  });

  var busca_counter = 0;

  $("#busca_mi_nota").submit(function(e) {
      e.preventDefault();
      var name = "";
      if ($("#busca_mi_nota input[name='name']").val()) {
          name = $("#busca_mi_nota input[name='name']").val();
      }
      try {
        validate_name(name);
      }
      catch (err) {
          $("#error").html(err.replace(/[\n]/g, '<br/>'));
          return;
      }
      var donor_name = name.replace(/ /g, "_");
      try{
        var matching_elems = $("svg").find("svg").find("g#vf-" + donor_name);
      } catch (err) {
        return;
      }
      var elem = matching_elems[busca_counter];
      elem = $(elem);

      $(".score_container").scrollLeft(elem.position().left - 600);

      elem.find("path").css({"stroke" :"red", "fill":"red"});
      $(".score_container").find("div." + donor_name).show()
        .css({"top":elem.position().top - 555, "left":elem.position().left});

      busca_counter += 1;
      if (busca_counter > matching_elems.length - 1) {
        busca_counter = 0;
      }

  });

  $(".score_view").mousewheel(function (e,d) {
    //If the score is playing disable mousewheel functionality
    if (artist.conductor.playing_now) {
      e.preventDefault();
    } else {
      var score_scroll = $(".score_container").scrollLeft();
      $(".score_container").scrollLeft(score_scroll - 10 * d);

      e.preventDefault();
    }
  });

  $("#blah").keyup(_.throttle(render, 250));
  render();
  renderAvailableNotesInputs();
  renderImgVisual();
});
