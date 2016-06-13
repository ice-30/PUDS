<?php

//config db
define("DB_HOST", "");
define("DB_USERNAME", "");
define("DB_PASSWORD", "");
define("DB_DATABASE", "");

//paypal
define("PAYPAL_CURRENCY", "USD");
define("PAYPAL_SANDBOX", true);
define("PAYPAL_ID", "");
define("PAYPAL_ID_SANDBOX", "" ); // https://developer.paypal.com/webapps/developer/applications/accounts
//steam api key
define("API", "");

//Donation System URL
define("DONATE_URL", "http://example.com/donate/");


define("OPENID_MODE", "curl"); // "curl" or "streams" , if nether works contact webhost admin and ask to enable/fix curl

$GAME = array(
	"gmod" => array(
		'name' => "Garry's Mod",
		'icon' => "icons/gmod.png",
		'display' => true,
		'servers' => array("RP")
	)
);

$SERVER = array(
	"RP" => array(
		'name' => "Garry's Mod",
		'icon' => "icons/gmod.png",
		'orderfile' => "order.php",
		'packages' => array(1,2,3)
	),
);

//Packages
$PACKAGES = array(
	1 => array(
		'title' => "VIP",
		'buytitle' => "DarkRP - VIP",
		'description' => "
			<b>Price: <b style=\"color:green;\">$10</b></b>
			</br>
			<b>Features:</b><br/>
			<b>1.</b> VIP rank<br/>
			<b>2.</b> 40,000 ingame Cash<br/>
			<br/>
			<b style=\"color:green;\">This rank is valid for 30 days.</b>",
		'price' => 10,
		'execute' => array(
			"darkrp" => array(
				'online' => array(
					0 => array(
						array("darkrp_money",40000),
						array("broadcast", array(255,0,0) ,"%name% has donated for VIP!" )
					)
				),
				'offline' => array(
					0 => array(
						array("cancel", true, "darkrp"),
						array("sv_cmd","ulx", "adduserid", "%steamid%", "vip-donator")
					),
					86400*30 => array(
						array("sv_cmd","ulx", "removeuserid", "%steamid%")
					)
				)
			)
		),
		'checkout' => "source.php"
	),
	2 => array(
		'title' => "Money",
		'buytitle' => "DarkRP - Money",
		'description' => "
			<b>Price: <b style=\"color:green;\">£10</b></b>
			</br>
			<b>Features:</b><br/>
			<b>100,000 ingame cash<br/>
			<br/>
			<b style=\"color:red;\">You will recieve your money immediately. This cannot be refunded.</b>",
		'price' => 10,
		'execute' => array(
			"darkrp" => array(
				'online' => array(
					0 => array(
						array("darkrp_money",100000),
					)
				),
				)
			)
		),
		'checkout' => "source.php"
	),
);

// Advanced
function price($pid, $playerid){
	global $PACKAGES;
	return $PACKAGES[$pid]['price']; // What price should user pay
}
function ipnPriceValidation($pid, $playerid, $price){
	global $PACKAGES;
	return $PACKAGES[$pid]["price"] == $price; // Check if price valid after payment was done.
}

// !!!!! IGNORE !!!!!
if(PAYPAL_SANDBOX){
	define("PAYPAL_URL", "https://www.sandbox.paypal.com/cgi-bin/webscr" );
}else{
	define("PAYPAL_URL", "https://www.paypal.com/cgi-bin/webscr" );
}
