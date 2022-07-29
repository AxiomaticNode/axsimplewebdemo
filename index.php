<?php
// AXIO COLLAB Webapp Tutorial #1 - Simple Base

		// We will handle all processing of data, then deliver the layout to the client with current info.
		// There are many "realtime" systems to provide faster updates to page content if we needed it with HTTP/PHP. Lets
		// just keep it simple though. If this was a release of a communication app then it could be needed.

		// I will try to post updated examples from this or another source with better coding practices, just as an upgrade to
		// to this or another code structure. TL:DR This code is PERFECTLY fine. Its just ugly. so what better to use for
		// future code examples right?
		


		// The encryption/decryption functions along with variable settings.
		// The blocks below just returns the processed text when requested.
		// Setting encryption/decryption stuff.
		//
		// This webapp encryption could be offered as an API by just including the two functions below in a seperate file
		// and parsing/displaying encrypted or decrypted text as requested via the HTTP GET request.
		// Of course this is an example, but Signal messaging encryption runs off of a similar system where texts are sent
		// encrypted with a key which is automatically decrypted upon arrival to a recipient with matching keys.
		 function axioEnc($texttoEnc ,$keytoEnc) {
			$encIV = "1029314761728394";
			$cipher = "AES-128-CTR";
			$iv_length = openssl_cipher_iv_length($cipher);
			$options=0;		

			$AXencText = $texttoEnc;
			$AXencKey = $keytoEnc;
			$AXencFin =  openssl_encrypt($AXencText, $cipher, $AXencKey, $options, $encIV);	
			return $AXencFin;
		}
		
		 function axioDec($texttoDec, $keytoDec) {
		  $encIV = "1029314761728394";
			$cipher = "AES-128-CTR";
			$options=0;		
			$iv_length = openssl_cipher_iv_length($cipher);
			$AXdecText = $texttoDec;
			$AXdecKey = $keytoDec;
			$AXdecFin = openssl_decrypt($AXdecText,$cipher,$AXdecKey,$options,$encIV);
			return $AXdecFin;
		}




		// lets initialize some variables we will need for the script
		
		// $appBody/subject = will be the basic content containers. It will be setup as needed and 
		// delivered when the layout is given to the browser.

		// $appTmpVar will be a variable, set to integer we can manipulate for convenience.
		
		$appBody = "BOILERPLATE CONTENT";
		$appSubject = "BOILERPLATE";
		
		$appTmpVar = 0;		
		
		// OUR APP WILL DO BASIC ENCRYPTION/DECRYPTION WITH A TEXT KEY. We will need some variables to handle the functionality of the app.
		$encKey = "";
		$encRaw = "";
		$postEnc = "";



		// $appFunction = will provide a "state" navigation placeholder. And when set is 
		// used to deliver app content. home/about/program function (conv) will be the states.


		// Checks to see if the HTTP Get string from the browser is set for func. If it is then set $appFunction
		// to whatever it is. Normally you would sanitize all sorts of weird character and string abberations out of it
		// for security. But as we can check directly against it, no matter what it is, it will not be a concern. 
		$appFunction = "";
		if (!isset($_GET['func'])) { 
			// if the variable is not set, set func to home. vice versa on else.
			$appFunction = "home";
		} else {
			$appFunction = $_GET['func']; 
		}
		
		

		// switch case check against the expected strings for navigation
		switch ($appFunction) {
				case "home":
						// homepage
						$appSubject = "Main Page";
						$appBody = "Webapp Testing, Hi There ! Webapp Testing, Hi There ! Webapp Testing, Hi There ! Webapp Testing, Hi There ! <br>Webapp Testing, Hi There ! Webapp Testing, Hi There ! Webapp Testing, Hi There ! <br>Webapp Testing, Hi There ! Webapp Testing, Hi There ! Webapp Testing, Hi There ! Webapp Testing, Hi There ! ";
					break;
					
				case "about":
						// about page
						$appSubject = "About Us";
						$appBody = "SOME IMAGE OR SOMETHING - ABOUT US PAGE Webapp Testing, Hi There !  Webapp Testing, Hi There !  Webapp Testing, Hi There !  Webapp Testing, Hi There !  Webapp Testing, Hi There ! <br>Webapp Testing, Hi There ! Webapp Testing, Hi There ! ";
				
					break;
					
				case "conv":
						$appSubject = "Encrypt or Decrypt text";
						

						// check if the action and key variables are set to determine if a form was submitted.
						if(isset($_GET['action']) && isset($_GET['key'])) {
							$tmpVar = $_GET['action'];
							$encKey = $_GET['key'];
							
							// action will be 1 for encryption, 2 for decryption. switch case selection can be expanded easily..
							switch ($tmpVar) {
									case 1:
										$appSubject = "Encryption Results:";
										if(isset($_GET['toenc'])) { $encRaw = $_GET['toenc']; }
										$postEnc = axioEnc($encRaw, $encKey);
										break;

									case 2:
										$appSubject = "Decryption Results:";
										if(isset($_GET['todec'])) { $encRaw = $_GET['todec']; }
										$postEnc = axioDec($encRaw, $encKey);
										break;
									
									default:
										$postEnc = "ERROR.";
									
							}
							
							
							$appSubject = "Processed result:";
							$appBody = "<div class=\"w3-container w3-padding-64\" id=\"FinishedText\">  <div class=\"w3-row\">    <div class=\"w3-padding-16\"><span class=\"w3-xlarge w3-border-teal w3-bottombar\">Conversion Results</span></div>      <form class=\"w3-container w3-card-4 w3-padding-16 w3-white\" action=\"index.php\" target=\"_self\">      <div class=\"w3-section\">              <label>Secret Key</label>                <label class=\"w3-input\">$encKey</label>      </div>      <div class=\"w3-section\">              <label>Modified text</label>        <textarea class=\"w3-input\" rows=\"4\">$postEnc</textarea>      </div>      </form>  </div></div>";
							
						} else {
							
							//  ENCRYPT / DECRYPT page !!!
							// func = conv was set, but no variables associated with a process request.
							// Send encrypt and decrypt formdata. this is the main convert request page.
							$appSubject = "Encrypt or Decrypt text.";
							$appBody = "<div class=\"w3-container w3-padding-64\" id=\"encForm\">  <div class=\"w3-row\"><div class=\"w3-padding-16\"><span class=\"w3-xlarge w3-border-teal w3-bottombar\">Encrypt Text</span></div>  <form class=\"w3-container w3-card-4 w3-padding-16 w3-white\" action=\"index.php\" target=\"_self\"><input type=\"hidden\" name=\"func\" value=\"conv\">  <div class=\"w3-section\">  <label>Secret Key</label><input class=\"w3-input\" type=\"text\" name=\"key\" required=\"\">  </div>  <div class=\"w3-section\">  <label>Text To Encrypt</label><input class=\"w3-input\" type=\"text\" name=\"toenc\" required=\"\">  </div>  <button type=\"submit\" class=\"w3-button w3-right w3-theme\" name=\"action\" value=\"1\">Encrypt</button>  </form>  </div></div><div class=\"w3-container w3-padding-64\" id=\"decForm\">  <div class=\"w3-row\"><div class=\"w3-padding-16\"><span class=\"w3-xlarge w3-border-teal w3-bottombar\">Decrypt Text</span></div>  <form class=\"w3-container w3-card-4 w3-padding-16 w3-white\" action=\"index.php?func=conv&v=2\" target=\"_self\">  <div class=\"w3-section\">  <label>Secret Key</label><input type=\"hidden\" name=\"action\" value=\"2\"><input type=\"hidden\" name=\"func\" value=\"conv\"><input class=\"w3-input\" type=\"text\" name=\"key\" required=\"\">  </div>  <div class=\"w3-section\">  <label>Text To Decrypt</label><input class=\"w3-input\" type=\"text\" name=\"todec\" required=\"\">  </div>  <button type=\"submit\" class=\"w3-button w3-right w3-theme\" name=\"action\" value=\"2\">Decrypt</button></form>  </div></div>";
						}
						
						break;
						
					default:
					$appBody = "ERROR";
					$appSubject = "DIE";
					echo "ERR";
					exit();
				break;
		}
	
	
	//
	// Code below is the layout code. Its what defines the interface. Two variables are passed on $appSubject and $appBody
	// which are echoed out in the html below for the subject and body spots of the webapp.
	//
	
