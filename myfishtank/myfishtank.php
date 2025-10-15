<?php
    defined('EMONCMS_EXEC') or die('Restricted access');
    global $path, $session, $v;
?>
<link href="<?php echo $path; ?>Modules/app/Views/css/config.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="<?php echo $path; ?>Modules/app/Views/css/dark.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="<?php echo $path; ?>Modules/app/Lib/appconf.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js?v=<?php echo $v; ?>"></script>

<style>
.title {
   margin-top:100px;
   color:#aaa;
   font-weight:bold;
   font-size:32px;
   line-height:32px;
}
.value {
   color:#888;
   font-weight:bold;
   font-size:64px;
   line-height:64px;
}
/* DivTable.com */
.divTable{
	display: table;
	width: 100%;
}
.divTableRow {
	display: table-row;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
}
.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
	font-weight: bold;
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}
</style>

<div id="app-block" style="display:none">
  <div class="col1">
    <div class="col1-inner">
      <div style="height:20px; border-bottom:1px solid #333; padding-bottom:8px;">
        <div style="float:right;">
          <i class="config-open icon-wrench icon-white" style="cursor:pointer; padding-right:5px;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="divTable" style="width: 100%;border: 1px solid #000;" >
    <div class="divTableBody">
      <div class="divTableRow">
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">Temperature</div>
            <div class="value"><span id="currentTemp">0</span></div>
          </div>
        </div>
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">TDS</div>
            <div class="value"><span id="tds">0</span></div>
          </div>
        </div>
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">PH</div>
            <div class="value"><span id="ph">0</span></div>
          </div>
        </div>
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">EC</div>
            <div class="value"><span id="ec">0</span>
          </div>
        </div>
      </div>
      </div>
      <div class="divTableRow">
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">Low Temperature</div>
            <div class="value"><span id="lowTemp">0</span></div>
          </div>
        </div>
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">High Temperature</div>
            <div class="value"><span id="highTemp">0</span></div>
          </div>
        </div>
        <div class="divTableCell">
          <div style="text-align:center">
            <div class="title">Salinity</div>
            <div class="value"><span id="salinity">0</span></div>
          </div>
        </div>
        <div class="divTableCell"> 
          <div style="text-align:center">
            <div class="title">Proportion S.G.</div>
            <div class="value"><span id="sg">0</span></div>
          </div>
        </div>
     </div>  
    </div>
  </div>
</div>    

<div id="app-setup" style="display:none; padding-top:50px" class="block">
    <h2 class="app-config-title">My Fish Tank</h2>
    <div class="app-config-description">
        <div class="app-config-description-inner">
            My Fish Tank
            
            <img src="<?php echo $path; ?><?php echo $appdir; ?>preview.png" style="width:600px" class="img-rounded">
        </div>
    </div>
    <div class="app-config"></div>
</div>

<div class="ajax-loader"></div>

<script>

// ----------------------------------------------------------------------
// Globals
// ----------------------------------------------------------------------
var apikey = "<?php print $apikey; ?>";
var sessionwrite = <?php echo $session['write']; ?>;
feed.apikey = apikey;

// ----------------------------------------------------------------------
// Display
// ----------------------------------------------------------------------
$("body").css('background-color','#222');
$(window).ready(function(){
    $("#footer").css('background-color','#181818');
    $("#footer").css('color','#999');
});
if (!sessionwrite) $(".config-open").hide();

// ----------------------------------------------------------------------
// Configuration
// ----------------------------------------------------------------------
config.app = {
    "currentTemp":{"type":"feed", "autoname":"currentTemp", "engine":"5", "description":"Current Temperature °C"},
    "tds":{"type":"feed", "autoname":"tds", "engine":"5", "description":"TDS PPM"},
    "ph":{"type":"feed", "autoname":"ph", "engine":"5", "description":"PH"},
    "highTemp":{"type":"feed", "autoname":"highTemp", "engine":"5", "description":"High Temp °C"},
    "lowTemp":{"type":"feed", "autoname":"lowTemp", "engine":"5", "description":"Low Temp °C"},
    "ec":{"type":"feed", "autoname":"ec", "engine":"5", "description":"EC US"},
    "salinity":{"type":"feed", "autoname":"salinity", "engine":"5", "description":"Salinity PPM"},
    "sg":{"type":"feed", "autoname":"sg", "engine":"5", "description":"Proportion S.G"}

};
config.name = "<?php echo $name; ?>";
config.db = <?php echo json_encode($config); ?>;
config.feeds = feed.list();

config.initapp = function(){init()};
config.showapp = function(){show()};
config.hideapp = function(){clear()};

// ----------------------------------------------------------------------
// APPLICATION
// ----------------------------------------------------------------------
var feeds = {};

config.init();

function init()
{   

}
    
function show()
{   
    $(".ajax-loader").hide();
    resize();
    updaterinst = setInterval(updater,5000);
}
   
function updater()
{
    var currentTemp = config.app.currentTemp.value;
    var tds = config.app.tds.value;
    var ph = config.app.ph.value;
    var highTemp = config.app.highTemp.value;
    var lowTemp = config.app.lowTemp.value;
    var ec = config.app.ec.value;
    var salinity = config.app.salinity.value;
    var sg = config.app.sg.value;
    feeds = feed.listbyid();

    $("#currentTemp").html((feeds[currentTemp].value*0.1).toFixed(2)+"°C");
    $("#tds").html((feeds[tds].value*1).toFixed(0)+"PPM");
    $("#ph").html((feeds[ph].value*0.01).toFixed(2)+"PH");
    $("#highTemp").html((feeds[highTemp].value*0.1).toFixed(2)+"°C");
    $("#lowTemp").html((feeds[lowTemp].value*0.1).toFixed(2)+"°C");
    $("#ec").html((feeds[ec].value*1).toFixed(0)+"US");
    $("#salinity").html((feeds[salinity].value*1).toFixed(0)+"PPM");
    $("#sg").html((feeds[sg].value*0.001).toFixed(3)+"S.G");
    
}

function resize() 
{
    updater();
}

function clear()
{
    clearInterval(updaterinst);
}

$(window).resize(function(){
    resize();
});

// ----------------------------------------------------------------------
// App log
// ----------------------------------------------------------------------
function app_log (level, message) {
    if (level=="ERROR") alert(level+": "+message);
    console.log(level+": "+message);
}
</script>

