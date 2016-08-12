{extends file="layout.tpl"}

{block name=title}{"Edit profile avatar"|_}{/block}

{block name=content}

<head>
  {foreach from=$cssFiles item=cssFile}
  <link type="text/css" href="{$wwwRoot}css/{$cssFile}" rel="stylesheet"/>
  {/foreach}
  {foreach from=$jsFiles item=jsFile}
  <script src="{$wwwRoot}js/{$jsFile}"></script>
  {/foreach}
  <style type="text/css">
    .jcrop-holder #preview-pane {
    display: block;
    position: absolute;
    z-index: 2000;
    top: 10px;
    right: -280px;
    padding: 6px;
    border: 1px rgba(0,0,0,.4) solid;
    background-color: white;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
    }
    #preview-pane .preview-container {
    width: 160px;
    height: 160px;
    overflow: hidden;
    }
  </style>
</head>

<body>
  <img src="../www/img/generated/{$rawFileName}?cb={1000000000|rand:9999999999}" id="target"/>
  <form id="avatarForm" action="saveAvatar" method="post">
    <div id="preview-pane">
      <div class="preview-container">
        <img src="../www/img/generated/{$rawFileName}?cb={1000000000|rand:9999999999}" class="jcrop-preview"/>
      </div>
    </div>
    <br>
    <input type="hidden" name="x0" value=""/>
    <input type="hidden" name="y0" value=""/>
    <input type="hidden" name="side" value=""/>
    <input type="submit" name="submit" value="{"Save"|_}"/>
    <a href="preferinte">{"Quit"|_}</a>
  </form>
</body>

<script type="text/javascript">
  jQuery(function($){
    var jcrop_api,
        boundx,
        boundy,
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),
        xsize = $pcnt.width(),
        ysize = $pcnt.height();
    $('#target').Jcrop({
      onChange: updatePreview,
      onSelect: updatePreview,
      aspectRatio: xsize / ysize
    },function(){
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      jcrop_api = this;
      $preview.appendTo(jcrop_api.ui.holder);
    });
  
    function updatePreview(c)
    {
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;
        $('#avatarForm input[name=x0]').val(c.x);
        $('#avatarForm input[name=y0]').val(c.y);
        $('#avatarForm input[name=side]').val(c.w);
        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
      }
    };
  
  });
</script>
{/block}