?>



<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>AXIO Collab Webapp #1</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">

<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
</head><body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i> &nbsp;[|=|]</button>
  <span class="w3-bar-item w3-right"><img src="axenc.png" border="0"></span>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index: 3; width: 300px; display: none;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="menuicon.png" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Welcome, <strong>User</strong></span><br>
      <a href=""# onClick="w3_close();">Close Menu</a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5><u>NAVBAR</u></h5>
  </div>
  <div class="w3-bar-block">
    
    <a href="index.php" class="w3-bar-item w3-button w3-padding w3-blue">&nbsp; Home
    <a href="index.php?func=about" class="w3-bar-item w3-button w3-padding" style="">&nbsp; About</a>
    <a href="index.php?func=conv" class="w3-bar-item w3-button w3-padding" style="">&nbsp; Encrypt / Decrypt</a><br><br>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor: pointer; display: none;" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i><?php echo $appSubject; ?></b></h5>
  </header>



	<?php echo $appBody; ?>
  

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">
    <hr>
    <p>Powered by <a href="#" target="_blank">AXIO COLLAB SYSTEMS</a></p>
  </footer>

  <!-- End page content -->
</div>

<script src="sidebar.js"></script>


</body><span style="--colorTabBar:var(--colorAccentBg); --colorFg:#dbdbdb; --colorFgAlpha:#dbdbdb1a; --colorFgIntense:#ffffff; --colorFgFaded:#d0d0d0; --colorFgFadedMore:#c5c5c5; --colorFgFadedMost:#acacac; --colorBg:#2f2f2f; --colorBgAlpha:#2f2f2fe6; --colorBgAlphaHeavy:#2f2f2fa6; --colorBgAlphaHeavier:#2f2f2f40; --colorBgAlphaBlur:#2f2f2fbf; --colorBgDark:#292929; --colorBgDarker:#242424; --colorBgLight:#333333; --colorBgLighter:#434343; --colorBgLightIntense:#373737; --colorBgIntense:#242424; --colorBgIntenser:#1c1c1c; --colorBgIntserAlpha:#191919eb; --colorBgInverse:#333333; --colorBgInverser:#3f3f3f; --colorBgFaded:#3b3b3b; --colorHighlightBg:#579c8e; --colorHighlightBgUnfocused:#454545; --colorHighlightBgAlpha:#579c8e1a; --colorHighlightBgDark:#3f8477; --colorHighlightFg:#ffffff; --colorHighlightFgAlpha:#ffffff80; --colorHighlightFgAlphaHeavy:#ffffff40; --colorAccentBg:#404040; --colorAccentBgAlpha:#74747466; --colorAccentBgAlphaHeavy:#74747440; --colorAccentBgDark:#303030; --colorAccentBgDarker:#1a1a1a; --colorAccentBgFaded:#343434; --colorAccentBgFadedMore:#555555; --colorAccentBgFadedMost:#747474; --colorAccentBorder:#383838; --colorAccentBorderDark:#2e2e2e; --colorAccentFg:#ffffff; --colorAccentFgFaded:#cacaca; --colorAccentFgAlpha:#ffffff40; --colorBorder:#1c1c1c; --colorBorderDisabled:#242424; --colorBorderSubtle:#222222; --colorBorderIntense:#0c0c0c; --colorSuccessBg:#06a700; --colorSuccessBgAlpha:#06a7001a; --colorSuccessFg:#ffffff; --colorWarningBg:#efaf00; --colorWarningBgAlpha:#efaf001a; --colorWarningFg:#000000; --colorErrorBg:#c64539; --colorErrorBgAlpha:#c645391a; --colorErrorFg:#ffffff; --colorWindowBg:#1d1e21; --colorWindowFg:#ffffff; --radiusRound:100px; --radiusRounded:2px; --radiusRoundedLess:2px; --radius:4px; --radiusHalf:2px; --scrollbarWidth:12px;"></span></html>



