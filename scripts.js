var xmlhttp = null;
var player  = 0;
var magic   = 0;

google_ad_client    = "pub-0556075448585716";
google_ad_width     = 728;
google_ad_height    = 90;
google_ad_format    = "728x90_as";
google_ad_type      = "text_image";
google_ad_channel   ="";
google_color_border = "222222";
google_color_bg     = "222222";
google_color_link   = "FF8800";
google_color_text   = "FFFFFF";
google_color_url    = "FFBB88";



function group_sel(group, id) {
  for (var i=0; i<5; i++) {
    document.getElementById(group + 'desc' + i).style.display = 'none';
    document.getElementById(group + 'pic'  + i).style.display = 'none';
  }
  document.getElementById(group + 'desc' + id).style.display = 'inline';
  document.getElementById(group + 'pic'  + id).style.display = 'inline';
}


function disp_password() {
  document.getElementById('formlogin').style.display = 'none';
  document.getElementById('formpassword').style.display = 'inline';
}


function disp_login() {
  document.getElementById('formpassword').style.display = 'none';
  document.getElementById('formlogin').style.display = 'inline';
}


function update_stats() {
  var total = 0;
  total += parseInt(document.getElementById('stat1').value);
  total += parseInt(document.getElementById('stat2').value);
  total += parseInt(document.getElementById('stat3').value);
  total += parseInt(document.getElementById('stat4').value);
  document.getElementById('statleft').innerHTML = (40 - total);
}



function attack_player(type, num) {
  document.getElementById('atkbtn1').disabled = true;
  document.getElementById('atkbtn2').disabled = true;
  document.getElementById('atkbtn3').disabled = true;
  document.getElementById('atkbtn4').disabled = true;

  xmlhttp = null;
  if (window.XMLHttpRequest) {              // code for Mozilla, etc.
    xmlhttp = new XMLHttpRequest();
  } else if (window.ActiveXObject) {        // code for IE
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  if (xmlhttp) {
    var str = 'quick=1';
    str += '&id=' + player;
    str += '&magic=' + magic;
    str += '&type=' + type;
    str += '&attack=' + num;
    xmlhttp.onreadystatechange = state_Change;
    xmlhttp.open('POST', 'attack.php', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send(str)
    return false;
  }

  return true;
}



function state_Change() {
  if (xmlhttp.readyState == 4) {            // if xmlhttp shows "loaded"
    if (xmlhttp.status != 200) {            // if not "OK"
      alert("Problem sending request: " + xmlhttp.statusText)
    } else {
      var str = xmlhttp.responseText;
      var arr= str.split('~');

      magic = arr[0];

      document.getElementById('status').innerHTML    = arr[ 1];

      document.getElementById('atklvl').innerHTML    = arr[ 2];
      document.getElementById('atkfame').innerHTML   = arr[ 3];
      document.getElementById('atkbling').innerHTML  = arr[ 4];
      document.getElementById('atkhptxt').innerHTML  = arr[ 5];
      document.getElementById('atkexptxt').innerHTML = arr[ 7];

      document.getElementById('deflvl').innerHTML    = arr[ 9];
      document.getElementById('deffame').innerHTML   = arr[10];
      document.getElementById('defbling').innerHTML  = arr[11];
      document.getElementById('defhptxt').innerHTML  = arr[12];

      document.getElementById('atkhp').style.width   = arr[ 6] + 'px';
      document.getElementById('atkexp').style.width  = arr[ 8] + 'px';

      document.getElementById('defhp').style.width   = arr[13] + 'px';
      document.getElementById('defhp').style.marginLeft = (300 - parseInt(arr[13])) + 'px';

      document.getElementById('rendertime').innerHTML = arr[14];

      document.getElementById('atkbtn1').disabled = false;
      document.getElementById('atkbtn2').disabled = false;
      document.getElementById('atkbtn3').disabled = false;
      document.getElementById('atkbtn4').disabled = false;
      xmlhttp = null;
    }
  }
}
