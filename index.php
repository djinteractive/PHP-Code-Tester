<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>PHP Code Tester</title>
  <style>
    body{font:200 1em/1.6em Consolas, "Consolas for BBEdit", "Andale Mono WT", "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", "DejaVu Sans Mono", "Bitstream Vera Sans Mono", "Liberation Mono", "Nimbus Mono L", Monaco, "Courier New", Courier, monospace;margin:0;padding:1em 2em;}
    small{display: inline;color: #aaa;font-size: 40%;}
    textarea,pre{display: block;font:inherit;margin-bottom:16px;padding-bottom:1em;overflow-y:auto;}
    textarea,pre{background:#f7f7f9;border:1px solid #e1e1e8;border-radius:2px;padding:8px;resize:none;min-height:16em;width:95%;min-width:20em;max-width:85em;}
    textarea:focus{border-color:#96c;outline:none;}
    pre{min-height:4em;}
    p{clear:both;}
    code{display:inline-block;margin:0 .25em;padding:0 .5em;line-height:1.4em;background:#eee;border:1px solid #e6e6e6;border-radius:2px;color:#c22;}
    button{display: inline-block;*display: inline;padding: 11px 19px;margin-bottom: 0;*margin-left: .3em;font-size: 17.5px;line-height: 20px;color: #ffffff;text-align: center;text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);vertical-align: middle;cursor: pointer;background-color: #006dcc;*background-color: #0044cc;background-image: -moz-linear-gradient(top, #0088cc, #0044cc);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);background-image: -o-linear-gradient(top, #0088cc, #0044cc);background-image: linear-gradient(to bottom, #0088cc, #0044cc);background-repeat: repeat-x;border: 1px solid #cccccc;*border: 0;border-color: #0044cc #0044cc #002a80;border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);border-bottom-color: #b3b3b3;-webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);*zoom: 1;-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);}
    button:hover,button:focus,button:active,button[disabled] {color: #ffffff;background-color: #0044cc;*background-color: #003bb3;outline: 0;}
    button.active {background-color: #003399 \9;}
    button:first-child {*margin-left: 0;}
    button:hover,button:focus {color: #ffffff;text-decoration: none;background-position: 0 -15px;-webkit-transition: background-position 0.1s linear;-moz-transition: background-position 0.1s linear;-o-transition: background-position 0.1s linear;transition: background-position 0.1s linear;}
    button:active {background-image: none;-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);}
    button[disabled] {cursor: default;background-image: none;opacity: 0.65;filter: alpha(opacity=65);-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;}
    #statusbar{position: relative;clear:both;display: block;padding-left:16px;width:95%;min-width:20em;max-width:85em;}
    #statusbar>div{background: #f7f7f9;border: solid #e1e1e8;border-width:0 0 1px 1px;display: block;float:right;font-size:75%;line-height:1.4em;margin-top:-16px;padding:2px 8px;}
    #statusbar>div:first-child{border-right-width:1px;}
    #statusbar>div>span{font-weight: bold;}

  </style>
</head>
<body>
<h1>PHP Code Tester <small>v<?php echo phpversion(); ?></small></h1>
<textarea id="code" placeholder="Enter your code here" wrap="off" autofocus></textarea>
<div id="statusbar">
  <div>Char: <span id="textposition">1</span></div>
  <div>Line: <span id="linenumber">1</span></div>
</div>
<p>
  <button id="run">Run</button>
  or press <code>meta</code>+<code>enter</code> to run.
</p>
<pre id="result"></pre>
<script src="jquery.min.js"></script>
<script>
// Run the PHP code
$("#run").click(function(event){
  event.preventDefault();
  var that, code;
  that = this;
  code = $("#code").val() || $("#code").text();

  if(!code) {
    $("#result").html("Enter some code first.");
    return false;
  }

  $(this).attr("disabled",true);

  $.post("process.php",{"code":code})
  .success(function(response){
    $("#result").html(response);
  })
  .error(function(xhr){
    $("#result").html(xhr.responseText);
  })
  .complete(function(xhr){
    console.log("AJAX Response",xhr);
    $(that).removeAttr("disabled");
  });
})

// Editor Controls
$("body").on("keydown","#code",function(event){
  var end, start,next,line;
  start = this.selectionStart;
  end = this.selectionEnd;
  next = (this.value.length>start) ? this.value.substring(start,start+1) : false;
  line = (this.value.substr(0,this.selectionStart).lastIndexOf("\n")+1);

  if(event.keyCode===9) { // Tab
    event.preventDefault();
    $(this).val($(this).val().substring(0, start) + "  " + $(this).val().substring(end));
    this.selectionStart = this.selectionEnd = start + 2;
  }

  if(event.keyCode===13 && event.metaKey) { // Meta + Enter
    event.preventDefault();
    $("#run").click();
  }

  if(event.keyCode===219 && event.metaKey) { // Indent <-
    event.preventDefault();
    $(this).val( $(this).val().substring(0,line) + $(this).val().substring(line + ((this.value.substring(line,line+2)==="  ") ? 2 : (this.value.substring(line,line+1)===" ") ? 1 : 0)));
    this.selectionStart = this.selectionEnd = Math.max(line,start - 2);
  }
  if(event.keyCode===221 && event.metaKey) { // Indent ->
    event.preventDefault();
    $(this).val( $(this).val().substring(0,line) + "  " + $(this).val().substring(line));
    this.selectionStart = this.selectionEnd = start + 2;
  }


  if(event.keyCode===219 && event.shiftKey) { // Braces
    event.preventDefault();
    $(this).val($(this).val().substring(0, start) + "{}" + $(this).val().substring(end));
    this.selectionStart = this.selectionEnd = start + 1;
  }
  if(event.keyCode===221 && event.shiftKey && next==="}") { // Close Braces
    event.preventDefault();
    this.selectionStart = this.selectionEnd = start + 1;
  }

  if(event.keyCode===57 && event.shiftKey) { // Brackets
    event.preventDefault();
    $(this).val($(this).val().substring(0, start) + "()" + $(this).val().substring(end));
    this.selectionStart = this.selectionEnd = start + 1;
  }
  if(event.keyCode===48 && event.shiftKey && next===")") { // Close Brackets
    event.preventDefault();
    this.selectionStart = this.selectionEnd = start + 1;
  }

  if(event.keyCode===222 && event.shiftKey!==true) { // Single Quotes
    event.preventDefault();
    if(next!=="'")
      $(this).val($(this).val().substring(0, start) + "''" + $(this).val().substring(end));
    this.selectionStart = this.selectionEnd = start + 1;
  }

  if(event.keyCode===222 && event.shiftKey) { // Double Quotes
    event.preventDefault();
    if(next!=="\"")
      $(this).val($(this).val().substring(0, start) + "\"\"" + $(this).val().substring(end));
    this.selectionStart = this.selectionEnd = start + 1;
  }
});

// Statusbar
$("body").on("keyup","#code",function(event){
  text = $(this).val() || $(this).text();

  // Line Number
  $("#linenumber").html(text.substr(0, this.selectionStart).split("\n").length);

  // Position
  $("#textposition").html(this.selectionStart-(this.value.substr(0,this.selectionStart).lastIndexOf("\n")+1));
});

</script>
</body>
</html>